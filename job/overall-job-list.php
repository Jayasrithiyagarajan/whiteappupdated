<?php
include_once('../inc/function.php');
include '../file/config.php';

$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Overall Job List</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/premium-nav.css">

    <style>
      body {
          background: #f8fafc;
          color: #111827;
          font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
      }

      .overall-jobs-directory {
          min-height: calc(100vh - 110px);
          padding: 12px 12px 48px;
      }

      .overall-jobs-directory .container-fluid {
          max-width: 1440px;
      }

      .directory-hero {
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 24px;
          margin-bottom: 24px;
          padding: 28px 24px;
          border-radius: 6px;
          background: linear-gradient(105deg, #284961 0%, #356d91 100%);
          color: #fff;
          box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
      }

      .directory-title h2 {
          margin: 0 0 8px;
          color: #fff;
          font-size: 25px;
          font-weight: 800;
          letter-spacing: 0;
      }

      .directory-title p {
          margin: 0;
          color: rgba(255, 255, 255, 0.78);
          font-size: 14px;
          font-weight: 600;
      }

      .hero-actions {
          display: flex;
          align-items: center;
          gap: 12px;
          flex-wrap: wrap;
          justify-content: flex-end;
      }

      .hero-stat {
          min-width: 68px;
          padding: 11px 14px;
          border-radius: 6px;
          background: rgba(255, 255, 255, 0.12);
          text-align: center;
      }

      .hero-stat strong {
          display: block;
          color: #fff;
          font-size: 21px;
          font-weight: 850;
          line-height: 1;
      }

      .hero-stat span {
          display: block;
          margin-top: 6px;
          color: rgba(255, 255, 255, 0.78);
          font-size: 11px;
          font-weight: 800;
      }

      .hero-stat.is-active {
          background: rgba(20, 148, 146, 0.74);
      }

      .hero-stat.is-expired {
          background: rgba(101, 116, 143, 0.72);
      }

      .btn-primary,
      .btn-clear,
      .filter-toggle {
          min-height: 40px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          gap: 8px;
          border: 0;
          border-radius: 6px;
          font-size: 13px;
          font-weight: 800;
          transition: background .2s ease, box-shadow .2s ease, transform .2s ease;
      }

      .btn-primary {
          padding: 10px 14px;
          background: #ffffff;
          color: #284961;
          box-shadow: none;
      }

      .btn-primary:hover {
          color: #284961;
          background: #eef7fb;
          transform: translateY(-1px);
      }

      .kpi-grid {
          display: grid;
          grid-template-columns: repeat(3, minmax(0, 1fr));
          gap: 16px;
          margin-bottom: 24px;
      }

      .kpi-card {
          min-height: 98px;
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 16px;
          padding: 20px;
          border: 1px solid #e8edf3;
          border-radius: 8px;
          background: #fff;
          box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
      }

      .kpi-card h6 {
          margin: 0 0 4px;
          color: #667085;
          font-size: 14px;
          font-weight: 800;
          letter-spacing: 0;
          text-transform: none;
      }

      .kpi-card h2 {
          margin: 0;
          color: #111827;
          font-size: 29px;
          font-weight: 850;
          letter-spacing: 0;
      }

      .kpi-icon {
          width: 48px;
          height: 48px;
          display: inline-flex;
          align-items: center;
          justify-content: center;
          flex: 0 0 auto;
          border-radius: 999px;
          font-size: 24px;
      }

      .kpi-icon.total { color: #0586c5; background: #eaf8ff; }
      .kpi-icon.active { color: #009b72; background: #e9fbf3; }
      .kpi-icon.expired { color: #e02424; background: #fff1f1; }
      .text-blue { color: #111827 !important; }
      .text-green { color: #009b72 !important; }
      .text-red { color: #e02424 !important; }

      .filter-section,
      .card-box {
          border: 1px solid #e8edf3;
          border-radius: 8px;
          background: #fff;
          box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
      }

      .filter-section {
          margin-bottom: 24px;
          padding: 22px 24px 24px;
      }

      .section-heading {
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 16px;
          margin-bottom: 18px;
      }

      .section-heading h5,
      .table-title h5 {
          margin: 0 0 4px;
          color: #111827;
          font-size: 20px;
          font-weight: 850;
          letter-spacing: 0;
          text-transform: none;
      }

      .section-heading p,
      .table-title p {
          margin: 0;
          color: #667085;
          font-size: 14px;
          font-weight: 500;
      }

      .filter-toggle {
          padding: 10px 14px;
          background: #eef7fb;
          color: #315b76;
      }

      .filter-row {
          display: grid;
          grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
          gap: 14px;
      }

      .filter-item {
          display: flex;
          flex-direction: column;
          justify-content: flex-end;
      }

      .filter-item label {
          display: block;
          margin-bottom: 7px;
          color: #475467;
          font-size: 12px;
          font-weight: 800;
      }

      .filter-item select,
      .filter-item input,
      .directory-search input {
          width: 100%;
          min-height: 40px;
          padding: 9px 12px;
          border: 1px solid #d7e0ea;
          border-radius: 6px;
          background: #fff;
          color: #334155;
          font-size: 14px;
          font-weight: 600;
      }

      .filter-item select:focus,
      .filter-item input:focus,
      .directory-search input:focus {
          outline: none;
          border-color: #9bc7df;
          box-shadow: 0 0 0 3px rgba(5, 134, 197, 0.1);
      }

      .btn-clear {
          width: 100%;
          margin-top: 0;
          padding: 9px 14px;
          background: #65748f;
          color: #fff;
      }

      .btn-clear:hover {
          background: #526177;
          color: #fff;
      }

      .card-box {
          overflow: hidden;
      }

      .table-panel-header {
          display: flex;
          align-items: center;
          justify-content: space-between;
          gap: 18px;
          padding: 24px;
          border-bottom: 1px solid #dfe7ef;
      }

      .table-tools {
          display: flex;
          align-items: center;
          gap: 12px;
          flex-wrap: wrap;
          justify-content: flex-end;
      }

      .directory-search {
          position: relative;
          width: min(448px, 100%);
      }

      .directory-search i {
          position: absolute;
          left: 13px;
          top: 50%;
          color: #98a2b3;
          transform: translateY(-50%);
          pointer-events: none;
      }

      .directory-search input {
          padding-left: 36px;
      }

      .table-shell {
          padding: 24px;
      }

      .dataTables_wrapper {
          color: #344054;
      }

      .dataTables_wrapper .dt-buttons {
          display: flex;
          flex-wrap: wrap;
          gap: 8px;
          margin: 0;
      }

      .dataTables_wrapper .dt-buttons .dt-button,
      .dataTables_wrapper .dt-buttons .btn,
      table.dataTable tbody .btn,
      table.dataTable tbody button.btn {
          min-height: 32px;
          margin: 0;
          padding: 7px 12px !important;
          border: 0 !important;
          border-radius: 5px !important;
          background: #65748f !important;
          color: #fff !important;
          box-shadow: none !important;
          font-size: 12px !important;
          font-weight: 800 !important;
          line-height: 1.2;
      }

      .dataTables_wrapper .dt-buttons .dt-button:hover,
      .dataTables_wrapper .dt-buttons .btn:hover,
      table.dataTable tbody .btn:hover,
      table.dataTable tbody button.btn:hover {
          background: #526177 !important;
          color: #fff !important;
      }

      .dataTables_wrapper .dataTables_filter {
          display: none;
      }

      .dataTables_wrapper .dataTables_info,
      .dataTables_wrapper .dataTables_paginate {
          padding-top: 16px;
          color: #667085 !important;
          font-size: 13px;
          font-weight: 700;
      }

      .dataTables_wrapper .dataTables_paginate .paginate_button {
          border: 1px solid #d7e0ea !important;
          border-radius: 5px !important;
          background: #fff !important;
          color: #475467 !important;
      }

      .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
          background: #eef7fb !important;
          color: #315b76 !important;
          border-color: #9bc7df !important;
      }

      .dataTables_wrapper .dataTables_paginate .paginate_button.current,
      .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
          background: #315b76 !important;
          color: #fff !important;
          border-color: #315b76 !important;
      }

      table.dataTable {
          width: 100% !important;
          border-collapse: collapse !important;
          border-spacing: 0 !important;
      }

      table.dataTable thead th {
          padding: 15px 16px !important;
          border: 0 !important;
          border-bottom: 1px solid #2b526c !important;
          background: #2b526c !important;
          color: #ffffff;
          font-size: 13px;
          font-weight: 850;
          letter-spacing: 0;
          text-transform: none;
          white-space: nowrap;
      }

      table.dataTable thead th,
      table.dataTable thead td,
      table.dataTable thead .sorting,
      table.dataTable thead .sorting_asc,
      table.dataTable thead .sorting_desc,
      table.dataTable thead .sorting_asc_disabled,
      table.dataTable thead .sorting_desc_disabled {
          background-color: #2b526c !important;
          background-image: none !important;
          color: #ffffff !important;
          padding-right: 16px !important;
      }

      table.dataTable thead .sorting:before,
      table.dataTable thead .sorting:after,
      table.dataTable thead .sorting_asc:before,
      table.dataTable thead .sorting_asc:after,
      table.dataTable thead .sorting_desc:before,
      table.dataTable thead .sorting_desc:after,
      table.dataTable thead .sorting_asc_disabled:before,
      table.dataTable thead .sorting_asc_disabled:after,
      table.dataTable thead .sorting_desc_disabled:before,
      table.dataTable thead .sorting_desc_disabled:after {
          display: none !important;
          content: "" !important;
      }

      table.dataTable tbody,
      table.dataTable tbody tr,
      table.dataTable tbody td {
          background: #ffffff !important;
      }

      table.dataTable tbody td {
          padding: 12px 16px !important;
          border-top: 1px solid #eef2f6;
          color: #526071;
          font-size: 14px;
          font-weight: 600;
          vertical-align: middle;
          white-space: nowrap;
      }

      table.dataTable thead th:nth-child(8),
      table.dataTable tbody td:nth-child(8) {
          width: 170px !important;
          min-width: 170px !important;
          max-width: 170px !important;
      }

      table.dataTable thead th:nth-child(4),
      table.dataTable tbody td:nth-child(4) {
          width: 130px !important;
          min-width: 130px !important;
          max-width: 130px !important;
      }

      table.dataTable tbody td:nth-child(4),
      table.dataTable tbody td:nth-child(8) {
          white-space: normal !important;
          overflow-wrap: anywhere;
          word-break: break-word;
          line-height: 1.35;
      }

      table.dataTable tbody tr:hover {
          background: #f8fbfd;
      }

      table.dataTable tbody td:first-child {
          color: #111827;
          font-weight: 850;
      }

      table.dataTable tbody .badge,
      table.dataTable tbody .s_alert {
          display: inline-flex;
          align-items: center;
          min-height: 22px;
          padding: 4px 10px !important;
          border-radius: 999px;
          border: 1px solid #d7e0ea;
          background: #f8fbfd;
          color: #526071;
          box-shadow: none;
          font-size: 11px !important;
          font-weight: 850;
          line-height: 1.1;
      }

      table.dataTable tbody .text-success,
      table.dataTable tbody .bg-success-light,
      table.dataTable tbody .badge-success {
          color: #009b72 !important;
          background: #e9fbf3 !important;
          border-color: #c9f2e4 !important;
      }

      table.dataTable tbody .text-danger,
      table.dataTable tbody .bg-danger-light,
      table.dataTable tbody .badge-danger {
          color: #e02424 !important;
          background: #fff1f1 !important;
          border-color: #ffd8d8 !important;
      }

      table.dataTable tbody a.text-primary {
          color: #0586c5 !important;
          font-weight: 850;
      }

      .dataTables_scrollBody {
           border-bottom: 0 !important;
       }
       /* table.dataTable thead th .dataTables_sizing {
           height: 0 !important;
           display: none !important;
       } */

       @media(max-width: 991px) {
          .directory-hero,
          .table-panel-header,
          .section-heading {
              align-items: stretch;
              flex-direction: column;
          }

          .hero-actions,
          .table-tools {
              justify-content: flex-start;
          }

          .kpi-grid {
              grid-template-columns: 1fr;
          }
      }

      @media(max-width: 767px) {
          .overall-jobs-directory {
              padding: 0 0 32px;
          }

          .directory-hero,
          .filter-section,
          .card-box {
              border-radius: 0;
          }

          .directory-hero,
          .filter-section,
          .table-panel-header,
          .table-shell {
              padding: 20px;
          }

          .hero-stat {
              flex: 1 1 80px;
          }
      }
    </style>
</head>
<body>

<?php include_once('../inc/nav.php'); ?>

<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <div class="directory-hero">
        <div class="directory-title">
            <h2>Project Directory</h2>
            <p>Manage project progress, assignments, inspection status, and reports</p>
        </div>
        <div class="hero-actions">
            <div class="hero-stat">
                <strong id="hero-stats-total">0</strong>
                <span>Total</span>
            </div>
            <div class="hero-stat is-active">
                <strong id="hero-stats-active">0</strong>
                <span>Active</span>
            </div>
            <div class="hero-stat is-expired">
                <strong id="hero-stats-expired">0</strong>
                <span>Expired</span>
            </div>
            <?php if ($user_role === 'admin') { ?>
                <a href="create-job.php" class="btn btn-primary"><i class="icofont-plus"></i> New Job</a>
            <?php } ?>
        </div>
    </div>

    <!-- <div class="kpi-grid">
        <div class="kpi-card">
            <div>
                <h6>Total Projects</h6>
                <h2 class="text-blue" id="stats-total">0</h2>
            </div>
            <span class="kpi-icon total"><i class="icofont-briefcase"></i></span>
        </div>
        <div class="kpi-card">
            <div>
                <h6>Active</h6>
                <h2 class="text-green" id="stats-active">0</h2>
            </div>
            <span class="kpi-icon active"><i class="icofont-check-circled"></i></span>
        </div>
        <div class="kpi-card">
            <div>
                <h6>Expired</h6>
                <h2 class="text-red" id="stats-expired">0</h2>
            </div>
            <span class="kpi-icon expired"><i class="icofont-close-circled"></i></span>
        </div>
    </div> -->

    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>Project Filters</h5>
                <p>Refine the project list by team, client, date, year, and status</p>
            </div>
            <button class="filter-toggle" type="button" onclick="clearFilters()"><i class="icofont-refresh"></i> Reset</button>
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
                <label>Status</label>
                <select id="status-filter">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Completed">Completed</option>
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
                <button class="btn-clear" onclick="clearFilters()"><i class="icofont-close"></i> Clear Filters</button>
            </div>
        </div>
    </div>

    <div class="card-box">
        <div class="table-panel-header">
            <div class="table-title">
                <h5>Project </h5>
                <p>View project records, track completion, and open details</p>
            </div>
            <div class="table-tools">
                <div class="directory-search">
                    <i class="icofont-search-1"></i>
                    <input type="search" id="job-search" placeholder="Search by project, client, inspector or equipment...">
                </div>
                <div id="table-buttons"></div>
            </div>
        </div>
        <div class="table-shell">
            <table id="job-table" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Date</th>
                        <th>Progress</th>
                        <th>Action</th>
                        <th>Checklist</th>
                        <th>Report</th>
                        <th>Reviewer</th>
                        <th>Certificate</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Equip.ID</th>
                        <th>Checklist Name</th>
                        <th>Sticker No</th>
                        <th>Certificate Type</th>
                        <th>Inspection Type</th>
                        <th>Equip.Type</th>
                        <th>Location</th>
                        <th>Inspector</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
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
            url: 'fetch_overall_jobs.php',
            type: 'POST',
            data: function(d) {
                d.filter_inspector = $('#filter-inspector').val();
                d.filter_client = $('#filter-client').val();
                d.filter_date_from = $('#filter-date-from').val();
                d.filter_date_to = $('#filter-date-to').val();
                d.filter_year = $('#filter-year').val();
                d.status_filter = $('#status-filter').val();
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
                        status_filter: $('#status-filter').val(),
                        filter_expiry_status: $('#filter-expiry-status').val(),
                        search_value: dt.search()
                    });
                    window.location.href = 'export_overall.php?' + params;
                },
                className: 'btn btn-secondary'
            },
            'copy', 'print'
        ],

        columnDefs: [
            { targets: -1, orderable: false }
        ],

        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }
    });

    loadFilters();
    loadStats();

    $('#filter-inspector, #filter-client, #filter-date-from, #filter-date-to, #filter-year, #status-filter, #filter-expiry-status').on('change', function() {
        table.ajax.reload();
        loadStats();
    });

    $('#job-search').on('input', function() {
        table.search(this.value).draw();
    });
});

function loadFilters(){
    $.ajax({
        url: 'fetch_overall_filters.php',
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
        url: 'fetch_overall_stats.php',
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
            $('#stats-total, #hero-stats-total').text(res.total);
            $('#stats-active, #hero-stats-active').text(res.active);
            $('#stats-expired, #hero-stats-expired').text(res.expired);
        }
    });
}

function clearFilters(){
    $('#filter-inspector').val('');
    $('#filter-client').val('');
    $('#filter-date-from').val('');
    $('#filter-date-to').val('');
    $('#filter-year').val('');
    $('#status-filter').val('');
    $('#filter-expiry-status').val('');
    $('#job-search').val('');

    table.search('');
    table.ajax.reload();
    loadStats();
}

function deleteProject(projectNo) {
    if (confirm("Are you sure you want to delete this project?")) {
        fetch(`delete_project.php?project_no=${projectNo}`, {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            if (data.status === 'success') {
                table.ajax.reload();
                loadStats();
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert("An error occurred while deleting the project.");
        });
    }
}
</script>

</body>
</html>

