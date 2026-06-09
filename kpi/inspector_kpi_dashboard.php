<?php
include_once('../inc/function.php');
include_once('../file/config.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'inspector') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Inspector KPI Dashboard</title>
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/premium-nav.css">
<style>
.kpi-main-content {
    padding: 24px;
    background: #f4f7fb;
    min-height: 100vh;
}
.kpi-page-header,
.kpi-card,
.kpi-summary-card,
.kpi-chart-card,
.kpi-table-card,
.sticker-card {
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.06);
}
.kpi-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.kpi-page-header h4 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: #0f172a;
}
.kpi-page-header p {
    margin: 4px 0 0;
    color: #64748b;
    font-size: 13px;
}
.filter-row {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.filter-row input,
.filter-row button {
    height: 40px;
    border-radius: 10px;
    border: 1px solid #dbe3ee;
    padding: 0 14px;
    font-size: 13px;
}
.filter-row button {
    border: none;
    font-weight: 700;
    cursor: pointer;
}
.btn-filter {
    background: #0f766e;
    color: #fff;
}
.btn-reset {
    background: #eef2f7;
    color: #334155;
}
.kpi-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}
.kpi-card {
    padding: 18px 16px;
    border-top: 4px solid;
}
.kpi-card.blue { border-color: #2563eb; }
.kpi-card.green { border-color: #16a34a; }
.kpi-card.orange { border-color: #f59e0b; }
.kpi-card.cyan { border-color: #0891b2; }
.kpi-card.red { border-color: #dc2626; }
.kpi-card.purple { border-color: #7c3aed; }
.kpi-card .label {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #64748b;
}
.kpi-card .value {
    font-size: 28px;
    line-height: 1;
    font-weight: 800;
    color: #0f172a;
    margin: 10px 0 8px;
}
.kpi-card .sub {
    font-size: 12px;
    color: #64748b;
}
.summary-grid,
.chart-grid {
    display: grid;
    gap: 16px;
    margin-bottom: 20px;
}
.summary-grid {
    grid-template-columns: repeat(4, 1fr);
}
.kpi-summary-card {
    padding: 18px;
    text-align: center;
}
.kpi-summary-card .sum-value {
    font-size: 22px;
    font-weight: 800;
    color: #0f172a;
}
.kpi-summary-card .sum-label {
    margin-top: 6px;
    font-size: 12px;
    color: #64748b;
}
.chart-grid {
    grid-template-columns: 1.2fr 1fr;
}
.chart-grid.equal {
    grid-template-columns: 1fr 1fr;
}
.kpi-chart-card,
.kpi-table-card,
.sticker-card {
    padding: 20px;
}
.card-title {
    margin: 0 0 14px;
    font-size: 15px;
    font-weight: 800;
    color: #0f172a;
}
.sticker-metrics {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 14px;
    margin-bottom: 18px;
}
.sticker-metric {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px;
    text-align: center;
}
.sticker-metric .sm-label {
    font-size: 11px;
    text-transform: uppercase;
    color: #64748b;
    font-weight: 800;
    letter-spacing: .08em;
}
.sticker-metric .sm-value {
    margin-top: 8px;
    font-size: 24px;
    font-weight: 800;
    color: #0f172a;
}
.pass-track {
    height: 24px;
    background: #e2e8f0;
    border-radius: 999px;
    overflow: hidden;
}
.pass-fill {
    height: 100%;
    width: 0;
    background: linear-gradient(90deg, #16a34a, #0891b2);
    border-radius: 999px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 10px;
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    transition: width .6s ease;
}
.recent-table {
    width: 100%;
    border-collapse: collapse;
}
.recent-table th,
.recent-table td {
    padding: 12px 10px;
    border-bottom: 1px solid #eef2f7;
    font-size: 13px;
    text-align: left;
}
.recent-table th {
    color: #64748b;
    font-size: 12px;
    text-transform: uppercase;
}
.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 999px;
    font-size: 11px;
    font-weight: 700;
}
.status-completed {
    background: #dcfce7;
    color: #166534;
}
.status-pending {
    background: #fef3c7;
    color: #92400e;
}
.loader {
    position: fixed;
    inset: 0;
    background: rgba(255,255,255,.55);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.spinner {
    width: 44px;
    height: 44px;
    border: 4px solid #dbe3ee;
    border-top-color: #0f766e;
    border-radius: 50%;
    animation: spin .8s linear infinite;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
@media (max-width: 1280px) {
    .kpi-grid { grid-template-columns: repeat(3, 1fr); }
    .summary-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 900px) {
    .chart-grid,
    .chart-grid.equal,
    .sticker-metrics {
        grid-template-columns: 1fr;
    }
}
@media (max-width: 640px) {
    .kpi-main-content { padding: 12px; }
    .kpi-page-header { padding: 16px; }
    .kpi-grid,
    .summary-grid {
        grid-template-columns: 1fr;
    }
    .filter-row {
        width: 100%;
    }
    .filter-row input,
    .filter-row button {
        width: 100%;
    }
}
</style>
</head>
<body>
<?php include_once('../inc/nav.php'); ?>

<div class="loader" id="kpiLoader">
    <div class="spinner"></div>
</div>

<div class="main-content">
    <div class="kpi-main-content">
        <div class="kpi-page-header">
            <div>
                <h4><i class="fas fa-chart-line" style="color:#0f766e;margin-right:8px;"></i>Inspector KPI Dashboard</h4>
                <p>Personal performance overview for <?php echo htmlspecialchars($_SESSION['username']); ?></p>
            </div>
            <div class="filter-row">
                <input type="date" id="kpi_date_from" title="From Date">
                <input type="date" id="kpi_date_to" title="To Date">
                <button class="btn-filter" onclick="loadKPIData()">Apply Filter</button>
                <button class="btn-reset" onclick="resetFilters()">Reset</button>
            </div>
        </div>

        <div class="kpi-grid">
            <div class="kpi-card blue">
                <div class="label">Total Projects</div>
                <div class="value" id="kv_total">0</div>
                <div class="sub">Assigned within selected range</div>
            </div>
            <div class="kpi-card green">
                <div class="label">Completed</div>
                <div class="value" id="kv_completed">0</div>
                <div class="sub"><span id="kv_completed_pct">0</span>% completion rate</div>
            </div>
            <div class="kpi-card orange">
                <div class="label">Pending Projects</div>
                <div class="value" id="kv_pending">0</div>
                <div class="sub">Projects still open</div>
            </div>
            <div class="kpi-card cyan">
                <div class="label">Pending Checklist</div>
                <div class="value" id="kv_checklist">0</div>
                <div class="sub">Checklist work remaining</div>
            </div>
            <div class="kpi-card red">
                <div class="label">Pending Report</div>
                <div class="value" id="kv_report">0</div>
                <div class="sub">Reports waiting to close</div>
            </div>
            <div class="kpi-card purple">
                <div class="label">Sticker Pass Rate</div>
                <div class="value" id="kv_sticker_rate">0%</div>
                <div class="sub">Based on sticker outcome</div>
            </div>
        </div>

        <div class="summary-grid">
            <div class="kpi-summary-card">
                <div class="sum-value" id="sum_date">All Time</div>
                <div class="sum-label">Date Range</div>
            </div>
            <div class="kpi-summary-card">
                <div class="sum-value"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                <div class="sum-label">Inspector</div>
            </div>
            <div class="kpi-summary-card">
                <div class="sum-value" id="sum_customers">0</div>
                <div class="sum-label">Customers Served</div>
            </div>
            <div class="kpi-summary-card">
                <div class="sum-value" id="sum_equipment_types">0</div>
                <div class="sum-label">Equipment Types</div>
            </div>
        </div>

        <div class="chart-grid">
            <div class="kpi-chart-card">
                <h6 class="card-title">Monthly Project Trend</h6>
                <div id="chart_monthly" style="min-height: 300px;"></div>
            </div>
            <div class="kpi-chart-card">
                <h6 class="card-title">Top Customers</h6>
                <div id="chart_customers" style="min-height: 300px;"></div>
            </div>
        </div>

        <div class="chart-grid equal">
            <div class="kpi-chart-card">
                <h6 class="card-title">Equipment Mix</h6>
                <div id="chart_equipment" style="min-height: 320px;"></div>
            </div>
            <div class="kpi-chart-card">
                <h6 class="card-title">Certificate Distribution</h6>
                <div id="chart_certificates" style="min-height: 320px;"></div>
            </div>
        </div>

        <div class="sticker-card" style="margin-bottom:20px;">
            <h6 class="card-title">Sticker Analytics</h6>
            <div class="sticker-metrics">
                <div class="sticker-metric">
                    <div class="sm-label">Used Stickers</div>
                    <div class="sm-value" id="sk_used">0</div>
                </div>
                <div class="sticker-metric">
                    <div class="sm-label">Passed</div>
                    <div class="sm-value" id="sk_passed">0</div>
                </div>
                <div class="sticker-metric">
                    <div class="sm-label">Failed</div>
                    <div class="sm-value" id="sk_failed">0</div>
                </div>
                <div class="sticker-metric">
                    <div class="sm-label">Pass Rate</div>
                    <div class="sm-value" id="sk_rate">0%</div>
                </div>
            </div>
            <div class="pass-track">
                <div class="pass-fill" id="sk_pass_fill">0%</div>
            </div>
        </div>

        <div class="kpi-table-card">
            <h6 class="card-title">Recent Projects</h6>
            <div class="table-responsive">
                <table class="recent-table">
                    <thead>
                        <tr>
                            <th>Project No</th>
                            <th>Customer</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody id="recent_projects_body">
                        <tr>
                            <td colspan="4">Loading...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<script>
var chartMonthly, chartCustomers, chartEquipment, chartCertificates;

$(document).ready(function() {
    loadKPIData();
});

function loadKPIData() {
    $('#kpiLoader').css('display', 'flex');

    $.get('fetch_inspector_kpi_data.php', {
        date_from: $('#kpi_date_from').val(),
        date_to: $('#kpi_date_to').val()
    }, function(data) {
        if (data.error) {
            return;
        }

        updateCards(data.kpi);
        updateSummary(data.summary);
        renderMonthly(data.monthly);
        renderCustomers(data.customers);
        renderEquipment(data.equipment);
        renderCertificates(data.certificates);
        updateSticker(data.sticker);
        updateRecentProjects(data.recent_projects || []);
    }, 'json').always(function() {
        $('#kpiLoader').hide();
    });
}

function resetFilters() {
    $('#kpi_date_from').val('');
    $('#kpi_date_to').val('');
    loadKPIData();
}

function updateCards(kpi) {
    var total = parseInt(kpi.total || 0, 10);
    var completed = parseInt(kpi.completed || 0, 10);
    var pending = parseInt(kpi.pending || 0, 10);
    var checklist = parseInt(kpi.pending_checklist || 0, 10);
    var report = parseInt(kpi.pending_report || 0, 10);
    var completion = parseFloat(kpi.completion_rate || 0);
    var stickerRate = parseFloat(kpi.sticker_rate || 0);

    animNum('kv_total', total);
    animNum('kv_completed', completed);
    animNum('kv_pending', pending);
    animNum('kv_checklist', checklist);
    animNum('kv_report', report);
    $('#kv_completed_pct').text(completion.toFixed(2));
    $('#kv_sticker_rate').text(stickerRate.toFixed(2) + '%');
}

function updateSummary(summary) {
    $('#sum_date').text(summary.date_label || 'All Time');
    animNum('sum_customers', summary.customer_count || 0);
    animNum('sum_equipment_types', summary.equipment_type_count || 0);
}

function renderMonthly(monthly) {
    var opts = {
        chart: { type: 'line', height: 300, toolbar: { show: false } },
        series: [
            { name: 'Total', data: monthly.total || [] },
            { name: 'Completed', data: monthly.completed || [] },
            { name: 'Pending', data: monthly.pending || [] }
        ],
        xaxis: { categories: monthly.labels || [] },
        colors: ['#2563eb', '#16a34a', '#f59e0b'],
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4 },
        legend: { position: 'top' },
        grid: { borderColor: '#e2e8f0' }
    };

    if (chartMonthly) chartMonthly.destroy();
    chartMonthly = new ApexCharts(document.querySelector('#chart_monthly'), opts);
    chartMonthly.render();
}

function renderCustomers(customers) {
    var opts = {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [
            { name: 'Total', data: customers.totals || [] },
            { name: 'Completed', data: customers.completed || [] }
        ],
        xaxis: {
            categories: customers.names || [],
            labels: {
                rotate: -35,
                formatter: function(val) {
                    return val && val.length > 12 ? val.substring(0, 12) + '...' : val;
                }
            }
        },
        colors: ['#0f766e', '#16a34a'],
        legend: { position: 'top' },
        plotOptions: { bar: { columnWidth: '58%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e2e8f0' }
    };

    if (chartCustomers) chartCustomers.destroy();
    chartCustomers = new ApexCharts(document.querySelector('#chart_customers'), opts);
    chartCustomers.render();
}

function renderEquipment(equipment) {
    var opts = {
        chart: { type: 'donut', height: 320 },
        series: equipment.data || [],
        labels: equipment.labels || [],
        colors: ['#2563eb', '#0f766e', '#16a34a', '#f59e0b', '#dc2626', '#7c3aed', '#0891b2', '#ea580c'],
        legend: { position: 'bottom' },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '62%' } } }
    };

    if (chartEquipment) chartEquipment.destroy();
    chartEquipment = new ApexCharts(document.querySelector('#chart_equipment'), opts);
    chartEquipment.render();
}

function renderCertificates(certificates) {
    var opts = {
        chart: { type: 'polarArea', height: 320, toolbar: { show: false } },
        series: certificates.data || [],
        labels: certificates.labels || [],
        stroke: { colors: ['#fff'] },
        fill: { opacity: 0.88 },
        legend: { position: 'bottom' },
        colors: ['#2563eb', '#16a34a', '#0891b2', '#f59e0b', '#dc2626', '#7c3aed', '#0f766e', '#ea580c', '#ec4899']
    };

    if (chartCertificates) chartCertificates.destroy();
    chartCertificates = new ApexCharts(document.querySelector('#chart_certificates'), opts);
    chartCertificates.render();
}

function updateSticker(sticker) {
    var used = parseInt(sticker.used_stickers || 0, 10);
    var passed = parseInt(sticker.passed || 0, 10);
    var failed = parseInt(sticker.failed || 0, 10);
    var rate = parseFloat(sticker.pass_rate || 0);

    animNum('sk_used', used);
    animNum('sk_passed', passed);
    animNum('sk_failed', failed);
    $('#sk_rate').text(rate.toFixed(2) + '%');
    $('#sk_pass_fill').css('width', Math.min(rate, 100) + '%').text(rate.toFixed(2) + '%');
}

function updateRecentProjects(projects) {
    var $body = $('#recent_projects_body');
    $body.empty();

    if (!projects.length) {
        $body.append('<tr><td colspan="4">No projects found for the selected range.</td></tr>');
        return;
    }

    projects.forEach(function(project) {
        var badgeClass = project.project_status === 'Completed' ? 'status-completed' : 'status-pending';
        $body.append(
            '<tr>' +
                '<td>#' + escapeHtml(project.project_no) + '</td>' +
                '<td>' + escapeHtml(project.customer_name || '-') + '</td>' +
                '<td><span class="status-badge ' + badgeClass + '">' + escapeHtml(project.project_status || 'Pending') + '</span></td>' +
                '<td>' + escapeHtml(project.creation_date || '-') + '</td>' +
            '</tr>'
        );
    });
}

function animNum(id, target) {
    target = parseInt(target, 10) || 0;
    var el = document.getElementById(id);
    if (!el) return;
    var current = 0;
    var steps = 30;
    var step = Math.max(1, Math.ceil(target / steps));
    var timer = setInterval(function() {
        current = Math.min(current + step, target);
        el.textContent = current.toLocaleString();
        if (current >= target) clearInterval(timer);
    }, 20);
}

function escapeHtml(text) {
    return String(text)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}
</script>
</body>
</html>
