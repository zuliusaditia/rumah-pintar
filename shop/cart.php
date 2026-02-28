<?php
session_start();
include "../koneksi.php";
include "header_shop.php";

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// =======================
// HANDLE UPDATE QTY
// =======================
if (isset($_POST['update'])) {

    foreach ($_POST['qty'] as $id => $qty) {

        $qty = (int) $qty;

        // cek stok terbaru
        $stmt = $conn->prepare("SELECT stok FROM products WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $stmt->close();

        if ($qty <= 0) {
            unset($_SESSION['cart'][$id]);
        } elseif ($qty <= $product['stok']) {
            $_SESSION['cart'][$id]['qty'] = $qty;
        }
    }
}

// =======================
// HANDLE REMOVE
// =======================
if (isset($_GET['remove'])) {
    $id = (int) $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

// =======================
// HITUNG TOTAL
// =======================
$total = 0;
?>

<section class="section">
<div class="container">
<h2 class="fw-bold mb-4">Keranjang Belanja</h2>

<?php if (empty($_SESSION['cart'])) { ?>
    <div class="alert alert-info">Keranjang kosong.</div>
<?php } else { ?>

<form method="POST">

<table class="table align-middle">

<tr>
    <th>Produk</th>
    <th>Harga</th>
    <th>Qty</th>
    <th>Subtotal</th>
    <th></th>
</tr>

<?php foreach ($_SESSION['cart'] as $id => $item) {

    $subtotal = $item['harga'] * $item['qty'];
    $total += $subtotal;
?>

<tr>
    <td><?= htmlspecialchars($item['nama']); ?></td>

    <td>Rp <?= number_format($item['harga'],0,',','.'); ?></td>

    <td style="width:120px;">
        <input type="number"
            name="qty[<?= $id ?>]"
            value="<?= $item['qty'] ?>"
            min="1"
            class="form-control">
    </td>

    <td>Rp <?= number_format($subtotal,0,',','.'); ?></td>

    <td>
        <a href="cart.php?remove=<?= $id ?>" 
            class="btn btn-sm btn-danger">
            Hapus
        </a>
    </td>
</tr>

<?php } ?>

<tr>
    <td colspan="3" class="text-end fw-bold">Total</td>
    <td class="fw-bold">
        Rp <?= number_format($total,0,',','.'); ?>
    </td>
    <td></td>
</tr>

</table>

<div class="d-flex justify-content-between">

<button type="submit" name="update" 
        class="btn btn-outline-dark">
    Update Keranjang
</button>

<a href="checkout.php" class="btn btn-primary-custom">
    Checkout
</a>

</div>

</form>

<?php } ?>

</div>
</section>

<?php include "footer_shop.php"; ?>