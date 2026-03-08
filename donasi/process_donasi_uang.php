<?php

error_reporting(E_ALL);
ini_set('display_errors',1);

include "../koneksi.php";
include "../includes/whatsapp.php";

/* =========================
AMBIL DATA FORM
========================= */

$nama    = $_POST['nama'];
$no_hp   = $_POST['no_hp'];
$nominal = (int) $_POST['nominal'];


/* =========================
VALIDASI FILE
========================= */

$allowed = ['jpg','jpeg','png'];

$ext = strtolower(pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION));

if(!in_array($ext,$allowed)){
    die("Format file tidak diizinkan");
}

if($_FILES['bukti']['size'] > 2*1024*1024){
    die("Ukuran file maksimal 2MB");
}


/* =========================
UPLOAD FILE
========================= */

$newname = uniqid("donasi_").".".$ext;

$upload_path = "../uploads/".$newname;

move_uploaded_file($_FILES['bukti']['tmp_name'],$upload_path);


/* =========================
INSERT DATABASE
========================= */

$status = "pending";

$stmt = $conn->prepare("
INSERT INTO donation_money
(nama,no_hp,nominal,bukti_transfer,status)
VALUES(?,?,?,?,?)
");

$stmt->bind_param(
"ssiss",
$nama,
$no_hp,
$nominal,
$newname,
$status
);

$stmt->execute();


/* =========================
FORMAT NOMOR WHATSAPP
========================= */

$no_hp = preg_replace('/[^0-9]/','',$no_hp);

if(substr($no_hp,0,1)=="0"){
    $no_hp="62".substr($no_hp,1);
}


/* =========================
FORMAT NOMINAL
========================= */

$nominal_format = number_format($nominal,0,',','.');


/* =========================
WHATSAPP KE USER
========================= */

$pesan_user = "Halo $nama 🙏

Terima kasih sudah berdonasi di Rumah Pintar.

Nominal Donasi: Rp $nominal_format

Donasi kamu sedang menunggu verifikasi admin.";

kirimWA($no_hp,$pesan_user);


/* =========================
WHATSAPP KE ADMIN
========================= */

$admin = ADMIN_CHAT_ID;

$pesan_admin = "📢 DONASI BARU MASUK

Nama: $nama
No HP: $no_hp
Nominal: Rp $nominal_format

Silakan cek dashboard admin.";

kirimWA($admin,$pesan_admin);


/* =========================
REDIRECT
========================= */

header("Location: ../donasi.php?status=success");
exit;

?>