<?php
include "koneksi.php";
include "includes/header.php";
?>

<!-- HERO -->
<section class="hero-section"
style="background:url('assets/img/kolaborasi.jpg') center/cover;">

<div class="hero-overlay"></div>

<div class="container hero-content text-center">

<h1 class="hero-title">
Kolaborasi Bersama Rumah Pintar
</h1>

<p class="hero-subtitle">
Mari bersama menciptakan dampak pendidikan yang lebih luas bagi anak-anak.
</p>

<div class="hero-actions">
<a href="#formKolaborasi" class="btn btn-primary-custom btn-lg">
Ajukan Kolaborasi
</a>
</div>

</div>

</section>


<!-- TENTANG KOLABORASI -->
<section class="section">

<div class="container">

<div class="row align-items-center g-5">

<div class="col-md-6">

<h2 class="fw-bold">
Mengapa Berkolaborasi?
</h2>

<p class="text-muted mt-3">
Rumah Pintar membuka kesempatan kolaborasi dengan berbagai pihak seperti 
perusahaan, komunitas, universitas, maupun individu untuk bersama-sama 
memberikan dampak positif bagi pendidikan anak-anak.
</p>

<p class="text-muted">
Melalui kolaborasi ini kita dapat memperluas akses pendidikan, menyediakan 
fasilitas belajar, serta menciptakan kegiatan yang bermanfaat bagi masyarakat.
</p>

</div>

<div class="col-md-6 text-center">

<img src="assets/img/kolaborasi-team.jpg"
class="img-fluid rounded shadow">

</div>

</div>

</div>

</section>


<!-- JENIS KOLABORASI -->
<section class="section section-soft">

<div class="container">

<div class="text-center mb-5">
<h2 class="fw-bold">Jenis Kolaborasi</h2>
<p class="text-muted">
Beberapa bentuk kolaborasi yang dapat dilakukan bersama Rumah Pintar
</p>
</div>

<div class="row g-4">

<!-- CSR -->
<div class="col-md-4">

<div class="card-custom text-center h-100">

<div class="program-icon">
<i class="bi bi-building"></i>
</div>

<h5 class="fw-bold">
Corporate CSR
</h5>

<p class="text-muted">
Perusahaan dapat mendukung program pendidikan melalui program CSR 
seperti bantuan fasilitas belajar, beasiswa, atau kegiatan sosial.
</p>

</div>

</div>


<!-- KOMUNITAS -->
<div class="col-md-4">

<div class="card-custom text-center h-100">

<div class="program-icon">
<i class="bi bi-people"></i>
</div>

<h5 class="fw-bold">
Kolaborasi Komunitas
</h5>

<p class="text-muted">
Komunitas dapat mengadakan kegiatan bersama seperti workshop, 
kelas inspirasi, dan kegiatan edukatif untuk anak-anak.
</p>

</div>

</div>


<!-- UNIVERSITAS -->
<div class="col-md-4">

<div class="card-custom text-center h-100">

<div class="program-icon">
<i class="bi bi-mortarboard"></i>
</div>

<h5 class="fw-bold">
Kolaborasi Pendidikan
</h5>

<p class="text-muted">
Universitas atau sekolah dapat bekerja sama dalam bentuk program 
pengabdian masyarakat dan kegiatan edukasi.
</p>

</div>

</div>

</div>

</div>

</section>


<!-- PARTNER -->
<section class="section">

<div class="container">

<div class="text-center mb-5">
<h2 class="fw-bold">
Partner & Kolaborator
</h2>
<p class="text-muted">
Beberapa pihak yang telah bekerja sama dengan Rumah Pintar
</p>
</div>

<div class="row text-center g-4">

<div class="col-6 col-md-3">
<img src="assets/img/partner1.png" class="img-fluid opacity-75">
</div>

<div class="col-6 col-md-3">
<img src="assets/img/partner2.png" class="img-fluid opacity-75">
</div>

<div class="col-6 col-md-3">
<img src="assets/img/partner3.png" class="img-fluid opacity-75">
</div>

<div class="col-6 col-md-3">
<img src="assets/img/partner4.png" class="img-fluid opacity-75">
</div>

</div>

</div>

</section>


<!-- FORM KOLABORASI -->
<section id="formKolaborasi" class="section section-soft">

<div class="container" style="max-width:700px;">

<div class="card-custom">

<h3 class="fw-bold text-center mb-4">
Ajukan Kolaborasi
</h3>

<form method="POST" action="process_kolaborasi.php">

<div class="mb-3">
<label class="form-label">Nama</label>
<input type="text" name="nama" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>
<input type="email" name="email" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Organisasi / Perusahaan</label>
<input type="text" name="organisasi" class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Pesan Kolaborasi</label>
<textarea name="pesan" rows="4" class="form-control" required></textarea>
</div>

<button class="btn btn-primary-custom w-100">
Kirim Pengajuan
</button>

</form>

</div>

</div>

</section>

<?php include "includes/footer.php"; ?>