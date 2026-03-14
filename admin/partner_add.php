<?php
include "../koneksi.php";
include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area p-4">

<h4 class="mb-4">Tambah Partner</h4>

<div class="card p-4 shadow-sm">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Nama Partner</label>
<input type="text" name="name" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Link Website</label>
<input type="text" name="link" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Logo Partner</label>
<input type="file" name="logo" class="form-control" required>
</div>

<button class="btn btn-primary">
Simpan Partner
</button>

<a href="partners.php" class="btn btn-secondary">
Kembali
</a>

</form>

</div>

</div>

</div>

<?php
/* ======================
PROCESS FORM
====================== */

if($_SERVER['REQUEST_METHOD']=="POST"){

$name = $_POST['name'];
$link = $_POST['link'];

$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
$newname = uniqid("partner_").".".$ext;

move_uploaded_file(
$_FILES['logo']['tmp_name'],
"../uploads/".$newname
);

$stmt = $conn->prepare("
INSERT INTO partners(name,logo,link,status)
VALUES(?,?,?,?)
");

$status="aktif";

$stmt->bind_param(
"ssss",
$name,
$newname,
$link,
$status
);

$stmt->execute();

echo "<script>
window.location='partners.php';
</script>";
}
?>

<?php include "partials/footer.php"; ?>