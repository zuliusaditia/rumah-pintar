<?php
include "../koneksi.php";
include "header_shop.php";

$query = "SELECT * FROM products WHERE status='aktif' ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<section class="section">
    <div class="container">

        <h2 class="fw-bold mb-5 text-center">Semua Produk Donasi</h2>

        <div class="row g-4">

        <?php while($row = mysqli_fetch_assoc($result)) { ?>

            <div class="col-md-6 col-lg-3">
                <div class="card-custom h-100">

                    <img src="../uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                        class="img-fluid rounded mb-3"
                        style="height:180px; object-fit:cover;">

                    <h6 class="fw-bold">
                        <?php echo htmlspecialchars($row['nama']); ?>
                    </h6>

                    <p class="text-muted small">
                        Rp <?php echo number_format($row['harga'],0,',','.'); ?>
                    </p>

                    <a href="detail_barang.php?id=<?php echo $row['id']; ?>" 
                        class="btn btn-primary-custom w-100">
                        Beli Sekarang
                    </a>

                </div>
            </div>

        <?php } ?>

        </div>

    </div>
</section>

<?php include "includes/footer.php"; ?>