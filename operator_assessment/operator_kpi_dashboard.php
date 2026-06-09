<?php
include_once('../inc/function.php');
include_once('../file/config.php');

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
<title>Operator Assessment - KPI Analytics</title>
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<link rel="stylesheet" href="../assets/css/premium-nav.css">
<style>
/* ============================================================
   OPERATOR ASSESSMENT KPI DASHBOARD
   ============================================================ */

.oa-kpi-main {
    padding: 24px;
    background: linear-gradient(135deg, #f0f4f8 0%, #e8edf5 100%);
    min-height: 100vh;
}

/* ── Page Header ── */
.oa-page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-radius: 16px;
    padding: 24px 28px;
    margin-bottom: 24px;
    box-shadow: 0 8px 32px rgba(30,41,59,.18);
    flex-wrap: wrap;
    gap: 16px;
}
.oa-page-header .hdr-left h4 {
    margin: 0;
    font-size: 20px;
    font-weight: 800;
    color: #fff;
    letter-spacing: -.3px;
}
.oa-page-header .hdr-left p {
    margin: 4px 0 0;
    font-size: 12px;
    color: #94a3b8;
}
.oa-page-header .hdr-left h4 i {
    background: linear-gradient(135deg, #818cf8, #6366f1);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-right: 10px;
}

/* ── Filter Bar ── */
.oa-filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.oa-filter-bar select,
.oa-filter-bar input[type="date"] {
    padding: 8px 12px;
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px;
    font-size: 13px;
    color: #e2e8f0;
    background: rgba(255,255,255,.08);
    backdrop-filter: blur(4px);
    min-width: 140px;
    transition: all .2s;
}
.oa-filter-bar select:focus,
.oa-filter-bar input[type="date"]:focus {
    outline: none;
    border-color: #818cf8;
    background: rgba(255,255,255,.12);
    box-shadow: 0 0 0 3px rgba(129,140,248,.15);
}
.oa-filter-bar select option { color: #1e293b; background: #fff; }

.btn-oa-filter {
    padding: 8px 22px;
    background: linear-gradient(135deg, #6366f1, #818cf8);
    color: #fff;
    border: none;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 700;
    cursor: pointer;
    transition: all .25s;
    box-shadow: 0 4px 14px rgba(99,102,241,.3);
}
.btn-oa-filter:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 20px rgba(99,102,241,.4);
}
.btn-oa-reset {
    padding: 8px 18px;
    background: rgba(255,255,255,.1);
    color: #e2e8f0;
    border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px;
    font-size: 13px;
    cursor: pointer;
    transition: all .2s;
}
.btn-oa-reset:hover { background: rgba(255,255,255,.18); }

/* ── KPI Cards Grid ── */
.oa-cards-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 18px;
    margin-bottom: 24px;
}
@media (max-width: 1200px) { .oa-cards-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px)  { .oa-cards-row { grid-template-columns: 1fr; } }

.oa-stat-card {
    background: #fff;
    border-radius: 14px;
    padding: 22px 20px;
    border-left: 4px solid;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    transition: all .3s cubic-bezier(.25,.8,.25,1);
    position: relative;
    overflow: hidden;
}
.oa-stat-card::after {
    content: '';
    position: absolute;
    top: 0; right: 0;
    width: 80px; height: 80px;
    border-radius: 0 0 0 80px;
    opacity: .05;
}
.oa-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,.1);
}

.oa-stat-card.c-indigo  { border-color: #6366f1; }
.oa-stat-card.c-indigo::after  { background: #6366f1; }
.oa-stat-card.c-emerald { border-color: #10b981; }
.oa-stat-card.c-emerald::after { background: #10b981; }
.oa-stat-card.c-amber   { border-color: #f59e0b; }
.oa-stat-card.c-amber::after   { background: #f59e0b; }
.oa-stat-card.c-rose     { border-color: #f43f5e; }
.oa-stat-card.c-rose::after     { background: #f43f5e; }
.oa-stat-card.c-sky     { border-color: #0ea5e9; }
.oa-stat-card.c-sky::after     { background: #0ea5e9; }
.oa-stat-card.c-violet  { border-color: #8b5cf6; }
.oa-stat-card.c-violet::after  { background: #8b5cf6; }
.oa-stat-card.c-teal    { border-color: #14b8a6; }
.oa-stat-card.c-teal::after    { background: #14b8a6; }
.oa-stat-card.c-orange  { border-color: #f97316; }
.oa-stat-card.c-orange::after  { background: #f97316; }

.osc-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .6px;
    color: #64748b;
    margin-bottom: 6px;
}
.osc-value {
    font-size: 28px;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
    margin: 6px 0;
}
.osc-sub {
    font-size: 11px;
    font-weight: 600;
    margin-top: 4px;
}
.osc-sub.txt-emerald { color: #10b981; }
.osc-sub.txt-amber   { color: #f59e0b; }
.osc-sub.txt-rose    { color: #f43f5e; }
.osc-sub.txt-indigo  { color: #6366f1; }
.osc-sub.txt-sky     { color: #0ea5e9; }

.osc-icon {
    width: 44px; height: 44px;
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 18px;
    flex-shrink: 0;
}
.osc-icon.bg-indigo  { background: linear-gradient(135deg, #eef2ff, #e0e7ff); color: #6366f1; }
.osc-icon.bg-emerald { background: linear-gradient(135deg, #ecfdf5, #d1fae5); color: #10b981; }
.osc-icon.bg-amber   { background: linear-gradient(135deg, #fffbeb, #fef3c7); color: #f59e0b; }
.osc-icon.bg-rose    { background: linear-gradient(135deg, #fff1f2, #ffe4e6); color: #f43f5e; }
.osc-icon.bg-sky     { background: linear-gradient(135deg, #f0f9ff, #e0f2fe); color: #0ea5e9; }
.osc-icon.bg-violet  { background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #8b5cf6; }
.osc-icon.bg-teal    { background: linear-gradient(135deg, #f0fdfa, #ccfbf1); color: #14b8a6; }
.osc-icon.bg-orange  { background: linear-gradient(135deg, #fff7ed, #ffedd5); color: #f97316; }

/* ── Filter Summary ── */
.oa-filter-summary {
    background: #fff;
    border-radius: 14px;
    padding: 20px 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    border: 1px solid #f1f5f9;
}
.oa-filter-summary h6 {
    font-size: 13px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.oa-filter-summary h6 i { color: #6366f1; }

.oa-summary-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 14px;
}
@media (max-width: 900px)  { .oa-summary-grid { grid-template-columns: repeat(3, 1fr); } }
@media (max-width: 576px)  { .oa-summary-grid { grid-template-columns: repeat(2, 1fr); } }

.oa-summary-item {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border-radius: 10px;
    padding: 14px;
    text-align: center;
    border: 1px solid #e2e8f0;
    transition: all .2s;
}
.oa-summary-item:hover { border-color: #cbd5e1; }
.oa-summary-item .sum-val {
    font-size: 15px;
    font-weight: 800;
    margin-bottom: 4px;
}
.oa-summary-item .sum-label {
    font-size: 10px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .4px;
    font-weight: 600;
}
.sum-val.c-indigo  { color: #6366f1; }
.sum-val.c-emerald { color: #10b981; }
.sum-val.c-amber   { color: #f59e0b; }
.sum-val.c-rose    { color: #f43f5e; }
.sum-val.c-sky     { color: #0ea5e9; }

/* ── Pass Rate Section ── */
.oa-pass-rates {
    background: #fff;
    border-radius: 14px;
    padding: 24px;
    margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    border: 1px solid #f1f5f9;
}
.oa-pass-rates h6 {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.oa-pass-rates h6 i { color: #10b981; }

.pass-rate-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
@media (max-width: 768px) { .pass-rate-row { grid-template-columns: 1fr; } }

.pr-item {
    text-align: center;
}
.pr-item .pr-label {
    font-size: 12px;
    font-weight: 600;
    color: #475569;
    margin-bottom: 10px;
}
.pr-bar-track {
    height: 28px;
    background: #f1f5f9;
    border-radius: 100px;
    overflow: hidden;
    position: relative;
}
.pr-bar-fill {
    height: 100%;
    border-radius: 100px;
    transition: width 1.2s cubic-bezier(.25,.8,.25,1);
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding-right: 12px;
    min-width: 40px;
}
.pr-bar-fill span {
    font-size: 11px;
    font-weight: 800;
    color: #fff;
    text-shadow: 0 1px 2px rgba(0,0,0,.2);
}
.pr-bar-fill.bg-completion { background: linear-gradient(90deg, #6366f1, #818cf8); }
.pr-bar-fill.bg-exam       { background: linear-gradient(90deg, #10b981, #34d399); }
.pr-bar-fill.bg-signals    { background: linear-gradient(90deg, #0ea5e9, #38bdf8); }

/* ── Chart Cards ── */
.oa-chart-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 22px;
    margin-bottom: 22px;
}
@media (max-width: 900px) { .oa-chart-row { grid-template-columns: 1fr; } }

.oa-chart-card {
    background: #fff;
    border-radius: 14px;
    padding: 24px 22px;
    box-shadow: 0 2px 10px rgba(0,0,0,.04);
    border: 1px solid #f1f5f9;
    position: relative;
    overflow: hidden;
    transition: all .3s;
}
.oa-chart-card:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,.08);
}
.oa-chart-card.full-width {
    grid-column: 1 / -1;
}
.occ-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}
.occ-header h6 {
    font-size: 14px;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
}
.occ-header h6 i {
    font-size: 14px;
    color: #6366f1;
}
.occ-footer {
    text-align: center;
    font-size: 11px;
    color: #94a3b8;
    margin-top: 10px;
    font-weight: 500;
}

/* ── Loading Overlay ── */
.oa-loader {
    position: fixed; inset: 0;
    background: rgba(255,255,255,.7);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
}
.oa-spinner {
    width: 48px; height: 48px;
    border: 4px solid #e2e8f0;
    border-top-color: #6366f1;
    border-radius: 50%;
    animation: oaSpin .7s linear infinite;
}
@keyframes oaSpin { to { transform: rotate(360deg); } }

/* ── No Data Message ── */
.oa-no-data {
    min-height: 240px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 13px;
    flex-direction: column;
    gap: 8px;
}
.oa-no-data i { font-size: 32px; opacity: .4; }

/* ── Responsive ── */
.oa-kpi-main {
    box-sizing: border-box;
    width: 100%;
    max-width: 100vw;
    overflow-x: hidden;
}

@media (max-width: 768px) {
    .oa-kpi-main { padding: 14px 12px; }
    .oa-page-header { padding: 18px; flex-direction: column; align-items: stretch; }
    .oa-filter-bar { display: grid; grid-template-columns: 1fr 1fr; width: 100%; gap: 8px; }
    .oa-filter-bar select, .oa-filter-bar input { width: 100%; box-sizing: border-box; }
    .osc-value { font-size: 22px; }
}
@media (max-width: 480px) {
    .oa-filter-bar { grid-template-columns: 1fr; }
    .oa-cards-row { grid-template-columns: 1fr; }
}
</style>
</head>
<body>

<?php include_once('../inc/nav.php'); ?>

<!-- Loading Overlay -->
<div class="oa-loader" id="oaLoader">
    <div class="oa-spinner"></div>
</div>

<div class="main-content">
<div class="oa-kpi-main">

    <!-- ═══════ PAGE HEADER + FILTER BAR ═══════ -->
    <div class="oa-page-header">
        <div class="hdr-left">
            <h4><i class="fas fa-user-hard-hat"></i>Operator Assessment KPI Analytics</h4>
            <p>Comprehensive Performance Metrics & Visual Intelligence</p>
        </div>
        <div class="oa-filter-bar">
            <input type="date" id="oa_date_from" title="From Date">
            <input type="date" id="oa_date_to"   title="To Date">
            <select id="oa_inspector">
                <option value="">All Inspectors</option>
            </select>
            <select id="oa_client">
                <option value="">All Clients</option>
            </select>
            <select id="oa_status">
                <option value="">All Statuses</option>
                <option value="PENDING">Pending</option>
                <option value="IN_PROGRESS">In Progress</option>
                <option value="COMPLETED">Completed</option>
            </select>
            <button class="btn-oa-filter" onclick="loadOAKPI()">
                <i class="fas fa-filter"></i> Filter
            </button>
            <button class="btn-oa-reset" onclick="resetOAFilters()">
                <i class="fas fa-undo"></i> Reset
            </button>
        </div>
    </div>

    <!-- ═══════ 8 KPI STAT CARDS ═══════ -->
    <div class="oa-cards-row">
        <!-- Total Assessments -->
        <div class="oa-stat-card c-indigo">
            <div>
                <div class="osc-label">Total Assessments</div>
                <div class="osc-value" id="oa_total">0</div>
                <div class="osc-sub txt-indigo">All operator assessments</div>
            </div>
            <div class="osc-icon bg-indigo"><i class="fas fa-clipboard-list"></i></div>
        </div>

        <!-- Completed -->
        <div class="oa-stat-card c-emerald">
            <div>
                <div class="osc-label">Completed</div>
                <div class="osc-value" id="oa_completed">0</div>
                <div class="osc-sub txt-emerald"><span id="oa_comp_pct">0</span>% of total</div>
            </div>
            <div class="osc-icon bg-emerald"><i class="fas fa-check-double"></i></div>
        </div>

        <!-- In Progress -->
        <div class="oa-stat-card c-amber">
            <div>
                <div class="osc-label">In Progress</div>
                <div class="osc-value" id="oa_in_progress">0</div>
                <div class="osc-sub txt-amber"><span id="oa_prog_pct">0</span>% of total</div>
            </div>
            <div class="osc-icon bg-amber"><i class="fas fa-spinner"></i></div>
        </div>

        <!-- Pending -->
        <div class="oa-stat-card c-rose">
            <div>
                <div class="osc-label">Pending</div>
                <div class="osc-value" id="oa_pending">0</div>
                <div class="osc-sub txt-rose"><span id="oa_pend_pct">0</span>% of total</div>
            </div>
            <div class="osc-icon bg-rose"><i class="fas fa-hourglass-half"></i></div>
        </div>

        <!-- Exam Passed -->
        <div class="oa-stat-card c-teal">
            <div>
                <div class="osc-label">Exams Passed</div>
                <div class="osc-value" id="oa_exam_passed">0</div>
                <div class="osc-sub txt-emerald">Written exam results</div>
            </div>
            <div class="osc-icon bg-teal"><i class="fas fa-file-signature"></i></div>
        </div>

        <!-- Exam Failed -->
        <div class="oa-stat-card c-orange">
            <div>
                <div class="osc-label">Exams Failed</div>
                <div class="osc-value" id="oa_exam_failed">0</div>
                <div class="osc-sub txt-rose">Written exam failures</div>
            </div>
            <div class="osc-icon bg-orange"><i class="fas fa-file-excel"></i></div>
        </div>

        <!-- Signals Passed -->
        <div class="oa-stat-card c-sky">
            <div>
                <div class="osc-label">Signals Passed</div>
                <div class="osc-value" id="oa_signals_passed">0</div>
                <div class="osc-sub txt-sky">Hand signals results</div>
            </div>
            <div class="osc-icon bg-sky"><i class="fas fa-hand-sparkles"></i></div>
        </div>

        <!-- Signals Failed -->
        <div class="oa-stat-card c-violet">
            <div>
                <div class="osc-label">Signals Failed</div>
                <div class="osc-value" id="oa_signals_failed">0</div>
                <div class="osc-sub txt-rose">Hand signals failures</div>
            </div>
            <div class="osc-icon bg-violet"><i class="fas fa-hand-paper"></i></div>
        </div>
    </div>

    <!-- ═══════ FILTER SUMMARY ═══════ -->
    <div class="oa-filter-summary">
        <h6><i class="fas fa-info-circle"></i> Active Filters</h6>
        <div class="oa-summary-grid">
            <div class="oa-summary-item">
                <div class="sum-val c-indigo" id="sum_date">All Time</div>
                <div class="sum-label">Date Range</div>
            </div>
            <div class="oa-summary-item">
                <div class="sum-val c-emerald" id="sum_inspector">All Inspectors</div>
                <div class="sum-label">Inspector</div>
            </div>
            <div class="oa-summary-item">
                <div class="sum-val c-amber" id="sum_client">All Clients</div>
                <div class="sum-label">Client</div>
            </div>
            <div class="oa-summary-item">
                <div class="sum-val c-sky" id="sum_status">All Statuses</div>
                <div class="sum-label">Status</div>
            </div>
            <div class="oa-summary-item">
                <div class="sum-val c-rose" id="sum_total">0</div>
                <div class="sum-label">Matching Records</div>
            </div>
        </div>
    </div>

    <!-- ═══════ PASS RATE BARS ═══════ -->
    <div class="oa-pass-rates">
        <h6><i class="fas fa-chart-bar"></i> Overall Pass Rates</h6>
        <div class="pass-rate-row">
            <div class="pr-item">
                <div class="pr-label">Completion Rate</div>
                <div class="pr-bar-track">
                    <div class="pr-bar-fill bg-completion" id="pr_completion" style="width:0%">
                        <span id="pr_completion_lbl">0%</span>
                    </div>
                </div>
            </div>
            <div class="pr-item">
                <div class="pr-label">Exam Pass Rate</div>
                <div class="pr-bar-track">
                    <div class="pr-bar-fill bg-exam" id="pr_exam" style="width:0%">
                        <span id="pr_exam_lbl">0%</span>
                    </div>
                </div>
            </div>
            <div class="pr-item">
                <div class="pr-label">Signals Pass Rate</div>
                <div class="pr-bar-track">
                    <div class="pr-bar-fill bg-signals" id="pr_signals" style="width:0%">
                        <span id="pr_signals_lbl">0%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ═══════ CHARTS ROW 1: Status + Monthly Trend ═══════ -->
    <div class="oa-chart-row">
        <div class="oa-chart-card">
            <div class="occ-header">
                <h6><i class="fas fa-chart-pie"></i> Assessment Status Distribution</h6>
            </div>
            <div id="chart_status" style="min-height:280px"></div>
            <div class="occ-footer">Pending · In Progress · Completed</div>
        </div>
        <div class="oa-chart-card">
            <div class="occ-header">
                <h6><i class="fas fa-chart-line"></i> Monthly Assessment Trend</h6>
            </div>
            <div id="chart_monthly" style="min-height:280px"></div>
            <div class="occ-footer">Last 12 months assessment volume</div>
        </div>
    </div>

    <!-- ═══════ CHARTS ROW 2: Exam vs Signals + Location ═══════ -->
    <div class="oa-chart-row">
        <div class="oa-chart-card">
            <div class="occ-header">
                <h6><i class="fas fa-columns"></i> Exam vs Signals Results</h6>
            </div>
            <div id="chart_exam_signals" style="min-height:280px"></div>
            <div class="occ-footer">Passed vs Failed comparison</div>
        </div>
        <div class="oa-chart-card">
            <div class="occ-header">
                <h6><i class="fas fa-map-marker-alt"></i> Location Distribution</h6>
            </div>
            <div id="chart_location" style="min-height:280px"></div>
            <div class="occ-footer">Onshore vs Offshore assessments</div>
        </div>
    </div>

    <!-- ═══════ CHARTS ROW 3: Top Clients + Top Inspectors ═══════ -->
    <div class="oa-chart-row">
        <div class="oa-chart-card">
            <div class="occ-header">
                <h6><i class="fas fa-building"></i> Top Clients by Volume</h6>
            </div>
            <div id="chart_clients" style="min-height:300px"></div>
            <div class="occ-footer">Top 10 clients by assessment count</div>
        </div>
        <div class="oa-chart-card">
            <div class="occ-header">
                <h6><i class="fas fa-user-tie"></i> Top Inspectors</h6>
            </div>
            <div id="chart_inspectors" style="min-height:300px"></div>
            <div class="occ-footer">Top 8 inspectors by assessment count</div>
        </div>
    </div>

    <!-- ═══════ CHARTS ROW 4: Pass Rate Trend + Client Pass/Fail ═══════ -->
    <div class="oa-chart-row">
        <div class="oa-chart-card">
            <div class="occ-header">
                <h6><i class="fas fa-chart-area"></i> Pass Rate Trend</h6>
            </div>
            <div id="chart_pass_trend" style="min-height:280px"></div>
            <div class="occ-footer">Exam & Signals pass rate over 12 months</div>
        </div>
        <div class="oa-chart-card">
            <div class="occ-header">
                <h6><i class="fas fa-layer-group"></i> Client Pass/Fail Breakdown</h6>
            </div>
            <div id="chart_client_pf" style="min-height:280px"></div>
            <div class="occ-footer">Top 8 clients: all-pass vs any-fail</div>
        </div>
    </div>

</div><!-- end oa-kpi-main -->
</div><!-- end main-content -->

<?php include_once('../inc/footer.php'); ?>

<script>
/* ============================================================
   OPERATOR ASSESSMENT KPI – CLIENT SCRIPT
   ============================================================ */
var oaRequest = null;
var chartStatus, chartMonthly, chartExamSignals, chartLocation;
var chartClients, chartInspectors, chartPassTrend, chartClientPF;
var isResetting = false;
var debounceTimer = null;

// ── Color Palette ──
var OA_COLORS = {
    indigo:  '#6366f1', emerald: '#10b981', amber: '#f59e0b',
    rose:    '#f43f5e', sky:     '#0ea5e9', violet: '#8b5cf6',
    teal:    '#14b8a6', orange:  '#f97316', slate:  '#64748b'
};

/* ══════════════════════════════════════════
   INIT
   ══════════════════════════════════════════ */
$(document).ready(function() {
    loadOAKPI();

    // Auto-filter on change (debounced to prevent multiple rapid calls)
    $('#oa_date_from, #oa_date_to, #oa_inspector, #oa_client, #oa_status').on('change', function() {
        if (isResetting) return; // Skip during reset
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function() {
            loadOAKPI();
        }, 300);
    });
});

function getOAParams() {
    return {
        date_from: ($('#oa_date_from').val() || '').trim(),
        date_to:   ($('#oa_date_to').val() || '').trim(),
        inspector: $('#oa_inspector').val() || '',
        client:    $('#oa_client').val() || '',
        status:    $('#oa_status').val() || '',
        _ts:       Date.now()
    };
}

function resetOAFilters() {
    isResetting = true;
    $('#oa_date_from').val('');
    $('#oa_date_to').val('');
    $('#oa_inspector').val('');
    $('#oa_client').val('');
    $('#oa_status').val('');
    isResetting = false;
    loadOAKPI();
}

/* ══════════════════════════════════════════
   MAIN DATA LOAD
   ══════════════════════════════════════════ */
function loadOAKPI() {
    var params = getOAParams();
    $('#oaLoader').css('display', 'flex');

    if (oaRequest && oaRequest.readyState !== 4) oaRequest.abort();

    oaRequest = $.ajax({
        url: 'fetch_operator_kpi_data.php',
        type: 'GET',
        dataType: 'json',
        cache: false,
        data: params
    }).done(function(data) {
        if (data.error) return;

        // Populate dropdowns (only first load)
        populateDropdowns(data);

        // KPI Cards
        updateOACards(data.kpi || {});

        // Filter Summary
        updateOASummary(data.filter_summary || {});

        // Pass Rate Bars
        updatePassRateBars(data.kpi || {});

        // Charts
        renderStatusChart(data.status_distribution || {});
        renderMonthlyChart(data.monthly_trend || {});
        renderExamSignalsChart(data.exam_vs_signals || {});
        renderLocationChart(data.location_distribution || {});
        renderClientsChart(data.top_clients || {});
        renderInspectorsChart(data.top_inspectors || {});
        renderPassTrendChart(data.pass_rate_trend || {});
        renderClientPFChart(data.client_pass_fail || {});

    }).always(function() {
        $('#oaLoader').hide();
    });
}

/* ══════════════════════════════════════════
   POPULATE DROPDOWNS
   ══════════════════════════════════════════ */
function populateDropdowns(data) {
    // Inspectors
    if (data.inspectors_list) {
        var sel = $('#oa_inspector');
        var curVal = sel.val();
        sel.find('option:not(:first)').remove();
        data.inspectors_list.forEach(function(n) {
            sel.append('<option value="' + n + '">' + n + '</option>');
        });
        if (curVal) sel.val(curVal);
    }
    // Clients
    if (data.clients_list) {
        var csel = $('#oa_client');
        var curC = csel.val();
        csel.find('option:not(:first)').remove();
        data.clients_list.forEach(function(c) {
            csel.append('<option value="' + c.id + '">' + c.name + '</option>');
        });
        if (curC) csel.val(curC);
    }
}

/* ══════════════════════════════════════════
   ANIMATED NUMBER
   ══════════════════════════════════════════ */
function animNum(id, target) {
    var el = document.getElementById(id);
    if (!el) return;
    var start = parseInt(el.textContent) || 0;
    if (start === target) return;
    var duration = 600;
    var startTime = null;
    function step(ts) {
        if (!startTime) startTime = ts;
        var pct = Math.min((ts - startTime) / duration, 1);
        el.textContent = Math.floor(start + (target - start) * pct);
        if (pct < 1) requestAnimationFrame(step);
        else el.textContent = target;
    }
    requestAnimationFrame(step);
}

/* ══════════════════════════════════════════
   UPDATE KPI CARDS
   ══════════════════════════════════════════ */
function updateOACards(kpi) {
    var total = kpi.total || 0;
    var comp  = kpi.completed || 0;
    var prog  = kpi.in_progress || 0;
    var pend  = kpi.pending || 0;

    animNum('oa_total', total);
    animNum('oa_completed', comp);
    animNum('oa_in_progress', prog);
    animNum('oa_pending', pend);
    animNum('oa_exam_passed', kpi.exam_passed || 0);
    animNum('oa_exam_failed', kpi.exam_failed || 0);
    animNum('oa_signals_passed', kpi.signals_passed || 0);
    animNum('oa_signals_failed', kpi.signals_failed || 0);

    $('#oa_comp_pct').text(total > 0 ? ((comp / total) * 100).toFixed(1) : 0);
    $('#oa_prog_pct').text(total > 0 ? ((prog / total) * 100).toFixed(1) : 0);
    $('#oa_pend_pct').text(total > 0 ? ((pend / total) * 100).toFixed(1) : 0);
}

/* ══════════════════════════════════════════
   UPDATE FILTER SUMMARY
   ══════════════════════════════════════════ */
function updateOASummary(fs) {
    $('#sum_date').text(fs.date_range || 'All Time');
    $('#sum_inspector').text(fs.inspector || 'All Inspectors');
    $('#sum_client').text(fs.client || 'All Clients');
    $('#sum_status').text(fs.status || 'All Statuses');
    $('#sum_total').text(fs.total || 0);
}

/* ══════════════════════════════════════════
   UPDATE PASS RATE BARS
   ══════════════════════════════════════════ */
function updatePassRateBars(kpi) {
    var cr = kpi.completion_rate || 0;
    var er = kpi.exam_pass_rate || 0;
    var sr = kpi.signals_pass_rate || 0;

    $('#pr_completion').css('width', Math.max(cr, 3) + '%');
    $('#pr_completion_lbl').text(cr + '%');
    $('#pr_exam').css('width', Math.max(er, 3) + '%');
    $('#pr_exam_lbl').text(er + '%');
    $('#pr_signals').css('width', Math.max(sr, 3) + '%');
    $('#pr_signals_lbl').text(sr + '%');
}

/* ══════════════════════════════════════════
   NO DATA HELPER
   ══════════════════════════════════════════ */
function renderNoData(sel, msg) {
    $(sel).html('<div class="oa-no-data"><i class="fas fa-chart-bar"></i><span>' + (msg || 'No data available') + '</span></div>');
}

/* ══════════════════════════════════════════
   SAFE CHART DESTROY (prevents removeChild errors)
   ══════════════════════════════════════════ */
function safeDestroy(chartRef, containerId) {
    if (chartRef) {
        try { chartRef.destroy(); } catch(e) { /* ignore */ }
    }
    if (containerId) {
        var el = document.querySelector(containerId);
        if (el) el.innerHTML = '';
    }
    return null;
}

/* ══════════════════════════════════════════
   CHART: Status Distribution (Donut)
   ══════════════════════════════════════════ */
function renderStatusChart(d) {
    chartStatus = safeDestroy(chartStatus, '#chart_status');
    var total = (d.data || []).reduce(function(a,b){return a+b;}, 0);
    if (total === 0) { renderNoData('#chart_status'); return; }

    chartStatus = new ApexCharts(document.querySelector('#chart_status'), {
        chart: { type: 'donut', height: 280, fontFamily: 'inherit' },
        series: d.data,
        labels: d.labels,
        colors: [OA_COLORS.amber, OA_COLORS.sky, OA_COLORS.emerald],
        plotOptions: {
            pie: {
                donut: {
                    size: '72%',
                    labels: {
                        show: true,
                        total: { show: true, label: 'Total', fontSize: '14px', fontWeight: 700, color: '#1e293b' }
                    }
                }
            }
        },
        dataLabels: { enabled: true, style: { fontSize: '13px', fontWeight: 700 } },
        legend: { position: 'bottom', fontSize: '12px', fontWeight: 600 },
        stroke: { width: 2, colors: ['#fff'] },
        tooltip: { y: { formatter: function(v) { return v + ' assessments'; } } }
    });
    chartStatus.render();
}

/* ══════════════════════════════════════════
   CHART: Monthly Trend (Area/Line)
   ══════════════════════════════════════════ */
function renderMonthlyChart(d) {
    chartMonthly = safeDestroy(chartMonthly, '#chart_monthly');
    if (!d.labels || !d.labels.length) { renderNoData('#chart_monthly'); return; }

    chartMonthly = new ApexCharts(document.querySelector('#chart_monthly'), {
        chart: { type: 'area', height: 280, fontFamily: 'inherit', toolbar: { show: false },
            dropShadow: { enabled: true, top: 4, left: 0, blur: 8, color: OA_COLORS.indigo, opacity: .15 }
        },
        series: [{ name: 'Assessments', data: d.data }],
        xaxis: { categories: d.labels, labels: { style: { fontSize: '11px', colors: '#64748b' } } },
        yaxis: { labels: { style: { fontSize: '11px', colors: '#64748b' } } },
        colors: [OA_COLORS.indigo],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: .45, opacityTo: .05, stops: [0, 95, 100] }
        },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { y: { formatter: function(v) { return v + ' assessments'; } } }
    });
    chartMonthly.render();
}

/* ══════════════════════════════════════════
   CHART: Exam vs Signals (Grouped Bar)
   ══════════════════════════════════════════ */
function renderExamSignalsChart(d) {
    chartExamSignals = safeDestroy(chartExamSignals, '#chart_exam_signals');
    if (!d.categories || !d.categories.length) { renderNoData('#chart_exam_signals'); return; }

    chartExamSignals = new ApexCharts(document.querySelector('#chart_exam_signals'), {
        chart: { type: 'bar', height: 280, fontFamily: 'inherit', toolbar: { show: false } },
        series: [
            { name: 'Passed', data: d.passed },
            { name: 'Failed', data: d.failed }
        ],
        xaxis: { categories: d.categories, labels: { style: { fontSize: '12px', fontWeight: 600 } } },
        colors: [OA_COLORS.emerald, OA_COLORS.rose],
        plotOptions: {
            bar: { columnWidth: '50%', borderRadius: 6, dataLabels: { position: 'top' } }
        },
        dataLabels: {
            enabled: true, offsetY: -20,
            style: { fontSize: '13px', fontWeight: 700, colors: ['#1e293b'] }
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        legend: { position: 'top', fontSize: '12px', fontWeight: 600 },
        tooltip: { y: { formatter: function(v) { return v + ' operators'; } } }
    });
    chartExamSignals.render();
}

/* ══════════════════════════════════════════
   CHART: Location Distribution (Pie)
   ══════════════════════════════════════════ */
function renderLocationChart(d) {
    chartLocation = safeDestroy(chartLocation, '#chart_location');
    var total = (d.data || []).reduce(function(a,b){return a+b;}, 0);
    if (total === 0) { renderNoData('#chart_location'); return; }

    chartLocation = new ApexCharts(document.querySelector('#chart_location'), {
        chart: { type: 'pie', height: 280, fontFamily: 'inherit' },
        series: d.data,
        labels: d.labels,
        colors: [OA_COLORS.teal, OA_COLORS.sky],
        dataLabels: {
            enabled: true,
            formatter: function(v, opts) {
                return opts.w.globals.labels[opts.seriesIndex] + ': ' + Math.round(v) + '%';
            },
            style: { fontSize: '12px', fontWeight: 700 }
        },
        legend: { position: 'bottom', fontSize: '12px', fontWeight: 600 },
        stroke: { width: 2, colors: ['#fff'] },
        tooltip: { y: { formatter: function(v) { return v + ' assessments'; } } }
    });
    chartLocation.render();
}

/* ══════════════════════════════════════════
   CHART: Top Clients (Horizontal Bar)
   ══════════════════════════════════════════ */
function renderClientsChart(d) {
    chartClients = safeDestroy(chartClients, '#chart_clients');
    if (!d.labels || !d.labels.length) { renderNoData('#chart_clients', 'No client data available'); return; }

    chartClients = new ApexCharts(document.querySelector('#chart_clients'), {
        chart: { type: 'bar', height: 300, fontFamily: 'inherit', toolbar: { show: false } },
        series: [{ name: 'Assessments', data: d.data }],
        xaxis: { categories: d.labels, labels: { style: { fontSize: '11px' } } },
        colors: [OA_COLORS.violet],
        plotOptions: {
            bar: { horizontal: true, borderRadius: 6, barHeight: '55%',
                dataLabels: { position: 'center' }
            }
        },
        dataLabels: {
            enabled: true,
            style: { fontSize: '12px', fontWeight: 700, colors: ['#fff'] }
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { y: { formatter: function(v) { return v + ' assessments'; } } }
    });
    chartClients.render();
}

/* ══════════════════════════════════════════
   CHART: Top Inspectors (Bar)
   ══════════════════════════════════════════ */
function renderInspectorsChart(d) {
    chartInspectors = safeDestroy(chartInspectors, '#chart_inspectors');
    if (!d.labels || !d.labels.length) { renderNoData('#chart_inspectors', 'No inspector data available'); return; }

    chartInspectors = new ApexCharts(document.querySelector('#chart_inspectors'), {
        chart: { type: 'bar', height: 300, fontFamily: 'inherit', toolbar: { show: false } },
        series: [{ name: 'Assessments', data: d.data }],
        xaxis: { categories: d.labels, labels: { style: { fontSize: '11px' } } },
        colors: [OA_COLORS.indigo],
        plotOptions: {
            bar: { columnWidth: '55%', borderRadius: 6,
                dataLabels: { position: 'top' }
            }
        },
        dataLabels: {
            enabled: true, offsetY: -20,
            style: { fontSize: '12px', fontWeight: 700, colors: ['#1e293b'] }
        },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        tooltip: { y: { formatter: function(v) { return v + ' assessments'; } } }
    });
    chartInspectors.render();
}

/* ══════════════════════════════════════════
   CHART: Pass Rate Trend (Dual Line)
   ══════════════════════════════════════════ */
function renderPassTrendChart(d) {
    chartPassTrend = safeDestroy(chartPassTrend, '#chart_pass_trend');
    if (!d.labels || !d.labels.length) { renderNoData('#chart_pass_trend'); return; }

    chartPassTrend = new ApexCharts(document.querySelector('#chart_pass_trend'), {
        chart: { type: 'line', height: 280, fontFamily: 'inherit', toolbar: { show: false },
            dropShadow: { enabled: true, top: 3, left: 0, blur: 6, opacity: .12 }
        },
        series: [
            { name: 'Exam Pass Rate', data: d.exam_rates },
            { name: 'Signals Pass Rate', data: d.signal_rates }
        ],
        xaxis: { categories: d.labels, labels: { style: { fontSize: '10px', colors: '#64748b' } } },
        yaxis: {
            min: 0, max: 100,
            labels: { formatter: function(v) { return v + '%'; }, style: { fontSize: '11px', colors: '#64748b' } }
        },
        colors: [OA_COLORS.emerald, OA_COLORS.sky],
        stroke: { curve: 'smooth', width: 3 },
        markers: { size: 4, strokeWidth: 2, strokeColors: '#fff' },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        legend: { position: 'top', fontSize: '12px', fontWeight: 600 },
        tooltip: { y: { formatter: function(v) { return v + '%'; } } }
    });
    chartPassTrend.render();
}

/* ══════════════════════════════════════════
   CHART: Client Pass/Fail (Stacked Bar)
   ══════════════════════════════════════════ */
function renderClientPFChart(d) {
    chartClientPF = safeDestroy(chartClientPF, '#chart_client_pf');
    if (!d.labels || !d.labels.length) { renderNoData('#chart_client_pf', 'No client data available'); return; }

    chartClientPF = new ApexCharts(document.querySelector('#chart_client_pf'), {
        chart: { type: 'bar', height: 280, stacked: true, fontFamily: 'inherit', toolbar: { show: false } },
        series: [
            { name: 'All Passed', data: d.passed },
            { name: 'Any Failed', data: d.failed }
        ],
        xaxis: { categories: d.labels, labels: { style: { fontSize: '10px' }, rotate: -30 } },
        colors: [OA_COLORS.emerald, OA_COLORS.rose],
        plotOptions: {
            bar: { columnWidth: '55%', borderRadius: 4 }
        },
        dataLabels: { enabled: false },
        grid: { borderColor: '#f1f5f9', strokeDashArray: 4 },
        legend: { position: 'top', fontSize: '12px', fontWeight: 600 },
        tooltip: { y: { formatter: function(v) { return v + ' operators'; } } }
    });
    chartClientPF.render();
}
</script>

</body>
</html>
