<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// Ambil statistik
$total_volunteer = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM volunteers"))['total'];
$pending_volunteer = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM volunteers WHERE status='pending'"))['total'];
$total_orders = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM orders"))['total'];
$total_articles = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) as total FROM articles"))['total'];

include "partials/header.php";
?>

<div class="container-fluid">
<div class="row">

<?php include "partials/sidebar.php"; ?>

<div class="col-md-9 col-lg-10 p-4">

<h2 class="mb-4">Dashboard</h2>

<div class="row g-4">

    <div class="col-md-6 col-lg-3">
        <div class="card card-dashboard p-3">
            <h6>Total Volunteer</h6>
            <h3><?= $total_volunteer ?></h3>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card card-dashboard p-3">
            <h6>Pending Volunteer</h6>
            <h3 class="text-warning"><?= $pending_volunteer ?></h3>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card card-dashboard p-3">
            <h6>Total Orders</h6>
            <h3><?= $total_orders ?></h3>
        </div>
    </div>

    <div class="col-md-6 col-lg-3">
        <div class="card card-dashboard p-3">
            <h6>Total Artikel</h6>
            <h3><?= $total_articles ?></h3>
        </div>
    </div>

</div>

</div>
</div>
</div>

<?php include "partials/footer.php"; ?>