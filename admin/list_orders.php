<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$limit = 8;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

$total_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM orders");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

$query = mysqli_query($conn,"SELECT * FROM orders ORDER BY id DESC LIMIT $start,$limit");

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
<th>Tanggal</th>
<th width="180">Aksi</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($query)): ?>
<tr>

<td><strong><?= $row['kode_order'] ?></strong></td>

<td><?= htmlspecialchars($row['nama']) ?></td>

<td>Rp <?= number_format($row['total'],0,',','.') ?></td>

<td><?= date('d M Y H:i',strtotime($row['created_at'])) ?></td>

<td>
<a href="detail_order.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-info">
<i class="bi bi-eye"></i>
</a>

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