<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn,"SELECT * FROM partners ORDER BY id DESC");

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Partners</h4>

<div class="card card-modern p-4">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-light">
<tr>
<th>ID</th>
<th>Logo</th>
<th>Nama</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($result)): ?>

<tr>

<td><?= $row['id'] ?></td>

<td>
<img src="../uploads/<?= $row['logo'] ?>" style="height:60px;">
</td>

<td><?= htmlspecialchars($row['name']) ?></td>

<td>

<a href="partner_edit.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-warning">
<i class="bi bi-pencil"></i>
</a>

<a href="partner_delete.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Hapus partner?')">
<i class="bi bi-trash"></i>
</a>

</td>

</tr>

<?php endwhile; ?>

</tbody>
</table>

</div>

</div>

</div>
</div>

<?php include "partials/footer.php"; ?>