<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Order tidak valid.");
}

$id = (int) $_GET['id'];

// ==========================
// GET ORDER
// ==========================
$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$order) {
    die("Order tidak ditemukan.");
}

// ==========================
// GET ITEMS
// ==========================
$stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Detail Order</h4>

<div class="card card-modern p-4 mb-4">

<h5>Kode Order: <strong><?= $order['kode_order'] ?></strong></h5>
<p><strong>Nama:</strong> <?= htmlspecialchars($order['nama']) ?></p>
<p><strong>No HP:</strong> <?= htmlspecialchars($order['no_hp']) ?></p>
<p><strong>Alamat:</strong> <?= htmlspecialchars($order['alamat']) ?></p>
<p><strong>Total:</strong> Rp <?= number_format($order['total'],0,',','.') ?></p>

<?php
$status_colors = [
    'pending' => 'secondary',
    'paid' => 'warning',
    'shipped' => 'info',
    'completed' => 'success',
    'cancelled' => 'danger'
];
?>

<p>
<strong>Status:</strong>
<span class="badge bg-<?= $status_colors[$order['status']] ?? 'secondary' ?>">
<?= ucfirst($order['status']) ?>
</span>
</p>

<?php if ($order['resi']) : ?>
<p><strong>Resi:</strong> <?= htmlspecialchars($order['resi']) ?></p>
<?php endif; ?>

</div>

<div class="card card-modern p-4 mb-4">

<h5 class="mb-3">Item Pesanan</h5>

<table class="table table-hover">
<thead>
<tr>
<th>Produk</th>
<th>Harga</th>
<th>Qty</th>
<th>Subtotal</th>
</tr>
</thead>
<tbody>

<?php while($item = $items->fetch_assoc()): ?>
<tr>
<td><?= htmlspecialchars($item['nama_produk']) ?></td>
<td>Rp <?= number_format($item['harga'],0,',','.') ?></td>
<td><?= $item['qty'] ?></td>
<td>Rp <?= number_format($item['subtotal'],0,',','.') ?></td>
</tr>
<?php endwhile; ?>

</tbody>
</table>

</div>

<!-- UPDATE STATUS -->
<div class="card card-modern p-4">

<h5 class="mb-3">Update Status</h5>

<form method="POST" action="update_order_status.php">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
<input type="hidden" name="id" value="<?= $order['id'] ?>">

<div class="mb-3">
<label>Status</label>
<select name="status" class="form-select">
<?php foreach(['pending','paid','shipped','completed','cancelled'] as $s): ?>
<option value="<?= $s ?>" <?= $order['status']==$s?'selected':'' ?>>
<?= ucfirst($s) ?>
</option>
<?php endforeach; ?>
</select>
</div>

<div class="mb-3">
<label>Nomor Resi (opsional)</label>
<input type="text" name="resi" class="form-control"
value="<?= htmlspecialchars($order['resi']) ?>">
</div>

<button class="btn btn-primary">
<i class="bi bi-save"></i> Update Order
</button>

</form>

</div>

</div>
</div>
</div>

<?php include "partials/footer.php"; ?>