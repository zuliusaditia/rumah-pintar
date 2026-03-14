<?php
include "../koneksi.php";
include "../includes/whatsapp.php";

$id = $_GET['id'];

$result = mysqli_query($conn,"SELECT * FROM orders WHERE id=$id");
$order = mysqli_fetch_assoc($result);

if(isset($_POST['message'])){

$message = $_POST['message'];

kirimWA($order['no_hp'],$message);

echo "<div class='alert alert-success'>Pesan terkirim</div>";

}
?>

<form method="POST">

<textarea name="message" class="form-control" rows="5">

Halo <?= $order['nama'] ?>

Pesanan Anda:
<?= $order['kode_order'] ?>

</textarea>

<button class="btn btn-success mt-2">
Kirim Pesan
</button>

</form>