<?php
include "koneksi.php";
include "includes/header.php";

if (!isset($_GET['id'])) {
    echo "Artikel tidak ditemukan.";
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM articles WHERE id=? AND status='publish'");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Artikel tidak ditemukan atau belum dipublish.";
    exit;
}

$data = $result->fetch_assoc();
$stmt->close();
?>

<section class="section">
    <div class="container" style="max-width: 800px;">

        <!-- Judul -->
        <h1 class="fw-bold mb-3">
            <?php echo htmlspecialchars($data['title']); ?>
        </h1>

        <!-- Tanggal -->
        <p class="text-muted mb-4">
            Dipublikasikan pada 
            <?php echo date("d F Y", strtotime($data['created_at'])); ?>
        </p>

        <!-- Gambar -->
        <?php if (!empty($data['image'])) { ?>
            <img src="uploads/<?php echo htmlspecialchars($data['image']); ?>" 
                class="img-fluid rounded mb-4" 
                style="width:100%; object-fit:cover;">
        <?php } ?>

        <!-- Konten -->
        <div style="line-height:1.8; font-size:16px;">
            <?php echo nl2br(htmlspecialchars($data['content'])); ?>
        </div>

        <!-- Tombol Kembali -->
        <div class="mt-5">
            <a href="kegiatan.php" class="btn btn-outline-custom">
                ← Kembali ke Kegiatan
            </a>
        </div>

    </div>
</section>

<?php include "includes/footer.php"; ?>