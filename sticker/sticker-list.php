<?php 
include_once('../inc/function.php');
include '../file/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logged_in_role = $_SESSION['role'] ?? null;
$logged_in_user = $_SESSION['username'] ?? null;

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
    <title>Sticker List</title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../assets/fonts/icofont/icofont.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/premium-directory.css">
    <link rel="stylesheet" href="../assets/css/premium-nav.css">
</head>
<body>

<?php include_once('../inc/nav.php'); ?>

<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <!-- Hero Section -->
    <div class="directory-hero">
        <div class="directory-title">
            <h2>Sticker Dashboard</h2>
            <p>Track sticker inventory, inspection status, expiry dates, and downloads</p>
        </div>
        
        <div class="hero-actions">
            <div class="hero-stat">
                <strong id="total-count">0</strong>
                <span>Total Stickers</span>
            </div>
            <div class="hero-stat">
                <strong id="unused-count">0</strong>
                <span>Unused Stickers</span>
            </div>
            <div class="hero-stat is-active">
                <strong id="passed-count">0</strong>
                <span>Passed Stickers</span>
            </div>
            <div class="hero-stat is-expired">
                <strong id="failed-count">0</strong>
                <span>Failed Stickers</span>
            </div>

            <?php if ($logged_in_role === 'admin'): ?>
                <a href="./add-sticker.php" class="btn btn-primary" style="margin-left: 12px; align-self: center;">
                    <i class="icofont-plus-circle"></i> Create Sticker
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>Sticker Filters</h5>
                <p>Refine by inspector, date, project, expiry, and status</p>
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
                <label>Created Date</label>
                <input type="date" id="filter-date">
            </div>
            <div class="filter-item">
                <label>Project ID</label>
                <input type="text" id="filter-project" placeholder="Project ID">
            </div>
            <div class="filter-item">
                <label>Expiry Date</label>
                <input type="date" id="filter-expiry">
            </div>
            <div class="filter-item">
                <label>Status</label>
                <select id="filter-status">
                    <option value="">All Status</option>
                    <option value="Unused">Unused</option>
                    <option value="Passed">Passed</option>
                    <option value="Failed">Failed</option>
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
                <h5>Sticker Records</h5>
                <p>Manage all stickers with inspection status and expiry tracking</p>
            </div>
            <div class="table-tools">
                <div class="directory-search">
                    <i class="icofont-search-1"></i>
                    <input type="search" id="customSearch" placeholder="Search sticker, project, inspector, status...">
                </div>
                <div id="table-buttons"></div>
            </div>
        </div>
        <div class="table-shell">
            <table id="sticker-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Sticker ID</th>
                        <th>Project ID</th>
                        <th>Inspect By</th>
                        <th>Created At</th>
                        <th>Inspection</th>
                        <th>Expiry</th>
                        <th>Sticker Status</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
</div>

<?php include_once('../inc/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
var table;

$(document).ready(function() {

    table = $('#sticker-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'desc']],

        ajax: {
            url: 'fetch_stickers.php',
            type: 'POST',
            data: function(d) {
                d.filter_inspector = $('#filter-inspector').val();
                d.filter_date = $('#filter-date').val();
                d.filter_project = $('#filter-project').val();
                d.filter_expiry = $('#filter-expiry').val();
                d.filter_status = $('#filter-status').val();
            }
        },

        dom: 'Brtip',
        columnDefs: [
            { targets: -1, orderable: false }
        ],

        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }
    });

    // Live Search
    $('#customSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Filter Change
    $('#filter-inspector, #filter-date, #filter-project, #filter-expiry, #filter-status').on('change', function() {
        table.ajax.reload();
        loadStats();
    });

    loadFilterOptions();
    loadStats();
});

function loadFilterOptions(){
    $.ajax({
        url: 'fetch_sticker_filter_options.php',   // ← You need to create this file
        type: 'GET',
        dataType: 'json',
        success: function(res){
            if(res.inspectors){
                $('#filter-inspector').append(res.inspectors.map(i => `<option value="${i}">${i}</option>`).join(''));
            }
        }
    });
}

function loadStats(){
    $.ajax({
        url: 'fetch_sticker_stats.php',
        type: 'POST',
        dataType: 'json',
        data: {
            filter_inspector: $('#filter-inspector').val(),
            filter_date: $('#filter-date').val(),
            filter_project: $('#filter-project').val(),
            filter_expiry: $('#filter-expiry').val(),
            filter_status: $('#filter-status').val()
        },
        success: function(res){
            animateCount('total-count',  res.total);
            animateCount('unused-count', res.unused);
            animateCount('passed-count', res.passed);
            animateCount('failed-count', res.failed);
        }
    });
}

function clearFilters(){
    $('#filter-inspector, #filter-date, #filter-project, #filter-expiry, #filter-status, #customSearch').val('');
    table.search('');
    table.ajax.reload();
    loadStats();
}

function animateCount(id, target){
    var el = $('#' + id);
    var n = parseInt(target) || 0;
    var duration = 800;
    var startTime = null;
    function step(ts){
        if (!startTime) startTime = ts;
        var progress = Math.min((ts - startTime) / duration, 1);
        var eased = 1 - Math.pow(1 - progress, 3);
        el.text(Math.round(eased * n));
        if (progress < 1) requestAnimationFrame(step);
        else el.text(n);
    }
    requestAnimationFrame(step);
}

function exportData() {
    var searchValue = $('#customSearch').val();
    var params = $.param({ 
        search_value: searchValue,
        filter_inspector: $('#filter-inspector').val(),
        filter_date: $('#filter-date').val(),
        filter_project: $('#filter-project').val(),
        filter_expiry: $('#filter-expiry').val(),
        filter_status: $('#filter-status').val()
    });
    window.location.href = 'export_stickers.php?' + params;
}
</script>

</body>
</html>