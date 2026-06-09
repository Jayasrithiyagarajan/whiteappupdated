<?php
session_start();
include_once('../../inc/function.php');
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../dashboard/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Certificate KPI Dashboard</title>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../../assets/css/style.css"> <!-- Assuming a global style file exists, adjusting path if needed -->
<link rel="stylesheet" href="../../assets/css/premium-nav.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet">

<style>
:root {
    --app-font: "PT Sans", system-ui, -apple-system, "Segoe UI", Roboto, Arial, sans-serif;
}

body {
    background:
        radial-gradient(circle at 14% 8%, rgba(20, 184, 166, 0.16), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(37, 99, 235, 0.13), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
    color: #111827;
    font-family: var(--app-font);
}

.kpi-dashboard,
.kpi-dashboard *,
.dataTables_wrapper,
.dataTables_wrapper *,
.dt-button,
.btn {
    font-family: var(--app-font) !important;
}

.kpi-dashboard .fa,
.kpi-dashboard .fas,
.dataTables_wrapper .fa,
.dataTables_wrapper .fas {
    font-family: "Font Awesome 6 Free" !important;
    font-weight: 900;
}

.kpi-dashboard {
    position: relative;
    min-height: calc(100vh - 110px);
    padding: 10px 10px 48px;
    overflow: hidden;
    background: transparent;
}

.kpi-dashboard:before {
    content: "";
    position: fixed;
    right: 4%;
    top: 140px;
    width: 360px;
    height: 360px;
    border-radius: 999px;
    background: rgba(20, 184, 166, 0.1);
    filter: blur(4px);
    pointer-events: none;
    z-index: -1;
}

.kpi-dashboard .container-fluid {
    max-width: 1680px;
}

.kpi-hero,
.stat-card,
.filter-section,
.chart-card,
.table-card {
    border: 1px solid rgba(255, 255, 255, 0.64);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48));
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.kpi-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 28px;
    padding: 26px 28px;
    border-radius: 22px;
}

.kpi-hero-title {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.kpi-hero-title .title-icon,
.stat-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex: 0 0 auto;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 16px 32px rgba(15, 23, 42, 0.1);
}

.kpi-hero-title .title-icon {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.16), rgba(20, 184, 166, 0.14));
    color: #2563eb;
    font-size: 24px;
}

.kpi-hero h2 {
    margin-bottom: 8px;
    color: #111827;
    font-size: clamp(25px, 2vw, 34px);
    font-weight: 800;
    letter-spacing: 0;
    text-transform: none;
}

.kpi-hero p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
    line-height: 1.45;
}

.stat-card {
    display: flex;
    align-items: center;
    gap: 18px;
    min-height: 140px;
    padding: 26px;
    border-radius: 20px;
    color: #111827;
    position: relative;
    overflow: hidden;
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
}

.stat-card:before {
    content: "";
    position: absolute;
    right: -32px;
    top: -32px;
    width: 120px;
    height: 120px;
    border-radius: 999px;
    background: rgba(37, 99, 235, 0.08);
}

.stat-card:hover {
    transform: translateY(-4px);
    border-color: rgba(20, 184, 166, 0.32);
    box-shadow: 0 30px 70px rgba(15, 23, 42, 0.16);
}

.stat-card > * {
    position: relative;
    z-index: 1;
}

.stat-icon {
    width: 60px;
    height: 60px;
    border-radius: 18px;
    font-size: 24px;
}

.stat-blue .stat-icon { background: rgba(37, 99, 235, 0.14); color: #2563eb; }
.stat-cyan .stat-icon { background: rgba(8, 145, 178, 0.14); color: #0891b2; }
.stat-green .stat-icon { background: rgba(20, 184, 166, 0.14); color: #0f766e; }
.stat-purple .stat-icon { background: rgba(112, 72, 232, 0.14); color: #7048e8; }
.stat-blue h2 { color: #2563eb; }
.stat-cyan h2 { color: #0891b2; }
.stat-green h2 { color: #059669; }
.stat-purple h2 { color: #7048e8; }

.stat-card h6 {
    margin: 0;
    color: #64748b;
    font-size: 13px;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.stat-card h2 {
    margin: 4px 0 0;
    font-size: 34px;
    font-weight: 850;
    letter-spacing: 0;
}

.filter-section,
.chart-card,
.table-card {
    border-radius: 22px;
    margin-bottom: 32px;
    padding: 26px;
    overflow: hidden;
}

.section-heading {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 18px;
    color: #111827;
    font-size: 17px;
    font-weight: 850;
    letter-spacing: 0;
}

.section-heading i {
    color: #2563eb;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 16px;
    align-items: end;
}

.filter-item {
    min-width: 0;
}

.filter-item label {
    display: block;
    margin-bottom: 8px;
    color: #334155;
    font-size: 13px;
    font-weight: 800;
}

.filter-item select,
.filter-item input {
    width: 100%;
    min-height: 50px;
    padding: 10px 12px;
    border: 1px solid rgba(148, 163, 184, 0.26) !important;
    border-radius: 13px !important;
    background: rgba(255, 255, 255, 0.72) !important;
    color: #111827;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
    font-size: 14px;
    font-weight: 700;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.filter-item select:focus,
.filter-item input:focus {
    border-color: rgba(37, 99, 235, 0.42) !important;
    background: rgba(255, 255, 255, 0.92) !important;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1) !important;
    outline: 0;
}

.btn-clear {
    width: 100%;
    min-height: 50px;
    padding: 10px 18px;
    border: 0;
    border-radius: 13px;
    background: linear-gradient(135deg, #111827, #172033);
    color: #fff;
    box-shadow: 0 16px 30px rgba(15, 23, 42, 0.18);
    cursor: pointer;
    font-size: 14px;
    font-weight: 800;
    transition: transform .2s ease, box-shadow .2s ease;
}

.btn-clear:hover {
    transform: translateY(-1px);
    box-shadow: 0 22px 42px rgba(20, 184, 166, 0.2);
}

.chart-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.chart-grid.two-col {
    grid-template-columns: 1.4fr 1fr;
}

.chart-card h5 {
    margin: 0 0 16px;
    color: #111827;
    font-size: 16px;
    font-weight: 850;
}

.chart-wrap {
    position: relative;
    min-height: 320px;
}

.chart-wrap canvas {
    display: block !important;
    width: 100% !important;
    height: 320px !important;
}

.chart-note {
    margin-top: 12px;
    color: #64748b;
    font-size: 12px;
    line-height: 1.45;
}

.dataTables_scrollBody {
    overflow-x: auto !important;
}

.actions a {
    margin-right: 8px;
}

.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2563eb, #14b8a6);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 14px;
    box-shadow: 0 10px 22px rgba(37, 99, 235, 0.2);
}

.dataTables_wrapper {
    color: #334155;
}

.dataTables_wrapper .dt-buttons {
    margin-bottom: 18px;
}

.dataTables_wrapper .dt-buttons .dt-button,
.dataTables_wrapper .dt-buttons .btn {
    min-height: 42px;
    margin-right: 8px;
    padding: 9px 16px;
    border: 1px solid rgba(148, 163, 184, 0.22) !important;
    border-radius: 12px !important;
    background: linear-gradient(135deg, #2563eb 0%, #16a3d8 52%, #14b8a6 100%) !important;
    color: #fff !important;
    box-shadow: 0 14px 28px rgba(37, 99, 235, 0.2);
    font-weight: 800;
}

.dataTables_wrapper .dataTables_filter label {
    color: #64748b;
    font-weight: 700;
}

.dataTables_wrapper .dataTables_filter input,
.dataTables_wrapper .dataTables_length select {
    min-height: 42px;
    margin-left: 8px;
    padding: 8px 12px;
    border: 1px solid rgba(148, 163, 184, 0.24);
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.72);
    color: #111827;
}

table.dataTable {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0 10px !important;
}

table.dataTable thead th {
    padding: 14px 12px !important;
    border: 0 !important;
    background: rgba(241, 245, 249, 0.78) !important;
    color: #334155;
    font-size: 12px;
    font-weight: 850;
    letter-spacing: .02em;
    text-transform: uppercase;
}

table.dataTable tbody tr {
    background: rgba(255, 255, 255, 0.62);
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
}

table.dataTable tbody td {
    padding: 14px 12px !important;
    border-top: 1px solid rgba(226, 232, 240, 0.58);
    border-bottom: 1px solid rgba(226, 232, 240, 0.58);
    color: #475569;
    vertical-align: middle;
}

table.dataTable tbody td:first-child {
    border-left: 1px solid rgba(226, 232, 240, 0.58);
    border-radius: 14px 0 0 14px;
    font-weight: 800;
    color: #334155;
}

table.dataTable tbody td:last-child {
    border-right: 1px solid rgba(226, 232, 240, 0.58);
    border-radius: 0 14px 14px 0;
}

table.dataTable tbody tr:hover {
    background: rgba(255, 255, 255, 0.88) !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 10px !important;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    border: 0 !important;
    background: #2563eb !important;
    color: #fff !important;
}

@media (max-width: 1200px) {
    .chart-grid,
    .chart-grid.two-col {
        grid-template-columns: 1fr 1fr;
    }

    .filter-row {
        grid-template-columns: repeat(3, minmax(0, 1fr));
    }
}

@media (max-width: 991px) {
    .kpi-dashboard {
        padding: 0 2px 36px;
    }

    .kpi-hero,
    .filter-section,
    .chart-card,
    .table-card {
        padding: 22px;
        border-radius: 18px;
    }
}

@media (max-width: 767px) {
    .kpi-hero {
        flex-direction: column;
        align-items: stretch;
    }

    .kpi-hero-title {
        align-items: flex-start;
    }

    .stat-card {
        min-height: 118px;
    }

    .stat-card h2 {
        font-size: 28px;
    }

    .filter-row,
    .chart-grid,
    .chart-grid.two-col {
        grid-template-columns: 1fr;
    }

    .chart-wrap {
        min-height: 280px;
    }

    .chart-wrap canvas {
        height: 280px !important;
    }

    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_length {
        float: none;
        text-align: left;
        margin-top: 12px;
    }

    .dataTables_wrapper .dataTables_filter input {
        width: 100%;
        margin: 8px 0 0;
    }
}
</style>
</head>

<body>

<?php include_once('../../inc/nav.php'); ?>

<div class="main-content d-flex flex-column kpi-dashboard">
<div class="container-fluid mt-4">

    <div class="kpi-hero">
        <div class="kpi-hero-title">
            <span class="title-icon"><i class="fa fa-chart-line"></i></span>
            <div>
                <h2>Certificate KPI Dashboard</h2>
                <p>Track project performance, certificate status, inspection mix, and customer activity in one premium dashboard.</p>
            </div>
        </div>
    </div>

    <!-- STATS ROW -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card stat-blue">
                <div class="stat-icon"><i class="fa fa-folder-open"></i></div>
                <div>
                    <h6>Total Projects</h6>
                    <h2 id="stats-total">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-cyan">
                <div class="stat-icon"><i class="fa fa-spinner"></i></div>
                <div>
                    <h6>Active Projects</h6>
                    <h2 id="stats-active">0</h2>
                </div>
            </div>
        </div>
         <div class="col-md-3">
            <div class="stat-card stat-green">
                <div class="stat-icon"><i class="fa fa-circle-check"></i></div>
                <div>
                    <h6>Completed Projects</h6>
                    <h2 id="stats-completed">0</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card stat-purple">
                <div class="stat-icon"><i class="fa fa-file-signature"></i></div>
                <div>
                    <h6>Total Certificates</h6>
                    <h2 id="stats-certificates">0</h2>
                </div>
            </div>
        </div>
    </div>


    <!-- FILTER SECTION -->
    <div class="filter-section">
        <h5 class="section-heading"><i class="fa fa-filter"></i> Filter Certificate KPI Data</h5>
        <div class="filter-row">
            <div class="filter-item">
                <label>Inspector</label>
                <select id="filter-inspector">
                    <option value="">All Inspectors</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Client</label>
                <select id="filter-client">
                    <option value="">All Clients</option>
                </select>
            </div>
            <div class="filter-item">
                <label>From Date</label>
                <input type="date" id="filter-date-from">
            </div>
            <div class="filter-item">
                <label>To Date</label>
                <input type="date" id="filter-date-to">
            </div>
             <div class="filter-item">
                <label>Equipment ID</label>
                <input type="text" id="filter-equipment" placeholder="Search Equipment ID">
            </div>
             <div class="filter-item">
                <label>Location</label>
                <input type="text" id="filter-location" placeholder="Search Location">
            </div>
            <div class="filter-item">
                <button class="btn-clear" onclick="clearFilters()">Clear Filters</button>
            </div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h5>Completed Certificates By Customer</h5>
            <div class="chart-wrap">
                <canvas id="customerCertificatesChart" height="320"></canvas>
            </div>
            <div class="chart-note">Top customers based on completed certificate count for the current filter.</div>
        </div>
        <div class="chart-card">
            <h5>Certificate Status</h5>
            <div class="chart-wrap">
                <canvas id="certificateStatusChart" height="320"></canvas>
            </div>
            <div class="chart-note">Overall done versus pending certificates.</div>
        </div>
        <div class="chart-card">
            <h5>Done By Inspection Type</h5>
            <div class="chart-wrap">
                <canvas id="inspectionTypeChart" height="320"></canvas>
            </div>
            <div class="chart-note">Completed certificate count grouped by inspection type.</div>
        </div>
    </div>

    <div class="chart-grid two-col">
        <div class="chart-card">
            <h5>Individual Certificate Reports</h5>
            <div class="chart-wrap">
                <canvas id="certificateReportsBarChart" height="320"></canvas>
            </div>
            <div class="chart-note">Counts from each certificate report table for the selected filter.</div>
        </div>
        <div class="chart-card">
            <h5>Certificate Reports Share</h5>
            <div class="chart-wrap">
                <canvas id="certificateReportsPieChart" height="320"></canvas>
            </div>
            <div class="chart-note">Distribution of individual certificate reports by certificate type.</div>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="table-card">
        <h4 class="section-heading"><i class="fa fa-table-list"></i> Certificate KPI Project List</h4>

        <table id="kpi-table" class="display nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Project No</th>
                    <th>Creation Date</th>
                    <th>Client</th>
                    <th>Location</th>
                    <th>Use Equipment ID</th>
                    <th>Equipment Type</th>
                    <th>Target Inspector</th>
                    <th>Sticker Status</th>
                    <th>Inspection Type</th>
                </tr>
            </thead>
        </table>
    </div>

</div>
</div>

<?php include_once('../../inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="../../assets/plugins/chartjs/Chart.min.js"></script>

<script>
var kpiTable;
var customerCertificatesChart;
var certificateStatusChart;
var inspectionTypeChart;
var certificateReportsBarChart;
var certificateReportsPieChart;

function getCurrentFilters() {
    return {
        filter_inspector: $('#filter-inspector').val(),
        filter_client: $('#filter-client').val(),
        filter_date_from: $('#filter-date-from').val(),
        filter_date_to: $('#filter-date-to').val(),
        filter_equipment: $('#filter-equipment').val(),
        filter_location: $('#filter-location').val()
    };
}

$(function(){

    kpiTable = $('#kpi-table').DataTable({
        processing:true,
        serverSide:true,

        ajax:{
            url:'fetch_kpi.php',
            type:'POST',
            data: function(d) {
                $.extend(d, getCurrentFilters());
            }
        },

        dom:'Bfrtip',
        buttons:[
            {
                text: 'Export CSV',
                action: function ( e, dt, node, config ) {
                    var filterInspector = $('#filter-inspector').val();
                    var filterClient = $('#filter-client').val();
                    var filterDateFrom = $('#filter-date-from').val();
                    var filterDateTo = $('#filter-date-to').val();
                    var filterEquipment = $('#filter-equipment').val();
                    var filterLocation = $('#filter-location').val();

                    var url = 'export_kpi.php?' + 
                        'filter_inspector=' + encodeURIComponent(filterInspector) +
                        '&filter_client=' + encodeURIComponent(filterClient) +
                        '&filter_date_from=' + encodeURIComponent(filterDateFrom) +
                        '&filter_date_to=' + encodeURIComponent(filterDateTo) +
                        '&filter_equipment=' + encodeURIComponent(filterEquipment) +
                        '&filter_location=' + encodeURIComponent(filterLocation);
                    
                    window.location.href = url;
                }
            },
            'copy', 'print'
        ],

        order:[[0,'desc']], // Default sort by Project No descending

        scrollX:true,
        autoWidth:false,

        pageLength:25,
        lengthMenu:[10,25,50,100]
    });

    // Load filter options (Inspectors and Clients)
    loadFilterOptions();
    loadStats();
    loadCharts();

    // Attach filter change handlers
    $('#filter-inspector, #filter-client, #filter-date-from, #filter-date-to, #filter-equipment, #filter-location').on('change keyup', function(){
        kpiTable.ajax.reload();
        loadStats();
        loadCharts();
    });

});

function loadFilterOptions(){
    $.ajax({
        url: 'fetch_filter_options.php', // We can reuse or create a new one
        type: 'GET',
        dataType: 'json',
        success: function(res){
            var inspectorSelect = $('#filter-inspector');
            var clientSelect = $('#filter-client');
            
            // Clear existing options except the first one
            inspectorSelect.find('option:not(:first)').remove();
            clientSelect.find('option:not(:first)').remove();

            if(res.inspectors){
                res.inspectors.forEach(function(inspector){
                    inspectorSelect.append('<option value="'+inspector+'">'+inspector+'</option>');
                });
            }
            if(res.clients){
                res.clients.forEach(function(client){
                     // Check if client is an object or string, adjust accordingly
                     var clientName = (typeof client === 'object') ? client.name : client;
                     var clientVal = (typeof client === 'object') ? client.id : client;
                    clientSelect.append('<option value="'+clientName+'">'+clientName+'</option>');
                });
            }
        }
    }); 
}

function loadStats(){
    $.ajax({
        url: 'fetch_stats.php',
        type: 'POST', // Use POST to send current filter data
        data: getCurrentFilters(),
        dataType: 'json',
        success: function(res){
            $('#stats-total').text(res.total);
            $('#stats-active').text(res.active);
            $('#stats-completed').text(res.completed);
            $('#stats-certificates').text(res.certificates);
        }
    });
}

function loadCharts(){
    $.ajax({
        url: 'fetch_charts.php',
        type: 'POST',
        data: getCurrentFilters(),
        dataType: 'json',
        success: function(res){
            renderCustomerCertificatesChart(res.customer_chart || {});
            renderCertificateStatusChart(res.status_chart || {});
            renderInspectionTypeChart(res.inspection_type_chart || {});
            renderCertificateReportsBarChart(res.certificate_reports_chart || {});
            renderCertificateReportsPieChart(res.certificate_reports_chart || {});
        }
    });
}

function renderCustomerCertificatesChart(data){
    var ctx = document.getElementById('customerCertificatesChart').getContext('2d');

    if (customerCertificatesChart) {
        customerCertificatesChart.destroy();
    }

    customerCertificatesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels || [],
            datasets: [{
                label: 'Completed Certificates',
                data: data.values || [],
                backgroundColor: '#2563eb',
                borderRadius: 8,
                maxBarThickness: 42
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: '#6b7280',
                        maxRotation: 35,
                        minRotation: 0
                    },
                    gridLines: { display: false }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0,
                        fontColor: '#6b7280'
                    },
                    gridLines: {
                        color: 'rgba(107,114,128,0.12)'
                    }
                }]
            }
        }
    });
}

function renderCertificateStatusChart(data){
    var ctx = document.getElementById('certificateStatusChart').getContext('2d');

    if (certificateStatusChart) {
        certificateStatusChart.destroy();
    }

    certificateStatusChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels || ['Done', 'Pending'],
            datasets: [{
                data: data.values || [0, 0],
                backgroundColor: ['#16a34a', '#f59e0b'],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
}

function renderInspectionTypeChart(data){
    var ctx = document.getElementById('inspectionTypeChart').getContext('2d');

    if (inspectionTypeChart) {
        inspectionTypeChart.destroy();
    }

    inspectionTypeChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels || [],
            datasets: [{
                data: data.values || [],
                backgroundColor: ['#0f766e', '#2563eb', '#7c3aed', '#f59e0b', '#dc2626', '#0891b2', '#ea580c', '#14b8a6'],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            },
            cutoutPercentage: 58
        }
    });
}

function renderCertificateReportsBarChart(data){
    var ctx = document.getElementById('certificateReportsBarChart').getContext('2d');

    if (certificateReportsBarChart) {
        certificateReportsBarChart.destroy();
    }

    certificateReportsBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels || [],
            datasets: [{
                label: 'Report Count',
                data: data.values || [],
                backgroundColor: ['#2563eb', '#16a34a', '#0891b2', '#f59e0b', '#dc2626', '#7c3aed', '#0f766e', '#ea580c', '#ec4899'],
                borderRadius: 8,
                maxBarThickness: 42
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{
                    ticks: {
                        fontColor: '#6b7280',
                        maxRotation: 30,
                        minRotation: 0
                    },
                    gridLines: { display: false }
                }],
                yAxes: [{
                    ticks: {
                        beginAtZero: true,
                        precision: 0,
                        fontColor: '#6b7280'
                    },
                    gridLines: {
                        color: 'rgba(107,114,128,0.12)'
                    }
                }]
            }
        }
    });
}

function renderCertificateReportsPieChart(data){
    var ctx = document.getElementById('certificateReportsPieChart').getContext('2d');

    if (certificateReportsPieChart) {
        certificateReportsPieChart.destroy();
    }

    certificateReportsPieChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels || [],
            datasets: [{
                data: data.values || [],
                backgroundColor: ['#2563eb', '#16a34a', '#0891b2', '#f59e0b', '#dc2626', '#7c3aed', '#0f766e', '#ea580c', '#ec4899'],
                borderColor: '#ffffff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'bottom'
            }
        }
    });
}

function clearFilters(){
    $('#filter-inspector').val('');
    $('#filter-client').val('');
    $('#filter-date-from').val('');
    $('#filter-date-to').val('');
    $('#filter-equipment').val('');
    $('#filter-location').val('');
    kpiTable.ajax.reload();
    loadStats();
    loadCharts();
}
</script>

</body>
</html>
