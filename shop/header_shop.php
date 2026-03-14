<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Shop - Rumah Pintar</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

<style>

body{
font-family:'Poppins',sans-serif;
background:#fafafa;
}

.shop-navbar{
background:white;
border-bottom:1px solid #eee;
}

.shop-navbar .nav-link{
font-weight:500;
}

/* CART ICON    */
.cart-icon{
position:relative;
font-size:22px;
color:#111;
text-decoration:none;
}

#cart-count{
position:absolute;
top:-8px;
right:-10px;
background:#ef4444;
color:white;
font-size:12px;
padding:2px 6px;
border-radius:20px;
}

</style>

</head>

<body>

<!-- SHOP NAVBAR -->
<nav class="navbar navbar-expand-lg shop-navbar">

<div class="container">

<a class="navbar-brand fw-bold" href="index.php">
Shop Rumah Pintar
</a>

<div class="ms-auto d-flex gap-2">

<a href="cart.php" class="cart-icon">
    <i class="bi bi-cart"></i>
    <span id="cart-count">0</span>
</a>

<a href="../index.php" class="btn btn-outline-secondary">

Kembali ke Website

</a>

</div>

</div>

</nav>