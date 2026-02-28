<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn,"SELECT * FROM volunteers ORDER BY id DESC");

include "partials/header.php";
?>

<div class="container-fluid">
<div class="row">

<?php include "partials/sidebar.php"; ?>

<div class="col-md-9 col-lg-10 p-4">

<h4 class="mb-4">Data Volunteer</h4>

<div class="card card-modern p-4">

<div class="table-responsive">
<table class="table table-hover align-middle">

<thead class="table-light">
<tr>
<th>Nama</th>
<th>Email</th>
<th>Status</th>
<th width="200">Aksi</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($result)): ?>
<tr>
<td><?= htmlspecialchars($row['nama']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
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
<form method="POST" action="approve_volunteer.php" class="d-inline">
<input type="hidden" name="id" value="<?= $row['id'] ?>">
<input type="hidden" name="status" value="approved">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<button class="btn btn-sm btn-success">
<i class="bi bi-check-lg"></i>
</button>
</form>
<?php endif; ?>

<?php if($row['status']=='approved'): ?>
<a href="generate_certificate.php?id=<?= $row['id'] ?>" target="_blank"
class="btn btn-sm btn-primary">
<i class="bi bi-file-earmark-pdf"></i>
</a>
<?php endif; ?>

</td>
</tr>
<?php endwhile; ?>

</tbody>
</table>
</div>

</div>
</div>
</div>
</div>

<?php include "partials/footer.php"; ?>