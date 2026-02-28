<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $status = $_POST['status'];

    if ($harga < 0 || $stok < 0) {
        $error = "Harga dan stok tidak boleh negatif.";
    } elseif (empty($nama)) {
        $error = "Nama produk wajib diisi.";
    } else {

        $image_name = null;

        // =====================
        // VALIDASI GAMBAR
        // =====================
        if (!empty($_FILES['image']['name'])) {

            $allowed_ext = ['jpg','jpeg','png','webp'];
            $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $size = $_FILES['image']['size'];
            $tmp  = $_FILES['image']['tmp_name'];

            if (!in_array($ext, $allowed_ext)) {
                $error = "Format gambar harus JPG, PNG, atau WEBP.";
            } elseif ($size > 2 * 1024 * 1024) {
                $error = "Ukuran gambar maksimal 2MB.";
            } else {
                $image_name = uniqid("produk_") . "." . $ext;
                move_uploaded_file($tmp, "../uploads/$image_name");
            }
        }

        if (empty($error)) {

            $stmt = $conn->prepare("
                INSERT INTO products 
                (nama, deskripsi, harga, stok, image, status, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, NOW())
            ");

            $stmt->bind_param(
                "ssiiss",
                $nama,
                $deskripsi,
                $harga,
                $stok,
                $image_name,
                $status
            );

            if ($stmt->execute()) {
                $success = "Produk berhasil ditambahkan.";
            } else {
                $error = "Gagal menyimpan produk.";
            }

            $stmt->close();
        }
    }
}

include "partials/header.php";
?>

<div class="container-fluid">
<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Tambah Produk</h4>

<div class="card p-4">

<?php if($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Nama Produk</label>
<input type="text" name="nama" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Deskripsi</label>
<textarea name="deskripsi" class="form-control" rows="4"></textarea>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Harga (Rp)</label>
<input type="number" name="harga" class="form-control" min="0" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Stok</label>
<input type="number" name="stok" class="form-control" min="0" required>
</div>
</div>

<div class="mb-3">
<label class="form-label">Thumbnail Produk</label>
<input type="file" name="image" class="form-control">
<small class="text-muted">Maksimal 2MB (JPG, PNG, WEBP)</small>
</div>

<div class="mb-4">
<label class="form-label">Status</label>
<select name="status" class="form-select">
<option value="aktif">Aktif</option>
<option value="nonaktif">Nonaktif</option>
</select>
</div>

<button class="btn btn-primary-custom">
<i class="bi bi-save"></i> Simpan Produk
</button>

<a href="list_produk.php" class="btn btn-secondary">
Kembali
</a>

</form>

</div>
</div>
</div>

<?php include "partials/footer.php"; ?>