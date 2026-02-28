<?php
$current = basename($_SERVER['PHP_SELF']);

$pending_volunteer = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM volunteers WHERE status='pending'")
)['total'];
?>

<div class="col-md-3 col-lg-2 p-0 sidebar">

<div class="p-3 text-center border-bottom">
    <h5>Rumah Pintar</h5>
</div>

<a href="dashboard.php" class="<?= $current=='dashboard.php'?'active':'' ?>">
    <i class="bi bi-speedometer2"></i> Dashboard
</a>

<a href="list_artikel.php" class="<?= $current=='list_artikel.php'?'active':'' ?>">
    <i class="bi bi-file-earmark-text"></i> Artikel
</a>

<a href="kelola_impact.php" class="<?= $current=='kelola_impact.php'?'active':'' ?>">
    <i class="bi bi-bar-chart"></i> Impact
</a>

<a href="list_volunteer.php" class="<?= $current=='list_volunteer.php'?'active':'' ?>">
    <i class="bi bi-people"></i> Volunteer
    <?php if($pending_volunteer>0): ?>
        <span class="badge bg-danger ms-auto"><?= $pending_volunteer ?></span>
    <?php endif; ?>
</a>

<a href="list_donasi_barang.php" class="<?= $current=='list_donasi_barang.php'?'active':'' ?>">
    <i class="bi bi-box-seam"></i> Donasi Barang
</a>

<a href="list_donasi_money.php" class="<?= $current=='list_donasi_money.php'?'active':'' ?>">
    <i class="bi bi-cash-coin"></i> Donasi Uang
</a>

<a href="list_produk.php" class="<?= $current=='list_produk.php'?'active':'' ?>">
    <i class="bi bi-bag"></i> Produk
</a>

<a href="list_orders.php" class="<?= $current=='list_orders.php'?'active':'' ?>">
    <i class="bi bi-receipt"></i> Orders
</a>

<a href="logout.php" class="text-danger">
    <i class="bi bi-box-arrow-right"></i> Logout
</a>

</div>