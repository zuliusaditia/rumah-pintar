<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include "../koneksi.php";   // koneksi dulu
include "header_shop.php";

/* =========================
GET SETTINGS
========================= */

function getSettings(){
    global $conn;
    $result = mysqli_query($conn, "SELECT * FROM settings LIMIT 1");
    return mysqli_fetch_assoc($result);
}

$settings = getSettings();

if (empty($_SESSION['cart'])) {
    echo "<div class='container mt-5'>
            <div class='alert alert-info'>Keranjang kosong.</div>
          </div>";
    exit;
}

$cart_items = [];
$total = 0;

foreach ($_SESSION['cart'] as $product_id => $qty) {

    $stmt = $conn->prepare("SELECT id,nama,harga FROM products WHERE id=?");
    $stmt->bind_param("i",$product_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();

    if(!$product) continue;

    $subtotal = $product['harga'] * $qty;

    $cart_items[] = [
        "id"=>$product_id,
        "nama"=>$product['nama'],
        "harga"=>$product['harga'],
        "qty"=>$qty,
        "subtotal"=>$subtotal
    ];

    $total += $subtotal;
}

$success = false;
$kode_order = "";

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);
    $patokan = $_POST['patokan'] ?? null;
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    if(!$nama || !$no_hp || !$alamat){

        echo "<div class='alert alert-danger container mt-3'>
        Semua field wajib diisi
        </div>";

    }else{

        $kode_order = "RP-" . date("Ymd") . "-" . rand(1000,9999);

        $stmt = $conn->prepare("
        INSERT INTO orders
        (kode_order,nama,no_hp,alamat,patokan,latitude,longitude,total)
        VALUES (?,?,?,?,?,?,?,?)
        ");

        $stmt->bind_param(
        "sssssssi",
        $kode_order,
        $nama,
        $no_hp,
        $alamat,
        $patokan,
        $latitude,
        $longitude,
        $total
        );

        $stmt->execute();
        $order_id = $stmt->insert_id;
        $stmt->close();

        foreach($cart_items as $item){

            $stmt = $conn->prepare("
            INSERT INTO order_items
            (order_id,product_id,nama_produk,harga,qty,subtotal)
            VALUES (?,?,?,?,?,?)
            ");

            $stmt->bind_param(
            "iisiii",
            $order_id,
            $item['id'],
            $item['nama'],
            $item['harga'],
            $item['qty'],
            $item['subtotal']
            );

            $stmt->execute();
            $stmt->close();

            $stmt = $conn->prepare("
            UPDATE products
            SET stok = stok - ?
            WHERE id = ?
            ");

            $stmt->bind_param("ii",$item['qty'],$item['id']);
            $stmt->execute();
            $stmt->close();
        }

        unset($_SESSION['cart']);
        $success = true;
    }
}
?>

<style>
.rekening-number {
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
    font-size:24px;
    font-weight:600;
    margin-bottom:8px;
}
.btn-primary-custom{
    background:#2F4B8F;
    color:white;
    border-color:#2F4B8F;
}

.btn-primary-custom:hover{
    background:#243c73;
    border-color:#243c73;
    color:white;
}

.copy-btn{
    border:none;
    background:#2F4B8F;
    color:white;
    padding:6px 12px;
    border-radius:6px;
    font-size:14px;
    cursor:pointer;
    transition:0.2s;
}

.copy-btn:hover{
    background:#1e3a6f;
}

.copy-btn.copied{
    background:#16a34a;
}

</style>

<section class="section">
<div class="container">

<?php if($success){ ?>

<div class="card p-5 text-center shadow-sm">

<h3 class="fw-bold text-success mb-3">
Pesanan Berhasil Dibuat
</h3>

<p>Kode Order Anda:</p>

<h4 class="fw-bold">
<?= htmlspecialchars($kode_order) ?>
</h4>

<hr>

<h5 class="mt-4">Silakan Transfer ke:</h5>

<div class="rekening-number">

        <span id="rekeningText">
            <?= htmlspecialchars($settings['rekening']) ?>
        </span>

        <button onclick="copyRekening()" class="copy-btn">
            Copy
        </button>

    </div>

    <h6 class="mb-1">
        <?= htmlspecialchars($settings['bank_name']) ?>
    </h6>

    <p class="text-muted mb-0">
        a.n <?= htmlspecialchars($settings['account_holder']) ?>
    </p>
<p class="mt-3">
Setelah transfer kirim bukti pembayaran untuk memproses pesanan Anda.
</p>

<a href="upload_bukti.php?order=<?= urlencode($kode_order) ?>" class="btn btn-primary-custom mt-3">
Upload Bukti Transfer
</a>

<a href="index.php" class="btn btn-primary-custom mt-3">
Kembali ke Toko
</a>

</div>

<?php } else { ?>

<div class="row">

<div class="col-lg-7">

<div class="card p-4 shadow-sm mb-4">

<h4 class="fw-bold mb-4">Informasi Pembeli</h4>

<form method="POST">

<div class="mb-3">
<label class="form-label">Nama Lengkap</label>
<input type="text" name="nama" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">No WhatsApp</label>
<input type="text" name="no_hp" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Cari Alamat</label>

<div class="input-group">

<input
type="text"
id="alamatSearch"
class="form-control"
placeholder="Cari alamat">

<button
type="button"
class="btn btn-outline-primary"
id="useLocation">

Gunakan Lokasi Saya

</button>

</div>
</div>

<div id="map" style="height:300px;" class="rounded mb-3"></div>

<div class="mb-3">
<label class="form-label">Alamat Lengkap</label>

<textarea
name="alamat"
class="form-control"
rows="4"
required></textarea>
</div>

<div class="mb-3">
<label class="form-label">Patokan</label>

<input
type="text"
name="patokan"
class="form-control">
</div>

<input type="hidden" name="latitude" id="latitude">
<input type="hidden" name="longitude" id="longitude">

<button class="btn btn-primary-custom w-100">
Buat Pesanan
</button>

</form>

</div>

</div>

<div class="col-lg-5">

<div class="card p-4 shadow-sm">

<h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

<?php foreach($cart_items as $item){ ?>

<div class="d-flex justify-content-between mb-2">

<div>
<?= htmlspecialchars($item['nama']) ?>
<br>
<small>x<?= $item['qty'] ?></small>
</div>

<div>
Rp <?= number_format($item['subtotal'],0,',','.') ?>
</div>

</div>

<?php } ?>

<hr>

<div class="d-flex justify-content-between fw-bold">

<div>Total</div>
<div>Rp <?= number_format($total,0,',','.') ?></div>

</div>

</div>

</div>

</div>

<?php } ?>

</div>
</section>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBj118F-Di2rzDSNYEhShCa6eit4YMB3Ls&libraries=places&callback=initMap" async defer></script>

<script>

let map;
let marker;
let autocomplete;

function initMap(){

const defaultLocation = { lat: -6.200000, lng: 106.816666 };

map = new google.maps.Map(document.getElementById("map"), {
zoom: 13,
center: defaultLocation
});

marker = new google.maps.Marker({
position: defaultLocation,
map: map,
draggable: true
});

function updateLatLng(position){

document.getElementById("latitude").value = position.lat();
document.getElementById("longitude").value = position.lng();

}

updateLatLng(marker.getPosition());

marker.addListener("dragend", function(){

updateLatLng(marker.getPosition());

reverseGeocode(marker.getPosition());

});

map.addListener("click", function(event){

marker.setPosition(event.latLng);

updateLatLng(event.latLng);

reverseGeocode(event.latLng);

});

const input = document.getElementById("alamatSearch");

autocomplete = new google.maps.places.Autocomplete(input,{
componentRestrictions:{country:"id"}
});

autocomplete.addListener("place_changed", function(){

const place = autocomplete.getPlace();

if(!place.geometry) return;

map.setCenter(place.geometry.location);
map.setZoom(16);

marker.setPosition(place.geometry.location);

updateLatLng(place.geometry.location);

document.querySelector("textarea[name='alamat']").value = place.formatted_address;

});

document.getElementById("useLocation").addEventListener("click",function(){

if(navigator.geolocation){

navigator.geolocation.getCurrentPosition(function(position){

const pos={
lat:position.coords.latitude,
lng:position.coords.longitude
};

map.setCenter(pos);
map.setZoom(16);

marker.setPosition(pos);

updateLatLng(marker.getPosition());

reverseGeocode(marker.getPosition());

});

}

});

function reverseGeocode(location){

const geocoder=new google.maps.Geocoder();

geocoder.geocode({location:location},function(results,status){

if(status==="OK"){
if(results[0]){
document.querySelector("textarea[name='alamat']").value=results[0].formatted_address;
}
}

});

}

}

function copyRekening(){
    const rekening = document.getElementById("rekening");
    rekening.select();
    document.execCommand("copy");
}


</script>

<?php include "footer_shop.php"; ?>