<?php
require_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"DELETE FROM partners WHERE id=$id");

header("Location: partners.php");
exit;