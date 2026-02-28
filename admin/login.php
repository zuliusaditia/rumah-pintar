<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_config.php";
include "../koneksi.php";

if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("CSRF tidak valid.");
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $admin = $result->fetch_assoc();

        if (password_verify($password, $admin['password'])) {

            session_regenerate_id(true);
            $_SESSION['admin'] = $admin['username'];

            header("Location: dashboard.php");
            exit;

        } else {
            $error = "Password salah.";
        }

    } else {
        $error = "User tidak ditemukan.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Login Admin - Rumah Pintar</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    height:100vh;
    background: rgb(63, 127, 212);
    display:flex;
    align-items:center;
    justify-content:center;
    font-family: 'Segoe UI', sans-serif;
}

.login-card {
    width:100%;
    max-width:420px;
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    border-radius:20px;
    padding:40px;
    box-shadow:0 20px 40px rgba(0, 0, 0, 0.15);
}

.logo-title {
    font-weight:700;
    font-size:22px;
}

.form-control {
    border-radius:12px;
    padding:12px;
}

.btn-login {
    background:rgb(63, 127, 212);
    border:none;
    border-radius:12px;
    padding:12px;
    font-weight:600;
}

.btn-login:hover {
    background:#e85c2c;
}

.password-toggle {
    cursor:pointer;
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    font-size:14px;
    color:#888;
}
</style>

</head>
<body>

<div class="login-card">

<div class="text-center mb-4">
    <div class="logo-title">Rumah Pintar</div>
    <small class="text-muted">Admin Panel</small>
</div>

<?php if ($error): ?>
<div class="alert alert-danger text-center">
<?= htmlspecialchars($error) ?>
</div>
<?php endif; ?>

<form method="POST">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

<div class="mb-3">
<label class="form-label">Username</label>
<input type="text" name="username" class="form-control" required>
</div>

<div class="mb-3 position-relative">
<label class="form-label">Password</label>
<input type="password" name="password" id="password" class="form-control" required>
<span class="password-toggle" onclick="togglePassword()">Show</span>
</div>

<button type="submit" class="btn btn-login w-100">
Login
</button>

</form>

<div class="text-center mt-4">
<small class="text-muted">© <?= date('Y') ?> Rumah Pintar</small>
</div>

</div>

<script>
function togglePassword() {
    const input = document.getElementById("password");
    const toggle = document.querySelector(".password-toggle");

    if (input.type === "password") {
        input.type = "text";
        toggle.innerText = "Hide";
    } else {
        input.type = "password";
        toggle.innerText = "Show";
    }
}
</script>

</body>
</html>