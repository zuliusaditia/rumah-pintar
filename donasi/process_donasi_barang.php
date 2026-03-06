<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include "../koneksi.php";
include "../includes/whatsapp.php";

$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$jenis = $_POST['jenis_barang'];
$jumlah = $_POST['jumlah'];

/* default jumlah barang */

$tas = 0;
$sepatu = 0;
$jam = 0;
$baju = 0;

/* mapping jenis barang */

if($jenis == "tas") $tas = $jumlah;
if($jenis == "sepatu") $sepatu = $jumlah;
if($jenis == "jam_tangan") $jam = $jumlah;
if($jenis == "baju") $baju = $jumlah;


/* upload foto */

$ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
$newname = uniqid("barang_").".".$ext;

move_uploaded_file($_FILES['foto']['tmp_name'], "uploads/".$newname);


/* insert database */

$stmt = $conn->prepare("
INSERT INTO donation_barang
(nama,no_hp,tas,sepatu,jam_tangan,baju,foto,status)
VALUES(?,?,?,?,?,?,?,?)
");

$status = "pending";

$stmt->bind_param(
"ssiiiiss",
$nama,
$no_hp,
$tas,
$sepatu,
$jam,
$baju,
$newname,
$status
);

$stmt->execute();


/* format nomor WA */

$no_hp = preg_replace('/[^0-9]/','',$no_hp);

if(substr($no_hp,0,1)=="0"){
$no_hp="62".substr($no_hp,1);
}


/* ambil alamat pengiriman */

$settings = mysqli_fetch_assoc(
mysqli_query($conn,"SELECT alamat_pengiriman FROM settings LIMIT 1")
);

$alamat = $settings['alamat_pengiriman'] ?? '';


/* WA ke user */

$pesan_user = "Halo $nama 🙏

Terima kasih sudah berdonasi barang di Rumah Pintar.

Silakan kirim barang donasi ke alamat berikut:

$alamat

Terima kasih atas kebaikanmu ❤️";

kirimWA($no_hp,$pesan_user);


/* WA ke admin */

$admin = "628123456789";

$pesan_admin = "📦 DONASI BARANG MASUK

Nama: $nama
No HP: $no_hp

Tas: $tas
Sepatu: $sepatu
Jam Tangan: $jam
Baju: $baju

Silakan cek dashboard admin.";

kirimWA($admin,$pesan_admin);


/* redirect */

header("Location: ../donasi.php?status=success");
exit;