<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* ========================
   SEARCH
======================== */
$search = $_GET['search'] ?? '';
$where = "";

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn,$search);
    $where = "WHERE nama LIKE '%$search%'";
}

/* ========================
   PAGINATION
======================== */
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

$total_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM products $where");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

$query = mysqli_query($conn,"SELECT * FROM products $where ORDER BY id DESC LIMIT $start,$limit");

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Produk</h4>
    <a href="tambah_produk.php" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Produk
    </a>
</div>

<form method="GET" class="mb-3">
<div class="input-group">
<input type="text" name="search" class="form-control"
placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
<button class="btn btn-outline-secondary">Search</button>
</div>
</form>

<div class="card card-modern p-4">

<div class="table-responsive">
<table class="table table-hover align-middle">

<thead class="table-light">
<tr>
<th>Thumbnail</th>
<th>Nama</th>
<th>Harga</th>
<th>Stok</th>
<th>Status</th>
<th width="180">Aksi</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($query)): ?>
<tr>

<td>
<?php if($row['image']): ?>
<img src="../uploads/<?= $row['image'] ?>"
style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
<?php endif; ?>
</td>

<td><?= htmlspecialchars($row['nama']) ?></td>

<td>Rp <?= number_format($row['harga'],0,',','.') ?></td>

<td>
<?php if($row['stok'] > 0): ?>
<span class="badge bg-success"><?= $row['stok'] ?> tersedia</span>
<?php else: ?>
<span class="badge bg-danger">Habis</span>
<?php endif; ?>
</td>

<td>
<?php if($row['status']=='aktif'): ?>
<span class="badge bg-success">Aktif</span>
<?php else: ?>
<span class="badge bg-secondary">Nonaktif</span>
<?php endif; ?>
</td>

<td>

<a href="edit_produk.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-primary">
<i class="bi bi-pencil"></i>
</a>

<a href="hapus_produk.php?id=<?= $row['id'] ?>"
class="btn btn-sm btn-danger"
onclick="return confirm('Yakin hapus produk?')">
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
<a class="page-link"
href="?page=<?= $i ?>&search=<?= urlencode($search) ?>">
<?= $i ?>
</a>
</li>
<?php endfor; ?>
</ul>
</nav>

</div>
</div>
</div>

<?php include "partials/footer.php"; ?>