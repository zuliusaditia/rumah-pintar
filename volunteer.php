<?php
include "koneksi.php";
include "includes/header.php";

$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $no_hp = trim($_POST['no_hp']);
    $umur = (int) $_POST['umur'];
    $pekerjaan = trim($_POST['pekerjaan']);
    $motivasi = trim($_POST['motivasi']);

    if (empty($nama) || empty($email) || empty($no_hp) || empty($umur)) {
        echo "<div class='container mt-3 alert alert-danger'>
                Semua field wajib diisi.
              </div>";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO volunteers 
            (nama,email,no_hp,umur,pekerjaan,motivasi,status) 
            VALUES (?,?,?,?,?,?,'pending')
        ");

        $stmt->bind_param(
            "sssiss",
            $nama,
            $email,
            $no_hp,
            $umur,
            $pekerjaan,
            $motivasi
        );

        $stmt->execute();
        $stmt->close();

        $success = true;
    }
}
?>

<section class="py-5">
<div class="container">

<h2 class="fw-bold mb-4 text-center">Daftar Sebagai Relawan</h2>

<?php if ($success) { ?>
    <div class="alert alert-success text-center">
        Pendaftaran berhasil dikirim! Tim kami akan menghubungi Anda.
    </div>
<?php } else { ?>

<div class="row justify-content-center">
<div class="col-lg-6">

<form method="POST" class="card p-4 shadow-sm">

    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="nama" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">No WhatsApp</label>
        <input type="text" name="no_hp" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Umur</label>
        <input type="number" name="umur" class="form-control" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Pekerjaan / Status</label>
        <input type="text" name="pekerjaan" class="form-control">
    </div>

    <div class="mb-3">
        <label class="form-label">Motivasi Bergabung</label>
        <textarea name="motivasi" class="form-control" rows="4"></textarea>
    </div>

    <button class="btn btn-primary-custom w-100">
        Kirim Pendaftaran
    </button>

</form>

</div>
</div>

<?php } ?>

</div>
</section>

<?php include "includes/footer.php"; ?>