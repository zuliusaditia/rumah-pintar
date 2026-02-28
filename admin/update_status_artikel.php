<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Akses tidak valid.");
}

if (!isset($_POST['csrf_token']) || 
    $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    die("CSRF token tidak valid.");
}

$id = (int) $_POST['id'];
$status = $_POST['status'];

if (!in_array($status,['draft','publish'])) {
    die("Status tidak valid.");
}

$stmt = $conn->prepare("UPDATE articles SET status=? WHERE id=?");
$stmt->bind_param("si",$status,$id);
$stmt->execute();
$stmt->close();

header("Location: list_artikel.php");
exit;