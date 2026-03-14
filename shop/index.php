<?php
include "../koneksi.php";
include "header_shop.php";

$query = "SELECT * FROM products WHERE status='aktif' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<!-- LOADING SCREEN -->
<div id="pageLoader">
    <div class="loader"></div>
</div>

<style>

/* =========================
LOADER
========================= */

#pageLoader{
position:fixed;
inset:0;
background:white;
display:flex;
align-items:center;
justify-content:center;
z-index:9999;
}

.loader{
width:50px;
height:50px;
border:4px solid #eee;
border-top:4px solid #f97316;
border-radius:50%;
animation:spin 1s linear infinite;
}

@keyframes spin{
0%{transform:rotate(0)}
100%{transform:rotate(360deg)}
}

/* =========================
SHOP HERO
========================= */

.shop-hero{
padding:60px 0;
background:#f8fafc;
text-align:center;
}

.shop-hero p{
max-width:700px;
margin:auto;
color:#6B7280;
}

/* =========================
PRODUCT CARD
========================= */

.product-card{
background:white;
border-radius:14px;
overflow:hidden;
box-shadow:0 6px 18px rgba(0,0,0,0.06);
transition:all .25s ease;
display:flex;
flex-direction:column;
height:100%;
}

.product-card:hover{
transform:translateY(-6px);
box-shadow:0 12px 28px rgba(0,0,0,0.08);
}


/* image */

.product-image-wrapper{
position:relative;
width:100%;
height:210px;
overflow:hidden;
background:#f3f4f6;
}

.product-img{
width:100%;
height:100%;
object-fit:cover;
}


/* stock badge */

.stock-badge{
position:absolute;
top:10px;
left:10px;
font-size:12px;
padding:4px 8px;
border-radius:6px;
font-weight:500;
}

.stock-badge.available{
background:#DCFCE7;
color:#166534;
}

.stock-badge.out{
background:#FEE2E2;
color:#991B1B;
}


/* info */

.product-info{
padding:14px;
}

.product-title{
font-weight:600;
font-size:15px;
margin-bottom:6px;
min-height:38px;
}

.product-price{
color:#1F3C88;
font-weight:600;
font-size:15px;
}


/* buttons */

.product-actions{
display:flex;
gap:8px;
padding:0 14px 14px;
margin-top:auto;
}

.btn-buy{
flex:1;
background:#1F3C88;
color:white;
border:none;
border-radius:8px;
padding:8px;
font-size:14px;
text-align:center;
text-decoration:none;
}

.btn-buy:hover{
background:#16306d;
}

.btn-cart{
flex:1;
border:1px solid #d1d5db;
background:white;
border-radius:8px;
padding:8px;
font-size:14px;
}

.btn-cart:hover{
background:#f3f4f6;
}

/* =========================
BACK BUTTON
========================= */

.back-home{
margin-bottom:30px;
}

/* ADDED TO CART ANIMATION */
.added-animation{
animation:addCart .6s ease;
}

@keyframes addCart{

0%{
transform:scale(1);
}

50%{
transform:scale(1.2);
}

100%{
transform:scale(1);
}

}

</style>


<!-- HERO / INTRO -->
<section class="shop-hero">

<div class="container">

<a href="../index.php" class="btn btn-outline-custom back-home">
← Kembali ke Beranda
</a>

<h2 class="fw-bold">
Belanja Sambil Berdonasi
</h2>

<p class="mt-3">
Setiap pembelian produk membantu mendukung kegiatan pendidikan
di Rumah Pintar. Seluruh keuntungan digunakan untuk program
belajar anak-anak.
</p>

</div>

</section>


<!-- PRODUCT LIST -->
<section class="section">

<div class="container">

<div class="row g-4">

<?php while($row = mysqli_fetch_assoc($result)) { 

$stok = (int)$row['stok'];
?>

<div class="col-md-6 col-lg-3">

<div class="product-card">

<div class="product-image-wrapper">

<img
src="../uploads/<?php echo htmlspecialchars($row['image']); ?>"
class="product-img"
>

<?php if($stok > 0): ?>
<span class="stock-badge available">
Stok <?php echo $stok; ?>
</span>
<?php else: ?>
<span class="stock-badge out">
Habis
</span>
<?php endif; ?>

</div>


<div class="product-info">

<h6 class="product-title">
<?php echo htmlspecialchars($row['nama']); ?>
</h6>

<div class="product-price">
Rp <?php echo number_format($row['harga'],0,',','.'); ?>
</div>

</div>


<div class="product-actions">

<a href="detail_barang.php?id=<?php echo $row['id']; ?>"
class="btn-buy">
Beli
</a>

<button
class="btn-cart add-cart"
data-id="<?php echo $row['id']; ?>"
>
Keranjang
</button>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</section>


<script>

/* =========================
PAGE LOADER FIX
========================= */

window.addEventListener("load", function(){

const loader = document.getElementById("pageLoader");

if(loader){
loader.style.opacity = "0";
loader.style.pointerEvents = "none";
loader.style.transition = "opacity .4s ease";

setTimeout(()=>{
loader.style.display = "none";
},400);

}

});

//add to cart button
const cartButtons = document.querySelectorAll(".add-cart");

cartButtons.forEach(button => {

button.addEventListener("click", function(){

const id = this.getAttribute("data-id");

fetch("update_cart.php", {

method: "POST",

headers: {
"Content-Type": "application/x-www-form-urlencoded"
},

body: "id=" + id + "&qty=1"

})

.then(response => response.text())

.then(data => {

showCartAnimation(this);

loadCartCount();

})

.catch(error => {

console.error("Error:", error);

});

});

});

function loadCartCount(){

fetch("cart_count.php")
.then(res => res.text())
.then(count => {

const counter = document.getElementById("cart-count");

if(counter){
counter.innerText = count;
}

});

}

loadCartCount();

function showCartAnimation(button){

button.classList.add("added-animation");

setTimeout(()=>{
button.classList.remove("added-animation");
},600);

}

</script>

<?php include "footer_shop.php"; ?>