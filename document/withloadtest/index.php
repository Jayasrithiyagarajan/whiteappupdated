<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}
$role = $_SESSION['role'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Load Test Certificates</title>

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
.action-icons a, .action-icons i {
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
    text-decoration: none;
    font-size: 14px;
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
td:nth-child(7), th:nth-child(7) {
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
            <h2>Load Test Certificates</h2>
            <p>Track load test certificates, expiry status, clients, inspectors, and examination records.</p>
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
            <?php if ($role !== 'inspector'): ?>
            <div class="filter-item">
                <label>Inspector</label>
                <select id="filter-inspector" class="form-select">
                    <option value="">All Inspectors</option>
                    <?php
                    $inspectors = $conn->query("SELECT DISTINCT inspector_name FROM withload WHERE inspector_name != '' ORDER BY inspector_name");
                    while($r = $inspectors->fetch_assoc()) echo "<option value='{$r['inspector_name']}'>{$r['inspector_name']}</option>";
                    ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="filter-item">
                <label>Date</label>
                <input type="date" id="filter-date" class="form-control">
            </div>
            <div class="filter-item">
                <label>Client</label>
                <select id="filter-client" class="form-select">
                    <option value="">All Clients</option>
                    <?php
                    $clients = $conn->query("SELECT DISTINCT customer_name FROM withload WHERE customer_name != '' ORDER BY customer_name");
                    while($r = $clients->fetch_assoc()) echo "<option value='{$r['customer_name']}'>{$r['customer_name']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <label>Status</label>
                <select id="filter-status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="expired">Expired</option>
                </select>
            </div>
            <div class="filter-item">
                <label>Year</label>
                <select id="filter-year" class="form-select">
                    <option value="">All Years</option>
                    <?php
                    $years = $conn->query("SELECT DISTINCT YEAR(examination_date) as yr FROM withload WHERE examination_date IS NOT NULL ORDER BY yr DESC");
                    while($r = $years->fetch_assoc()) echo "<option value='{$r['yr']}'>{$r['yr']}</option>";
                    ?>
                </select>
            </div>
            <div class="filter-item">
                <button class="btn-clear" type="button" onclick="clearWithloadFilters()">
                    <i class="icofont-close"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-box">
        <div class="table-panel-header">
            <div class="table-title">
                <h5>Load Test Certificate Register</h5>
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
            <table id="withload-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Project No</th>
                        <th style="display:none">Sort</th>
                        <th>Certificate No</th>
                        <th>Equipment</th>
                        <th>Equip. ID</th>
                        <th>Inspector</th>
                        <th>Client</th>
                        <th>Location</th>
                        <th>Exam Date</th>
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
$(document).ready(function() {
    var table = $('#withload-table').DataTable({
        processing: true,
        serverSide: true,
        deferRender: true,
        scrollX: true,
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        order: [[1, 'desc']],
        dom: 'Brtip',
        buttons: [
            {
                text: '<i class="fa fa-file-excel me-1"></i> Export Excel',
                className: 'btn btn-primary',
                action: function (e, dt, node, config) {
                    var params = dt.ajax.params();
                    var form = $('<form>', {
                        action: 'fetch_withload_certificates.php',
                        method: 'POST'
                    });

                    $.each(params, function (key, value) {
                        if (typeof value === 'object') {
                            if (key === 'search') {
                                form.append($('<input>', { type: 'hidden', name: 'search[value]', value: value.value }));
                            } else if (key === 'order') {
                                $.each(value, function(i, v) {
                                    form.append($('<input>', { type: 'hidden', name: 'order['+i+'][column]', value: v.column }));
                                    form.append($('<input>', { type: 'hidden', name: 'order['+i+'][dir]',    value: v.dir }));
                                });
                            }
                        } else {
                            form.append($('<input>', { type: 'hidden', name: key, value: value }));
                        }
                    });

                    form.append($('<input>', { type: 'hidden', name: 'filter_inspector', value: $('#filter-inspector').val() }));
                    form.append($('<input>', { type: 'hidden', name: 'filter_date',      value: $('#filter-date').val() }));
                    form.append($('<input>', { type: 'hidden', name: 'filter_client',    value: $('#filter-client').val() }));
                    form.append($('<input>', { type: 'hidden', name: 'filter_status',    value: $('#filter-status').val() }));
                    form.append($('<input>', { type: 'hidden', name: 'filter_year',      value: $('#filter-year').val() }));
                    form.append($('<input>', { type: 'hidden', name: 'export',           value: 'true' }));

                    $('body').append(form);
                    form.submit();
                    form.remove();
                }
            }
        ],

        ajax: {
            url: 'fetch_withload_certificates.php',
            type: 'POST',
            data: function(d) {
                d.filter_inspector = $('#filter-inspector').val();
                d.filter_date      = $('#filter-date').val();
                d.filter_client    = $('#filter-client').val();
                d.filter_status    = $('#filter-status').val();
                d.filter_year      = $('#filter-year').val();
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
            { data: 'project_no',      orderData: [1] },
            { data: 'project_no_sort', visible: false, searchable: false },
            { data: 'certificate_no' },
            { data: 'item_name' },
            { data: 'equipment_id' },
            { data: 'inspector' },
            { data: 'customer_name' },
            { data: 'location' },
            { data: 'exam_date' },
            { data: 'status' },
            { data: 'actions', orderable: false, searchable: false }
        ],

        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }
    });

    $('#customSearch').on('keyup', function() {
        table.search(this.value).draw();
    });

    // Filter changes trigger reload
    $('#filter-inspector, #filter-client, #filter-status, #filter-year').on('change', function() {
        table.ajax.reload();
    });
    $('#filter-date').on('change', function() {
        table.ajax.reload();
    });

    // Reset all filters
    $('#reset-filters').on('click', function() {
        clearWithloadFilters();
    });
});

function clearWithloadFilters() {
    $('#filter-inspector, #filter-client, #filter-status, #filter-year').val('');
    $('#filter-date').val('');
    $('#customSearch').val('');
    $('#withload-table').DataTable().search('');
    $('#withload-table').DataTable().ajax.reload();
}

function deleteRow(projectNo) {
    if (confirm('Are you sure you want to delete this certificate?')) {
        $.ajax({
            url: 'delete_project.php',
            type: 'POST',
            data: { project_no: projectNo },
            success: function(response) {
                try {
                    var res = JSON.parse(response);
                    if (res.success) {
                        $('#withload-table').DataTable().ajax.reload();
                    } else {
                        alert('Error: ' + res.message);
                    }
                } catch (e) {
                    $('#withload-table').DataTable().ajax.reload();
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
