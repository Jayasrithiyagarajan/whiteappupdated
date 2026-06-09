<?php
// session_start();
include_once('../inc/function.php');
include_once('../file/config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Operator Assessment Dashboard</title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <!-- Chart JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* ===== GLOBAL ===== */
        body {
            background: #f3f5fb;
            font-family: "Segoe UI", Roboto, sans-serif;
        }

        .main-content {
            padding-bottom: 40px;
        }

        /* ===== CARD BOX ===== */
        .card-box {
            background: #fff;
            border-radius: 14px;
            padding: 22px;
            border: 1px solid #edf0f7;
            box-shadow: 0 6px 18px rgba(0,0,0,.04);
            transition: .25s;
            height: 100%;
        }
        .card-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,.06);
        }

        /* ===== KPI CARDS ===== */
        .kpi-row .card-box {
            text-align: center;
        }
        .card-box h6 {
            font-weight: 600;
            margin-bottom: 8px;
            color: #6c757d;
        }
        .card-box h2 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 0;
        }

        .text-success { color: #22c55e!important; }
        .text-warning { color: #f59e0b!important; }
        .text-info { color: #0ea5e9!important; }
        .text-danger { color: #ef4444!important; }
        .text-primary { color: #6045e2!important; }

        /* ===== FILTER SECTION ===== */
        .filter-section {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            border: 1px solid #edf0f7;
            box-shadow: 0 4px 12px rgba(0,0,0,.03);
            margin-bottom: 25px;
        }

        .filter-row {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-item {
            flex: 1 1 200px;
        }

        .filter-item label {
            font-weight: 600;
            font-size: 13px;
            margin-bottom: 6px;
            display: block;
            color: #555;
        }

        .filter-item select,
        .filter-item input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #e2e6f0;
            border-radius: 8px;
            font-size: 14px;
            background: #fafbff;
        }

        .filter-item select:focus,
        .filter-item input:focus {
            outline: none;
            border-color: #6045e2;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(96,69,226,.08);
        }

        /* ===== BUTTON ===== */
        .btn-clear {
            padding: 10px 20px;
            background: #6045e2;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
        }
        .btn-clear:hover {
            background: #4e36c9;
        }

        /* ===== TABLE ===== */
        table.dataTable {
            border-collapse: separate!important;
            border-spacing: 0 8px!important;
        }

        #assessment-table tbody tr {
            background: #fff;
            box-shadow: 0 2px 6px rgba(0,0,0,.03);
            border-radius: 10px;
        }

        #assessment-table tbody td {
            border-top: none!important;
            padding: 12px 10px;
            vertical-align: middle;
        }

        .dataTables_scrollBody {
            overflow-x: auto!important;
        }

        /* ===== CHARTS ===== */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* ===== BADGES ===== */
        .badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 12px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .card-box h2 { font-size: 24px; }
            .filter-row { gap: 12px; }
        }

        @media (max-width: 768px) {
            .kpi-row > div {
                width: 100%!important;
                max-width: 100%!important;
                flex: 0 0 100%!important;
                margin-bottom: 15px;
            }
            .filter-row { flex-direction: column; }
            .filter-item { width: 100%; }
        }
    </style>
</head>

<body>

<div class="main-content">
<div class="container-fluid mt-4">

    <!-- HEADER TITLE -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-dark font-weight-bold">Operator Assessment Dashboard</h4>
        <?php if ($_SESSION['role'] === 'admin'): ?>
            <a href="create-assessment.php" class="btn btn-primary" style="background: #6045e2; border-color: #6045e2;">
                <i class="fas fa-plus"></i> New Assessment
            </a>
        <?php endif; ?>
    </div>
    
    <!-- KPI SECTION -->
    <div class="row kpi-row mb-4">
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card-box">
                <h6>Total Operators</h6>
                <h2 class="text-primary" id="kpi-total">0</h2>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card-box">
                <h6>Exams Passed</h6>
                <h2 class="text-success" id="kpi-exam-passed">0</h2>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card-box">
                <h6>Exams Failed</h6>
                <h2 class="text-danger" id="kpi-exam-failed">0</h2>
            </div>
        </div>
        <div class="col-md-2 col-sm-6 mb-3">
            <div class="card-box">
                <h6>Signals Passed</h6>
                <h2 class="text-success" id="kpi-signals-passed">0</h2>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card-box">
                <h6>Signals Failed</h6>
                <h2 class="text-danger" id="kpi-signals-failed">0</h2>
            </div>
        </div>
    </div>

    <!-- CHARTS SECTION -->
    <div class="row w-100 mb-4 mx-0">
        <div class="col-md-4 pl-0 pr-2">
            <div class="card-box">
                <h6 class="mb-3">Assessment Status</h6>
                <div class="chart-container">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-8 pr-0 pl-2">
            <div class="card-box">
                <h6 class="mb-3">Assessments Over Time</h6>
                <div class="chart-container">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- FILTER SECTION -->
    <div class="filter-section">
        <h5 class="mb-3">Filter Assessments</h5>
        <div class="filter-row">
            <div class="filter-item">
                <label>Date</label>
                <input type="date" id="filter-date">
            </div>
            <div class="filter-item">
                <label>Location</label>
                <input type="text" id="filter-location" placeholder="e.g. Riyadh">
            </div>
            <div class="filter-item">
                <label>Status</label>
                <select id="filter-status">
                    <option value="">All Statuses</option>
                    <option value="PENDING">Pending</option>
                    <option value="IN_PROGRESS">In Progress</option>
                    <option value="COMPLETED">Completed</option>
                </select>
            </div>
            <div class="filter-item">
                <button class="btn-clear" onclick="clearFilters()">Clear Filters</button>
            </div>
        </div>
    </div>

    <!-- TABLE SECTION -->
    <div class="card-box">
        <h4 class="mb-3 font-weight-bold">Recent Assessments</h4>
        <div class="table-responsive">
            <table id="assessment-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Assessment No</th>
                        <th>Date</th>
                        <th>Operator Name</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th>Exam Result</th>
                        <th>Signals Result</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
</div>

<?php include_once('../inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
var assessmentTable;
var statusChart;
var trendChart;

$(function(){

    // 1. Initialize DataTable
    assessmentTable = $('#assessment-table').DataTable({
        processing: true,
        serverSide: true,
        ajax:{
            url: 'fetch_dashboard_table.php',
            type: 'POST',
            data: function(d) {
                d.filter_date = $('#filter-date').val();
                d.filter_location = $('#filter-location').val();
                d.filter_status = $('#filter-status').val();
            }
        },
        order: [[0, 'desc']], // ID column
        scrollX: true,
        autoWidth: false,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        columnDefs: [
            { targets: 7, orderable: false } // Actions column unorderable
        ]
    });

    // 2. Attach filter change handlers
    $('#filter-date, #filter-location, #filter-status').on('change keyup', function(){
        assessmentTable.ajax.reload();
        // Since filtering might be an active action, we could choose to filter charts/KPIs as well. 
        // For now, dashboards usually show global KPIs. But let's load KPIs to make it dynamic if we pass filters in future.
    });

    // 3. Load KPIs
    loadKPIs();

    // 4. Load Charts
    loadCharts();

});

function clearFilters() {
    $('#filter-date').val('');
    $('#filter-location').val('');
    $('#filter-status').val('');
    assessmentTable.ajax.reload();
}

function loadKPIs() {
    $.ajax({
        url: 'fetch_dashboard_kpi.php',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.error) return;
            $('#kpi-total').text(res.total);
            $('#kpi-exam-passed').text(res.exam_passed);
            $('#kpi-exam-failed').text(res.exam_failed);
            $('#kpi-signals-passed').text(res.signals_passed);
            $('#kpi-signals-failed').text(res.signals_failed);
        }
    });
}

function loadCharts() {
    $.ajax({
        url: 'fetch_dashboard_charts.php',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.error) return;

            // Status Donut Chart
            var ctxStatus = document.getElementById('statusChart').getContext('2d');
            statusChart = new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: res.status_distribution.labels,
                    datasets: [{
                        data: res.status_distribution.data,
                        backgroundColor: ['#0ea5e9', '#f59e0b', '#22c55e'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    },
                    cutout: '70%'
                }
            });

            // Monthly Trend Bar Chart
            var ctxTrend = document.getElementById('trendChart').getContext('2d');
            trendChart = new Chart(ctxTrend, {
                type: 'bar',
                data: {
                    labels: res.monthly_trend.labels,
                    datasets: [{
                        label: 'Assessments',
                        data: res.monthly_trend.data,
                        backgroundColor: '#6045e2',
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true,
                            grid: { borderDash: [5, 5] }
                        },
                        x: {
                            grid: { display: false }
                        }
                    }
                }
            });
        }
    });
}
</script>

</body>
</html>
