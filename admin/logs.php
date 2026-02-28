<?php
require_once "session_config.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM admin_logs ORDER BY id DESC");
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>Admin Activity Logs</h2>

<a href="dashboard.php">Kembali ke Dashboard</a>
<br><br>

<table border="1" cellpadding="10">
    <tr>
        <th>No</th>
        <th>Admin</th>
        <th>Action</th>
        <th>Waktu</th>
    </tr>

<?php
$no = 1;
while ($row = $result->fetch_assoc()) {
?>
<tr>
    <td><?php echo $no++; ?></td>
    <td><?php echo htmlspecialchars($row['admin_username']); ?></td>
    <td><?php echo htmlspecialchars($row['action']); ?></td>
    <td><?php echo htmlspecialchars($row['created_at']); ?></td>
</tr>
<?php } ?>

</table>

<?php
$stmt->close();
?>