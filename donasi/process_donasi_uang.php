<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

include "../koneksi.php";
include "../includes/whatsapp.php";

$nama = $_POST['nama'];
$no_hp = $_POST['no_hp'];
$nominal = $_POST['nominal'];

/* upload bukti */

$ext = pathinfo($_FILES['bukti']['name'], PATHINFO_EXTENSION);
$newname = uniqid("donasi_").".".$ext;

move_uploaded_file($_FILES['bukti']['tmp_name'], "uploads/".$newname);

$status="pending";

$stmt=$conn->prepare("
INSERT INTO donation_money
(nama,no_hp,nominal,bukti_transfer,status)
VALUES(?,?,?,?,?)
");

$stmt->bind_param("ssiss",$nama,$no_hp,$nominal,$newname,$status);
$stmt->execute();

/* =====================
FORMAT NOMOR WA
===================== */

$no_hp = preg_replace('/[^0-9]/','',$no_hp);

if(substr($no_hp,0,1)=="0"){
$no_hp="62".substr($no_hp,1);
}

/* =====================
PESAN USER
===================== */

$pesan_user = "Halo $nama 🙏

Terima kasih sudah berdonasi di Rumah Pintar.

Nominal Donasi: Rp $nominal

Donasi kamu sedang menunggu verifikasi admin.";

/* kirim ke user */

kirimWA($no_hp,$pesan_user);


/* =====================
PESAN ADMIN
===================== */

$admin="628123456789";

$pesan_admin="📢 DONASI BARU MASUK

Nama: $nama
No HP: $no_hp
Nominal: Rp $nominal

Silakan cek dashboard admin.";

/* kirim ke admin */

kirimWA($admin,$pesan_admin);


header("Location: ../donasi.php?status=success");
exit;