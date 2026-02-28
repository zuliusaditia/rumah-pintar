<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "koneksi.php";
include "includes/header.php";

$success = false;
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $nominal = (int) $_POST['nominal'];

    if ($nominal <= 0) {
        $error_message = "Nominal tidak valid.";
    }

    if (!isset($_FILES['bukti']) || $_FILES['bukti']['error'] !== 0) {
        $error_message = "File tidak valid.";
    }

    if (empty($error_message)) {

        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $file_name = $_FILES['bukti']['name'];
        $file_tmp = $_FILES['bukti']['tmp_name'];
        $file_size = $_FILES['bukti']['size'];

        $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            $error_message = "Format file hanya JPG, JPEG, PNG.";
        }

        if ($file_size > 2 * 1024 * 1024) {
            $error_message = "Ukuran file maksimal 2MB.";
        }

        if (empty($error_message)) {

            $new_name = uniqid("donasi_", true) . "." . $ext;
            $folder = "uploads/";

            if (!move_uploaded_file($file_tmp, $folder . $new_name)) {
                $error_message = "Upload gagal.";
            } else {

                $status = "pending";

                $stmt = $conn->prepare(
                    "INSERT INTO donation_money 
                    (nama, no_hp, nominal, bukti_transfer, status) 
                    VALUES (?, ?, ?, ?, ?)"
                );

                $stmt->bind_param("ssiss", $nama, $no_hp, $nominal, $new_name, $status);

                if ($stmt->execute()) {
                    $success = true;
                } else {
                    $error_message = "Terjadi kesalahan sistem.";
                }

                $stmt->close();
            }
        }
    }
}
?>

<!-- HERO MINI -->
<section class="section section-soft text-center">
    <div class="container">
        <h1 class="fw-bold">Donasi Uang</h1>
        <p class="text-muted">Bersama kita bantu pendidikan anak-anak Rumah Pintar</p>
    </div>
</section>

<!-- INFO REKENING -->
<section class="section">
    <div class="container" style="max-width:700px;">
        <div class="card-custom text-center">
            <h5 class="fw-bold mb-3">Transfer ke Rekening</h5>
            <p class="mb-1">Bank BCA</p>
            <h3 class="fw-bold">1234567890</h3>
            <p>a.n Yayasan Rumah Pintar</p>
        </div>
    </div>
</section>

<!-- FORM -->
<section class="section">
    <div class="container" style="max-width:600px;">

        <div class="card-custom">

            <h4 class="fw-bold text-center mb-4">Konfirmasi Donasi</h4>

            <?php if ($success) { ?>
                <div class="alert alert-success text-center">
                    Donasi berhasil dikirim. Menunggu verifikasi admin.
                </div>
            <?php } ?>

            <?php if (!empty($error_message)) { ?>
                <div class="alert alert-danger text-center">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">No WhatsApp</label>
                    <input type="text" name="no_hp" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nominal Donasi</label>

                    <!-- Quick Nominal -->
                    <div class="d-flex gap-2 mb-2 flex-wrap">
                        <button type="button" class="btn btn-outline-custom btn-sm" onclick="setNominal(50000)">50rb</button>
                        <button type="button" class="btn btn-outline-custom btn-sm" onclick="setNominal(100000)">100rb</button>
                        <button type="button" class="btn btn-outline-custom btn-sm" onclick="setNominal(250000)">250rb</button>
                    </div>

                    <input type="number" name="nominal" id="nominalInput" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Bukti Transfer</label>
                    <input type="file" name="bukti" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary-custom w-100">
                    Kirim Konfirmasi
                </button>

            </form>
        </div>
    </div>
</section>

<script>
function setNominal(value) {
    document.getElementById('nominalInput').value = value;
}
</script>

<?php include "includes/footer.php"; ?>