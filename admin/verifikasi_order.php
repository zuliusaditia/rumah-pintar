<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'] ?? 0;

$stmt = $conn->prepare("
UPDATE orders
SET status='paid'
WHERE id=?
");

$stmt->bind_param("i",$id);
$stmt->execute();
$stmt->close();

header("Location: list_orders.php");
exit;