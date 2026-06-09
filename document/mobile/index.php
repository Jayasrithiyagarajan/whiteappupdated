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
<title>Mobile Crane With Load Test</title>

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

/* === Fixed Width Columns with Wrapping === */
table.dataTable th:nth-child(4),  /* Inspected Item */
table.dataTable td:nth-child(4) {
    width: 200px !important;
    min-width: 200px !important;
    max-width: 200px !important;
    white-space: normal !important;
    word-wrap: break-word !important;
}

table.dataTable th:nth-child(6),  /* Inspector */
table.dataTable td:nth-child(6) {
    width: 180px !important;
    min-width: 180px !important;
    max-width: 180px !important;
    white-space: normal !important;
    word-wrap: break-word !important;
}

table.dataTable th:nth-child(7),  /* Client */
table.dataTable td:nth-child(7) {
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

/* Dynamic colors based on initial letter */
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
<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <!-- Hero Section -->
    <div class="directory-hero">
        <div class="directory-title">
            <h2>Mobile Crane With Load Test</h2>
            <p>Monitor mobile crane certificates, expiry status, clients, inspectors, and examination records.</p>
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
                    $inspectors = $conn->query("SELECT DISTINCT inspector_name FROM mobile_crane_loadtest WHERE inspector_name != '' ORDER BY inspector_name");
                    while($row = $inspectors->fetch_assoc()) echo "<option value='{$row['inspector_name']}'>{$row['inspector_name']}</option>";
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
                    $clients = $conn->query("SELECT DISTINCT customer_name FROM mobile_crane_loadtest WHERE customer_name != '' ORDER BY customer_name");
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
                    $years = $conn->query("SELECT DISTINCT YEAR(created_at) as y FROM mobile_crane_loadtest ORDER BY y DESC");
                    while($row = $years->fetch_assoc()) echo "<option value='{$row['y']}'>{$row['y']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <button class="btn-clear" type="button" onclick="clearMobileCraneFilters()">
                    <i class="icofont-close"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-box">
        <div class="table-panel-header">
            <div class="table-title">
                <h5>Mobile Crane Certificate Register</h5>
                <p>Search, sort, export, filter, and manage certificate records.</p>
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
            <table id="mobile-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Project No</th>
                        <th style="display:none">Project Sort</th>
                        <th>Certificate No</th>
                        <th>Inspected Item</th>
                        <th>Equipment ID</th>
                        <th>Inspector</th>
                        <th>Client</th>
                        <th>Location</th>
                        <th>Examination Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
</div>

<?php 
include_once('../../inc/footer.php');
?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
var mobileCraneTable = $('#mobile-table').DataTable({
    processing: true,
    serverSide: true,
    scrollX: true,
    pageLength: 10,
    order: [[1, 'desc']],
    dom: 'Brtip',
    buttons: [
        {
            text: '<i class="fa fa-file-csv me-1"></i> Export CSV',
            className: 'btn btn-primary',
            action: function (e, dt, node, config) {
                var params = dt.ajax.params();
                var form = $('<form>', {
                    action: 'fetch_mobile_crane_loadtest.php',
                    method: 'POST'
                });

                $.each(params, function (key, value) {
                    if (typeof value === 'object') {
                        if (key === 'search') {
                            form.append($('<input>', { type: 'hidden', name: 'search[value]', value: value.value }));
                        } else if (key === 'order') {
                             $.each(value, function(i, v) {
                                form.append($('<input>', { type: 'hidden', name: 'order['+i+'][column]', value: v.column }));
                                form.append($('<input>', { type: 'hidden', name: 'order['+i+'][dir]', value: v.dir }));
                             });
                        }
                    } else {
                        form.append($('<input>', { type: 'hidden', name: key, value: value }));
                    }
                });

                form.append($('<input>', { type: 'hidden', name: 'filter_inspector', value: $('#filter-inspector').val() }));
                form.append($('<input>', { type: 'hidden', name: 'filter_date', value: $('#filter-date').val() }));
                form.append($('<input>', { type: 'hidden', name: 'filter_client', value: $('#filter-client').val() }));
                form.append($('<input>', { type: 'hidden', name: 'filter_status', value: $('#filter-status').val() }));
                form.append($('<input>', { type: 'hidden', name: 'filter_year', value: $('#filter-year').val() }));
                form.append($('<input>', { type: 'hidden', name: 'export', value: 'true' }));

                $('body').append(form);
                form.submit();
                form.remove();
            }
        }
    ],

    ajax: {
        url: 'fetch_mobile_crane_loadtest.php',
        type: 'POST',
        data: function(d) {
            d.filter_inspector = $('#filter-inspector').val();
            d.filter_date = $('#filter-date').val();
            d.filter_client = $('#filter-client').val();
            d.filter_status = $('#filter-status').val();
            d.filter_year = $('#filter-year').val();
        },
        dataSrc: function(json) {
            $('#kpi-total').text(json.kpi.total);
            $('#kpi-active').text(json.kpi.active);
            $('#kpi-expired').text(json.kpi.expired);
            return json.data;
        }
    },

    columns: [
        { data: 'project_no', orderData: [1] },
        { data: 'project_no_sort', visible: false, searchable: false },
        { data: 'certificate_no' },
        { data: 'equipment_description' },
        { data: 'equipment_id' },
        { data: 'inspector' },
        { data: 'employer_address' },
        { data: 'premises_address' },
        { data: 'examination_date' },
        { data: 'status' },
        { data: 'actions', orderable: false, searchable: false }
    ],

    initComplete: function() {
        this.api().buttons().container().appendTo('#table-buttons');
    }
});

$('#customSearch').on('keyup', function() {
    mobileCraneTable.search(this.value).draw();
});

$('.form-select, .form-control').on('change keyup', function() {
    mobileCraneTable.ajax.reload();
});

$('#reset-filters').on('click', function() {
    clearMobileCraneFilters();
});

function clearMobileCraneFilters() {
    $('.form-select, .form-control, #customSearch').val('');
    mobileCraneTable.search('');
    mobileCraneTable.ajax.reload();
}
</script>
</body>
</html>