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
    $where = "WHERE title LIKE '%$search%'";
}

/* ========================
   PAGINATION
======================== */
$limit = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

$total_query = mysqli_query($conn,"SELECT COUNT(*) as total FROM articles $where");
$total_data = mysqli_fetch_assoc($total_query)['total'];
$total_pages = ceil($total_data / $limit);

$query = mysqli_query($conn,"SELECT * FROM articles $where ORDER BY id DESC LIMIT $start,$limit");

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<div class="d-flex justify-content-between align-items-center mb-4">
   <h4>Daftar Artikel</h4>
   <a href="tambah_artikel.php" class="btn btn-primary">
      <i class="bi bi-plus-lg"></i> Tambah Artikel
   </a>
</div>

<!-- SEARCH -->
<form method="GET" class="mb-3">
<div class="input-group">
<input type="text" name="search" class="form-control"
       placeholder="Cari artikel..." value="<?= htmlspecialchars($search) ?>">
<button class="btn btn-outline-secondary">Search</button>
</div>
</form>

<form method="POST" action="bulk_artikel.php">
<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

<div class="card card-modern p-4">

<div class="d-flex mb-3 gap-2">
<select name="bulk_action" class="form-select w-auto">
<option value="">Bulk Action</option>
<option value="publish">Publish</option>
<option value="draft">Jadikan Draft</option>
<option value="delete">Hapus</option>
</select>
<button class="btn btn-dark btn-sm">Apply</button>
</div>

<div class="table-responsive">
<table class="table table-hover align-middle">

<thead class="table-light">
<tr>
<th><input type="checkbox" id="checkAll"></th>
<th>Thumbnail</th>
<th>Judul</th>
<th>Status</th>
<th>Tanggal</th>
<th width="220">Aksi</th>
</tr>
</thead>

<tbody>
<?php while($row = mysqli_fetch_assoc($query)): ?>
<tr>

<td>
<input type="checkbox" name="ids[]" value="<?= $row['id'] ?>">
</td>

<td>
<?php if($row['image']): ?>
<img src="../uploads/<?= $row['image'] ?>"
     style="width:60px;height:60px;object-fit:cover;border-radius:8px;">
<?php else: ?>
<span class="text-muted">No Image</span>
<?php endif; ?>
</td>

<td><strong><?= htmlspecialchars($row['title']) ?></strong></td>

<td>
<?php if($row['status']=='draft'): ?>
<span class="badge bg-secondary">Draft</span>
<?php else: ?>
<span class="badge bg-success">Published</span>
<?php endif; ?>
</td>

<td><?= date('d M Y',strtotime($row['created_at'])) ?></td>

<td>

<a href="../detail_kegiatan.php?id=<?= $row['id'] ?>"
   target="_blank"
   class="btn btn-sm btn-info">
<i class="bi bi-eye"></i>
</a>

<a href="edit_artikel.php?id=<?= $row['id'] ?>"
   class="btn btn-sm btn-primary">
<i class="bi bi-pencil"></i>
</a>

<a href="hapus_artikel.php?id=<?= $row['id'] ?>"
   class="btn btn-sm btn-danger"
   onclick="return confirm('Yakin hapus?')">
<i class="bi bi-trash"></i>
</a>

</td>

</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>
</div>
</form>

<!-- PAGINATION -->
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

<script>
document.getElementById("checkAll").addEventListener("click",function(){
    document.querySelectorAll("input[name='ids[]']")
    .forEach(cb=>cb.checked=this.checked);
});
</script>

<?php include "partials/footer.php"; ?>