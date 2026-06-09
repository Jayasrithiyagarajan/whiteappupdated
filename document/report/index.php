<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports</title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/premium-directory.css">
    <link rel="stylesheet" href="../../assets/css/premium-nav.css">
</head>
<body>

<?php include_once('../../inc/nav.php'); ?>

<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <!-- Hero Section - Exact match from Checklist -->
    <div class="directory-hero">
        <div class="directory-title">
            <h2>Reports Directory</h2>
            <p>Review generated reports, monitor status, and track inspection records</p>
        </div>
        <div class="hero-actions">
            <div class="hero-stat">
                <strong id="kpi-total">0</strong>
                <span>Total</span>
            </div>
            <div class="hero-stat is-active">
                <strong id="kpi-active">0</strong>
                <span>Active</span>
            </div>
            <div class="hero-stat is-expired">
                <strong id="kpi-expired">0</strong>
                <span>Expired</span>
            </div>
            <div class="hero-stat" style="background: rgba(0, 155, 114, 0.74);">
                <strong id="kpi-completed">0</strong>
                <span>Completed</span>
            </div>
            <div class="hero-stat" style="background: rgba(217, 119, 6, 0.74);">
                <strong id="kpi-pending">0</strong>
                <span>Pending</span>
            </div>
        </div>
    </div>

    <!-- Filter Section - Same as Checklist -->
    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>Report Filters</h5>
                <p>Refine by inspector, client, date, year, and expiry</p>
            </div>
            <button class="filter-toggle" type="button" onclick="clearFilters()">
                <i class="icofont-refresh"></i> Reset
            </button>
        </div>

        <div class="filter-row">
            <div class="filter-item">
                <label>Inspector</label>
                <select id="filter-inspector">
                    <option value="">All Inspectors</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Date</label>
                <input type="date" id="filter-date">
            </div>
            <div class="filter-item">
                <label>Client</label>
                <select id="filter-client">
                    <option value="">All Clients</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Year</label>
                <select id="filter-year">
                    <option value="">All Years</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Expiry Date</label>
                <input type="date" id="filter-expiry">
            </div>
            <div class="filter-item">
                <button class="btn-clear" onclick="clearFilters()">
                    <i class="icofont-close"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-box">
        <div class="table-panel-header">
            <div class="table-title">
                <h5>Reports List</h5>
                <p>View and manage all generated inspection reports</p>
            </div>
            <div class="table-tools">
                <div class="directory-search">
                    <i class="icofont-search-1"></i>
                    <input type="search" id="report-search" placeholder="Search by report, project, checklist, inspector...">
                </div>
                <div id="table-buttons"></div>
            </div>
        </div>
        <div class="table-shell">
            <table id="report-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Project No</th>
                        <th>Report No</th>
                        <th>Checklist No</th>
                        <th>Inspection Date</th>
                        <th>Company</th>
                        <th>Equipment ID</th>
                        <th>Serial No</th>
                        <th>Sticker No</th>
                        <th>Location</th>
                        <th>Inspector</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
</div>

<?php include_once('../../inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
var reportTable;

$(document).ready(function() {
    reportTable = $('#report-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'desc']],

        ajax: {
            url: 'fetch_reports.php',
            type: 'POST',
            data: function(d) {
                d.filter_inspector = $('#filter-inspector').val();
                d.filter_date = $('#filter-date').val();
                d.filter_client = $('#filter-client').val();
                d.filter_year = $('#filter-year').val();
                d.filter_expiry = $('#filter-expiry').val();
            }
        },

        dom: 'Brtip',
        buttons: [
            {
                text: 'Export to Excel',
                className: 'btn btn-secondary',
                action: function() {
                    window.location.href = 'export_reports_excel.php';
                }
            },
            'copy', 'print'
        ],

        columnDefs: [
            { targets: 12, orderable: false }
        ],

        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }
    });

    loadFilterOptions();
    loadReportKPI();

    $('#filter-inspector, #filter-date, #filter-client, #filter-year, #filter-expiry').on('change', function() {
        reportTable.ajax.reload();
        loadReportKPI();
    });

    // Search functionality
    $('#report-search').on('input', function() {
        reportTable.search(this.value).draw();
    });
});

function loadFilterOptions(){
    $.ajax({
        url: 'fetch_filter_options.php',
        type: 'GET',
        dataType: 'json',
        success: function(res){
            $('#filter-inspector').append(res.inspectors.map(i => `<option value="${i}">${i}</option>`).join(''));
            $('#filter-client').append(res.clients.map(c => `<option value="${c}">${c}</option>`).join(''));
            $('#filter-year').append(res.years.map(y => `<option value="${y}">${y}</option>`).join(''));
        }
    });
}

function loadReportKPI(){
    $.ajax({
        url: 'fetch_report_kpi.php',
        type: 'GET',
        dataType: 'json',
        data: {
            filter_inspector: $('#filter-inspector').val(),
            filter_date: $('#filter-date').val(),
            filter_client: $('#filter-client').val(),
            filter_year: $('#filter-year').val(),
            filter_expiry: $('#filter-expiry').val()
        },
        success: function(res){
            $('#kpi-total').text(res.total || 0);
            $('#kpi-completed').text(res.completed || 0);
            $('#kpi-pending').text(res.pending || 0);
            $('#kpi-active').text(res.active || 0);
            $('#kpi-expired').text(res.expired || 0);
        }
    });
}

function clearFilters(){
    $('#filter-inspector, #filter-date, #filter-client, #filter-year, #filter-expiry, #report-search').val('');
    reportTable.search('');
    reportTable.ajax.reload();
    loadReportKPI();
}

function deleteReport(projectNo, reportNo){
    if(!confirm('Delete this report?')) return;

    fetch('delete_report.php',{
        method:'POST',
        headers:{'Content-Type':'application/x-www-form-urlencoded'},
        body:'project_no='+projectNo+'&report_no='+reportNo
    })
    .then(r=>r.json())
    .then(d=>{
        if(d.success){
            reportTable.ajax.reload(null,false);
            loadReportKPI();
        } else {
            alert(d.message || 'Delete failed');
        }
    });
}
</script>

</body>
</html>