<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn,"SELECT * FROM impact_stats ORDER BY id DESC");

include "partials/header.php";
?>

<div class="container-fluid">
<div class="row">

<?php include "partials/sidebar.php"; ?>

<div class="col-md-9 col-lg-10 p-4">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Impact</h4>
    <a href="tambah_impact.php" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Impact
    </a>
</div>

<div class="card card-modern p-4">

<div class="table-responsive">
<table class="table table-hover align-middle">

<br><br>

<thead class="table-light">
<tr>
    <th>No</th>
    <th>Icon</th>
    <th>Value</th>
    <th>Label</th>
    <th>Aksi</th>
</tr>

<?php $no=1; while($row=$result->fetch_assoc()) { ?>
<tr>
    <td><?= $no++; ?></td>
    <td><i class="bi bi-<?= htmlspecialchars($row['icon']); ?>"></i></td>
    <td><?= htmlspecialchars($row['value']); ?></td>
    <td><?= htmlspecialchars($row['label']); ?></td>
    <td>
        <a href="edit_impact.php?id=<?= $row['id']; ?>">Edit</a> |
        <a href="hapus_impact.php?id=<?= $row['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
    </td>
</tr>
<?php } ?>

</table>

<?php $stmt->close(); ?>