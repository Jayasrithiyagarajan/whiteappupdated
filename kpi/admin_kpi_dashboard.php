<?php
include_once('../inc/function.php');
include_once('../file/config.php');

if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Admin Dashboard - KPI Reports & Analytics</title>
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/premium-nav.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<style>
body {
    background:
        radial-gradient(circle at 14% 8%, rgba(20, 184, 166, 0.16), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(37, 99, 235, 0.13), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
}

.kpi-main-content {
    position: relative;
    padding: 10px 10px 48px;
    background:
        radial-gradient(circle at 14% 8%, rgba(20, 184, 166, 0.16), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(37, 99, 235, 0.13), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
    min-height: 100vh;
    overflow: hidden;
}
.kpi-main-content:before {
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

/* --- Page Header --- */
.kpi-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, rgba(255,255,255,.78), rgba(255,255,255,.48));
    border: 1px solid rgba(255,255,255,.64);
    border-radius: 22px;
    padding: 26px 28px;
    margin-bottom: 28px;
    box-shadow: 0 24px 60px rgba(15,23,42,.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    flex-wrap: wrap;
    gap: 16px;
}
.kpi-page-header .hdr-left h4 {
    margin: 0 0 8px;
    font-size: clamp(24px, 2vw, 34px);
    font-weight: 850;
    color: #111827;
    letter-spacing: 0;
    text-transform: none;
}
.kpi-page-header .hdr-left p {
    margin: 0;
    font-size: 14px;
    color: #64748b;
}
.kpi-filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.kpi-filter-bar input[type="date"] {
    min-height: 46px;
    padding: 10px 12px;
    border: 1px solid rgba(148,163,184,.26);
    border-radius: 13px;
    font-size: 13px;
    font-weight: 700;
    color: #111827;
    background: rgba(255,255,255,.72);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.88);
}
.kpi-filter-bar .kpi-insp-select {
    min-height: 46px;
    padding: 10px 12px;
    border: 1px solid rgba(148,163,184,.26);
    border-radius: 13px;
    font-size: 13px;
    min-width: 160px;
    font-weight: 700;
    color: #111827;
    background: rgba(255,255,255,.72);
    box-shadow: inset 0 1px 0 rgba(255,255,255,.88);
}
.btn-kpi-filter {
    min-height: 46px;
    padding: 10px 20px;
    background: linear-gradient(135deg, #2563eb 0%, #16a3d8 52%, #14b8a6 100%);
    color: #fff;
    border: none;
    border-radius: 13px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 18px 34px rgba(37,99,235,.24);
    transition: transform .2s ease, box-shadow .2s ease;
}
.btn-kpi-filter:hover { transform: translateY(-1px); box-shadow: 0 22px 42px rgba(20,184,166,.2); }
.btn-kpi-reset {
    min-height: 46px;
    padding: 10px 16px;
    background: rgba(255,255,255,.72);
    color: #374151;
    border: 1px solid rgba(148,163,184,.26);
    border-radius: 13px;
    font-size: 13px;
    font-weight: 800;
    cursor: pointer;
    box-shadow: 0 12px 26px rgba(15,23,42,.08);
}
.btn-kpi-reset:hover { background: rgba(255,255,255,.92); }

/* --- KPI Top Cards --- */
.kpi-cards-row {
    display: grid;
    grid-template-columns: repeat(6, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}
@media (max-width: 1300px) { .kpi-cards-row { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 768px)  { .kpi-cards-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px)  { .kpi-cards-row { grid-template-columns: 1fr; } }

.kpi-stat-card {
    position: relative;
    background: linear-gradient(135deg, rgba(255,255,255,.78), rgba(255,255,255,.48));
    border-radius: 20px;
    padding: 22px 18px;
    border: 1px solid rgba(255,255,255,.64);
    box-shadow: 0 24px 60px rgba(15,23,42,.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    overflow: hidden;
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
}
.kpi-stat-card:before {
    content: "";
    position: absolute;
    right: -34px;
    top: -34px;
    width: 112px;
    height: 112px;
    border-radius: 999px;
    background: rgba(37,99,235,.08);
}
.kpi-stat-card:hover {
    transform: translateY(-4px);
    border-color: rgba(20,184,166,.32);
    box-shadow: 0 30px 70px rgba(15,23,42,.16);
}
.kpi-stat-card > * { position: relative; z-index: 1; }
.kpi-stat-card.c-blue,
.kpi-stat-card.c-green,
.kpi-stat-card.c-orange,
.kpi-stat-card.c-cyan,
.kpi-stat-card.c-red,
.kpi-stat-card.c-purple { border-top: 0; }

.ksc-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    margin-bottom: 4px;
}
.ksc-value {
    font-size: 30px;
    font-weight: 850;
    color: #111827;
    line-height: 1;
    margin: 4px 0;
}
.ksc-sub {
    font-size: 11px;
    color: #64748b;
    margin-top: 4px;
}
.ksc-sub.txt-green  { color: #10b981; }
.ksc-sub.txt-orange { color: #f59e0b; }
.ksc-sub.txt-red    { color: #ef4444; }
.ksc-sub.txt-cyan   { color: #06b6d4; }
.ksc-sub.txt-purple { color: #8b5cf6; }

.ksc-icon {
    width: 48px; height: 48px;
    border-radius: 16px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
    box-shadow: inset 0 1px 0 rgba(255,255,255,.9), 0 16px 32px rgba(15,23,42,.1);
}
.ksc-icon.bg-blue   { background: rgba(37,99,235,.14); color: #2563eb; }
.ksc-icon.bg-green  { background: rgba(16,185,129,.14); color: #059669; }
.ksc-icon.bg-orange { background: rgba(245,158,11,.16); color: #b45309; }
.ksc-icon.bg-cyan   { background: rgba(6,182,212,.14); color: #0891b2; }
.ksc-icon.bg-red    { background: rgba(239,68,68,.14); color: #dc2626; }
.ksc-icon.bg-purple { background: rgba(139,92,246,.14); color: #7048e8; }

/* --- Filter Summary Row --- */
.kpi-filter-summary {
    background: linear-gradient(135deg, rgba(255,255,255,.78), rgba(255,255,255,.48));
    border: 1px solid rgba(255,255,255,.64);
    border-radius: 22px;
    padding: 22px 24px;
    margin-bottom: 24px;
    box-shadow: 0 24px 60px rgba(15,23,42,.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
.kpi-filter-summary h6 {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 16px;
}
.kpi-summary-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
}
@media (max-width: 900px) { .kpi-summary-grid { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 480px) { .kpi-summary-grid { grid-template-columns: 1fr; } }

.kpi-summary-item {
    background: rgba(255,255,255,.56);
    border-radius: 16px;
    padding: 16px;
    text-align: center;
    border: 1px solid rgba(226,232,240,.58);
}
.kpi-summary-item .sum-val {
    font-size: 20px;
    font-weight: 800;
    margin-bottom: 4px;
}
.kpi-summary-item .sum-label {
    font-size: 12px;
    color: #64748b;
}
.sum-val.c-blue   { color: #6045e2; }
.sum-val.c-green  { color: #10b981; }
.sum-val.c-orange { color: #f59e0b; }
.sum-val.c-red    { color: #ef4444; }

/* --- Charts Grid --- */
.kpi-chart-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
@media (max-width: 900px) { .kpi-chart-row { grid-template-columns: 1fr; } }

.kpi-chart-card {
    background: linear-gradient(135deg, rgba(255,255,255,.78), rgba(255,255,255,.48));
    border: 1px solid rgba(255,255,255,.64);
    border-radius: 22px;
    padding: 22px 20px;
    box-shadow: 0 24px 60px rgba(15,23,42,.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
.kpi-chart-card.full-width {
    grid-column: 1 / -1;
}
.kcc-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 4px;
}
.kcc-header h6 {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}
.kcc-footer {
    text-align: center;
    font-size: 12px;
    color: #94a3b8;
    margin-top: 10px;
}

/* --- Sticker Analytics Section --- */
.sticker-analytics {
    background: linear-gradient(135deg, rgba(255,255,255,.78), rgba(255,255,255,.48));
    border: 1px solid rgba(255,255,255,.64);
    border-radius: 22px;
    padding: 22px 24px;
    box-shadow: 0 24px 60px rgba(15,23,42,.1);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    margin-bottom: 24px;
}
.sticker-analytics h6 {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 18px;
    border-bottom: 1px solid #f1f5f9;
    padding-bottom: 10px;
}

.sticker-kpi-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}
@media (max-width: 900px) { .sticker-kpi-grid { grid-template-columns: repeat(2, 1fr); } }

.sticker-kpi-item {
    text-align: center;
    padding: 18px;
    border-radius: 16px;
    border: 1px solid rgba(226,232,240,.58);
    background: rgba(255,255,255,.56) !important;
}
.sticker-kpi-item.sk-total  { border-color: rgba(37,99,235,.28); }
.sticker-kpi-item.sk-used   { border-color: rgba(245,158,11,.3); }
.sticker-kpi-item.sk-pass   { border-color: rgba(16,185,129,.3); }
.sticker-kpi-item.sk-fail   { border-color: rgba(239,68,68,.3); }

.sticker-kpi-item .sk-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 6px;
}
.sk-total .sk-label  { color: #6045e2; }
.sk-used  .sk-label  { color: #f59e0b; }
.sk-pass  .sk-label  { color: #10b981; }
.sk-fail  .sk-label  { color: #ef4444; }

.sticker-kpi-item .sk-val {
    font-size: 28px;
    font-weight: 800;
    color: #1e293b;
}

/* Pass Rate Bar */
.pass-rate-section {
    margin-bottom: 20px;
}
.pass-rate-section .pr-label {
    text-align: center;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
}
.pass-rate-bar-track {
    height: 26px;
    background: rgba(241,245,249,.82);
    border-radius: 100px;
    overflow: hidden;
    position: relative;
}
.pass-rate-bar-fill {
    height: 100%;
    background: linear-gradient(90deg, #10b981, #06b6d4);
    border-radius: 100px;
    transition: width 1s ease;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 10px;
}
.pass-rate-bar-fill span {
    font-size: 12px;
    font-weight: 700;
    color: #fff;
}

.sticker-bottom-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
}
@media (max-width: 700px) { .sticker-bottom-grid { grid-template-columns: 1fr; } }

.sticker-bottom-item {
    border-radius: 16px;
    padding: 16px;
    border-left: 4px solid;
    background: rgba(255,255,255,.56);
}
.sticker-bottom-item.sbi-green  { border-color: #10b981; }
.sticker-bottom-item.sbi-red    { border-color: #ef4444; }
.sticker-bottom-item.sbi-blue   { border-color: #6045e2; }

.sticker-bottom-item .sbi-sub {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #64748b;
    margin-bottom: 6px;
}
.sticker-bottom-item .sbi-val {
    font-size: 22px;
    font-weight: 800;
    color: #1e293b;
}
.sticker-bottom-item.sbi-green .sbi-val { color: #10b981; }
.sticker-bottom-item.sbi-red   .sbi-val { color: #ef4444; }
.sticker-bottom-item.sbi-blue  .sbi-val { color: #6045e2; }

/* === Loading Overlay === */
.kpi-loader {
    position: fixed; inset: 0;
    background: rgba(248,250,252,.58);
    backdrop-filter: blur(8px);
    display: flex; align-items: center; justify-content: center;
    z-index: 9999;
    display: none;
}
.kpi-spinner {
    width: 44px; height: 44px;
    border: 4px solid #e2e8f0;
    border-top-color: #2563eb;
    border-radius: 50%;
    animation: spin .7s linear infinite;
}
@keyframes spin { to { transform: rotate(360deg); } }

/* === Responsive Tweaks === */
.kpi-main-content {
    box-sizing: border-box;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
}
.kpi-page-header, .kpi-stat-card, .kpi-summary-item, .kpi-chart-card, .sticker-analytics, .sticker-kpi-item, .sticker-bottom-item {
    min-width: 0;
    box-sizing: border-box;
    word-wrap: break-word;
}
.kpi-chart-card {
    position: relative;
    width: 100%;
}
.kpi-chart-card > div[id^="chart_"] {
    max-width: 100%;
}

@media (max-width: 768px) {
    .kpi-main-content { padding: 12px 10px; }
    .kpi-page-header { padding: 16px; flex-direction: column; align-items: stretch; gap: 12px; }
    .kpi-page-header .hdr-left { align-items: flex-start; text-align: left; }
    .kpi-filter-bar { display: grid; grid-template-columns: 1fr 1fr; width: 100%; gap: 10px; }
    .kpi-filter-bar .kpi-insp-select { grid-column: 1 / -1; }
    .kpi-filter-bar input, .kpi-filter-bar select, .kpi-filter-bar button { width: 100%; box-sizing: border-box; min-width: 0; }
    .ksc-value { font-size: 22px; }
    .kpi-chart-row { gap: 16px; }
    .sticker-analytics { padding: 16px; }
    .pass-rate-bar-track { height: 22px; }
}

@media (max-width: 480px) {
    .kpi-filter-bar { grid-template-columns: 1fr; }
    .kpi-cards-row { grid-template-columns: 1fr; }
    .ksc-value { font-size: 20px; }
}
</style>
</head>
<body>

<?php include_once('../inc/nav.php'); ?>

<!-- Loading Overlay -->
<div class="kpi-loader" id="kpiLoader">
    <div class="kpi-spinner"></div>
</div>

<div class="main-content">
<div class="kpi-main-content">

    <!-- ======= PAGE HEADER + FILTER BAR ======= -->
    <div class="kpi-page-header">
        <div class="hdr-left">
            <h4><i class="fas fa-chart-line" style="color:#6045e2;margin-right:8px"></i>Admin Dashboard - KPI Reports &amp; Analytics</h4>
            <p>Comprehensive Business Intelligence &amp; Performance Metrics</p>
        </div>
        <div class="kpi-filter-bar">
            <input type="date" id="kpi_date_from" title="From Date">
            <input type="date" id="kpi_date_to"   title="To Date">
            <select class="kpi-insp-select" id="kpi_inspector">
                <option value="">All Inspectors</option>
            </select>
            <button class="btn-kpi-filter" onclick="loadKPIData()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn-kpi-reset" onclick="resetFilters()">Reset</button>
        </div>
    </div>

    <!-- ======= 6 KPI STAT CARDS ======= -->
    <div class="kpi-cards-row">
        <!-- Total Projects -->
        <div class="kpi-stat-card c-blue">
            <div>
                <div class="ksc-label">Total Projects</div>
                <div class="ksc-value" id="kv_total">0</div>
                <div class="ksc-sub txt-green">
                    Completed: <span id="kv_total_comp">0</span> |
                    Rate: <span id="kv_total_rate">0</span>%
                </div>
            </div>
            <div class="ksc-icon bg-blue"><i class="fas fa-briefcase"></i></div>
        </div>

        <!-- Completed -->
        <div class="kpi-stat-card c-green">
            <div>
                <div class="ksc-label">Completed</div>
                <div class="ksc-value" id="kv_completed">0</div>
                <div class="ksc-sub txt-green"><span id="kv_comp_pct">0</span>% of total</div>
            </div>
            <div class="ksc-icon bg-green"><i class="fas fa-check-circle"></i></div>
        </div>

        <!-- In Progress -->
        <div class="kpi-stat-card c-orange">
            <div>
                <div class="ksc-label">In Progress</div>
                <div class="ksc-value" id="kv_inprogress">0</div>
                <div class="ksc-sub txt-orange"><span id="kv_prog_pct">0</span>% of total</div>
            </div>
            <div class="ksc-icon bg-orange"><i class="fas fa-spinner"></i></div>
        </div>

        <!-- Completion Rate -->
        <div class="kpi-stat-card c-cyan">
            <div>
                <div class="ksc-label">Completion Rate</div>
                <div class="ksc-value" id="kv_comp_rate">0%</div>
                <div class="ksc-sub txt-cyan">Based on <span id="kv_cr_total">0</span> projects</div>
            </div>
            <div class="ksc-icon bg-cyan"><i class="fas fa-chart-bar"></i></div>
        </div>

        <!-- Pending -->
        <div class="kpi-stat-card c-red">
            <div>
                <div class="ksc-label">Pending</div>
                <div class="ksc-value" id="kv_pending">0</div>
                <div class="ksc-sub txt-red"><span id="kv_pend_pct">0</span>% of total</div>
            </div>
            <div class="ksc-icon bg-red"><i class="fas fa-times-circle"></i></div>
        </div>

        <!-- Review Acceptance -->
        <div class="kpi-stat-card c-purple">
            <div>
                <div class="ksc-label">Review Acceptance</div>
                <div class="ksc-value" id="kv_review_rate">0%</div>
                <div class="ksc-sub txt-purple">
                    <span id="kv_review_num">0</span> of <span id="kv_review_tot">0</span>
                </div>
            </div>
            <div class="ksc-icon bg-purple"><i class="fas fa-clipboard-check"></i></div>
        </div>
    </div>

    <!-- ======= FILTER SUMMARY ======= -->
    <div class="kpi-filter-summary">
        <h6>Filter Summary</h6>
        <div class="kpi-summary-grid">
            <div class="kpi-summary-item">
                <div class="sum-val c-blue" id="sum_date">All Time</div>
                <div class="sum-label">Date Range</div>
            </div>
            <div class="kpi-summary-item">
                <div class="sum-val c-green" id="sum_inspector">All Inspectors</div>
                <div class="sum-label">Inspector Filter</div>
            </div>
            <div class="kpi-summary-item">
                <div class="sum-val c-orange" id="sum_total">0</div>
                <div class="sum-label">Total Projects in Range</div>
            </div>
            <div class="kpi-summary-item">
                <div class="sum-val c-red" id="sum_inspectors">0</div>
                <div class="sum-label">Active Inspectors</div>
            </div>
        </div>
    </div>

    <!-- ======= CHARTS ROW 1: Trends + Top Inspectors ======= -->
    <div class="kpi-chart-row">
        <!-- Monthly Project Trends -->
        <div class="kpi-chart-card">
            <div class="kcc-header">
                <h6>Monthly Project Trends</h6>
                <i class="fas fa-ellipsis-v" style="color:#94a3b8;cursor:pointer"></i>
            </div>
            <div id="chart_monthly" style="min-height:260px"></div>
            <div class="kcc-footer" id="monthly_footer">Showing data for selected period</div>
        </div>

        <!-- Top Performers - Inspectors -->
        <div class="kpi-chart-card">
            <div class="kcc-header">
                <h6>Top Performers - Inspectors</h6>
            </div>
            <div id="chart_inspectors" style="min-height:260px"></div>
            <div class="kcc-footer">Showing top 8 inspectors</div>
        </div>
    </div>

    <!-- ======= CHARTS ROW 2: Customers + Equipment ======= -->
    <div class="kpi-chart-row">
        <!-- Top Customers by Project Volume -->
        <div class="kpi-chart-card">
            <div class="kcc-header">
                <h6>Top Customers by Project Volume</h6>
            </div>
            <div id="chart_customers" style="min-height:260px"></div>
            <div class="kcc-footer">Showing top 10 customers</div>
        </div>

        <!-- Equipment Type Analysis -->
        <div class="kpi-chart-card">
            <div class="kcc-header">
                <h6>Equipment Type Analysis</h6>
            </div>
            <div id="chart_equipment" style="min-height:260px"></div>
            <div class="kcc-footer">Showing top 10 equipment types</div>
        </div>
    </div>

    <!-- ======= CHARTS ROW 3: Certificate Distribution ======= -->
    <div class="kpi-chart-row">
        <!-- Certificate Distribution Analysis -->
        <div class="kpi-chart-card full-width">
            <div class="kcc-header">
                <h6>Certificate Distribution Analysis</h6>
            </div>
            <div id="chart_certificates" style="min-height:320px;"></div>
            <div class="kcc-footer">Showing counts across all 9 certificate categories</div>
        </div>
    </div>

    <!-- ======= STICKER ANALYTICS ======= -->
    <div class="sticker-analytics">
        <h6>Sticker Analytics (Project Based)</h6>

        <!-- 4 Sticker KPI Boxes -->
        <div class="sticker-kpi-grid">
            <div class="sticker-kpi-item sk-total">
                <div class="sk-label">Total Stickers</div>
                <div class="sk-val" id="sk_total">0</div>
            </div>
            <div class="sticker-kpi-item sk-used">
                <div class="sk-label">Used Stickers</div>
                <div class="sk-val" id="sk_used">0</div>
            </div>
            <div class="sticker-kpi-item sk-pass">
                <div class="sk-label">Passed</div>
                <div class="sk-val" id="sk_passed">0</div>
            </div>
            <div class="sticker-kpi-item sk-fail">
                <div class="sk-label">Failed</div>
                <div class="sk-val" id="sk_failed">0</div>
            </div>
        </div>

        <!-- Pass Rate Bar -->
        <div class="pass-rate-section">
            <div class="pr-label">Pass Rate</div>
            <div class="pass-rate-bar-track">
                <div class="pass-rate-bar-fill" id="sk_pass_bar" style="width:0%">
                    <span id="sk_pass_label">0%</span>
                </div>
            </div>
        </div>

        <!-- Bottom Grid -->
        <div class="sticker-bottom-grid">
            <div class="sticker-bottom-item sbi-green">
                <div class="sbi-sub">Sticker-Passed</div>
                <div class="sbi-val" id="sk_bottom_passed">0</div>
            </div>
            <div class="sticker-bottom-item sbi-red">
                <div class="sbi-sub">Sticker-Failed</div>
                <div class="sbi-val" id="sk_bottom_failed">0</div>
            </div>
            <div class="sticker-bottom-item sbi-blue">
                <div class="sbi-sub">Pass Rate</div>
                <div class="sbi-val" id="sk_bottom_rate">0%</div>
            </div>
        </div>
    </div>

</div><!-- end kpi-main-content -->
</div><!-- end main-content -->

<?php include_once('../inc/footer.php'); ?>

<script>
/* ============================================================
   CHART INSTANCES
   ============================================================ */
var chartMonthly, chartInspectors, chartCustomers, chartEquipment, chartCertificates;
var kpiRequest = null;

function renderNoData(containerSelector, message) {
    $(containerSelector).html(
        '<div style="min-height:240px;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:13px;">' +
        message +
        '</div>'
    );
}

/* ============================================================
   INIT: Set default dates (current month)
   ============================================================ */
$(document).ready(function() {
    // Load inspectors list then load data
    loadInspectorsList();

    $('#kpi_date_from, #kpi_date_to, #kpi_inspector').on('change', function() {
        loadKPIData();
    });
});

function getFilterParams() {
    return {
        date_from: ($('#kpi_date_from').val() || '').trim(),
        date_to: ($('#kpi_date_to').val() || '').trim(),
        inspector: $('#kpi_inspector').val() || '',
        _ts: Date.now()
    };
}

/* ============================================================
   LOAD INSPECTOR DROP-DOWN
   ============================================================ */
function loadInspectorsList() {
    $.ajax({
        url: 'fetch_admin_kpi_data.php',
        type: 'GET',
        dataType: 'json',
        cache: false,
        data: { date_from: '', date_to: '', inspector: '', _ts: Date.now() },
        success: function(data) {
            if (data.inspectors_list) {
                var sel = $('#kpi_inspector');
                sel.find('option:not(:first)').remove();
                data.inspectors_list.forEach(function(name) {
                    sel.append('<option value="' + name + '">' + name + '</option>');
                });
            }
        },
        complete: function() {
            loadKPIData();
        }
    });
}

/* ============================================================
   MAIN LOAD FUNCTION
   ============================================================ */
function loadKPIData() {
    var params = getFilterParams();

    $('#kpiLoader').show();

    if (kpiRequest && kpiRequest.readyState !== 4) {
        kpiRequest.abort();
    }

    kpiRequest = $.ajax({
        url: 'fetch_admin_kpi_data.php',
        type: 'GET',
        dataType: 'json',
        cache: false,
        data: params
    }).done(function(data) {
        if (data.error) {
            return;
        }

        updateKPICards(data.kpi || {}, data.filter_summary || {});
        updateFilterSummary(data.filter_summary || {});
        renderMonthlyChart(data.monthly || {});
        renderInspectorsChart(data.top_inspectors || {});
        renderCustomersChart(data.top_customers || {});
        renderEquipmentChart(data.equipment || {});
        renderCertificatesChart(data.certificates || {});
        updateStickerAnalytics(data.sticker || {});
    }).always(function() {
        $('#kpiLoader').hide();
    });
}

/* ============================================================
   RESET
   ============================================================ */
function resetFilters() {
    $('#kpi_date_from').val('');
    $('#kpi_date_to').val('');
    $('#kpi_inspector').val('');
    loadKPIData();
}

/* ============================================================
   UPDATE KPI CARDS
   ============================================================ */
function updateKPICards(kpi, sum) {
    var total    = kpi.total    || 0;
    var comp     = kpi.completed|| 0;
    var prog     = kpi.in_progress || 0;
    var pending  = kpi.pending  || 0;
    var cRate    = kpi.completion_rate || 0;
    var rRate    = kpi.review_acceptance || 0;
    var rNum     = kpi.review_accepted || 0;

    var compPct  = total > 0 ? ((comp / total) * 100).toFixed(2) : 0;
    var progPct  = total > 0 ? ((prog / total) * 100).toFixed(2) : 0;
    var pendPct  = total > 0 ? ((pending / total) * 100).toFixed(2) : 0;

    animNum('kv_total',      total);
    animNum('kv_completed',  comp);
    animNum('kv_inprogress', prog);
    animNum('kv_pending',    pending);
    $('#kv_total_comp').text(comp);
    $('#kv_total_rate').text(cRate);
    $('#kv_comp_pct').text(compPct);
    $('#kv_prog_pct').text(progPct);
    $('#kv_pend_pct').text(pendPct);
    $('#kv_comp_rate').text(cRate + '%');
    $('#kv_cr_total').text(total);
    $('#kv_review_rate').text(rRate + '%');
    $('#kv_review_num').text(rNum);
    $('#kv_review_tot').text(total);
}

/* ============================================================
   UPDATE FILTER SUMMARY
   ============================================================ */
function updateFilterSummary(fs) {
    $('#sum_date').text(fs.date_label      || 'All Time');
    $('#sum_inspector').text(fs.inspector_label || 'All Inspectors');
    animNum('sum_total',      fs.total_in_range    || 0);
    animNum('sum_inspectors', fs.active_inspectors || 0);
}

/* ============================================================
   RENDER: Monthly Trends (Line Chart)
   ============================================================ */
function renderMonthlyChart(monthly) {
    var labels    = monthly.labels    || [];
    var totals    = monthly.total     || [];
    var completed = monthly.completed || [];
    var progress  = monthly.progress  || [];

    if (!labels.length) {
        if (chartMonthly) { chartMonthly.destroy(); chartMonthly = null; }
        renderNoData('#chart_monthly', 'No monthly data available');
        return;
    }

    $('#chart_monthly').empty();

    var opts = {
        chart: { type: 'line', height: 300, toolbar: { show: false } },
        series: [
            { name: 'Total', data: totals },
            { name: 'Completed', data: completed },
            { name: 'In Progress', data: progress }
        ],
        xaxis: { categories: labels },
        colors: ['#6045e2', '#10b981', '#f59e0b'],
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4 },
        legend: { position: 'top' },
        grid: { borderColor: '#e2e8f0' }
    };

    if (chartMonthly) { chartMonthly.destroy(); }
    chartMonthly = new ApexCharts(document.querySelector('#chart_monthly'), opts);
    chartMonthly.render();

    // Footer
    var footer = '';
    if (monthly.date_from && monthly.date_to) {
        footer = 'Showing ' + (monthly.count||1) + ' month(s) from ' + monthly.date_from + ' to ' + monthly.date_to;
    } else {
        footer = 'Showing ' + (monthly.count||0) + ' month(s)';
    }
    $('#monthly_footer').text(footer);
}

/* ============================================================
   RENDER: Top Inspectors (Horizontal Bar)
   ============================================================ */
function renderInspectorsChart(data) {
    var names = (data.names     || []).slice().reverse();
    var tots  = (data.totals    || []).slice().reverse();
    var comps = (data.completed || []).slice().reverse();

    if (!names.length) {
        if (chartInspectors) { chartInspectors.destroy(); chartInspectors = null; }
        renderNoData('#chart_inspectors', 'No inspector data available');
        return;
    }

    $('#chart_inspectors').empty();

    var opts = {
        chart: { type: 'bar', height: 280, toolbar: { show: false } },
        plotOptions: { bar: { horizontal: true, barHeight: '60%' } },
        series: [
            { name: 'Total', data: tots },
            { name: 'Completed', data: comps }
        ],
        xaxis: { categories: names },
        colors: ['#6045e2', '#10b981'],
        legend: { position: 'top' },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e2e8f0' }
    };

    if (chartInspectors) { chartInspectors.destroy(); }
    chartInspectors = new ApexCharts(document.querySelector('#chart_inspectors'), opts);
    chartInspectors.render();
}

/* ============================================================
   RENDER: Top Customers (Vertical Bar)
   ============================================================ */
function renderCustomersChart(data) {
    var names = data.names     || [];
    var tots  = data.totals    || [];
    var comps = data.completed || [];

    if (!names.length) {
        if (chartCustomers) { chartCustomers.destroy(); chartCustomers = null; }
        renderNoData('#chart_customers', 'No customer data available');
        return;
    }

    $('#chart_customers').empty();

    var opts = {
        chart: { type: 'bar', height: 300, toolbar: { show: false } },
        series: [
            { name: 'Total', data: tots },
            { name: 'Completed', data: comps }
        ],
        xaxis: {
            categories: names,
            labels: { rotate: -35 }
        },
        colors: ['#6045e2', '#10b981'],
        legend: { position: 'top' },
        plotOptions: { bar: { columnWidth: '58%' } },
        dataLabels: { enabled: false },
        grid: { borderColor: '#e2e8f0' }
    };

    if (chartCustomers) { chartCustomers.destroy(); }
    chartCustomers = new ApexCharts(document.querySelector('#chart_customers'), opts);
    chartCustomers.render();
}

/* ============================================================
   RENDER: Equipment Type (Donut)
   ============================================================ */
function renderEquipmentChart(data) {
    var labels = data.labels || [];
    var series = data.data   || [];

    if (!labels.length || !series.length) {
        if (chartEquipment) { chartEquipment.destroy(); chartEquipment = null; }
        renderNoData('#chart_equipment', 'No equipment data available');
        return;
    }

    $('#chart_equipment').empty();

    var opts = {
        chart: { type: 'donut', height: 320 },
        series: series,
        labels: labels,
        legend: { position: 'bottom' },
        colors: ['#6045e2','#10b981','#06b6d4','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#eab308'],
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '62%' } } }
    };

    if (chartEquipment) { chartEquipment.destroy(); }
    chartEquipment = new ApexCharts(document.querySelector('#chart_equipment'), opts);
    chartEquipment.render();
}

/* ============================================================
   RENDER: Certificate Distribution (Bar Chart)
   ============================================================ */
function renderCertificatesChart(data) {
    if (!data) return;
    var labels = data.labels || [];
    var series = data.data   || [];

    if (!labels.length || !series.length) {
        if (chartCertificates) { chartCertificates.destroy(); chartCertificates = null; }
        renderNoData('#chart_certificates', 'No certificate distribution data available');
        return;
    }

    $('#chart_certificates').empty();

    var opts = {
        chart: { 
            type: 'bar',
            height: 380,
            toolbar: { show: false }
        },
        series: [{
            name: 'Certificates',
            data: series
        }],
        xaxis: {
            categories: labels,
            labels: {
                rotate: -25
            }
        },
        plotOptions: {
            bar: {
                borderRadius: 6,
                distributed: true,
                columnWidth: '55%'
            }
        },
        dataLabels: { enabled: false },
        legend: { show: false },
        colors: ['#6045e2','#10b981','#06b6d4','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316'],
        grid: { borderColor: '#e2e8f0' }
    };

    if (chartCertificates) { chartCertificates.destroy(); }
    chartCertificates = new ApexCharts(document.querySelector('#chart_certificates'), opts);
    chartCertificates.render();
}

/* ============================================================
   UPDATE: Sticker Analytics
   ============================================================ */
function updateStickerAnalytics(sticker) {
    var tot  = sticker.total_stickers  || 0;
    var used = sticker.used_stickers   || 0;
    var pass = sticker.passed          || 0;
    var fail = sticker.failed          || 0;
    var rate = sticker.pass_rate       || 0;

    animNum('sk_total',  tot);
    animNum('sk_used',   used);
    animNum('sk_passed', pass);
    animNum('sk_failed', fail);

    animNum('sk_bottom_passed', pass);
    animNum('sk_bottom_failed', fail);
    $('#sk_bottom_rate').text(rate + '%');

    // Animate pass rate bar
    setTimeout(function() {
        $('#sk_pass_bar').css('width', Math.min(rate, 100) + '%');
        $('#sk_pass_label').text(rate + '%');
    }, 300);
}

/* ============================================================
   HELPER: Animated counter
   ============================================================ */
function animNum(id, target) {
    target = parseInt(target) || 0;
    var el = document.getElementById(id);
    if (!el) return;
    var current = 0, steps = 40, step = Math.ceil(target / steps);
    var timer = setInterval(function() {
        current = Math.min(current + step, target);
        el.textContent = current.toLocaleString();
        if (current >= target) clearInterval(timer);
    }, 20);
}
</script>
</body>
</html>
