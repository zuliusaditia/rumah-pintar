<?php
include "koneksi.php";
include "includes/header.php";

// Ambil data impact dari database
$impact_query = "SELECT * FROM impact_stats ORDER BY id ASC";
$impact_result = mysqli_query($conn, $impact_query);
?>

<!-- HERO SECTION -->
<section class="py-5 text-center bg-light">
    <div class="container">
        <h1 class="fw-bold mb-3">
            Membangun Masa Depan Melalui Pendidikan Sukarela
        </h1>
        <p class="lead text-muted">
            Rumah Pintar adalah sekolah sukarela dan yayasan sosial berbasis pendidikan 
            yang memberikan ruang belajar gratis bagi anak-anak yang membutuhkan.
        </p>
        <div class="mt-4">
            <a href="volunteer.php" class="btn btn-primary-custom me-2">
                Jadi Relawan
            </a>
            <a href="donasi.php" class="btn btn-outline-dark">
                Dukung Program
            </a>
        </div>
    </div>
</section>

<!-- CERITA AWAL -->
<section class="py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4">Cerita Kami</h2>
                <p class="text-muted">
                    Rumah Pintar lahir dari kepedulian terhadap anak-anak yang memiliki 
                    keterbatasan akses pendidikan. Dimulai dari kelas kecil dengan relawan 
                    sederhana, kami percaya bahwa setiap anak berhak mendapatkan kesempatan 
                    belajar yang layak.
                </p>
                <p class="text-muted">
                    Hari ini, Rumah Pintar berkembang menjadi ruang belajar alternatif 
                    yang terbuka bagi siapa saja yang ingin berkontribusi, baik sebagai 
                    relawan, donatur, maupun mitra kolaborasi.
                </p>
            </div>
            <div class="col-lg-6">
                <img src="assets/img/kegiatan.jpg" 
                     class="img-fluid rounded shadow-sm" 
                     alt="Kegiatan Rumah Pintar">
            </div>
        </div>
    </div>
</section>

<!-- VISI MISI -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row text-center">

            <div class="col-md-6 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100">
                    <h4 class="fw-bold">Visi</h4>
                    <p class="text-muted">
                        Menjadi ruang belajar alternatif yang inklusif, 
                        berkelanjutan, dan berdampak nyata bagi masa depan anak-anak Indonesia.
                    </p>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="p-4 shadow-sm rounded bg-white h-100">
                    <h4 class="fw-bold">Misi</h4>
                    <ul class="text-muted text-start">
                        <li>Memberikan pendidikan tambahan gratis.</li>
                        <li>Menggerakkan relawan muda untuk berkontribusi.</li>
                        <li>Membangun kolaborasi sosial & corporate.</li>
                        <li>Menjaga transparansi dalam setiap donasi.</li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- NILAI NILAI -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-5">Nilai-Nilai Kami</h2>
        <div class="row">

            <div class="col-md-3 mb-4">
                <h5>🤝 Kolaborasi</h5>
                <p class="text-muted small">
                    Bekerja bersama relawan, orang tua, dan mitra.
                </p>
            </div>

            <div class="col-md-3 mb-4">
                <h5>❤️ Kepedulian</h5>
                <p class="text-muted small">
                    Mengutamakan kebutuhan anak-anak dan masyarakat.
                </p>
            </div>

            <div class="col-md-3 mb-4">
                <h5>📖 Pendidikan</h5>
                <p class="text-muted small">
                    Fokus pada pembelajaran yang inklusif dan berkualitas.
                </p>
            </div>

            <div class="col-md-3 mb-4">
                <h5>🔍 Transparansi</h5>
                <p class="text-muted small">
                    Terbuka dalam pengelolaan program dan donasi.
                </p>
            </div>

        </div>
    </div>
</section>

<!-- IMPACT SECTION (DYNAMIC) -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold mb-5">Dampak Nyata Kami</h2>
        <div class="row">

        <?php while($impact = mysqli_fetch_assoc($impact_result)) { ?>
            <div class="col-md-3 mb-4">
                <h2 class="fw-bold text-primary">
                    <?php echo $impact['angka']; ?>
                </h2>
                <p class="text-muted">
                    <?php echo htmlspecialchars($impact['label']); ?>
                </p>
            </div>
        <?php } ?>

        </div>
    </div>
</section>

<!-- CTA -->
<section class="py-5 text-center">
    <div class="container">
        <h2 class="fw-bold mb-3">
            Mari Jadi Bagian dari Perubahan
        </h2>
        <p class="text-muted mb-4">
            Bersama kita bisa membuka lebih banyak ruang belajar 
            dan kesempatan untuk masa depan yang lebih baik.
        </p>

        <a href="volunteer.php" class="btn btn-primary-custom me-2">
            Daftar Relawan
        </a>

        <a href="donasi.php" class="btn btn-outline-dark">
            Donasi Sekarang
        </a>
    </div>
</section>

<?php include "includes/footer.php"; ?>