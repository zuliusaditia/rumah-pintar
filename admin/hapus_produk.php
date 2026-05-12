<?php
require_once __DIR__ . "/../admin/session_config.php";
require_once __DIR__ . "/../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM products WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$stmt->close();

header("Location: list_produk.php");
exit;