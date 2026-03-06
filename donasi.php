<?php
include "includes/header.php";
?>

<style>
.donasi-section{display:none;}
.donasi-section.active{display:block;}

.donasi-switcher{
display:flex;
justify-content:center;
gap:12px;
margin-bottom:40px;
}

.switch-btn{
padding:12px 28px;
border:none;
border-radius:10px;
background:#e5e5e5;
font-weight:600;
cursor:pointer;
}

.switch-btn.active{
background:#2F4B8F;
color:white;
}
</style>


<section class="section text-center">
<div class="container">

<h1 class="fw-bold">Donasi Rumah Pintar</h1>
<p class="text-muted">Pilih jenis donasi</p>

<br><br>

<div class="donasi-switcher">

<button class="switch-btn active" data-target="uang">
Donasi Uang
</button>

<button class="switch-btn" data-target="barang">
Donasi Barang
</button>

</div>


<div id="section-uang" class="donasi-section active">
<?php include "donasi/donasi_uang_form.php"; ?>
</div>

<div id="section-barang" class="donasi-section">
<?php include "donasi/donasi_barang_form.php"; ?>
</div>

</div>
</section>


<script>

document.addEventListener("DOMContentLoaded", function(){

const buttons=document.querySelectorAll(".switch-btn");
const uang=document.getElementById("section-uang");
const barang=document.getElementById("section-barang");

buttons.forEach(btn=>{
btn.addEventListener("click",function(){

buttons.forEach(b=>b.classList.remove("active"));
this.classList.add("active");

if(this.dataset.target==="uang"){
uang.classList.add("active");
barang.classList.remove("active");
}else{
barang.classList.add("active");
uang.classList.remove("active");
}

});
});

});

</script>

<?php include "includes/footer.php"; ?>

<?php if(isset($_GET['status']) && $_GET['status']=='success'){ ?>

<script>

document.addEventListener("DOMContentLoaded", function(){

Swal.fire({
icon:'success',
title:'Donasi Berhasil!',
text:'Terima kasih telah berdonasi 🙏',
confirmButtonColor:'#2F4B8F'
});

});

</script>

<?php } ?>