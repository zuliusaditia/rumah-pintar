<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================
PAGINATION
========================= */

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

$total_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM orders");
$total_data = mysqli_fetch_assoc($total_query)['total'];

$total_pages = ceil($total_data / $limit);

/* =========================
GET ORDERS
========================= */

$query = mysqli_query($conn,"
SELECT * FROM orders
ORDER BY id DESC
LIMIT $start,$limit
");

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Daftar Order</h4>

<div class="card card-modern p-4">

<div class="table-responsive">

<table class="table table-hover align-middle">

<thead class="table-light">

<tr>
<th>Kode</th>
<th>Nama</th>
<th>Total</th>
<th>Status</th>
<th>Bukti</th>
<th>Tanggal</th>
<th width="220">Aksi</th>
</tr>

</thead>

<tbody>

<?php while($row = mysqli_fetch_assoc($query)): ?>

<tr>

<td>
<strong><?= htmlspecialchars($row['kode_order']) ?></strong>
</td>

<td>
<?= htmlspecialchars($row['nama']) ?>
</td>

<td>
Rp <?= number_format($row['total'],0,',','.') ?>
</td>

<td>

<?php if($row['status']=='pending'): ?>

<span class="badge bg-warning text-dark">
Pending
</span>

<?php elseif($row['status']=='paid'): ?>

<span class="badge bg-success">
Paid
</span>

<?php elseif($row['status']=='shipped'): ?>

<span class="badge bg-primary">
Dikirim
</span>

<?php else: ?>

<span class="badge bg-secondary">
Selesai
</span>

<?php endif; ?>

</td>

<td>

<?php if(!empty($row['bukti_transfer'])): ?>

<a href="../uploads/bukti/<?= $row['bukti_transfer'] ?>"
target="_blank"
class="btn btn-sm btn-outline-secondary">

<i class="bi bi-image"></i>

</a>

<?php else: ?>

<span class="text-muted">-</span>

<?php endif; ?>

</td>

<td>

<?= date('d M Y H:i',strtotime($row['created_at'])) ?>

</td>

<td>

<a href="detail_order.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-info">

<i class="bi bi-eye"></i>

</a>

<?php if($row['status']=='pending'): ?>

<a href="verifikasi_order.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-success"
onclick="return confirm('Verifikasi pembayaran?')">

<i class="bi bi-check"></i>

</a>

<?php endif; ?>

<a href="hapus_order.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Yakin hapus order?')">

<i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php endwhile; ?>

</tbody>

</table>

</div>

</div>

<!-- =========================
PAGINATION
========================= -->

<nav class="mt-4">

<ul class="pagination">

<?php for($i=1;$i<=$total_pages;$i++): ?>

<li class="page-item <?= $i==$page?'active':'' ?>">

<a class="page-link" href="?page=<?= $i ?>">
<?= $i ?>
</a>

</li>

<?php endfor; ?>

</ul>

</nav>

</div>

</div>

<?php include "partials/footer.php"; ?>