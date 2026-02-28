<?php
include "koneksi.php";
include "includes/header.php";

// PAGINATION CONFIG
$limit = 6;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

$start = ($page - 1) * $limit;

// TOTAL DATA
$total_query = "SELECT COUNT(*) as total FROM articles WHERE status='publish'";
$total_result = mysqli_query($conn, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_articles = $total_row['total'];

$total_pages = ceil($total_articles / $limit);

// DATA PER PAGE
$query = "SELECT * FROM articles 
        WHERE status='publish' 
        ORDER BY id DESC 
        LIMIT $start, $limit";

$result = mysqli_query($conn, $query);
?>

<section class="section">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Kegiatan Rumah Pintar</h2>
            <p class="text-muted">Semua aktivitas dan program terbaru kami</p>
        </div>

        <div class="row g-4">

        <?php while($row = mysqli_fetch_assoc($result)) { ?>
            <div class="col-md-6 col-lg-4">
                <div class="card-custom h-100">

                    <?php if (!empty($row['image'])) { ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" 
                             class="img-fluid rounded mb-3"
                             style="height:200px; object-fit:cover;">
                    <?php } ?>

                    <h5 class="fw-bold">
                        <?php echo htmlspecialchars($row['title']); ?>
                    </h5>

                    <p class="text-muted small mb-2">
                        <?php echo date("d F Y", strtotime($row['created_at'])); ?>
                    </p>

                    <p class="text-muted">
                        <?php echo substr(strip_tags($row['content']), 0, 120) . "..."; ?>
                    </p>

                    <a href="detail_kegiatan.php?id=<?php echo $row['id']; ?>" 
                       class="btn btn-outline-custom mt-2">
                        Baca Selengkapnya
                    </a>

                </div>
            </div>
        <?php } ?>

        </div>

        <!-- PAGINATION -->
        <?php if ($total_pages > 1) { ?>
        <div class="d-flex justify-content-center mt-5">
            <nav>
                <ul class="pagination">

                    <?php if ($page > 1) { ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>">
                                Previous
                            </a>
                        </li>
                    <?php } ?>

                    <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                        <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if ($page < $total_pages) { ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>">
                                Next
                            </a>
                        </li>
                    <?php } ?>

                </ul>
            </nav>
        </div>
        <?php } ?>

    </div>
</section>

<?php include "includes/footer.php"; ?>