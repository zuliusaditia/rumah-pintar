<?php
require_once "session_config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("DELETE FROM impact_stats WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$stmt->close();

header("Location: kelola_impact.php");
exit;