<?php
require_once "session_config.php";
include "../koneksi.php";

if($_SERVER['REQUEST_METHOD']=='POST'){

$title = $_POST['title'];
$subtitle = $_POST['subtitle'];
$button_text = $_POST['button_text'];
$button_link = $_POST['button_link'];
$sort_order = $_POST['sort_order'];
$status = $_POST['status'];

/* upload image */

$ext = strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
$newName = uniqid("hero_").".".$ext;

move_uploaded_file(
$_FILES['image']['tmp_name'],
"../uploads/".$newName
);

/* insert */

$stmt = $conn->prepare("
INSERT INTO hero_slides
(title,subtitle,button_text,button_link,image,status,sort_order)
VALUES(?,?,?,?,?,?,?)
");

$stmt->bind_param(
"ssssssi",
$title,
$subtitle,
$button_text,
$button_link,
$newName,
$status,
$sort_order
);

$stmt->execute();

header("Location: hero.php");
exit;

}

include "partials/header.php";
?>

<div class="container-fluid">
<?php include "partials/sidebar.php"; ?>

<div class="content-area p-4">

<h4 class="mb-4">Tambah Hero</h4>

<div class="card p-4">

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Title</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Subtitle</label>
<textarea name="subtitle" class="form-control"></textarea>
</div>

<div class="mb-3">
<label class="form-label">Button Text</label>
<input type="text" name="button_text" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Button Link</label>
<input type="text" name="button_link" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Hero Image</label>
<input type="file" name="image" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Sort Order</label>
<input type="number" name="sort_order" class="form-control" value="0">
</div>

<div class="mb-3">
<label class="form-label">Status</label>

<select name="status" class="form-select">
<option value="aktif">Aktif</option>
<option value="nonaktif">Nonaktif</option>
</select>

</div>

<button class="btn btn-primary">
Simpan
</button>

</form>

</div>

</div>
</div>

<?php include "partials/footer.php"; ?>