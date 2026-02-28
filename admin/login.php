<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "logger.php";
require_once "session_config.php";

// JANGAN include koneksi lagi kalau sudah ada di session_config
// include "../koneksi.php";

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $admin = $result->fetch_assoc();

        // ==========================
        // CEK RATE LIMIT
        // ==========================
        if ($admin['login_attempts'] >= 5) {

            $last_attempt = strtotime($admin['last_attempt']);
            $now = time();
            $diff = $now - $last_attempt;

            if ($diff < 600) { // 10 menit
                $remaining = ceil((600 - $diff) / 60);
                $error = "Terlalu banyak percobaan login. Coba lagi dalam $remaining menit.";
            } else {
                // Reset setelah 10 menit
                $reset = $conn->prepare("UPDATE admins SET login_attempts=0 WHERE id=?");
                $reset->bind_param("i", $admin['id']);
                $reset->execute();
                $admin['login_attempts'] = 0;
            }
        }

        if (!isset($error)) {

            if (password_verify($password, $admin['password'])) {

                // LOGIN BERHASIL → RESET ATTEMPTS
                $reset = $conn->prepare("UPDATE admins SET login_attempts=0 WHERE id=?");
                $reset->bind_param("i", $admin['id']);
                $reset->execute();

                session_regenerate_id(true);
                $_SESSION['admin'] = $admin['username'];

                log_activity($conn, $admin['username'], "Login berhasil");
                header("Location: dashboard.php");
                exit;

            } else {

                // PASSWORD SALAH → TAMBAH ATTEMPT
                $update = $conn->prepare(
                    "UPDATE admins 
                    SET login_attempts = login_attempts + 1, 
                        last_attempt = NOW() 
                    WHERE id=?"
                );
                $update->bind_param("i", $admin['id']);
                $update->execute();

                $error = "Password salah.";
            }
        }

    } else {
        $error = "User tidak ditemukan.";
    }

    $stmt->close();
}
?>

<h2>Login Admin</h2>

<?php if (isset($error)) : ?>
    <p style="color:red;"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST">
    Username:<br>
    <input type="text" name="username" required><br><br>

    Password:<br>
    <input type="password" name="password" required><br><br>

    <button type="submit">Login</button>
</form>