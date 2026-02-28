<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    die("Produk tidak ditemukan.");
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $harga = (int) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $status = $_POST['status'];

    $image_name = $product['image'];

    if (!empty($_FILES['image']['name'])) {

        $allowed_ext = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $size = $_FILES['image']['size'];
        $tmp  = $_FILES['image']['tmp_name'];

        if (!in_array($ext, $allowed_ext)) {
            $error = "Format gambar tidak valid.";
        } elseif ($size > 2*1024*1024) {
            $error = "Ukuran maksimal 2MB.";
        } else {
            $image_name = uniqid("produk_") . "." . $ext;
            move_uploaded_file($tmp, "../uploads/$image_name");
        }
    }

    if (!$error) {

        $stmt = $conn->prepare("
            UPDATE products 
            SET nama=?, deskripsi=?, harga=?, stok=?, image=?, status=? 
            WHERE id=?
        ");

        $stmt->bind_param(
            "ssiissi",
            $nama,
            $deskripsi,
            $harga,
            $stok,
            $image_name,
            $status,
            $id
        );

        $stmt->execute();
        $stmt->close();

        header("Location: list_produk.php");
        exit;
    }
}

include "partials/header.php";
?>

<div class="container-fluid">
<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Edit Produk</h4>

<div class="card p-4">

<?php if($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Nama Produk</label>
<input type="text" name="nama" 
value="<?= htmlspecialchars($product['nama']) ?>"
class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Deskripsi</label>
<textarea name="deskripsi" class="form-control" rows="4"><?= htmlspecialchars($product['deskripsi']) ?></textarea>
</div>

<div class="row">
<div class="col-md-6 mb-3">
<label class="form-label">Harga</label>
<input type="number" name="harga" 
value="<?= $product['harga'] ?>"
class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Stok</label>
<input type="number" name="stok" 
value="<?= $product['stok'] ?>"
class="form-control" required>
</div>
</div>

<div class="mb-3">
<label class="form-label">Gambar</label>
<input type="file" name="image" class="form-control">
<?php if($product['image']): ?>
<img src="../uploads/<?= $product['image'] ?>"
style="width:120px;margin-top:10px;border-radius:8px;">
<?php endif; ?>
</div>

<div class="mb-4">
<label class="form-label">Status</label>
<select name="status" class="form-select">
<option value="aktif" <?= $product['status']=='aktif'?'selected':'' ?>>Aktif</option>
<option value="nonaktif" <?= $product['status']=='nonaktif'?'selected':'' ?>>Nonaktif</option>
</select>
</div>

<button class="btn btn-primary-custom">
<i class="bi bi-save"></i> Update Produk
</button>

<a href="list_produk.php" class="btn btn-secondary">
Kembali
</a>

</form>

</div>
</div>
</div>

<?php include "partials/footer.php"; ?>