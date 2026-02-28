<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Toko Donasi - Rumah Pintar</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Design System -->
    <link href="../assets/css/design-system.css" rel="stylesheet">
    
    <!-- Leaflet CDN for maps (if needed) -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
<div class="container">

<a class="navbar-brand fw-bold" href="index.php">
    Toko Donasi
</a>

<ul class="navbar-nav ms-auto">
    <li class="nav-item">
        <a class="nav-link position-relative" href="cart.php">
            🛒
            <?php if ($cart_count > 0) { ?>
            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                <?= $cart_count ?>
            </span>
            <?php } ?>
        </a>
    </li>
</ul>

</div>
</nav>