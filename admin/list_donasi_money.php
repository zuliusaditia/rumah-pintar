<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn,"SELECT * FROM donation_money ORDER BY id DESC");

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Donasi Uang</h4>

<div class="card card-modern p-4">

<div class="table-responsive">
<table class="table table-hover align-middle">

<thead class="table-light">
<tr>
<th>Nama</th>
<th>Nominal</th>
<th>Status</th>
<th>Aksi</th>
<th>Bukti</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>

<td><?= htmlspecialchars($row['nama']) ?></td>

<td>
Rp <?= number_format($row['nominal'],0,',','.') ?>
</td>

<td>
<?php if($row['status']=='pending'): ?>
<span class="badge bg-warning">Pending</span>
<?php elseif($row['status']=='approved'): ?>
<span class="badge bg-success">Approved</span>
<?php else: ?>
<span class="badge bg-danger">Rejected</span>
<?php endif; ?>
</td>

<td>
<?php if($row['status']=='pending'): ?>

<form method="POST" action="verifikasi_donasi_money.php" class="d-inline">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="status" value="approved">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<button class="btn btn-sm btn-success">
<i class="bi bi-check-lg"></i>
</button>
</form>

<form method="POST" action="verifikasi_donasi_money.php" class="d-inline">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="status" value="rejected">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<button class="btn btn-sm btn-danger">
<i class="bi bi-x-lg"></i>
</button>
</form>

<?php else: ?>
<span class="badge bg-secondary">Sudah Diverifikasi</span>
<?php endif; ?>
</td>

<td>
<a href="../uploads/<?= $row['bukti_transfer'] ?>"
target="_blank"
class="btn btn-sm btn-secondary">
<i class="bi bi-file-earmark-image"></i>
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