<?php
session_start();
include "../koneksi.php";
include "header_shop.php";

$cart = $_SESSION['cart'] ?? [];

$products = [];
$total = 0;

if($cart){

$ids = implode(",",array_keys($cart));

$query = mysqli_query($conn,"
SELECT * FROM products
WHERE id IN ($ids)
");

while($row = mysqli_fetch_assoc($query)){

$row['qty'] = $cart[$row['id']];
$row['subtotal'] = $row['harga'] * $row['qty'];

$total += $row['subtotal'];

$products[] = $row;

}

}
?>

<section class="section">

<div class="container">

<h2 class="fw-bold mb-4">
Keranjang Belanja
</h2>

<a href="index.php" class="btn btn-outline-custom mb-4">
← Lanjut Belanja
</a>

<?php if(!$products): ?>

<div class="alert alert-info">
Keranjang masih kosong.
</div>

<?php else: ?>

<div class="table-responsive">

<table class="table align-middle">

<thead>
<tr>
<th>Produk</th>
<th>Harga</th>
<th>Qty</th>
<th>Subtotal</th>
<th></th>
</tr>
</thead>

<tbody>

<?php foreach($products as $p): ?>

<tr>

<td class="d-flex align-items-center gap-3">

<img
src="../uploads/<?= $p['image'] ?>"
style="width:70px;height:70px;object-fit:cover;border-radius:8px;"
>

<div>
<strong><?= htmlspecialchars($p['nama']) ?></strong>
</div>

</td>

<td>
Rp <?= number_format($p['harga'],0,',','.') ?>
</td>

<td>

<form action="update_cart.php" method="POST" class="d-flex">

<input type="hidden" name="id" value="<?= $p['id'] ?>">

<input
type="number"
name="qty"
value="<?= $p['qty'] ?>"
min="1"
class="form-control"
style="width:80px"
>

<button class="btn btn-sm btn-outline-secondary ms-2">
Update
</button>

</form>

</td>

<td>
Rp <?= number_format($p['subtotal'],0,',','.') ?>
</td>

<td>

<a
href="remove_cart.php?id=<?= $p['id'] ?>"
class="btn btn-sm btn-danger">
Hapus
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

<div class="text-end mt-4">

<h4 class="fw-bold">
Total: Rp <?= number_format($total,0,',','.') ?>
</h4>

<a href="checkout.php" class="btn btn-primary-custom mt-3">
Checkout
</a>

</div>

<?php endif; ?>

</div>

</section>

<?php include "includes/footer.php"; ?>