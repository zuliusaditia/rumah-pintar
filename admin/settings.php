<?php
ob_start();

require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   HANDLE AJAX REQUEST
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header('Content-Type: application/json');

    try {

        $settings = mysqli_fetch_assoc(
            mysqli_query($conn, "SELECT * FROM settings LIMIT 1")
        );

        $site_name = trim($_POST['site_name'] ?? '');
        $site_description = trim($_POST['site_description'] ?? '');
        $admin_email = trim($_POST['admin_email'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $rekening = trim($_POST['rekening'] ?? '');
        $bank_name = trim($_POST['bank_name'] ?? '');
        $account_holder = trim($_POST['account_holder'] ?? '');
        $min_donation = (int)($_POST['min_donation'] ?? 0);
        $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;

        $logo = $settings['logo'] ?? '';

        /* =========================
           UPLOAD LOGO
        ========================= */
        if (!empty($_FILES['logo']['name'])) {

            $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg','jpeg','png','webp'];

            if (!in_array($ext,$allowed)) {
                $response = [
                "status" => "success",
                "message" => "Settings berhasil disimpan"
                ];

                echo json_encode($response);
                file_put_contents("debug_response.txt", json_encode($response));

                exit;
            }

            $newName = uniqid("logo_") . "." . $ext;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], "../uploads/".$newName)) {
                $logo = $newName;
            }
        }

        /* =========================
           UPDATE SETTINGS
        ========================= */
        $stmt = $conn->prepare("
            UPDATE settings SET
            site_name=?,
            site_description=?,
            logo=?,
            admin_email=?,
            whatsapp=?,
            rekening=?,
            bank_name=?,
            account_holder=?,
            min_donation=?,
            maintenance_mode=?,
            updated_at=NOW()
            WHERE id=1
        ");

        $stmt->bind_param(
            "ssssssssii",
            $site_name,
            $site_description,
            $logo,
            $admin_email,
            $whatsapp,
            $rekening,
            $bank_name,
            $account_holder,
            $min_donation,
            $maintenance_mode
        );

        $stmt->execute();

        echo json_encode([
            "status"=>"success",
            "message"=>"Settings berhasil disimpan"
        ]);

        exit;

    } catch (Exception $e) {

        echo json_encode([
            "status"=>"error",
            "message"=>$e->getMessage()
        ]);

        exit;
    }
}

/* =========================
   LOAD SETTINGS
========================= */
$settings = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT * FROM settings LIMIT 1")
);

include "partials/header.php";
?>

<div class="container-fluid">

<?php include "partials/sidebar.php"; ?>

<div class="content-area p-4">

<h4 class="mb-4">System Settings</h4>

<div class="card p-4 shadow-sm">

<form id="settingsForm" enctype="multipart/form-data">

<div class="row">

<div class="col-md-6 mb-3">
<label class="form-label">Nama Website</label>
<input type="text" name="site_name"
value="<?= htmlspecialchars($settings['site_name'] ?? '') ?>"
class="form-control" required>
</div>

<div class="col-md-6 mb-3">
<label class="form-label">Email Admin</label>
<input type="email" name="admin_email"
value="<?= htmlspecialchars($settings['admin_email'] ?? '') ?>"
class="form-control">
</div>

</div>

<div class="mb-3">
<label class="form-label">Deskripsi Website</label>
<textarea name="site_description"
class="form-control"><?= htmlspecialchars($settings['site_description'] ?? '') ?></textarea>
</div>

<div class="mb-3">
<label class="form-label">WhatsApp</label>
<input type="text" name="whatsapp"
value="<?= htmlspecialchars($settings['whatsapp'] ?? '') ?>"
class="form-control">
</div>

<div class="row">

<div class="col-md-4 mb-3">
<label class="form-label">Nama Bank</label>
<input type="text" name="bank_name"
value="<?= htmlspecialchars($settings['bank_name'] ?? '') ?>"
class="form-control">
</div>

<div class="col-md-4 mb-3">
<label class="form-label">Nomor Rekening</label>
<input type="text" name="rekening"
value="<?= htmlspecialchars($settings['rekening'] ?? '') ?>"
class="form-control">
</div>

<div class="col-md-4 mb-3">
<label class="form-label">Atas Nama</label>
<input type="text" name="account_holder"
value="<?= htmlspecialchars($settings['account_holder'] ?? '') ?>"
class="form-control">
</div>

</div>

<div class="mb-3">
<label class="form-label">Minimum Donasi (Rp)</label>
<input type="number" name="min_donation"
value="<?= $settings['min_donation'] ?? 0 ?>"
class="form-control">
</div>

<div class="mb-3">
<label class="form-label">Logo Website</label>
<input type="file" name="logo" class="form-control">

<?php if(!empty($settings['logo'])): ?>

<img src="../uploads/<?= $settings['logo'] ?>"
style="height:60px;margin-top:10px;">

<?php endif; ?>

</div>

<div class="form-check mb-4">

<input class="form-check-input"
type="checkbox"
name="maintenance_mode"
<?= !empty($settings['maintenance_mode']) ? 'checked' : '' ?>>

<label class="form-check-label">
Aktifkan Maintenance Mode
</label>

</div>

<button type="submit" class="btn btn-primary">
Simpan Settings
</button>

</form>

</div>
</div>
</div>

<div class="position-fixed top-0 end-0 p-3" style="z-index:9999">

<div id="liveToast"
class="toast align-items-center text-bg-success border-0">

<div class="d-flex">

<div class="toast-body" id="toastMessage"></div>

<button type="button"
class="btn-close btn-close-white me-2 m-auto"
data-bs-dismiss="toast"></button>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function(){

const form = document.getElementById("settingsForm");
const toastElement = document.getElementById("liveToast");
const toastMessage = document.getElementById("toastMessage");
const toast = new bootstrap.Toast(toastElement);

form.addEventListener("submit", async function(e){

e.preventDefault();

const button = form.querySelector("button");
button.disabled = true;
button.innerHTML = "Menyimpan...";

const formData = new FormData(form);

try {

const response = await fetch("settings.php", {
method: "POST",
body: formData
});

const result = await response.json();

if(result.status === "success"){
toastElement.classList.remove("text-bg-danger");
toastElement.classList.add("text-bg-success");
} else {
toastElement.classList.remove("text-bg-success");
toastElement.classList.add("text-bg-danger");
}

toastMessage.innerText = result.message;
toast.show();

} catch(error){

toastElement.classList.remove("text-bg-success");
toastElement.classList.add("text-bg-danger");
toastMessage.innerText = "Server response error";
toast.show();

console.error(error);

}

button.disabled = false;
button.innerHTML = "Simpan Settings";

});

});

</script>

<?php include "partials/footer.php"; ?>
