<?php
include "includes/header.php";
include "koneksi.php";

$id = $_GET['id'];

$query = "SELECT * FROM articles WHERE id='$id' AND status='publish'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Artikel tidak ditemukan.";
    exit;
}
?>

<h1><?php echo $data['title']; ?></h1>

<p><?php echo $data['created_at']; ?></p>

<hr>

<p><?php echo nl2br($data['content']); ?></p>

<br>
<a href="kegiatan.php">← Kembali ke Kegiatan</a>

<?php include "includes/footer.php"; ?>