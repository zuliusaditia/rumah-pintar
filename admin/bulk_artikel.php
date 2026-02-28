<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: list_artikel.php");
    exit;
}

if (
    !isset($_POST['csrf_token']) ||
    !isset($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
) {
    die("CSRF tidak valid");
}

$action = $_POST['bulk_action'] ?? '';
$ids = $_POST['ids'] ?? [];

if (empty($action) || empty($ids)) {
    header("Location: list_artikel.php");
    exit;
}

$id_list = implode(",", array_map('intval', $ids));

if ($action === 'delete') {

    $query = "DELETE FROM articles WHERE id IN ($id_list)";
    mysqli_query($conn, $query);

} elseif (in_array($action, ['draft', 'publish'])) {

    $stmt = $conn->prepare("UPDATE articles SET status=? WHERE id IN ($id_list)");
    $stmt->bind_param("s", $action);
    $stmt->execute();
    $stmt->close();
}

header("Location: list_artikel.php");
exit;