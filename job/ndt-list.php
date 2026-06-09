<?php
include_once('../inc/function.php');
include '../file/config.php';

$logged_in_user = $_SESSION['username'] ?? null;
$user_role      = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NDT Job List</title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/fonts/icofont/icofont.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/premium-directory.css">
    <link rel="stylesheet" href="../assets/css/premium-nav.css">

<style>
.status-badge {
    display: inline-flex;
    align-items: center;
    min-height: 24px;
    padding: 4px 12px !important;
    border-radius: 999px;
    font-size: 12px !important;
    font-weight: 700;
    line-height: 1.2;
}
.status-badge.badge-success {
    color: #009b72 !important;
    background: #e9fbf3 !important;
    border: 1px solid #c9f2e4 !important;
}
.status-badge.badge-danger {
    color: #e02424 !important;
    background: #fff1f1 !important;
    border: 1px solid #ffd8d8 !important;
}
.status-badge.pending {
    color: #b45309 !important;
    background: #fffbeb !important;
    border: 1px solid #fde68a !important;
}
.status-badge.badge-warning {
    color: #1d4ed8 !important;
    background: #eff6ff !important;
    border: 1px solid #bfdbfe !important;
}
.action-icons a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    background: #f0f4f8;
    color: #475569;
    margin-right: 4px;
    transition: all 0.2s;
}
.action-icons a:hover {
    background: #e0f2fe;
    color: #0284c7;
}

/* Fixed Width Columns */
table.dataTable th:nth-child(7), table.dataTable td:nth-child(7),
table.dataTable th:nth-child(14), table.dataTable td:nth-child(14) {
    width: 180px !important;
    min-width: 180px !important;
    max-width: 180px !important;
    white-space: normal !important;
    word-wrap: break-word !important;
}

.avatar-circle {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 800;
    font-size: 14px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    flex-shrink: 0;
}

/* Dynamic Avatar Colors */
<?php
$colors = [
    'A' => '#3b82f6', 'B' => '#10b981', 'C' => '#f59e0b', 'D' => '#ef4444',
    'E' => '#8b5cf6', 'F' => '#ec4899', 'G' => '#14b8a6', 'H' => '#f97316',
    'I' => '#6366f1', 'J' => '#22c55e', 'K' => '#eab308', 'L' => '#e11d48',
    'M' => '#7c3aed', 'N' => '#db2777', 'O' => '#0ea5e9', 'P' => '#f43f5e',
    'Q' => '#4f46e5', 'R' => '#16a34a', 'S' => '#ca8a04', 'T' => '#dc2626',
    'U' => '#6d28d9', 'V' => '#e11d48', 'W' => '#0284c8', 'X' => '#b45309',
    'Y' => '#7e22ce', 'Z' => '#c026d3'
];
foreach ($colors as $letter => $color) {
    echo ".avatar-circle.initial-$letter { background-color: $color !important; }\n";
}
?>
</style>
</head>
<body>

<?php include_once('../inc/nav.php'); ?>

<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <!-- Hero Section - KPIs moved here like Lifting Gear -->
    <div class="directory-hero">
        <div class="directory-title">
            <span class="title-icon"><i class="icofont-laboratory"></i></span>
            <div>
                <h2>NDT Job List</h2>
                <p>Track NDT jobs, filter inspection assignments, and review active or expired project status.</p>
            </div>
        </div>
        
        <div class="hero-actions">
            <?php if ($user_role === 'admin') { ?>
                <a href="create-job.php" class="btn btn-primary">
                    <i class="icofont-plus"></i> New Job
                </a>
            <?php } ?>
            
            <!-- KPIs -->
            <div class="hero-stat">
                <strong id="stats-total">0</strong>
                <span>Total Projects</span>
            </div>
            <div class="hero-stat is-active">
                <strong id="stats-active">0</strong>
                <span>Active Projects</span>
            </div>
            <div class="hero-stat is-expired">
                <strong id="stats-expired">0</strong>
                <span>Expired Projects</span>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>NDT Job Filters</h5>
                <p>Refine by inspector, client, date range, year, and expiry status</p>
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
                <label>Client</label>
                <select id="filter-client">
                    <option value="">All Clients</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Date From</label>
                <input type="date" id="filter-date-from">
            </div>
            <div class="filter-item">
                <label>Date To</label>
                <input type="date" id="filter-date-to">
            </div>
            <div class="filter-item">
                <label>Year</label>
                <select id="filter-year">
                    <option value="">All Years</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Active / Expired</label>
                <select id="filter-expiry-status">
                    <option value="">All</option>
                    <option value="Active">Active</option>
                    <option value="Expired">Expired</option>
                </select>
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
                <h5>NDT Jobs</h5>
                <p>View all NDT inspection jobs and their current status</p>
            </div>
            <div class="table-tools">
                <div class="directory-search">
                    <i class="icofont-search-1"></i>
                    <input type="search" id="job-search" placeholder="Search by job no, customer, inspector, equipment...">
                </div>
                <div id="table-buttons"></div>
            </div>
        </div>
        <div class="table-shell">
            <table id="job-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Job No</th>
                        <th>Date</th>
                        <th>Checklist Status</th>
                        <th>Report Status</th>
                        <th>Review Status</th>
                        <th>Certificate Status</th>
                        <th>Customer</th>
                        <th>Project Status</th>
                        <th>Action</th>
                        <th>Checklist Type</th>
                        <th>Equip Type</th>
                        <th>Equip ID</th>
                        <th>Location</th>
                        <th>Inspector</th>
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
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
// ==================== JAVASCRIPT (Functionality Unchanged) ====================
var table;

$(document).ready(function() {
    
    table = $('#job-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[1, 'desc']],

        ajax: {
            url: 'fetch_ndt_jobs.php',
            type: 'POST',
            data: function(d) {
                d.filter_inspector = $('#filter-inspector').val();
                d.filter_client = $('#filter-client').val();
                d.filter_date_from = $('#filter-date-from').val();
                d.filter_date_to = $('#filter-date-to').val();
                d.filter_year = $('#filter-year').val();
                d.filter_expiry_status = $('#filter-expiry-status').val();
            }
        },

        dom: 'Brtip',
        buttons: [
            {
                text: 'Export CSV',
                action: function (e, dt, node, config) {
                    var params = $.param({
                        filter_inspector: $('#filter-inspector').val(),
                        filter_client: $('#filter-client').val(),
                        filter_date_from: $('#filter-date-from').val(),
                        filter_date_to: $('#filter-date-to').val(),
                        filter_year: $('#filter-year').val(),
                        filter_expiry_status: $('#filter-expiry-status').val(),
                        search_value: dt.search()
                    });
                    window.location.href = 'export_ndt.php?' + params;
                },
                className: 'btn btn-secondary'
            },
            'copy', 'print'
        ],

        columnDefs: [
            { targets: 8, orderable: false }
        ],

        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }
    });

    loadFilters();
    loadStats();

    $('#filter-inspector, #filter-client, #filter-date-from, #filter-date-to, #filter-year, #filter-expiry-status').on('change', function() {
        table.ajax.reload();
        loadStats();
    });

    $('#job-search').on('input', function() {
        table.search(this.value).draw();
    });
});

function loadFilters(){
    $.ajax({
        url: 'fetch_ndt_filters.php',
        type: 'GET',
        dataType: 'json',
        success: function(res){
            var insSelect = $('#filter-inspector');
            res.inspectors.forEach(function(i){
                insSelect.append('<option value="'+i+'">'+i+'</option>');
            });

            var cliSelect = $('#filter-client');
            res.clients.forEach(function(c){
                cliSelect.append('<option value="'+c+'">'+c+'</option>');
            });

            var yearSelect = $('#filter-year');
            res.years.forEach(function(y){
                yearSelect.append('<option value="'+y+'">'+y+'</option>');
            });
        }
    });
}

function loadStats(){
    $.ajax({
        url: 'fetch_ndt_stats.php',
        type: 'POST',
        dataType: 'json',
        data: {
            filter_inspector: $('#filter-inspector').val(),
            filter_client: $('#filter-client').val(),
            filter_date_from: $('#filter-date-from').val(),
            filter_date_to: $('#filter-date-to').val(),
            filter_year: $('#filter-year').val(),
            filter_expiry_status: $('#filter-expiry-status').val()
        },
        success: function(res){
            $('#stats-total').text(res.total || 0);
            $('#stats-active').text(res.active || 0);
            $('#stats-expired').text(res.expired || 0);
        }
    });
}

function clearFilters(){
    $('#filter-inspector').val('');
    $('#filter-client').val('');
    $('#filter-date-from').val('');
    $('#filter-date-to').val('');
    $('#filter-year').val('');
    $('#filter-expiry-status').val('');
    $('#job-search').val('');
    
    table.search('');
    table.ajax.reload();
    loadStats();
}
</script>

</body>
</html>