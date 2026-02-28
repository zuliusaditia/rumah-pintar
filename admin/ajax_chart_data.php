<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    exit;
}

$type = $_GET['type'] ?? 'orders'; // orders / revenue
$month = $_GET['month'] ?? date('Y-m');
$compare = $_GET['compare'] ?? '0';

$start = date('Y-m-01', strtotime($month));
$end   = date('Y-m-t', strtotime($month));

if ($type === 'revenue') {
    $select = "SUM(total)";
} else {
    $select = "COUNT(*)";
}

$query = mysqli_query($conn,"
    SELECT DATE(created_at) as tanggal,
    $select as total
    FROM orders
    WHERE DATE(created_at) BETWEEN '$start' AND '$end'
    AND status IN ('paid','completed')
    GROUP BY tanggal
    ORDER BY tanggal ASC
");

$labels = [];
$data = [];

while ($row = mysqli_fetch_assoc($query)) {
    $labels[] = $row['tanggal'];
    $data[] = (int)$row['total'];
}

$response = [
    'labels' => $labels,
    'data'   => $data
];

/* ======================
   COMPARE BULAN LALU
====================== */
if ($compare == 1) {

    $prevMonth = date('Y-m', strtotime($month . ' -1 month'));
    $prevStart = date('Y-m-01', strtotime($prevMonth));
    $prevEnd   = date('Y-m-t', strtotime($prevMonth));

    $prevQuery = mysqli_query($conn,"
        SELECT $select as total
        FROM orders
        WHERE DATE(created_at) BETWEEN '$prevStart' AND '$prevEnd'
        AND status IN ('paid','completed')
    ");

    $prevTotal = mysqli_fetch_assoc($prevQuery)['total'] ?? 0;

    $currentTotal = array_sum($data);

    $growth = 0;
    if ($prevTotal > 0) {
        $growth = (($currentTotal - $prevTotal) / $prevTotal) * 100;
    }

    $response['growth'] = round($growth,2);
}

echo json_encode($response);