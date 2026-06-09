<?php
// session_start();
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
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Rocking Test Certificates</title>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="../../assets/fonts/icofont/icofont.min.css">
<link rel="stylesheet" href="../../assets/css/style.css">
<link rel="stylesheet" href="../../assets/css/premium-directory.css">
<link rel="stylesheet" href="../../assets/css/premium-nav.css">

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
.action-icons a.view-icon:hover {
    background: #e0f2fe;
    color: #0284c7;
}
.action-icons a.download-icon:hover {
    background: #dcfce7;
    color: #15803d;
}

/* Client Column - Fixed width with word wrap */
/* Avatar Circle */
.avatar-circle {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: white !important;
    flex-shrink: 0;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
    text-align: center;
}

/* Make sure client column word-wrap works better */
td:nth-child(6), th:nth-child(6) {
    width: 180px !important;
    min-width: 180px !important;
    max-width: 180px !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-all !important;
    vertical-align: top;
}
</style>
</head>

<body>
<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <!-- Hero Section -->
    <div class="directory-hero">
        <div class="directory-title">
            <h2>Rocking Test</h2>
            <p>Monitor rocking test certificates, expiry status, clients, and inspectors</p>
        </div>

        <div class="hero-actions">
            <div class="hero-stat">
                <strong id="kpi-total">0</strong>
                <span>Total Certificates</span>
            </div>
            <div class="hero-stat is-active">
                <strong id="kpi-active">0</strong>
                <span>Active</span>
            </div>
            <div class="hero-stat is-expired">
                <strong id="kpi-expired">0</strong>
                <span>Expired</span>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>Certificate Filters</h5>
                <p>Refine by inspector, date, client, status, and year</p>
            </div>
            <button id="reset-filters" class="filter-toggle" type="button">
                <i class="icofont-refresh"></i> Reset
            </button>
        </div>

        <div class="filter-row">
            <div class="filter-item">
                <label>Inspector</label>
                <select id="filter-inspector" class="form-select">
                    <option value="">All Inspectors</option>
                    <?php 
                    $inspectors = $conn->query("SELECT DISTINCT inspector FROM rocking_test_certificate WHERE inspector != '' ORDER BY inspector");
                    while($row = $inspectors->fetch_assoc()) echo "<option value='{$row['inspector']}'>{$row['inspector']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <label>Date</label>
                <input type="date" id="filter-date" class="form-control">
            </div>
            <div class="filter-item">
                <label>Client</label>
                <select id="filter-client" class="form-select">
                    <option value="">All Clients</option>
                    <?php 
                    $clients = $conn->query("SELECT DISTINCT customer_name FROM rocking_test_certificate WHERE customer_name != '' ORDER BY customer_name");
                    while($row = $clients->fetch_assoc()) echo "<option value='{$row['customer_name']}'>{$row['customer_name']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <label>Status</label>
                <select id="filter-status" class="form-select">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Year</label>
                <select id="filter-year" class="form-select">
                    <option value="">All Years</option>
                    <?php 
                    $years = $conn->query("SELECT DISTINCT YEAR(this_exam_date) as y FROM rocking_test_certificate ORDER BY y DESC");
                    while($row = $years->fetch_assoc()) echo "<option value='{$row['y']}'>{$row['y']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <button class="btn-clear" type="button" onclick="clearRockingFilters()">
                    <i class="icofont-close"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-box">
        <div class="table-panel-header">
            <div class="table-title">
                <h5>Rocking Test Records</h5>
                <p>Manage certificates with inspection details and expiry tracking</p>
            </div>
            <div class="table-tools">
                <div class="directory-search">
                    <i class="icofont-search-1"></i>
                    <input type="search" id="customSearch" placeholder="Search project, certificate, client, inspector...">
                </div>
                <div id="table-buttons"></div>
            </div>
        </div>
        <div class="table-shell">
            <table id="rock-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Project No</th>
                        <th>Certificate No</th>
                        <th>Item Type</th>
                        <th>Identification No</th>
                        <th>Inspector</th>
                        <th>Client</th>
                        <th>Location</th>
                        <th>Inspection Date</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(document).ready(function() {
    var table = $('#rock-table').DataTable({
        processing: true,
        serverSide: true,
        deferRender: true,
        scrollX: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[7, 'desc']], // this_exam_date DESC
        dom: 'Brtip',
        buttons: [
            { 
                extend: 'excelHtml5', 
                text: '<i class="fa fa-file-excel me-1"></i> Export Excel',
                className: 'btn btn-primary',
                title: 'Rocking_Test_Certificates' 
            }
        ],

        ajax: {
            url: 'fetch_rocking_tests.php',
            type: 'POST',
            data: function(d) {
                d.filter_inspector = $('#filter-inspector').val();
                d.filter_date = $('#filter-date').val();
                d.filter_client = $('#filter-client').val();
                d.filter_status = $('#filter-status').val();
                d.filter_year = $('#filter-year').val();
            },
            dataSrc: function(json) {
                if (json.kpi) {
                    $('#kpi-total').text(json.kpi.total);
                    $('#kpi-active').text(json.kpi.active);
                    $('#kpi-expired').text(json.kpi.expired);
                }
                return json.data;
            }
        },

        columns: [
            { data: 'project_no' },
            { data: 'certificate_no' },
            { data: 'inspected_item_type' },
            { data: 'identification_no' },
            { data: 'inspector' },
            { data: 'customer_name' },
            { data: 'location' },
            { data: 'this_exam_date' },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false }
        ],

        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }
    });

    $('#customSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    $('.form-select, .form-control').on('change keyup', function() {
        table.ajax.reload();
    });

    $('#reset-filters').on('click', function() {
        clearRockingFilters();
    });
});

function clearRockingFilters() {
    $('.form-select, .form-control, #customSearch').val('');
    $('#rock-table').DataTable().search('');
    $('#rock-table').DataTable().ajax.reload();
}

function deleteRow(projectNo) {
    if (confirm('Are you sure you want to delete this certificate?')) {
        $.ajax({
            url: 'delete.php',
            type: 'POST',
            data: { project_no: projectNo },
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    if (res.success) {
                        $('#rock-table').DataTable().ajax.reload();
                    } else {
                        alert('Error: ' + res.message);
                    }
                } catch (e) {
                    // If not JSON, just reload or alert
                    $('#rock-table').DataTable().ajax.reload();
                }
            },
            error: function() {
                alert('An error occurred while trying to delete the record.');
            }
        });
    }
}
</script>

</body>
</html>
