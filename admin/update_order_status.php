<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

if (
    !isset($_POST['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die("CSRF tidak valid.");
}

$id = (int) $_POST['id'];
$status = $_POST['status'];
$resi = trim($_POST['resi']);

$allowed = ['pending','paid','shipped','completed','cancelled'];

if (!in_array($status, $allowed)) {
    die("Status tidak valid.");
}

$stmt = $conn->prepare("UPDATE orders SET status=?, resi=? WHERE id=?");
$stmt->bind_param("ssi", $status, $resi, $id);
$stmt->execute();
$stmt->close();

header("Location: detail_order.php?id=".$id);
exit;