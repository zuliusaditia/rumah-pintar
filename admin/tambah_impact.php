<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $value = (int) $_POST['value'];
    $icon  = trim($_POST['icon']);

    if ($value < 0) {
        $error = "Nilai tidak boleh negatif.";
    } else {

        $stmt = $conn->prepare("
            INSERT INTO impact_stats (title, value, icon) 
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sis", $title, $value, $icon);

        if ($stmt->execute()) {
            $success = "Impact berhasil ditambahkan.";
        } else {
            $error = "Gagal menyimpan impact.";
        }

        $stmt->close();
    }
}

include "partials/header.php";
?>

<div class="container-fluid">
<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Tambah Impact</h4>

<div class="card p-4">

<?php if($error): ?>
<div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<?php if($success): ?>
<div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<form method="POST">

<div class="mb-3">
<label class="form-label">Judul Impact</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Nilai</label>
<input type="number" name="value" class="form-control" min="0" required>
</div>

<div class="mb-3">
<label class="form-label">Icon (Bootstrap Icon)</label>
<input type="text" name="icon" class="form-control" placeholder="bi-people">
<small class="text-muted">
Gunakan icon dari https://icons.getbootstrap.com/
</small>
</div>

<div class="preview-icon mt-3 text-center">
<i id="iconPreview" class="bi fs-1 text-primary"></i>
</div>

<button class="btn btn-primary-custom mt-4">
<i class="bi bi-save"></i> Simpan Impact
</button>

<a href="kelola_impact.php" class="btn btn-secondary">
Kembali
</a>

</form>

</div>
</div>
</div>

<script>
document.querySelector("input[name='icon']")
.addEventListener("input", function() {
    document.getElementById("iconPreview").className =
        "bi " + this.value + " fs-1 text-primary";
});
</script>

<?php include "partials/footer.php"; ?>