<?php
include "koneksi.php";

$settings = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT * FROM settings LIMIT 1")
);


// Maintenance Mode
if ($settings && $settings['maintenance_mode'] == 1) {
    echo "
    <div style='
        height:100vh;
        display:flex;
        align-items:center;
        justify-content:center;
        font-family:Poppins;
        background:#f4f6f9;
    '>
        <div style='text-align:center'>
            <h1>🚧 Website Sedang Maintenance</h1>
            <p>Kami akan segera kembali.</p>
        </div>
    </div>
    ";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($settings['site_name'] ?? 'Rumah Pintar') ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="assets/css/design-system.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm navbar-custom">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php" style="color:#1F3C88;">

            <?php if(!empty($settings['logo'])): ?>
                <img src="uploads/<?= $settings['logo'] ?>" 
                    style="height:40px;margin-right:8px;">
            <?php endif; ?>

            <?= htmlspecialchars($settings['site_name'] ?? 'Rumah Pintar') ?>

        </a>

        <button class="navbar-toggler" type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="tentang.php">Tentang</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="kegiatan.php">Kegiatan</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="donasi.php">Donasi</a>
                </li>

                <li class="nav-item ms-lg-3">
                    <a class="btn btn-primary-custom" href="donasi.php">
                        Donasi Sekarang
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>