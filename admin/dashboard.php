<?php
require_once "session_config.php";
include "../koneksi.php";

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

/* =========================
   SUMMARY DATA
========================= */

$revenue = mysqli_fetch_assoc(
    mysqli_query($conn,"
        SELECT SUM(total) as total_revenue 
        FROM orders 
        WHERE status IN ('paid','completed')
    ")
)['total_revenue'] ?? 0;

$total_orders = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM orders")
)['total'];

$pending_orders = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM orders WHERE status='pending'")
)['total'];

$total_donasi = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT SUM(nominal) as total FROM donation_money WHERE status='approved'")
)['total'] ?? 0;

$total_volunteer = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM volunteers WHERE status='approved'")
)['total'];

include "partials/header.php";
?>

<style>
.chart-container {
    position: relative;
    height: 350px;
}
</style>

<div class="container-fluid">
<?php include "partials/sidebar.php"; ?>

<div class="content-area p-4">

<h4 class="mb-4">Dashboard Analytics</h4>

<div class="row g-4">

<div class="col-md-4">
<div class="card shadow-sm p-4">
<h6>Total Revenue</h6>
<h4 class="fw-bold text-success">
Rp <?= number_format($revenue,0,',','.') ?>
</h4>
</div>
</div>

<div class="col-md-4">
<div class="card shadow-sm p-4">
<h6>Total Orders</h6>
<h4 class="fw-bold"><?= $total_orders ?></h4>
</div>
</div>

<div class="col-md-4">
<div class="card shadow-sm p-4">
<h6>Pending Orders</h6>
<h4 class="fw-bold text-warning"><?= $pending_orders ?></h4>
</div>
</div>

<div class="col-md-4">
<div class="card shadow-sm p-4">
<h6>Total Donasi Uang</h6>
<h4 class="fw-bold text-primary">
Rp <?= number_format($total_donasi,0,',','.') ?>
</h4>
</div>
</div>

<div class="col-md-4">
<div class="card shadow-sm p-4">
<h6>Volunteer Aktif</h6>
<h4 class="fw-bold text-info"><?= $total_volunteer ?></h4>
</div>
</div>

</div>

<!-- =======================
     ANALYTICS CHART
======================== -->
<div class="card shadow-sm p-4 mt-5">

<div class="d-flex flex-wrap gap-3 mb-4 align-items-center">

<select id="chartType" class="form-select w-auto">
<option value="orders">Jumlah Order</option>
<option value="revenue">Revenue</option>
</select>

<select id="chartStyle" class="form-select w-auto">
<option value="line">Line Chart</option>
<option value="bar">Bar Chart</option>
</select>

<input type="month" id="monthPicker" 
class="form-control w-auto"
value="<?= date('Y-m') ?>">

<div class="form-check">
<input class="form-check-input" type="checkbox" id="compareToggle">
<label class="form-check-label">
Compare Bulan Lalu
</label>
</div>

</div>

<h6 id="growthText" class="mb-3"></h6>

<div class="chart-container">
<canvas id="analyticsChart"></canvas>
</div>

</div>

</div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
let chart;

async function loadChart() {

    const type = document.getElementById('chartType').value;
    const style = document.getElementById('chartStyle').value;
    const month = document.getElementById('monthPicker').value;
    const compare = document.getElementById('compareToggle').checked ? 1 : 0;

    const res = await fetch(`ajax_chart_data.php?type=${type}&month=${month}&compare=${compare}`);
    const data = await res.json();

    const ctx = document.getElementById('analyticsChart').getContext('2d');

    if (chart) chart.destroy();

    chart = new Chart(ctx, {
        type: style,
        data: {
            labels: data.labels,
            datasets: [{
                label: type === 'orders' ? 'Jumlah Order' : 'Revenue',
                data: data.data,
                borderColor: '#ff6b35',
                backgroundColor: 'rgba(255,107,53,0.2)',
                fill: style === 'line',
                tension: 0.3,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 }
                }
            }
        }
    });

    if (compare && data.growth !== undefined) {
        const growthText = document.getElementById('growthText');
        growthText.innerText = `Growth vs Bulan Lalu: ${data.growth}%`;
        growthText.className = data.growth >= 0 ? "text-success" : "text-danger";
    } else {
        document.getElementById('growthText').innerText = "";
    }
}

document.getElementById('chartType').addEventListener('change', loadChart);
document.getElementById('chartStyle').addEventListener('change', loadChart);
document.getElementById('monthPicker').addEventListener('change', loadChart);
document.getElementById('compareToggle').addEventListener('change', loadChart);

loadChart();
</script>

<?php include "partials/footer.php"; ?>