<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

// ==========================
// HANYA POST
// ==========================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid.");
}

// ==========================
// VALIDASI CSRF
// ==========================
if (
    !isset($_POST['csrf_token']) ||
    $_POST['csrf_token'] !== $_SESSION['csrf_token']
) {
    die("CSRF token tidak valid.");
}

// ==========================
// VALIDASI INPUT
// ==========================
$id = (int) $_POST['id'];
$status = $_POST['status'];

$allowed_status = ['approved', 'rejected'];

if (!in_array($status, $allowed_status)) {
    die("Status tidak valid.");
}

// ==========================
// UPDATE STATUS
// ==========================
$stmt = $conn->prepare("
    UPDATE donation_money 
    SET status=? 
    WHERE id=?
");

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

$stmt->bind_param("si", $status, $id);

if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$stmt->close();

// ==========================
// OPTIONAL: ACTIVITY LOG
// ==========================
// (Kalau lo sudah punya table admin_logs)
// $admin = $_SESSION['admin'];
// $action = "Verifikasi donasi uang ID $id menjadi $status";
// mysqli_query($conn, "INSERT INTO admin_logs (admin, action) VALUES ('$admin','$action')");

// ==========================
// REDIRECT
// ==========================
header("Location: list_donasi_money.php");
exit;