<?php
require_once __DIR__ . "/../koneksi.php";

$id = $_GET['id'];

mysqli_query($conn,"
DELETE FROM hero_slides
WHERE id=$id
");

header("Location: hero.php");
exit;