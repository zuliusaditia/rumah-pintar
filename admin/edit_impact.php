<?php
require_once "session_config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM impact_stats WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $label = $_POST['label'];
    $value = $_POST['value'];
    $icon = $_POST['icon'];

    $stmt = $conn->prepare("UPDATE impact_stats SET label=?,value=?,icon=? WHERE id=?");
    $stmt->bind_param("sssi",$label,$value,$icon,$id);
    $stmt->execute();
    $stmt->close();

    header("Location: kelola_impact.php");
    exit;
}
?>

<h2>Edit Impact</h2>

<form method="POST">
    Label:<br>
    <input type="text" name="label" value="<?= htmlspecialchars($data['label']); ?>" required><br><br>

    Value:<br>
    <input type="text" name="value" value="<?= htmlspecialchars($data['value']); ?>" required><br><br>

    Icon:<br>
    <input type="text" name="icon" value="<?= htmlspecialchars($data['icon']); ?>"><br><br>

    <button type="submit">Update</button>
</form>