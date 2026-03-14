<?php

include "../koneksi.php";
include "header_shop.php";

$id = $_GET['id'];

$query = mysqli_query($conn,"SELECT * FROM products WHERE id='$id'");
$data = mysqli_fetch_assoc($query);

$stok = (int)$data['stok'];

?>

<div class="container py-5">

<div class="row">

<div class="col-md-6">

<img
src="../uploads/<?php echo htmlspecialchars($data['image']); ?>"
class="img-fluid rounded"
>

</div>


<div class="col-md-6">

<h3 class="fw-bold">
<?php echo htmlspecialchars($data['nama']); ?>
</h3>

<h4 class="text-primary mt-3">
Rp <?php echo number_format($data['harga'],0,',','.'); ?>
</h4>

<p class="mt-3">
<?php echo nl2br(htmlspecialchars($data['deskripsi'])); ?>
</p>

<p class="mt-2">

<?php if($stok > 0){ ?>

<span class="text-success">
Stok tersedia (<?php echo $stok ?>)
</span>

<?php } else { ?>

<span class="text-danger">
Stok habis
</span>

<?php } ?>

</p>


<div class="d-flex gap-2 mt-4">

<a href="checkout.php?id=<?php echo $data['id']; ?>"
class="btn btn-primary">
Beli Sekarang
</a>

<button
class="btn btn-outline-dark add-cart"
data-id="<?php echo $data['id']; ?>"
>
Tambah ke Keranjang
</button>

</div>

</div>

</div>

</div>

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