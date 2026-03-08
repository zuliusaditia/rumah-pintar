<?php
/* ===============================
EDIT 1
HAPUS LIMIT 1 supaya semua slide diambil
=============================== */

$hero_query = mysqli_query($conn,"
SELECT * FROM hero_slides
WHERE status='aktif'
ORDER BY sort_order ASC
");
?>

<style>

/* CSS lama tetap dipakai */
.hero-section{
position:relative;
height:calc(100vh - 80px);
background-size:cover;
background-position:center;
display:flex;
align-items:center;
justify-content:center;
color:white;
text-align:center;

filter:contrast(1.05);
}

.hero-overlay{
position:absolute;
inset:0;

background:linear-gradient(
to bottom,
rgba(31,60,136,0.00) 0%,
rgba(31,60,136,0.25) 40%,
rgba(31,60,136,0.65) 75%,
rgba(31,60,136,0.95) 100%
);
}

.hero-content{
position:relative;
z-index:2;
max-width:800px;
}

.hero-title{
font-size:clamp(36px,5vw,56px);
font-weight:700;
line-height:1.2;
text-shadow:0 5px 20px rgba(0,0,0,0.4);
}

.hero-subtitle{
font-size:18px;
margin-top:15px;
opacity:0.95;
text-shadow:0 4px 10px rgba(0,0,0,0.4);
}

.hero-actions{
margin-top:25px;
display:flex;
justify-content:center;
gap:15px;
flex-wrap:wrap;
}

</style>


<!-- ===============================
EDIT 2
TAMBAH CAROUSEL WRAPPER
=============================== -->

<section class="hero-slider">

<div id="heroCarousel"
class="carousel slide carousel-fade"
data-bs-ride="carousel"
data-bs-interval="5000">

<div class="carousel-inner">

<?php
$active = true;

/* ===============================
EDIT 3
LOOP SEMUA HERO
=============================== */

while($hero = mysqli_fetch_assoc($hero_query)){
?>

<div class="carousel-item <?= $active ? 'active' : '' ?>">

<section class="hero-section"
style="background-image:url('uploads/<?= $hero['image'] ?>')">

<div class="hero-overlay"></div>

<div class="container hero-content">

<h1 class="hero-title">
<?= htmlspecialchars($hero['title']) ?>
</h1>

<p class="hero-subtitle">
<?= htmlspecialchars($hero['subtitle']) ?>
</p>

<div class="hero-actions">

<a href="donasi.php" class="btn btn-primary-custom btn-lg">
Donasi Sekarang
</a>

<a href="volunteer.php" class="btn btn-outline-light btn-lg">
Jadi Relawan
</a>

</div>

</div>

</section>

</div>

<?php
$active = false;
}
?>

</div>


<!-- ===============================
EDIT 4
TAMBAH BUTTON NAVIGATION
=============================== -->

<button class="carousel-control-prev"
type="button"
data-bs-target="#heroCarousel"
data-bs-slide="prev">

<span class="carousel-control-prev-icon"></span>

</button>

<button class="carousel-control-next"
type="button"
data-bs-target="#heroCarousel"
data-bs-slide="next">

<span class="carousel-control-next-icon"></span>

</button>

</div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>