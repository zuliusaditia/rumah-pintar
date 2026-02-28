<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "../koneksi.php";
include "header_shop.php";

if (empty($_SESSION['cart'])) {
    echo "<div class='container mt-5'>
            <div class='alert alert-info'>Keranjang kosong.</div>
          </div>";
    exit;
}

// =======================
// HITUNG TOTAL
// =======================
$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['harga'] * $item['qty'];
}

$success = false;
$kode_order = "";

// =======================
// HANDLE SUBMIT
// =======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = trim($_POST['nama']);
    $no_hp = trim($_POST['no_hp']);
    $alamat = trim($_POST['alamat']);
    $patokan = $_POST['patokan'] ?? null;
    $latitude = $_POST['latitude'] ?? null;
    $longitude = $_POST['longitude'] ?? null;

    if (empty($nama) || empty($no_hp) || empty($alamat)) {
        echo "<div class='alert alert-danger container mt-3'>
                Semua field wajib diisi.
              </div>";
    } else {

        $kode_order = "RP-" . date("Ymd") . "-" . rand(1000,9999);

        // =======================
        // INSERT ORDER
        // =======================
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

        // =======================
        // INSERT ORDER ITEMS
        // =======================
        foreach ($_SESSION['cart'] as $item) {

            $subtotal = $item['harga'] * $item['qty'];

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
                $subtotal
            );

            $stmt->execute();
            $stmt->close();

            // =======================
            // KURANGI STOK
            // =======================
            $stmt = $conn->prepare("
                UPDATE products 
                SET stok = stok - ? 
                WHERE id = ?
            ");

            $stmt->bind_param("ii", $item['qty'], $item['id']);
            $stmt->execute();
            $stmt->close();
        }

        unset($_SESSION['cart']);
        $success = true;
    }
}
?>

<section class="section">
<div class="container">

<?php if ($success) { ?>

    <div class="card p-5 text-center shadow-sm">
        <h3 class="fw-bold text-success mb-3">Pesanan Berhasil Dibuat 🎉</h3>
        <p>Kode Order Anda:</p>
        <h4 class="fw-bold"><?= htmlspecialchars($kode_order) ?></h4>

        <hr>

        <h5 class="mt-4">Silakan Transfer ke:</h5>
        <p class="mb-1">Bank BCA</p>
        <h4 class="fw-bold">1234567890</h4>
        <p>a.n Yayasan Rumah Pintar</p>

        <p class="mt-3">
            Setelah transfer, kirim bukti pembayaran ke WhatsApp kami.
        </p>

        <a href="index.php" class="btn btn-primary-custom mt-3">
            Kembali ke Toko
        </a>
    </div>

<?php } else { ?>

<div class="row">

<!-- LEFT FORM -->
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
                    <input type="text" id="alamatSearch" class="form-control" placeholder="Cari alamat...">
                    <button type="button" class="btn btn-outline-primary" id="useLocation">
                        Gunakan Lokasi Saya
                    </button>
                </div>
                <div id="suggestions" class="list-group position-absolute w-100" style="z-index:1000;"></div>
            </div>

            <div id="map" style="height:300px;" class="rounded mb-3"></div>

            <div class="mb-3">
                <label class="form-label">Alamat Lengkap</label>
                <textarea name="alamat" class="form-control" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Patokan</label>
                <input type="text" name="patokan" class="form-control" placeholder="Contoh: Depan Indomaret">
            </div>

            <input type="hidden" name="latitude" id="latitude">
            <input type="hidden" name="longitude" id="longitude">

            <button class="btn btn-primary-custom w-100">
                Buat Pesanan
            </button>

        </form>
    </div>

</div>

<!-- RIGHT SUMMARY -->
<div class="col-lg-5">

    <div class="card p-4 shadow-sm">
        <h5 class="fw-bold mb-3">Ringkasan Pesanan</h5>

        <?php foreach ($_SESSION['cart'] as $item) { ?>

            <div class="d-flex justify-content-between mb-2">
                <div>
                    <?= htmlspecialchars($item['nama']) ?>
                    <br>
                    <small>x<?= $item['qty'] ?></small>
                </div>
                <div>
                    Rp <?= number_format($item['harga'] * $item['qty'],0,',','.') ?>
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

<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
var map = L.map('map').setView([-6.200000, 106.816666], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

var marker;

// ===============================
// FUNCTION SET MARKER
// ===============================
function setMarker(lat, lon) {

    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([lat, lon], {draggable:true}).addTo(map);
    map.setView([lat, lon], 16);

    document.getElementById("latitude").value = lat;
    document.getElementById("longitude").value = lon;

    reverseGeocode(lat, lon);

    marker.on('dragend', function(e) {
        var pos = e.target.getLatLng();
        document.getElementById("latitude").value = pos.lat;
        document.getElementById("longitude").value = pos.lng;
        reverseGeocode(pos.lat, pos.lng);
    });
}

// ===============================
// REVERSE GEOCODE
// ===============================
function reverseGeocode(lat, lon) {
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`)
    .then(res => res.json())
    .then(data => {
        if (data.display_name) {
            document.querySelector("textarea[name='alamat']").value = data.display_name;
        }
    });
}

// ===============================
// CLICK MAP
// ===============================
map.on('click', function(e) {
    setMarker(e.latlng.lat, e.latlng.lng);
});

// ===============================
// GUNAKAN LOKASI SAYA
// ===============================
document.getElementById("useLocation").addEventListener("click", function() {

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {

            var lat = position.coords.latitude;
            var lon = position.coords.longitude;

            setMarker(lat, lon);

        }, function() {
            alert("Tidak bisa mengambil lokasi.");
        });
    } else {
        alert("Browser tidak mendukung geolocation.");
    }
});

// ===============================
// AUTOCOMPLETE DROPDOWN
// ===============================
document.getElementById("alamatSearch").addEventListener("input", function() {

    var query = this.value;
    var suggestionsBox = document.getElementById("suggestions");
    suggestionsBox.innerHTML = "";

    if (query.length < 3) return;

    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`)
    .then(res => res.json())
    .then(data => {

        data.slice(0,5).forEach(place => {

            var item = document.createElement("a");
            item.className = "list-group-item list-group-item-action";
            item.textContent = place.display_name;

            item.addEventListener("click", function() {
                setMarker(place.lat, place.lon);
                document.getElementById("alamatSearch").value = place.display_name;
                suggestionsBox.innerHTML = "";
            });

            suggestionsBox.appendChild(item);
        });
    });

});

// Klik luar dropdown → hide
document.addEventListener("click", function(e){
    if (!e.target.closest("#alamatSearch")) {
        document.getElementById("suggestions").innerHTML = "";
    }
});
</script>

<?php include "footer_shop.php"; ?>