<?php
include "../koneksi.php";
include "partials/header.php";

$id = $_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM partners WHERE id=$id");
$data = mysqli_fetch_assoc($query);
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area p-4">

<h4 class="mb-4">Edit Partner</h4>

<div class="card p-4 shadow-sm">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Nama Partner</label>
<input type="text" name="name"
value="<?= htmlspecialchars($data['name']) ?>"
class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Link Website</label>
<input type="text" name="link"
value="<?= htmlspecialchars($data['link']) ?>"
class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Logo Sekarang</label>
<br>
<img src="../uploads/<?= $data['logo'] ?>"
style="height:70px;border-radius:8px;">
</div>

<div class="mb-3">
<label class="form-label">Ganti Logo (Opsional)</label>
<input type="file" name="logo" class="form-control">
</div>

<button class="btn btn-primary">
Update Partner
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
PROCESS UPDATE
====================== */

if($_SERVER['REQUEST_METHOD']=="POST"){

$name = $_POST['name'];
$link = $_POST['link'];

$logo = $data['logo'];

/* cek jika upload logo baru */

if(!empty($_FILES['logo']['name'])){

$ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
$newname = uniqid("partner_").".".$ext;

move_uploaded_file(
$_FILES['logo']['tmp_name'],
"../uploads/".$newname
);

$logo = $newname;
}

/* update database */

$stmt = $conn->prepare("
UPDATE partners
SET name=?, logo=?, link=?
WHERE id=?
");

$stmt->bind_param(
"sssi",
$name,
$logo,
$link,
$id
);

$stmt->execute();

echo "<script>
window.location='partners.php';
</script>";
}
?>

<?php include "partials/footer.php"; ?>