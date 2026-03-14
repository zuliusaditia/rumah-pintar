<?php
require_once "session_config.php";
include "../koneksi.php";
include "../includes/fonnte.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

/* =========================
GET ORDER
========================= */

$stmt = $conn->prepare("SELECT * FROM orders WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if(!$order){
echo "<div class='container mt-5'>
<div class='alert alert-danger'>Order tidak ditemukan</div>
</div>";
exit;
}

/* =========================
GET ORDER ITEMS
========================= */

$stmt = $conn->prepare("
SELECT * FROM order_items
WHERE order_id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();
$items = $stmt->get_result();
$stmt->close();

/* =========================
UPDATE STATUS
========================= */

if(isset($_POST['status'])){

$new_status = $_POST['status'];

$stmt = $conn->prepare("
UPDATE orders
SET status=?
WHERE id=?
");

$stmt->bind_param("si",$new_status,$id);
$stmt->execute();
$stmt->close();

/* =========================
KIRIM WHATSAPP
========================= */

$message = "Halo {$order['nama']}

Status pesanan Anda telah diperbarui.

Kode Order:
{$order['kode_order']}

Status terbaru:
{$new_status}

Terima kasih telah berbelanja di Rumah Pintar.";

kirimWA($order['no_hp'],$message);

header("Location: detail_order.php?id=".$id);
exit;
}

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Detail Order</h4>

<div class="row">

<!-- ORDER INFO -->

<div class="col-lg-6">

<div class="card card-modern p-4 mb-4">

<h5 class="mb-3">Informasi Order</h5>

<p>
<strong>Kode Order</strong><br>
<?= htmlspecialchars($order['kode_order']) ?>
</p>

<p>
<strong>Nama</strong><br>
<?= htmlspecialchars($order['nama']) ?>
</p>

<p>
<strong>No WhatsApp</strong><br>
<?= htmlspecialchars($order['no_hp']) ?>
</p>

<p>
<strong>Alamat</strong><br>
<?= htmlspecialchars($order['alamat']) ?>
</p>

<?php if(!empty($order['patokan'])): ?>

<p>
<strong>Patokan</strong><br>
<?= htmlspecialchars($order['patokan']) ?>
</p>

<?php endif; ?>

<p>
<strong>Total</strong><br>
Rp <?= number_format($order['total'],0,',','.') ?>
</p>

<p>
<strong>Status</strong><br>

<?php if($order['status']=='pending'): ?>

<span class="badge bg-warning text-dark">Pending</span>

<?php elseif($order['status']=='paid'): ?>

<span class="badge bg-success">Paid</span>

<?php elseif($order['status']=='shipped'): ?>

<span class="badge bg-primary">Dikirim</span>

<?php else: ?>

<span class="badge bg-secondary">Selesai</span>

<?php endif; ?>

</p>

<p>
<strong>Tanggal Order</strong><br>
<?= date('d M Y H:i',strtotime($order['created_at'])) ?>
</p>

<hr>

<h6>Update Status</h6>

<form method="POST">

<select name="status" class="form-select mb-2">

<option value="pending" <?= $order['status']=='pending'?'selected':'' ?>>
Pending
</option>

<option value="paid" <?= $order['status']=='paid'?'selected':'' ?>>
Paid
</option>

<option value="shipped" <?= $order['status']=='shipped'?'selected':'' ?>>
Shipped
</option>

<option value="done" <?= $order['status']=='done'?'selected':'' ?>>
Done
</option>

</select>

<button class="btn btn-success btn-sm">
Update Status
</button>

</form>

<hr>

<a
href="https://wa.me/<?= preg_replace('/[^0-9]/','',$order['no_hp']) ?>"
target="_blank"
class="btn btn-success">

Chat WhatsApp

</a>

</div>

</div>


<!-- BUKTI TRANSFER -->

<div class="col-lg-6">

<div class="card card-modern p-4 mb-4">

<h5 class="mb-3">Bukti Transfer</h5>

<?php if(!empty($order['bukti_transfer'])): ?>

<img
src="../uploads/bukti/<?= $order['bukti_transfer'] ?>"
class="img-fluid rounded shadow-sm">

<?php else: ?>

<p class="text-muted">
Belum ada bukti transfer
</p>

<?php endif; ?>

</div>


<!-- MAP -->

<div class="card card-modern p-4">

<h5 class="mb-3">Lokasi Pembeli</h5>

<?php if(!empty($order['latitude'])): ?>

<div id="map" style="height:300px;"></div>

<?php else: ?>

<p class="text-muted">
Lokasi tidak tersedia
</p>

<?php endif; ?>

</div>

</div>

</div>


<!-- ORDER ITEMS -->

<div class="card card-modern p-4 mt-4">

<h5 class="mb-3">Produk Dibeli</h5>

<table class="table table-bordered">

<tr>
<th>Produk</th>
<th width="120">Harga</th>
<th width="80">Qty</th>
<th width="150">Subtotal</th>
</tr>

<?php while($item = mysqli_fetch_assoc($items)): ?>

<tr>

<td>
<?= htmlspecialchars($item['nama_produk']) ?>
</td>

<td>
Rp <?= number_format($item['harga'],0,',','.') ?>
</td>

<td>
<?= $item['qty'] ?>
</td>

<td>
Rp <?= number_format($item['subtotal'],0,',','.') ?>
</td>

</tr>

<?php endwhile; ?>

</table>

</div>

</div>

</div>

<?php if(!empty($order['latitude'])): ?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBj118F-Di2rzDSNYEhShCa6eit4YMB3Ls"></script>

<script>

function initMap(){

const location = {
lat: <?= $order['latitude'] ?>,
lng: <?= $order['longitude'] ?>
};

const map = new google.maps.Map(
document.getElementById("map"),
{
zoom:15,
center:location
});

new google.maps.Marker({
position:location,
map:map
});

}

window.onload = initMap;

</script>

<?php endif; ?>

<?php include "partials/footer.php"; ?>