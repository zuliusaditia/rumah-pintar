<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// ==========================
// HANDLE INSERT
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

    if (empty($title) || empty($content)) {
        die("Judul dan konten wajib diisi.");
    }

    $image = null;

    // ==========================
    // HANDLE IMAGE UPLOAD
    // ==========================
    if (!empty($_FILES['image']['name'])) {

        $allowed = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed)) {
            die("Format gambar tidak valid.");
        }

        $newName = uniqid('artikel_') . '.' . $ext;
        move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/$newName");

        $image = $newName;
    }

    $stmt = $conn->prepare("
        INSERT INTO articles 
        (title, content, image, status, created_at) 
        VALUES (?, ?, ?, ?, NOW())
    ");

    $stmt->bind_param("ssss", $title, $content, $image, $status);
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

<h4 class="mb-4">Tambah Artikel</h4>

<div class="card card-modern p-4 shadow-sm">

<form method="POST" enctype="multipart/form-data">

<input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

<div class="mb-3">
<label class="form-label">Judul Artikel</label>
<input type="text" name="title" class="form-control" required>
</div>

<div class="mb-3">
<label class="form-label">Thumbnail</label>
<input type="file" name="image" class="form-control">
<small class="text-muted">Format: JPG, PNG, WEBP</small>
</div>

<div class="mb-3">
<label class="form-label">Konten</label>
<div id="editor" style="height:400px;"></div>
<input type="hidden" name="content" id="hiddenContent">
</div>

<div class="mb-3">
<label class="form-label">Status</label>
<select name="status" class="form-select">
<option value="draft">Draft</option>
<option value="publish">Publish</option>
</select>
</div>

<button class="btn btn-primary">
<i class="bi bi-save"></i> Simpan Artikel
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
    placeholder: 'Tulis artikel di sini...',
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

// Sync sebelum submit
document.querySelector("form").addEventListener("submit", function() {
    document.getElementById("hiddenContent").value = quill.root.innerHTML;
});
</script>

<?php include "partials/footer.php"; ?>