<?php
require_once "session_config.php";
include "../koneksi.php";

$result = mysqli_query($conn,"
SELECT * FROM hero_slides
ORDER BY sort_order ASC
");

include "partials/header.php";
?>

<div class="container-fluid">
<?php include "partials/sidebar.php"; ?>

<div class="content-area p-4">

<h4 class="mb-4">Hero Slider</h4>

<a href="hero_add.php" class="btn btn-primary mb-3">
+ Tambah Hero
</a>

<div class="card p-3">

<table class="table">

<thead>
<tr>
<th>Image</th>
<th>Title</th>
<th>Status</th>
<th>Action</th>
</tr>
</thead>

<tbody>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td>
<img src="../uploads/<?= $row['image'] ?>" width="120">
</td>

<td>
<?= htmlspecialchars($row['title']) ?>
</td>

<td>
<?= $row['status'] ?>
</td>

<td>

<a href="hero_delete.php?id=<?= $row['id'] ?>"
class="btn btn-danger btn-sm"
onclick="return confirm('Hapus hero?')">
Delete
</a>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>
</div>

<?php include "partials/footer.php"; ?>