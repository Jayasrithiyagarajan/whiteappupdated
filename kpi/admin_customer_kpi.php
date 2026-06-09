<?php
include_once('../inc/function.php');
include_once('../file/config.php');

// ONLY Admin Role Allowed
if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin - Customer KPI Report</title>

<!-- DataTables CSS for the breakdown table -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/premium-nav.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
/* ============================================================
   ADMIN CUSTOMER KPI DASHBOARD (Premium UI)
   ============================================================ */
.akpi-main {
    padding: 24px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    min-height: 100vh;
}

/* ── Page Header ── */
.akpi-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 24px;
    box-shadow: 0 8px 32px rgba(15,23,42,.2);
    flex-wrap: wrap;
    gap: 16px;
}
.akpi-page-header .hdr-left h4 {
    margin: 0; font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.3px;
}
.akpi-page-header .hdr-left p {
    margin: 4px 0 0; font-size: 13px; color: #94a3b8;
}
.akpi-page-header .hdr-left h4 i {
    background: linear-gradient(135deg, #38bdf8, #0284c7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-right: 12px;
}

/* ── Filter Bar ── */
.akpi-filter-bar {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.akpi-filter-bar select,
.akpi-filter-bar input[type="date"] {
    padding: 9px 14px;
    border: 1px solid rgba(255,255,255,.15); border-radius: 8px;
    font-size: 13px; color: #e2e8f0;
    background: rgba(255,255,255,.08); backdrop-filter: blur(4px);
    transition: all .2s;
}
.akpi-filter-bar select option {
    background: #1e293b; color: #fff;
}
.akpi-filter-bar select:focus,
.akpi-filter-bar input[type="date"]:focus {
    outline: none; border-color: #38bdf8; background: rgba(255,255,255,.12);
}
.btn-akpi-filter {
    padding: 9px 24px;
    background: linear-gradient(135deg, #0284c7, #38bdf8);
    color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: all .25s; box-shadow: 0 4px 14px rgba(2,132,199,.3);
}
.btn-akpi-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(2,132,199,.4); }
.btn-akpi-reset {
    padding: 9px 20px;
    background: rgba(255,255,255,.1); color: #e2e8f0; border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px; font-size: 13px; cursor: pointer; transition: all .2s;
}
.btn-akpi-reset:hover { background: rgba(255,255,255,.18); }

/* ── KPI Cards ── */
.akpi-cards-row {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;
}
@media (max-width: 1200px) { .akpi-cards-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px)  { .akpi-cards-row { grid-template-columns: 1fr; } }

.akpi-stat-card {
    background: #fff; border-radius: 16px; padding: 24px 22px;
    border-left: 5px solid; box-shadow: 0 4px 15px rgba(0,0,0,.04);
    display: flex; justify-content: space-between; align-items: flex-start;
    transition: all .3s cubic-bezier(.25,.8,.25,1); position: relative; overflow: hidden;
}
.akpi-stat-card::after {
    content: ''; position: absolute; top: 0; right: 0; width: 90px; height: 90px;
    border-radius: 0 0 0 90px; opacity: .04;
}
.akpi-stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,.08); }

.akpi-stat-card.c-emerald { border-color: #10b981; }
.akpi-stat-card.c-emerald::after { background: #10b981; }
.akpi-stat-card.c-blue    { border-color: #3b82f6; }
.akpi-stat-card.c-blue::after    { background: #3b82f6; }
.akpi-stat-card.c-purple  { border-color: #8b5cf6; }
.akpi-stat-card.c-purple::after  { background: #8b5cf6; }
.akpi-stat-card.c-orange  { border-color: #f97316; }
.akpi-stat-card.c-orange::after  { background: #f97316; }

.asc-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #64748b; margin-bottom: 8px; }
.asc-value { font-size: 32px; font-weight: 800; color: #1e293b; line-height: 1; margin: 6px 0; }

.asc-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;
}
.asc-icon.bg-emerald { background: linear-gradient(135deg, #ecfdf5, #a7f3d0); color: #10b981; }
.asc-icon.bg-blue    { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #3b82f6; }
.asc-icon.bg-purple  { background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #8b5cf6; }
.asc-icon.bg-orange  { background: linear-gradient(135deg, #fff7ed, #ffedd5); color: #f97316; }

/* ── Charts ── */
.akpi-chart-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
@media (max-width: 900px) { .akpi-chart-row { grid-template-columns: 1fr; } }
.akpi-chart-row.one-col { grid-template-columns: 1fr; }

.akpi-card-box {
    background: #fff; border-radius: 16px; padding: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,.03); border: 1px solid #f1f5f9; transition: all .3s;
}
.akpi-card-box:hover { box-shadow: 0 8px 25px rgba(0,0,0,.06); }
.acb-header { display: flex; align-items: center; margin-bottom: 16px; gap: 10px; justify-content: space-between;}
.acb-header-left { display: flex; align-items: center; gap: 10px; }
.acb-header h6 { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0; }
.acb-header i { font-size: 16px; color: #3b82f6; }

/* ── Loader & No Data ── */
.akpi-loader { position: fixed; inset: 0; background: rgba(255,255,255,.8); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 9999; }
.akpi-spinner { width: 50px; height: 50px; border: 4px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: akpiSpin .8s linear infinite; }
@keyframes akpiSpin { to { transform: rotate(360deg); } }
.akpi-no-data { min-height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; font-size: 14px; gap: 10px; }
.akpi-no-data i { font-size: 36px; opacity: .3; }

/* ── DataTable Overrides ── */
.table-slim th { border-top: none; text-transform: uppercase; font-size: 11px; letter-spacing: .5px; color: #64748b; background: #f8fafc; }
.table-slim td { vertical-align: middle; color: #334155; font-size: 13px; font-weight: 500;}
div.dataTables_wrapper div.dataTables_filter input {
    border-radius: 6px; border: 1px solid #cbd5e1; padding: 4px 10px; margin-left: 8px;
}
.page-item.active .page-link { background-color: #3b82f6; border-color: #3b82f6; }

/* Premium glass UI refresh - presentation only */
body {
    background:
        radial-gradient(circle at 14% 8%, rgba(20, 184, 166, 0.16), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(37, 99, 235, 0.13), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
    color: #111827;
    font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
}

.akpi-main {
    position: relative;
    min-height: 100vh;
    padding: 10px 10px 48px;
    overflow: hidden;
    background:
        radial-gradient(circle at 14% 8%, rgba(20, 184, 166, 0.16), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(37, 99, 235, 0.13), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
}

.akpi-main:before {
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

.akpi-page-header,
.akpi-stat-card,
.akpi-card-box {
    border: 1px solid rgba(255,255,255,.64);
    background: linear-gradient(135deg, rgba(255,255,255,.78), rgba(255,255,255,.48));
    box-shadow: 0 24px 60px rgba(15,23,42,.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.akpi-page-header {
    position: relative;
    padding: 26px 28px;
    margin-bottom: 28px;
    border-radius: 22px;
    overflow: hidden;
}

.akpi-page-header:before {
    content: "";
    position: absolute;
    right: -60px;
    top: -80px;
    width: 220px;
    height: 220px;
    border-radius: 999px;
    background: rgba(37, 99, 235, 0.08);
    pointer-events: none;
}

.akpi-page-header .hdr-left,
.akpi-filter-bar {
    position: relative;
    z-index: 1;
}

.akpi-page-header .hdr-left h4 {
    margin: 0 0 8px;
    color: #111827;
    font-size: clamp(24px, 2vw, 34px);
    font-weight: 850;
    letter-spacing: 0;
    text-transform: none;
}

.akpi-page-header .hdr-left p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
}

.akpi-page-header .hdr-left h4 i {
    width: 54px;
    height: 54px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 14px;
    border-radius: 17px;
    background: linear-gradient(135deg, rgba(37,99,235,.16), rgba(20,184,166,.14));
    color: #2563eb;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.9), 0 16px 32px rgba(15,23,42,.1);
    font-size: 22px;
    vertical-align: middle;
    -webkit-text-fill-color: initial;
    -webkit-background-clip: initial;
}

.akpi-filter-bar select,
.akpi-filter-bar input[type="date"] {
    min-height: 46px;
    padding: 10px 12px;
    border: 1px solid rgba(148,163,184,.26);
    border-radius: 13px;
    background: rgba(255,255,255,.72);
    color: #111827;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.88);
    font-size: 13px;
    font-weight: 700;
}

.akpi-filter-bar select option {
    background: #fff;
    color: #111827;
}

.akpi-filter-bar select:focus,
.akpi-filter-bar input[type="date"]:focus {
    outline: none;
    border-color: rgba(37,99,235,.42);
    background: rgba(255,255,255,.92);
    box-shadow: 0 0 0 4px rgba(37,99,235,.1);
}

.btn-akpi-filter {
    min-height: 46px;
    padding: 10px 20px;
    border: 0;
    border-radius: 13px;
    background: linear-gradient(135deg, #2563eb 0%, #16a3d8 52%, #14b8a6 100%);
    color: #fff;
    box-shadow: 0 18px 34px rgba(37,99,235,.24);
    font-size: 13px;
    font-weight: 800;
}

.btn-akpi-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 22px 42px rgba(20,184,166,.2);
}

.btn-akpi-reset {
    min-height: 46px;
    padding: 10px 16px;
    border: 1px solid rgba(148,163,184,.26);
    border-radius: 13px;
    background: rgba(255,255,255,.72);
    color: #374151;
    box-shadow: 0 12px 26px rgba(15,23,42,.08);
    font-size: 13px;
    font-weight: 800;
}

.btn-akpi-reset:hover {
    background: rgba(255,255,255,.92);
}

.akpi-cards-row {
    gap: 16px;
    margin-bottom: 20px;
}

.akpi-stat-card {
    min-height: 136px;
    padding: 24px 22px;
    border-left: 1px solid rgba(255,255,255,.64);
    border-radius: 20px;
}

.akpi-stat-card:before {
    content: "";
    position: absolute;
    right: -34px;
    top: -34px;
    width: 112px;
    height: 112px;
    border-radius: 999px;
    background: rgba(37,99,235,.08);
}

.akpi-stat-card:after {
    display: none;
}

.akpi-stat-card:hover {
    transform: translateY(-4px);
    border-color: rgba(20,184,166,.32);
    box-shadow: 0 30px 70px rgba(15,23,42,.16);
}

.akpi-stat-card > * {
    position: relative;
    z-index: 1;
}

.akpi-stat-card.c-emerald { border-top: 3px solid rgba(5,150,105,.78); }
.akpi-stat-card.c-blue { border-top: 3px solid rgba(37,99,235,.78); }
.akpi-stat-card.c-purple { border-top: 3px solid rgba(139,92,246,.78); }
.akpi-stat-card.c-orange { border-top: 3px solid rgba(245,158,11,.78); }

.asc-label {
    color: #64748b;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: .04em;
}

.asc-value {
    color: #111827;
    font-size: 34px;
    font-weight: 850;
}

.asc-icon {
    width: 50px;
    height: 50px;
    border-radius: 16px;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.9), 0 16px 32px rgba(15,23,42,.1);
}

.asc-icon.bg-emerald { background: rgba(16,185,129,.14); color: #059669; }
.asc-icon.bg-blue { background: rgba(37,99,235,.14); color: #2563eb; }
.asc-icon.bg-purple { background: rgba(139,92,246,.14); color: #7048e8; }
.asc-icon.bg-orange { background: rgba(245,158,11,.16); color: #b45309; }

.akpi-chart-row {
    gap: 20px;
    margin-bottom: 20px;
}

.akpi-card-box {
    min-width: 0;
    padding: 24px;
    border-radius: 22px;
}

.akpi-card-box:hover {
    transform: translateY(-2px);
    border-color: rgba(20,184,166,.28);
    box-shadow: 0 30px 70px rgba(15,23,42,.13);
}

.acb-header h6 {
    color: #111827;
    font-size: 15px;
    font-weight: 850;
}

.acb-header i {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(37,99,235,.12);
    color: #2563eb;
}

.akpi-loader {
    background: rgba(248,250,252,.58);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.akpi-spinner {
    width: 48px;
    height: 48px;
    border-top-color: #2563eb;
}

.table-responsive {
    border-radius: 16px;
}

.table-slim {
    border-collapse: separate !important;
    border-spacing: 0 8px !important;
}

.table-slim th {
    padding: 14px 12px !important;
    border: 0 !important;
    background: rgba(241,245,249,.78);
    color: #334155;
    font-size: 11px;
    font-weight: 850;
    letter-spacing: .02em;
}

.table-slim td {
    padding: 14px 12px !important;
    border-top: 1px solid rgba(226,232,240,.58);
    border-bottom: 1px solid rgba(226,232,240,.58);
    background: rgba(255,255,255,.62);
    color: #475569;
    font-weight: 600;
}

.table-slim tbody tr td:first-child {
    border-left: 1px solid rgba(226,232,240,.58);
    border-radius: 14px 0 0 14px;
}

.table-slim tbody tr td:last-child {
    border-right: 1px solid rgba(226,232,240,.58);
    border-radius: 0 14px 14px 0;
}

.table-slim tbody tr:hover td {
    background: rgba(255,255,255,.88);
}

div.dataTables_wrapper {
    color: #334155;
}

div.dataTables_wrapper div.dataTables_filter input {
    min-height: 40px;
    padding: 8px 12px;
    border: 1px solid rgba(148,163,184,.24);
    border-radius: 12px;
    background: rgba(255,255,255,.72);
    color: #111827;
    font-weight: 700;
}

div.dataTables_wrapper div.dataTables_filter label,
div.dataTables_wrapper div.dataTables_info {
    color: #64748b;
    font-weight: 700;
}

.page-link {
    border-radius: 10px !important;
    color: #2563eb;
}

.page-item.active .page-link {
    border-color: #2563eb;
    background: #2563eb;
}

@media (max-width: 768px) {
    .akpi-main { padding: 12px 10px; }
    .akpi-page-header { padding: 18px; align-items: stretch; flex-direction: column; }
    .akpi-filter-bar { display: grid; grid-template-columns: 1fr 1fr; width: 100%; }
    .akpi-filter-bar select,
    .akpi-filter-bar input,
    .akpi-filter-bar button { width: 100%; min-width: 0; }
    .asc-value { font-size: 28px; }
    .akpi-card-box { padding: 18px; }
}

@media (max-width: 480px) {
    .akpi-filter-bar { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<?php include_once('../inc/header.php'); ?>

<div class="akpi-loader" id="akpiLoader"><div class="akpi-spinner"></div></div>

<div class="main-content">
<div class="akpi-main">

    <!-- Header -->
    <div class="akpi-page-header">
        <div class="hdr-left">
            <h4><i class="fas fa-users-cog"></i>Customer KPI Report</h4>
            <p>Admin view of customer-aggregated project and checklist performance.</p>
        </div>
        <div class="akpi-filter-bar">
            <!-- Dynamic dropdown loaded by JS -->
            <select id="flt_customer">
                <option value="">All Customers</option>
            </select>
            
            <input type="date" id="flt_date_from" title="From Date">
            <input type="date" id="flt_date_to"   title="To Date">
            
            <button class="btn-akpi-filter" onclick="loadAdminCustomerKPI()">
                <i class="fas fa-filter"></i> Apply
            </button>
            <button class="btn-akpi-reset" onclick="resetAdminCustomerKPI()">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </div>

    <!-- 4 Core Stat Cards -->
    <div class="akpi-cards-row">
        <div class="akpi-stat-card c-emerald">
            <div>
                <div class="asc-label">Total Customers</div>
                <div class="asc-value" id="aval_customers">0</div>
            </div>
            <div class="asc-icon bg-emerald"><i class="fas fa-users"></i></div>
        </div>
        <div class="akpi-stat-card c-blue">
            <div>
                <div class="asc-label">Total Projects</div>
                <div class="asc-value" id="aval_projects">0</div>
            </div>
            <div class="asc-icon bg-blue"><i class="fas fa-briefcase"></i></div>
        </div>
        <div class="akpi-stat-card c-purple">
            <div>
                <div class="asc-label">Total Checklists</div>
                <div class="asc-value" id="aval_checklists">0</div>
            </div>
            <div class="asc-icon bg-purple"><i class="fas fa-clipboard-list"></i></div>
        </div>
        <div class="akpi-stat-card c-orange">
            <div>
                <div class="asc-label">Total Certificates</div>
                <div class="asc-value" id="aval_certificates">0</div>
            </div>
            <div class="asc-icon bg-orange"><i class="fas fa-certificate"></i></div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="akpi-chart-row">
        <div class="akpi-card-box">
            <div class="acb-header">
                <div class="acb-header-left"><i class="fas fa-chart-pie"></i><h6>Overall Project Status</h6></div>
            </div>
            <div id="chart_status" style="min-height:300px"></div>
        </div>
        <div class="akpi-card-box">
            <div class="acb-header">
                <div class="acb-header-left"><i class="fas fa-chart-area"></i><h6>Monthly Project Flow</h6></div>
            </div>
            <div id="chart_monthly" style="min-height:300px"></div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="akpi-chart-row">
        <div class="akpi-card-box">
            <div class="acb-header">
                <div class="acb-header-left"><i class="fas fa-crown"></i><h6>Top Customers By Volume</h6></div>
            </div>
            <div id="chart_topcust" style="min-height:320px"></div>
        </div>
        <div class="akpi-card-box">
            <div class="acb-header">
                <div class="acb-header-left"><i class="fas fa-tasks"></i><h6>Checklist Distribution</h6></div>
            </div>
            <div id="chart_checks" style="min-height:320px"></div>
        </div>
    </div>

    <!-- Breakdown Table -->
    <div class="akpi-card-box">
        <div class="acb-header mb-4">
            <div class="acb-header-left"><i class="fas fa-table"></i><h6>Detailed Customer Breakdown</h6></div>
        </div>
        <div class="table-responsive">
            <table id="tblCustomers" class="table table-hover table-slim w-100">
                <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th class="text-center">Total Projects</th>
                        <th class="text-center text-success">Completed Projects</th>
                        <th class="text-center text-danger">Pending Projects</th>
                        <th class="text-center text-primary">Total Checklists</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Injected via JS -->
                </tbody>
            </table>
        </div>
    </div>

</div> <!-- /.akpi-main -->
</div> <!-- /.main-content -->

<!-- ApexCharts -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<?php include_once('../inc/footer.php'); ?>

<!-- DataTables JS for the table -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
var reqAdmin = null;
var chStatus, chMonthly, chTopCust, chChecks;
var isClearing = false;
var dtCustomers = null;

var CKPI = {
    emerald: '#10b981', blue: '#3b82f6', purple: '#8b5cf6', orange: '#f97316',
    rose: '#f43f5e', amber: '#f59e0b', sky: '#0ea5e9', teal: '#14b8a6'
};

function initAnimNumber(elId, target) {
    var el = document.getElementById(elId);
    if (!el) return;
    var start = parseInt(el.textContent) || 0;
    var duration = 1200, startTime = null;
    function step(ts) {
        if (!startTime) startTime = ts;
        var pct = Math.min((ts - startTime)/duration, 1);
        el.textContent = Math.floor(start + (target - start)*pct);
        if (pct < 1) requestAnimationFrame(step); else el.textContent = target;
    }
    requestAnimationFrame(step);
}

function safeDestroy(cr, sel) {
    if (cr) { try { cr.destroy(); } catch(e){} }
    if (sel) { var e = document.querySelector(sel); if(e) e.innerHTML = ''; }
    return null;
}

function renderNoDataMsg(sel) {
    $(sel).html('<div class="akpi-no-data"><i class="fas fa-folder-open"></i><span>No data available</span></div>');
}

$(document).ready(function() {
    // Initialize empty datatable
    dtCustomers = $('#tblCustomers').DataTable({
        pageLength: 10,
        lengthChange: false,
        ordering: true,
        order: [[1, 'desc']], // Sort by total projects desc
        language: { search: "", searchPlaceholder: "Search here..." }
    });

    loadAdminCustomerKPI();

    // Auto update on date change
    $('#flt_date_from, #flt_date_to, #flt_customer').on('change', function() {
        if (!isClearing) loadAdminCustomerKPI();
    });
});

function resetAdminCustomerKPI() {
    isClearing = true;
    $('#flt_date_from, #flt_date_to, #flt_customer').val('');
    isClearing = false;
    loadAdminCustomerKPI();
}

function loadAdminCustomerKPI() {
    var params = {
        date_from: $('#flt_date_from').val(),
        date_to:   $('#flt_date_to').val(),
        customer:  $('#flt_customer').val()
    };
    $('#akpiLoader').css('display', 'flex');

    if (reqAdmin && reqAdmin.readyState !== 4) reqAdmin.abort();
    reqAdmin = $.ajax({
        url: 'fetch_admin_customer_kpi_data.php',
        type: 'GET',
        data: params,
        dataType: 'json'
    }).done(function(res) {
        if (res.error) { alert(res.error); return; }
        
        var k = res.kpi || {};
        initAnimNumber('aval_customers', k.total_customers || 0);
        initAnimNumber('aval_projects', k.total_projects || 0);
        initAnimNumber('aval_checklists', k.total_checklists || 0);
        initAnimNumber('aval_certificates', k.total_certificates || 0);

        // Populate dropdown if not already populated with what we typed
        if ($('#flt_customer option').length <= 1 && res.customers_list) {
            var curr = $('#flt_customer').val();
            var $sel = $('#flt_customer').empty().append('<option value="">All Customers</option>');
            $.each(res.customers_list, function(i, val) {
                $sel.append('<option value="'+val+'">'+val+'</option>');
            });
            $sel.val(curr);
        }

        drawStatusChart(res.project_status || {});
        drawMonthlyChart(res.monthly_trend || {});
        drawTopCustChart(res.top_customers || {});
        drawChecklistChart(res.checklist_distribution || {});

        // Update Table
        updateDataTable(res.table_data || []);

    }).always(function() {
        $('#akpiLoader').hide();
    });
}

function updateDataTable(data) {
    dtCustomers.clear();
    $.each(data, function(i, row) {
        dtCustomers.row.add([
            '<strong>' + row.name + '</strong>',
            '<div class="text-center">' + row.projects + '</div>',
            '<div class="text-center text-success fw-bold">' + row.completed + '</div>',
            '<div class="text-center text-danger fw-bold">' + row.pending + '</div>',
            '<div class="text-center text-primary fw-bold">' + row.checklists + '</div>'
        ]);
    });
    dtCustomers.draw();
}

// ── 1. Status Donut ──
function drawStatusChart(d) {
    chStatus = safeDestroy(chStatus, '#chart_status');
    var total = (d.data || []).reduce((a,b) => a+b, 0);
    if(total === 0) { renderNoDataMsg('#chart_status'); return; }

    chStatus = new ApexCharts(document.querySelector('#chart_status'), {
        chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
        series: d.data, labels: d.labels,
        colors: [CKPI.rose, CKPI.amber, CKPI.emerald],
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Projects' } } } } },
        dataLabels: { enabled: true },
        legend: { position: 'bottom' }
    });
    chStatus.render();
}

// ── 2. Monthly Area ──
function drawMonthlyChart(d) {
    chMonthly = safeDestroy(chMonthly, '#chart_monthly');
    if(!d.labels || !d.labels.length) { renderNoDataMsg('#chart_monthly'); return; }

    chMonthly = new ApexCharts(document.querySelector('#chart_monthly'), {
        chart: { type: 'area', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [{ name: 'Projects', data: d.data }],
        xaxis: { categories: d.labels },
        colors: [CKPI.blue],
        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 100] } },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false }
    });
    chMonthly.render();
}

// ── 3. Top Customers Bar (Horizontal) ──
function drawTopCustChart(d) {
    chTopCust = safeDestroy(chTopCust, '#chart_topcust');
    if(!d.labels || !d.labels.length) { renderNoDataMsg('#chart_topcust'); return; }

    chTopCust = new ApexCharts(document.querySelector('#chart_topcust'), {
        chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [{ name: 'Projects', data: d.data }],
        xaxis: { categories: d.labels },
        colors: [CKPI.sky],
        plotOptions: { bar: { horizontal: true, borderRadius: 5, dataLabels: { position: 'center' } } },
        dataLabels: { enabled: true, style: { colors: ['#fff'] } }
    });
    chTopCust.render();
}

// ── 4. Checklist Pie ──
function drawChecklistChart(d) {
    chChecks = safeDestroy(chChecks, '#chart_checks');
    var total = (d.data || []).reduce((a,b) => a+b, 0);
    if(total === 0) { renderNoDataMsg('#chart_checks'); return; }

    chChecks = new ApexCharts(document.querySelector('#chart_checks'), {
        chart: { type: 'pie', height: 320, fontFamily: 'inherit' },
        series: d.data, labels: d.labels,
        colors: [CKPI.purple, CKPI.teal, CKPI.blue, CKPI.orange, CKPI.emerald, CKPI.rose, CKPI.amber, CKPI.sky],
        dataLabels: { enabled: true },
        legend: { position: 'right' }
    });
    chChecks.render();
}
</script>
</body>
</html>
