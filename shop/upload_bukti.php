<?php
session_start();
include "../koneksi.php";
include "header_shop.php";

$order_code = $_GET['order'] ?? '';

if(!$order_code){
    echo "<div class='container mt-5'><div class='alert alert-danger'>Kode order tidak ditemukan.</div></div>";
    exit;
}

$stmt = $conn->prepare("SELECT id FROM orders WHERE kode_order=?");
$stmt->bind_param("s",$order_code);
$stmt->execute();
$result = $stmt->get_result();
$order = $result->fetch_assoc();
$stmt->close();

if(!$order){
    echo "<div class='container mt-5'><div class='alert alert-danger'>Order tidak ditemukan.</div></div>";
    exit;
}

$success = false;

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if(isset($_FILES['bukti']) && $_FILES['bukti']['error'] === 0){

        $file = $_FILES['bukti'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg','jpeg','png','webp'];

        if(!in_array($ext,$allowed)){
            echo "<div class='alert alert-danger container mt-3'>Format file tidak didukung.</div>";
        }else{

            $filename = "bukti_" . time() . "." . $ext;
            $target = "../uploads/bukti/" . $filename;

            if(!is_dir("../uploads/bukti")){
                mkdir("../uploads/bukti",0777,true);
            }

            move_uploaded_file($file['tmp_name'],$target);

            $stmt = $conn->prepare("
            UPDATE orders
            SET bukti_transfer=?, status='pending'
            WHERE kode_order=?
            ");

            $stmt->bind_param("ss",$filename,$order_code);
            $stmt->execute();
            $stmt->close();

            $success = true;
        }

    }
}
?>

<div class="container mt-5">

<?php if($success){ ?>

<div class="alert alert-success text-center">
Bukti transfer berhasil diupload. Menunggu verifikasi admin.
</div>

<?php } else { ?>

<div class="card p-4 shadow-sm">

<h4 class="fw-bold mb-3">Upload Bukti Transfer</h4>

<p>Kode Order: <strong><?= htmlspecialchars($order_code) ?></strong></p>

<form method="POST" enctype="multipart/form-data">

<div class="mb-3">
<label class="form-label">Upload Bukti Transfer</label>
<input type="file" name="bukti" class="form-control" required>
</div>

<button class="btn btn-primary-custom">
Upload Bukti
</button>

</form>

</div>

<?php } ?>

</div>

<?php include "footer_shop.php"; ?>