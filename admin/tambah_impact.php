<?php
require_once "session_config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $label = $_POST['label'];
    $value = $_POST['value'];
    $icon = $_POST['icon'];

    $stmt = $conn->prepare("INSERT INTO impact_stats (label,value,icon) VALUES (?,?,?)");
    $stmt->bind_param("sss",$label,$value,$icon);
    $stmt->execute();
    $stmt->close();

    header("Location: kelola_impact.php");
    exit;
}
?>

<h2>Tambah Impact</h2>

<form method="POST">
    Label:<br>
    <input type="text" name="label" required><br><br>

    Value:<br>
    <input type="text" name="value" required><br><br>

    Icon (contoh: people, heart, cash-stack):<br>
    <input type="text" name="icon"><br><br>

    <button type="submit">Simpan</button>
</form>