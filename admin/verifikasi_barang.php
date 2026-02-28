<?php
require_once "session_config.php";
require_once "logger.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    die("Akses tidak valid.");
}

// // ======================
// // CSRF VALIDATION
// // ======================
// if (!isset($_POST['csrf_token']) || 
//     !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
//     die("CSRF token tidak valid.");
// }

// ======================
// VALIDASI INPUT
// ======================
$id = (int) $_POST['id'];

$allowed_status = ['approved', 'rejected'];
if (!in_array($_POST['status'], $allowed_status)) {
    die("Status tidak valid.");
}

$status = $_POST['status'];

// ======================
// UPDATE STATUS
// ======================
$stmt = $conn->prepare("UPDATE donation_barang SET status=? WHERE id=?");
$stmt->bind_param("si", $status, $id);

if ($stmt->execute()) {

    // ======================
    // LOG ACTIVITY
    // ======================
    $action_text = "Mengubah status donasi barang ID $id menjadi $status";
    log_activity($conn, $_SESSION['admin'], $action_text);

}

$stmt->close();

header("Location: list_donasi_barang.php");
exit;