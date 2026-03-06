<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM impact_stats WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$impact = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$impact) {
    die("Data tidak ditemukan.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = trim($_POST['title']);
    $value = (int) $_POST['value'];
    $icon  = trim($_POST['icon']);

    $stmt = $conn->prepare("
        UPDATE impact_stats 
        SET title=?, value=?, icon=? 
        WHERE id=?
    ");
    $stmt->bind_param("sisi",$title,$value,$icon,$id);
    $stmt->execute();
    $stmt->close();

    header("Location: kelola_impact.php");
    exit;
}

include "partials/header.php";
?>

<div class="container-fluid">
<?php include "partials/sidebar.php"; ?>

<div class="content-area">

<h4 class="mb-4">Edit Impact</h4>

<div class="card p-4">

<form method="POST">

<div class="mb-3">
<label class="form-label">Judul Impact</label>
<input type="text" name="title"
value="<?= htmlspecialchars($impact['title']) ?>"
class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Nilai</label>
<input type="number" name="value"
value="<?= $impact['value'] ?>"
class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Icon</label>
<input type="text" name="icon"
value="<?= $impact['icon'] ?>"
class="form-control">
</div>

<div class="text-center mb-3">
<i id="iconPreview" 
class="bi <?= $impact['icon'] ?> fs-1 text-primary"></i>
</div>

<button class="btn btn-primary-custom">
Update Impact
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