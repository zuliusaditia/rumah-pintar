<?php
include "../koneksi.php";
require_once "session_config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = $_GET['id'];

$query = "DELETE FROM articles WHERE id='$id'";
mysqli_query($conn, $query);

header("Location: list_artikel.php");
?>