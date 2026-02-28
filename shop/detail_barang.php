<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../koneksi.php";
include "header_shop.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "Produk tidak valid.";
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM products WHERE id=? AND status='aktif'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Produk tidak ditemukan.";
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$error_message = "";

// ==========================
// HANDLE ADD TO CART
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $qty = (int) $_POST['qty'];

    // Cek stok realtime
    $stmt = $conn->prepare("SELECT stok FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();
    $dbProduct = $res->fetch_assoc();
    $stmt->close();

    if ($qty <= 0) {
        $error_message = "Jumlah tidak valid.";
    } elseif ($qty > $dbProduct['stok']) {
        $error_message = "Stok tidak mencukupi.";
    } else {

        // Kalau produk sudah ada → tambah qty
        if (isset($_SESSION['cart'][$id])) {

            $newQty = $_SESSION['cart'][$id]['qty'] + $qty;

            if ($newQty > $dbProduct['stok']) {
                $error_message = "Total di keranjang melebihi stok.";
            } else {
                $_SESSION['cart'][$id]['qty'] = $newQty;
            }

        } else {
            // Tambah produk baru
            $_SESSION['cart'][$id] = [
                'id' => $product['id'],
                'nama' => $product['nama'],
                'harga' => $product['harga'],
                'qty' => $qty
            ];
        }

        if (empty($error_message)) {
            header("Location: cart.php");
            exit;
        }
    }
}
?>

<section class="section">
<div class="container">
<div class="row">

<div class="col-md-6">
    <img src="../uploads/<?= htmlspecialchars($product['image']); ?>" 
        class="img-fluid rounded">
</div>

<div class="col-md-6">
    <h2 class="fw-bold"><?= htmlspecialchars($product['nama']); ?></h2>
    <p class="text-muted"><?= htmlspecialchars($product['deskripsi']); ?></p>
    <h4 class="fw-bold">Rp <?= number_format($product['harga'],0,',','.'); ?></h4>

    <p>Stok: 
        <?php if ($product['stok'] > 0) { ?>
            <span class="text-success"><?= $product['stok']; ?> tersedia</span>
        <?php } else { ?>
            <span class="text-danger">Habis</span>
        <?php } ?>
    </p>

    <?php if (!empty($error_message)) { ?>
        <div class="alert alert-danger"><?= $error_message; ?></div>
    <?php } ?>

    <?php if ($product['stok'] > 0) { ?>
        <form method="POST">
            <div class="mb-3">
                <input type="number" 
                        name="qty" 
                        class="form-control" 
                        min="1" 
                        max="<?= $product['stok']; ?>" 
                        required>
            </div>

            <button class="btn btn-primary-custom w-100">
                Tambah ke Keranjang
            </button>
        </form>
    <?php } else { ?>
        <button class="btn btn-secondary w-100" disabled>
            Stok Habis
        </button>
    <?php } ?>

</div>

</div>
</div>
</section>

<script>
document.querySelector("form")?.addEventListener("submit", function(e) {

    const qtyInput = document.querySelector("input[name='qty']");
    if (!qtyInput) return;

    const qty = parseInt(qtyInput.value);
    const max = parseInt(qtyInput.max);

    if (qty > max) {
        alert("Stok tidak mencukupi!");
        e.preventDefault();
    }
});
</script>

<?php include "includes/footer.php"; ?>