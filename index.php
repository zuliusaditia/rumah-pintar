<?php
include "koneksi.php";
include "includes/header.php";

// Ambil 3 artikel terbaru
$query = "SELECT * FROM articles WHERE status='publish' ORDER BY id DESC LIMIT 3";
$result = mysqli_query($conn, $query);
?>

<!-- HERO SECTION -->
<section class="hero d-flex align-items-center" style="background: linear-gradient(rgba(31,60,136,0.7), rgba(31,60,136,0.7)), url('assets/img/hero.jpeg'); background-size: cover; background-position: center; min-height: 90vh; color: white;">
    <div class="container text-center">
        <h1 class="fw-bold" style="font-size: 48px;">
            Membangun Masa Depan Anak Bersama Rumah Pintar
        </h1>
        <p class="mt-3 fs-5">
            Sekolah sukarela yang menghadirkan pendidikan dan harapan melalui semangat gotong royong.
        </p>

        <div class="mt-4">
            <a href="donasi.php" class="btn btn-primary-custom me-3">
                Donasi Sekarang
            </a>
            <a href="volunteer.php" class="btn btn-outline-light">
                Jadi Relawan
            </a>
        </div>
    </div>
</section>
<br>

<!-- IMPACT NUMBERS -->
<section class="section text-center">
    <div class="container">
        <h2 class="fw-bold mb-5">Dampak Rumah Pintar</h2>

        <div class="row g-4">

        <?php
        
        $query = "SELECT * FROM impact_stats ORDER BY id ASC";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>

            <div class="col-6 col-md-3">
                <div class="p-3">
                    
                    <!-- ICON -->
                    <?php if (!empty($row['icon'])) { ?>
                        <div class="mb-3" style="font-size: 32px; color:#1F3C88;">
                            <i class="bi bi-<?php echo htmlspecialchars($row['icon']); ?>"></i>
                        </div>
                    <?php } ?>

                    <!-- ANGKA -->
                    <div class="impact-number">
                        <?php echo htmlspecialchars($row['value']); ?>
                    </div>

                    <!-- LABEL -->
                    <div class="impact-label">
                        <?php echo htmlspecialchars($row['label']); ?>
                    </div>

                </div>
            </div>

        <?php } ?>

        </div>
    </div>
</section>

<!-- TENTANG SINGKAT -->
<section class="section bg-light">
    <div class="container text-center" style="color:#1F3C88;">
        <h2>Tentang Kami</h2>
        <p>
        Rumah Pintar adalah sekolah sukarela yang berdiri untuk membantu anak-anak di sekitar mendapatkan pendidikan tambahan,
        pendampingan belajar, serta pembinaan karakter.
        </p>
    </div>
</section>

<br>

<!-- PROGRAM -->
<section class="section section-soft">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold">Program Kami</h2>
            <p class="text-muted">Berbagai cara untuk ikut berkontribusi bersama Rumah Pintar</p>
        </div>

        <div class="row g-4">

            <!-- DONASI UANG -->
            <div class="col-md-6 col-lg-3">
                <div class="card-custom text-center h-100">
                    <div class="mb-3" style="font-size:32px; color:#1F3C88;">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                    <h5 class="fw-bold">Donasi Uang</h5>
                    <p class="text-muted">Dukung operasional sekolah dan kegiatan belajar anak-anak.</p>
                    <a href="donasi.php" class="btn btn-outline-custom mt-3">Selengkapnya</a>
                </div>
            </div>

            <!-- DONASI BARANG -->
            <div class="col-md-6 col-lg-3">
                <div class="card-custom text-center h-100">
                    <div class="mb-3" style="font-size:32px; color:#1F3C88;">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5 class="fw-bold">Donasi Barang</h5>
                    <p class="text-muted">Salurkan tas, sepatu, buku, dan perlengkapan sekolah.</p>
                    <a href="donasi_barang.php" class="btn btn-outline-custom mt-3">Selengkapnya</a>
                </div>
            </div>

            <!-- RELAWAN -->
            <div class="col-md-6 col-lg-3">
                <div class="card-custom text-center h-100">
                    <div class="mb-3" style="font-size:32px; color:#1F3C88;">
                        <i class="bi bi-people"></i>
                    </div>
                    <h5 class="fw-bold">Jadi Relawan</h5>
                    <p class="text-muted">Berbagi waktu dan ilmu untuk mendampingi anak-anak belajar.</p>
                    <a href="volunteer.php" class="btn btn-outline-custom mt-3">Selengkapnya</a>
                </div>
            </div>

            <!-- KOLABORASI -->
            <div class="col-md-6 col-lg-3">
                <div class="card-custom text-center h-100">
                    <div class="mb-3" style="font-size:32px; color:#1F3C88;">
                        <i class="bi bi-building"></i>
                    </div>
                    <h5 class="fw-bold">Kolaborasi</h5>
                    <p class="text-muted">Kerja sama bersama perusahaan & komunitas untuk dampak lebih luas.</p>
                    <a href="kolaborasi.php" class="btn btn-outline-custom mt-3">Selengkapnya</a>
                </div>
            </div>

        </div>
    </div>
</section>

<br>
<!-- PRODUCT -->
<section class="section">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Belanja Untuk Donasi</h2>
            <a href="shop/index.php" class="btn btn-outline-custom btn-sm">Lihat Semua</a>
        </div>

        <div class="product-scroll d-flex overflow-auto gap-3 pb-3">

        <?php
        $query = "SELECT * FROM products WHERE status='aktif' ORDER BY id DESC LIMIT 8";
        $result = mysqli_query($conn, $query);

        while($row = mysqli_fetch_assoc($result)) {
        ?>

            <div class="card-custom" style="min-width:250px;">

                <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                    class="img-fluid rounded mb-3"
                    style="height:150px; object-fit:cover;">

                <h6 class="fw-bold">
                    <?php echo htmlspecialchars($row['nama']); ?>
                </h6>

                <p class="text-muted small">
                    Rp <?php echo number_format($row['harga'],0,',','.'); ?>
                </p>

                <a href="detail_barang.php?id=<?php echo $row['id']; ?>" 
                    class="btn btn-primary-custom btn-sm w-100">
                    Beli
                </a>

            </div>

        <?php } ?>

        </div>

    </div>
</section>

<!-- KEGIATAN TERBARU -->
<section class="section">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Kegiatan Terbaru</h2>
            <p class="text-muted">Dokumentasi aktivitas dan program Rumah Pintar</p>
        </div>

        <div class="row g-4">

        <?php
        include "koneksi.php";

        $query = "SELECT * FROM articles WHERE status='publish' ORDER BY id DESC LIMIT 3";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
        ?>

            <div class="col-md-4">
                <div class="card-custom h-100">

                    <?php if (!empty($row['image'])) { ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                            class="img-fluid rounded mb-3" 
                            style="height:200px; object-fit:cover;">
                    <?php } ?>

                    <h5 class="fw-bold">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h5>

                    <p class="text-muted">
                        <?php 
                        echo substr(strip_tags($row['content']), 0, 100) . "..."; 
                        ?>
                    </p>

                    <a href="detail_kegiatan.php?id=<?php echo $row['id']; ?>" 
                    class="btn btn-outline-custom mt-2">
                    Baca Selengkapnya
                    </a>

                </div>
            </div>

        <?php } ?>

        </div>
    </div>
</section>
<br>

<!-- CTA -->
<div style="background:#f97316; color:white; padding:30px; text-align:center;">
    <h2>Yuk Jadi Bagian dari Rumah Pintar</h2>
    <p>Dukung kegiatan kami melalui donasi atau menjadi relawan.</p>
    <a href="#" style="background:white; color:#f97316; padding:10px 20px; text-decoration:none;">
        Dukung Sekarang
    </a>
</div>

<?php include "includes/footer.php"; ?>