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
<title>Admin - Inspector KPI Dashboard</title>

<!-- DataTables CSS for the breakdown table -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">

<link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/premium-nav.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
/* ============================================================
   ADMIN INSPECTOR KPI DASHBOARD (Premium UI)
   ============================================================ */
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap');

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
    font-family: 'Outfit', sans-serif !important;
}
.akpi-page-header .hdr-left p {
    margin: 4px 0 0; font-size: 13px; color: #94a3b8;
    font-family: 'Outfit', sans-serif !important;
}
.akpi-page-header .hdr-left h4 i {
    background: linear-gradient(135deg, #8b5cf6, #a78bfa);
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
    font-weight: 600;
}
.akpi-filter-bar select option {
    background: #1e293b; color: #fff;
}
.akpi-filter-bar select:focus,
.akpi-filter-bar input[type="date"]:focus {
    outline: none; border-color: #8b5cf6; background: rgba(255,255,255,.12);
}
.btn-akpi-filter {
    padding: 9px 24px;
    background: linear-gradient(135deg, #8b5cf6, #a78bfa);
    color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: all .25s; box-shadow: 0 4px 14px rgba(139,92,246,.3);
}
.btn-akpi-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(139,92,246,.4); }
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

.akpi-stat-card.c-purple  { border-color: #8b5cf6; }
.akpi-stat-card.c-purple::after  { background: #8b5cf6; }
.akpi-stat-card.c-blue    { border-color: #3b82f6; }
.akpi-stat-card.c-blue::after    { background: #3b82f6; }
.akpi-stat-card.c-emerald { border-color: #10b981; }
.akpi-stat-card.c-emerald::after { background: #10b981; }
.akpi-stat-card.c-orange  { border-color: #f97316; }
.akpi-stat-card.c-orange::after  { background: #f97316; }

.asc-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #64748b; margin-bottom: 8px; font-family: 'Outfit', sans-serif !important; }
.asc-value { font-size: 32px; font-weight: 800; color: #1e293b; line-height: 1; margin: 6px 0; font-family: 'Outfit', sans-serif !important; }

.asc-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;
}
.asc-icon.bg-purple  { background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #8b5cf6; }
.asc-icon.bg-blue    { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #3b82f6; }
.asc-icon.bg-emerald { background: linear-gradient(135deg, #ecfdf5, #a7f3d0); color: #10b981; }
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
.acb-header h6 { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0; font-family: 'Outfit', sans-serif !important; }
.acb-header i { font-size: 16px; color: #8b5cf6; }

/* ── Loader & No Data ── */
.akpi-loader { position: fixed; inset: 0; background: rgba(255,255,255,.8); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 9999; }
.akpi-spinner { width: 50px; height: 50px; border: 4px solid #e2e8f0; border-top-color: #8b5cf6; border-radius: 50%; animation: akpiSpin .8s linear infinite; }
@keyframes akpiSpin { to { transform: rotate(360deg); } }
.akpi-no-data { min-height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; font-size: 14px; gap: 10px; }
.akpi-no-data i { font-size: 36px; opacity: .3; }

/* ── DataTable Overrides ── */
.table-slim th { border-top: none; text-transform: uppercase; font-size: 11px; letter-spacing: .5px; color: #64748b; background: #f8fafc; font-family: 'Outfit', sans-serif !important; }
.table-slim td { vertical-align: middle; color: #334155; font-size: 13px; font-weight: 500;}
div.dataTables_wrapper div.dataTables_filter input {
    border-radius: 6px; border: 1px solid #cbd5e1; padding: 4px 10px; margin-left: 8px;
}
.page-item.active .page-link { background-color: #8b5cf6; border-color: #8b5cf6; }

/* Premium glass UI refresh */
body {
    background:
        radial-gradient(circle at 14% 8%, rgba(139, 92, 246, 0.16), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(59, 130, 246, 0.13), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
    color: #111827;
    font-family: 'Outfit', Inter, system-ui, -apple-system, sans-serif;
}

.akpi-main {
    position: relative;
    min-height: 100vh;
    padding: 10px 10px 48px;
    overflow: hidden;
    background:
        radial-gradient(circle at 14% 8%, rgba(139, 92, 246, 0.16), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(59, 130, 246, 0.13), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
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
    background: rgba(139, 92, 246, 0.08);
    pointer-events: none;
}

.akpi-page-header .hdr-left h4 i {
    width: 54px;
    height: 54px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-right: 14px;
    border-radius: 17px;
    background: linear-gradient(135deg, rgba(139, 92, 246, 0.16), rgba(59, 130, 246, 0.14));
    color: #8b5cf6;
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
    border-color: rgba(139, 92, 246, .42);
    background: rgba(255,255,255,.92);
    box-shadow: 0 0 0 4px rgba(139, 92, 246, .1);
}

.btn-akpi-filter {
    min-height: 46px;
    padding: 10px 20px;
    border: 0;
    border-radius: 13px;
    background: linear-gradient(135deg, #8b5cf6 0%, #3b82f6 100%);
    color: #fff;
    box-shadow: 0 18px 34px rgba(139, 92, 246, .24);
    font-size: 13px;
    font-weight: 800;
}

.btn-akpi-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 22px 42px rgba(139, 92, 246, .35);
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
    background: rgba(139, 92, 246, .08);
}

.akpi-stat-card:after {
    display: none;
}

.akpi-stat-card:hover {
    transform: translateY(-4px);
    border-color: rgba(139, 92, 246, .32);
    box-shadow: 0 30px 70px rgba(15,23,42,.16);
}

.akpi-stat-card.c-purple  { border-top: 3px solid rgba(139,92,246,.78); }
.akpi-stat-card.c-blue    { border-top: 3px solid rgba(59,130,246,.78); }
.akpi-stat-card.c-emerald { border-top: 3px solid rgba(16,185,129,.78); }
.akpi-stat-card.c-orange  { border-top: 3px solid rgba(245,158,11,.78); }

.asc-icon.bg-purple  { background: rgba(139, 92, 246, .14); color: #8b5cf6; }
.asc-icon.bg-blue    { background: rgba(59, 130, 246, .14); color: #3b82f6; }
.asc-icon.bg-emerald { background: rgba(16, 185, 129, .14); color: #10b981; }
.asc-icon.bg-orange  { background: rgba(245, 158, 11, .16); color: #d97706; }

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

.page-link {
    border-radius: 10px !important;
    color: #8b5cf6;
}

.page-item.active .page-link {
    border-color: #8b5cf6;
    background: #8b5cf6;
}

.btn-detail-view {
    padding: 6px 14px !important;
    border-radius: 10px !important;
    font-size: 12px !important;
    background: linear-gradient(135deg, #8b5cf6, #3b82f6) !important;
    color: white !important;
    border: none !important;
    cursor: pointer;
    font-weight: 700;
    transition: all 0.2s ease;
}
.btn-detail-view:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(139,92,246,0.3);
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
            <h4><i class="fas fa-id-card-clip"></i>Inspector Performance Dashboard</h4>
            <p>Comprehensive monitoring and comparison of inspectors' workloads and outputs.</p>
        </div>
        <div class="akpi-filter-bar">
            <!-- Dynamic dropdown loaded by JS -->
            <select id="flt_inspector">
                <option value="">All Inspectors</option>
            </select>
            
            <input type="date" id="flt_date_from" title="From Date">
            <input type="date" id="flt_date_to"   title="To Date">
            
            <button class="btn-akpi-filter" onclick="loadAdminInspectorKPI()">
                <i class="fas fa-filter"></i> Apply
            </button>
            <button class="btn-akpi-reset" onclick="resetAdminInspectorKPI()">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </div>

    <!-- 4 Core Stat Cards -->
    <div class="akpi-cards-row">
        <div class="akpi-stat-card c-purple">
            <div>
                <div class="asc-label">Total Inspectors</div>
                <div class="asc-value" id="aval_inspectors">0</div>
            </div>
            <div class="asc-icon bg-purple"><i class="fas fa-user-shield"></i></div>
        </div>
        <div class="akpi-stat-card c-blue">
            <div>
                <div class="asc-label">Total Projects</div>
                <div class="asc-value" id="aval_projects">0</div>
            </div>
            <div class="asc-icon bg-blue"><i class="fas fa-briefcase"></i></div>
        </div>
        <div class="akpi-stat-card c-emerald">
            <div>
                <div class="asc-label">Completed Projects</div>
                <div class="asc-value" id="aval_completed">0</div>
            </div>
            <div class="asc-icon bg-emerald"><i class="fas fa-circle-check"></i></div>
        </div>
        <div class="akpi-stat-card c-orange">
            <div>
                <div class="asc-label">Avg. Completion Rate</div>
                <div class="asc-value" id="aval_rate">0%</div>
            </div>
            <div class="asc-icon bg-orange"><i class="fas fa-percent"></i></div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="akpi-chart-row">
        <div class="akpi-card-box">
            <div class="acb-header">
                <div class="acb-header-left"><i class="fas fa-chart-pie"></i><h6>Workload Distribution</h6></div>
            </div>
            <div id="chart_workload" style="min-height:300px"></div>
        </div>
        <div class="akpi-card-box">
            <div class="acb-header">
                <div class="acb-header-left"><i class="fas fa-chart-bar"></i><h6>Monthly Inspection Trends</h6></div>
            </div>
            <div id="chart_monthly" style="min-height:300px"></div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="akpi-chart-row">
        <div class="akpi-card-box">
            <div class="acb-header">
                <div class="acb-header-left"><i class="fas fa-tags"></i><h6>Sticker Quality Comparison</h6></div>
            </div>
            <div id="chart_sticker" style="min-height:320px"></div>
        </div>
        <div class="akpi-card-box">
            <div class="acb-header">
                <div class="acb-header-left"><i class="fas fa-clock-rotate-left"></i><h6>Pending Backlog Distribution</h6></div>
            </div>
            <div id="chart_backlog" style="min-height:320px"></div>
        </div>
    </div>

    <!-- Breakdown Ledger Table -->
    <div class="akpi-card-box">
        <div class="acb-header mb-4">
            <div class="acb-header-left"><i class="fas fa-list-check"></i><h6>Detailed Inspector Performance Ledger</h6></div>
        </div>
        <div class="table-responsive">
            <table id="tblInspectors" class="table table-hover table-slim w-100">
                <thead>
                    <tr>
                        <th>Inspector Name</th>
                        <th class="text-center">Total Projects</th>
                        <th class="text-center text-success">Completed</th>
                        <th class="text-center text-warning">Pending Projects</th>
                        <th class="text-center text-primary">Pending Checklists</th>
                        <th class="text-center text-danger">Pending Reports</th>
                        <th class="text-center">Sticker Pass Rate</th>
                        <th class="text-center">Action</th>
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
var chWorkload, chMonthly, chSticker, chBacklog;
var isClearing = false;
var dtInspectors = null;

var CKPI = {
    purple: '#8b5cf6', blue: '#3b82f6', emerald: '#10b981', orange: '#f97316',
    rose: '#f43f5e', amber: '#f59e0b', sky: '#0ea5e9', teal: '#14b8a6', gray: '#94a3b8'
};

function initAnimNumber(elId, target, suffix = '') {
    var el = document.getElementById(elId);
    if (!el) return;
    var start = parseFloat(el.textContent) || 0;
    var duration = 1200, startTime = null;
    function step(ts) {
        if (!startTime) startTime = ts;
        var pct = Math.min((ts - startTime)/duration, 1);
        var val = start + (target - start)*pct;
        el.textContent = (suffix === '%' ? Math.floor(val) : Math.floor(val).toLocaleString()) + suffix;
        if (pct < 1) requestAnimationFrame(step); else el.textContent = target.toLocaleString() + suffix;
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
    // Initialize ledger datatable
    dtInspectors = $('#tblInspectors').DataTable({
        pageLength: 10,
        lengthChange: false,
        ordering: true,
        order: [[1, 'desc']], // Sort by total projects desc
        language: { search: "", searchPlaceholder: "Search inspectors..." }
    });

    loadAdminInspectorKPI();

    // Auto update on change
    $('#flt_date_from, #flt_date_to, #flt_inspector').on('change', function() {
        if (!isClearing) loadAdminInspectorKPI();
    });
});

function resetAdminInspectorKPI() {
    isClearing = true;
    $('#flt_date_from, #flt_date_to, #flt_inspector').val('');
    isClearing = false;
    loadAdminInspectorKPI();
}

function loadAdminInspectorKPI() {
    var params = {
        date_from: $('#flt_date_from').val(),
        date_to:   $('#flt_date_to').val(),
        inspector:  $('#flt_inspector').val()
    };
    $('#akpiLoader').css('display', 'flex');

    if (reqAdmin && reqAdmin.readyState !== 4) reqAdmin.abort();
    reqAdmin = $.ajax({
        url: 'fetch_admin_inspector_kpi_data.php',
        type: 'GET',
        data: params,
        dataType: 'json'
    }).done(function(res) {
        if (res.error) { alert(res.error); return; }
        
        var k = res.kpi || {};
        initAnimNumber('aval_inspectors', k.total_inspectors || 0);
        initAnimNumber('aval_projects', k.total_projects || 0);
        initAnimNumber('aval_completed', k.completed_projects || 0);
        initAnimNumber('aval_rate', k.completion_rate || 0, '%');

        // Populate dropdown dynamic list once
        if ($('#flt_inspector option').length <= 1 && res.inspectors_list) {
            var curr = $('#flt_inspector').val();
            var $sel = $('#flt_inspector').empty().append('<option value="">All Inspectors</option>');
            $.each(res.inspectors_list, function(i, val) {
                $sel.append('<option value="'+val+'">'+val+'</option>');
            });
            $sel.val(curr);
        }

        drawWorkloadChart(res.workload || {});
        drawMonthlyChart(res.monthly || {});
        drawStickerChart(res.sticker || {});
        drawBacklogChart(res.backlog || {});

        // Update Table
        updateDataTable(res.table_data || []);

    }).always(function() {
        $('#akpiLoader').hide();
    });
}

function updateDataTable(data) {
    dtInspectors.clear();
    $.each(data, function(i, row) {
        var actionBtn = '<button class="btn-detail-view" onclick="viewInspectorDetail(\'' + escapeJs(row.name) + '\')"><i class="fas fa-chart-line mr-1"></i> View Stats</button>';
        dtInspectors.row.add([
            '<strong>' + row.name + '</strong>',
            '<div class="text-center font-weight-bold">' + row.total + '</div>',
            '<div class="text-center text-success font-weight-bold">' + row.completed + ' (' + row.rate + '%)</div>',
            '<div class="text-center text-warning font-weight-bold">' + row.pending + '</div>',
            '<div class="text-center text-primary font-weight-bold">' + row.checklists + '</div>',
            '<div class="text-center text-danger font-weight-bold">' + row.reports + '</div>',
            '<div class="text-center font-weight-bold">' + row.sticker_rate + '</div>',
            '<div class="text-center">' + actionBtn + '</div>'
        ]);
    });
    dtInspectors.draw();
}

function viewInspectorDetail(name) {
    $('#flt_inspector').val(name).trigger('change');
    $('html, body').animate({ scrollTop: 0 }, 'smooth');
}

function escapeJs(str) {
    return str.replace(/'/g, "\\'");
}

// ── 1. Workload Share Donut ──
function drawWorkloadChart(d) {
    chWorkload = safeDestroy(chWorkload, '#chart_workload');
    var total = (d.data || []).reduce((a,b) => a+b, 0);
    if(total === 0) { renderNoDataMsg('#chart_workload'); return; }

    chWorkload = new ApexCharts(document.querySelector('#chart_workload'), {
        chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
        series: d.data, labels: d.labels,
        colors: [CKPI.purple, CKPI.blue, CKPI.emerald, CKPI.orange, CKPI.rose, CKPI.sky, CKPI.teal],
        plotOptions: { 
            pie: { 
                donut: { 
                    size: '65%', 
                    labels: { 
                        show: true, 
                        total: { show: true, label: 'Inspections', formatter: () => total.toLocaleString() } 
                    } 
                } 
            } 
        },
        dataLabels: { enabled: false },
        legend: { position: 'bottom' }
    });
    chWorkload.render();
}

// ── 2. Monthly Column Trends ──
function drawMonthlyChart(d) {
    chMonthly = safeDestroy(chMonthly, '#chart_monthly');
    if(!d.labels || !d.labels.length) { renderNoDataMsg('#chart_monthly'); return; }

    chMonthly = new ApexCharts(document.querySelector('#chart_monthly'), {
        chart: { type: 'bar', height: 300, stacked: true, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [
            { name: 'Completed', data: d.completed },
            { name: 'Pending', data: d.pending }
        ],
        xaxis: { categories: d.labels },
        colors: [CKPI.emerald, CKPI.orange],
        plotOptions: { bar: { columnWidth: '40%', borderRadius: 6 } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        legend: { position: 'top' },
        dataLabels: { enabled: false }
    });
    chMonthly.render();
}

// ── 3. Sticker comparison bar ──
function drawStickerChart(d) {
    chSticker = safeDestroy(chSticker, '#chart_sticker');
    if(!d.labels || !d.labels.length) { renderNoDataMsg('#chart_sticker'); return; }

    chSticker = new ApexCharts(document.querySelector('#chart_sticker'), {
        chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [
            { name: 'Passed', data: d.passed },
            { name: 'Failed', data: d.failed }
        ],
        xaxis: { categories: d.labels },
        colors: [CKPI.emerald, CKPI.rose],
        plotOptions: { bar: { columnWidth: '50%', borderRadius: 4 } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        legend: { position: 'top' }
    });
    chSticker.render();
}

// ── 4. Backlog checklist vs report bar ──
function drawBacklogChart(d) {
    chBacklog = safeDestroy(chBacklog, '#chart_backlog');
    if(!d.labels || !d.labels.length) { renderNoDataMsg('#chart_backlog'); return; }

    chBacklog = new ApexCharts(document.querySelector('#chart_backlog'), {
        chart: { type: 'bar', height: 320, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [
            { name: 'Pending Checklists', data: d.checklists },
            { name: 'Pending Reports', data: d.reports }
        ],
        xaxis: { categories: d.labels },
        colors: [CKPI.blue, CKPI.rose],
        plotOptions: { bar: { columnWidth: '50%', borderRadius: 4 } },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        legend: { position: 'top' }
    });
    chBacklog.render();
}
</script>
</body>
</html>
