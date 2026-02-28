<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location : login.php");
    exit;
}

/*==========================
    SEARCH
==========================*/
$search = $_GET['search'] ?? '';
$where = "";

if (!empty($search)) {
    $search = mysqli_real_escape_string($conn, $search);
    $where = "WHERE nama LIKE '%$search%'";
}

/*==========================
    PAGINATION
==========================*/
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$start = ($page - 1) * $limit;

$query = mysqli_query($conn, "SELECT * FROM products $where ORDER BY id DESC LIMIT $start,$limit");

include "partial/header.php";
?>

<div class="container-fluid">
<div class="row">
<?php include "partial/sidebar.php"; ?>

<div class="col-md-9 col-lg-10 p-4">
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4>Daftar Produk</h4>
    <a href="tambah_produk.php" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Produk
    </a>
</div>

<form method="GET" class="mb-3">
    <div class="input group">
        <input type="text" name="search" class="form-control"
            placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
        <button class="btn btn-outline-secondary">Search</button>
    </div>
</form>

<div class="card card-modern p-4">
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= $row['nama'] ?></td>
                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                    <td><?= $row['stok'] ?></td>
                    <td>
                        <a href="edit_produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="hapus_produk.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<nav class="mt-4">
    <ul class="pagination">
        <?php for($i=1;$i<=$total_pages;$i++): ?>
        <li class="page-item <?= $i==$page?'active':'' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
        </li>
        <?php endfor; ?>
    </ul>
</nav>

</div>
</div>
</div>

<?php include "partial/footer.php"; ?>