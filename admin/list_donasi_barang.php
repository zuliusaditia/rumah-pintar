<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$result = mysqli_query($conn,"SELECT * FROM donation_barang ORDER BY id DESC");

include "partials/header.php";
?>

<div class="container-fluid">

    <?php include "partials/sidebar.php"; ?>

    <div class="content-area">

        <h4 class="mb-4">Donasi Barang</h4>

        <div class="card card-modern p-4">

            <div class="table-responsive">
            <table class="table table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>No HP</th>
                        <th>Barang</th>
                        <th>No HP</th>
                        <th width="180">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['nama']) ?></td>

                        <td><?= htmlspecialchars($row['no_hp']) ?></td>
                        <td>
                        Tas: <?= $row['tas'] ?><br>
                        Sepatu: <?= $row['sepatu'] ?><br>
                        Jam: <?= $row['jam_tangan'] ?><br>
                        Baju: <?= $row['baju'] ?>
                        </td>

                        <td>
                        <?php if($row['status']=='pending'): ?>
                        <span class="badge bg-warning">Pending</span>
                        <?php elseif($row['status']=='approved'): ?>
                        <span class="badge bg-success">Approved</span>
                        <?php else: ?>
                        <span class="badge bg-danger">Rejected</span>
                        <?php endif; ?>
                        </td>

                        <td>

                        <?php if($row['status']=='pending'): ?>
                        <form method="POST" action="verifikasi_barang.php" class="d-inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="status" value="approved">
                        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                        <button class="btn btn-sm btn-success">
                        <i class="bi bi-check-lg"></i>
                        </button>
                        </form>
                        <?php endif; ?>

                        <a href="../uploads/<?= $row['foto'] ?>" target="_blank"
                        class="btn btn-sm btn-secondary">
                        <i class="bi bi-image"></i>
                        </a>

                        </td>

                    </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>

        </div>
    </div>
</div>

<?php include "partials/footer.php"; ?>
