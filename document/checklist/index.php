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
    <title>Checklist List</title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/premium-directory.css">
    <link rel="stylesheet" href="../../assets/css/premium-nav.css">

    <style>
    td:nth-child(6), th:nth-child(6) {
    width: 180px !important;
    min-width: 180px !important;
    max-width: 180px !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-all !important;
    vertical-align: top;
}


   td:nth-child(7), th:nth-child(7) {
    width: 180px !important;
    min-width: 180px !important;
    max-width: 180px !important;
    white-space: normal !important;
    word-wrap: break-word !important;
    word-break: break-all !important;
    vertical-align: top;
}

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

    </style>
</head>
<body>

<?php include_once('../../inc/nav.php'); ?>

<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <div class="directory-hero">
        <div class="directory-title">
            <h2>Checklist Directory</h2>
            <p>Review inspection checklists, monitor completion status, and filter records</p>
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

    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>Checklist Filters</h5>
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
                <label>Checklist Type</label>
                <select id="filter-type">
                    <option value="">All Types</option>
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

    <div class="card-box">
        <div class="table-panel-header">
            <div class="table-title">
                <h5>Checklist Records</h5>
                <p>View and manage all inspection checklists</p>
            </div>
            <div class="table-tools">
                <div class="directory-search">
                    <i class="icofont-search-1"></i>
                    <input type="search" id="checklist-search" placeholder="Search by checklist, project, inspector or equipment...">
                </div>
                <div id="table-buttons"></div>
            </div>
        </div>
        <div class="table-shell">
            <table id="checklist-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Checklist No</th>
                        <th>Project No</th>
                        <th>Inspector</th>
                        <th>Equipment</th>
                        <th>Checklist Type</th>
                        <th>Company</th>
                        <th>Equipment No</th>
                        <th>Serial No</th>
                        <th>Sticker No</th>
                        <th>Location</th>
                        <th>Created At</th>
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
var checklistTable;

$(document).ready(function() {
    checklistTable = $('#checklist-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[10, 'desc']],

        ajax: {
            url: 'fetch_checklists.php',
            type: 'POST',
            data: function(d) {
                d.filter_inspector = $('#filter-inspector').val();
                d.filter_type = $('#filter-type').val();
                d.filter_date = $('#filter-date').val();
                d.filter_client = $('#filter-client').val();
                d.filter_year = $('#filter-year').val();
                d.filter_expiry = $('#filter-expiry').val();
            }
        },

        dom: 'Brtip',
        buttons: [
            {
                text: 'Export CSV',
                className: 'btn btn-secondary',
                action: function() {
                    window.location.href = 'export_checklists_excel.php';
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
    loadChecklistKPI();

    $('#filter-inspector, #filter-type, #filter-date, #filter-client, #filter-year, #filter-expiry').on('change', function() {
        checklistTable.ajax.reload();
        loadChecklistKPI();
    });

    $('#checklist-search').on('input', function() {
        checklistTable.search(this.value).draw();
    });
});

function loadFilterOptions(){
    $.ajax({
        url: 'fetch_checklist_filter_options.php',
        type: 'GET',
        dataType: 'json',
        success: function(res){
            $('#filter-inspector').append(res.inspectors.map(i => `<option value="${i}">${i}</option>`).join(''));
            $('#filter-client').append(res.clients.map(c => `<option value="${c}">${c}</option>`).join(''));
            $('#filter-year').append(res.years.map(y => `<option value="${y}">${y}</option>`).join(''));
            $('#filter-type').append(res.types.map(t => {
                let formatted = t.replace(/[-_]/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
                return `<option value="${t}">${formatted}</option>`;
            }).join(''));
        }
    });
}

function loadChecklistKPI(){
    $.ajax({
        url: 'fetch_checklist_kpi.php',
        type: 'GET',
        dataType: 'json',
        data: {
            filter_inspector: $('#filter-inspector').val(),
            filter_type: $('#filter-type').val(),
            filter_date: $('#filter-date').val(),
            filter_client: $('#filter-client').val(),
            filter_year: $('#filter-year').val(),
            filter_expiry: $('#filter-expiry').val()
        },
        success: function(res){
            $('#kpi-total').text(res.total);
            $('#kpi-completed').text(res.completed);
            $('#kpi-pending').text(res.pending);
            $('#kpi-active').text(res.active);
            $('#kpi-expired').text(res.expired);
        }
    });
}

function clearFilters(){
    $('#filter-inspector, #filter-type, #filter-date, #filter-client, #filter-year, #filter-expiry, #checklist-search').val('');
    checklistTable.search('');
    checklistTable.ajax.reload();
    loadChecklistKPI();
}
</script>

</body>
</html>