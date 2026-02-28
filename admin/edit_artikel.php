<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// ==========================
// VALIDASI ID
// ==========================
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID tidak valid.");
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM articles WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    die("Artikel tidak ditemukan.");
}

// ==========================
// HANDLE UPDATE
// ==========================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (
        !isset($_POST['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    ) {
        die("CSRF tidak valid.");
    }

    $title   = trim($_POST['title']);
    $content = $_POST['content'];
    $status  = $_POST['status'];

    $image = $data['image'];

    if (!empty($_FILES['image']['name'])) {
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        $newName = uniqid('artikel_') . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$newName");
        $image = $newName;
    }

    $stmt = $conn->prepare("UPDATE articles SET title=?, content=?, image=?, status=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $content, $image, $status, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: list_artikel.php");
    exit;
}

include "partials/header.php";
?>

<div class="container-fluid">
<div class="row">
<?php include "partials/sidebar.php"; ?>

<div class="col-md-9 col-lg-10 p-4">

<h4 class="mb-4">Edit Artikel</h4>

<div class="card card-modern p-4">

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

<div class="mb-3">
<label class="form-label">Judul</label>
<input type="text" name="title" class="form-control"
value="<?= htmlspecialchars($data['title']) ?>" required>
</div>

<div class="mb-3">
<label class="form-label">Thumbnail</label>
<input type="file" name="image" class="form-control">
<?php if($data['image']): ?>
<img src="../uploads/<?= htmlspecialchars($data['image']) ?>"
style="width:120px;margin-top:10px;border-radius:8px;">
<?php endif; ?>
</div>

<div class="mb-3">
<label class="form-label">Konten</label>
<div id="editor" style="height:400px;"></div>
<input type="hidden" name="content" id="hiddenContent">
</div>

<div class="mb-3">
<label class="form-label">Status</label>
<select name="status" class="form-select">
<option value="draft" <?= $data['status']=='draft'?'selected':'' ?>>Draft</option>
<option value="publish" <?= $data['status']=='publish'?'selected':'' ?>>Publish</option>
</select>
</div>

<button class="btn btn-primary">
<i class="bi bi-save"></i> Update Artikel
</button>

<a href="list_artikel.php" class="btn btn-secondary">
Kembali
</a>

</form>
</div>
</div>
</div>
</div>

<!-- QUILL -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            ['link', 'image'],
            [{ list: 'ordered'}, { list: 'bullet' }],
            ['clean']
        ]
    }
});

// Load existing content safely
quill.root.innerHTML = <?= json_encode($data['content']) ?>;

// Sync before submit
document.querySelector("form").addEventListener("submit", function() {
    document.getElementById("hiddenContent").value = quill.root.innerHTML;
});
</script>

<?php include "partials/footer.php"; ?>