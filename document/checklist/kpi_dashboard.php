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
<title>Checklist KPI Dashboard</title>
<link rel="stylesheet" href="<?php echo $url; ?>assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/icofont/icofont.min.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/themify-icons/themify-icons.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/et-lineicon/et-lineicons.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
<link rel="stylesheet" href="<?php echo $url; ?>assets/css/premium-nav.css">
<style>
:root {
    --kpi-ink: #172033;
    --kpi-muted: #667085;
    --kpi-line: rgba(148, 163, 184, 0.22);
    --kpi-panel: rgba(255, 255, 255, 0.82);
    --kpi-panel-strong: rgba(255, 255, 255, 0.94);
    --kpi-blue: #2563eb;
    --kpi-teal: #0f766e;
    --kpi-red: #dc2626;
    --kpi-shadow: 0 22px 60px rgba(15, 23, 42, 0.11);
}
body {
    background:
        radial-gradient(circle at 12% 8%, rgba(37, 99, 235, 0.13), transparent 32%),
        radial-gradient(circle at 92% 18%, rgba(20, 184, 166, 0.12), transparent 30%),
        linear-gradient(180deg, #f7fbff 0%, #eef4f9 48%, #f8fafc 100%);
    color: var(--kpi-ink);
}
.main-content { background: transparent; }
.checklist-kpi-page {
    position: relative;
    max-width: 1680px;
    margin: 0 auto;
    padding: 28px;
}
.checklist-kpi-page:before,
.checklist-kpi-page:after {
    content: "";
    position: fixed;
    width: 320px;
    height: 320px;
    border-radius: 999px;
    filter: blur(8px);
    pointer-events: none;
    z-index: -1;
}
.checklist-kpi-page:before {
    top: 120px;
    right: 5%;
    background: rgba(37, 99, 235, 0.08);
}
.checklist-kpi-page:after {
    left: 16%;
    bottom: 8%;
    background: rgba(15, 118, 110, 0.09);
}
.hero-card,
.filter-card,
.metric-card,
.chart-card {
    border: 1px solid var(--kpi-line);
    border-radius: 18px;
    background: linear-gradient(135deg, var(--kpi-panel-strong), var(--kpi-panel));
    box-shadow: var(--kpi-shadow);
    backdrop-filter: blur(18px);
    -webkit-backdrop-filter: blur(18px);
}
.hero-card {
    position: relative;
    overflow: hidden;
    min-height: 178px;
    padding: 30px 32px;
    margin-bottom: 24px;
    color: #fff;
    background:
        linear-gradient(135deg, rgba(15, 23, 42, 0.96), rgba(29, 78, 216, 0.9) 55%, rgba(15, 118, 110, 0.86)),
        linear-gradient(135deg, #0f172a, #1d4ed8);
}
.hero-card:before {
    content: "";
    position: absolute;
    inset: 0;
    background:
        linear-gradient(90deg, rgba(255,255,255,0.11) 1px, transparent 1px),
        linear-gradient(180deg, rgba(255,255,255,0.08) 1px, transparent 1px);
    background-size: 46px 46px;
    opacity: .55;
}
.hero-card:after {
    content: "";
    position: absolute;
    right: -82px;
    top: -100px;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    border: 46px solid rgba(255, 255, 255, 0.1);
}
.hero-title,
.hero-sub {
    position: relative;
    z-index: 1;
}
.hero-title {
    margin: 0;
    color: #fff;
    font-size: clamp(26px, 2.2vw, 40px);
    line-height: 1.12;
    font-weight: 900;
    letter-spacing: 0;
}
.hero-sub {
    margin-top: 12px;
    color: rgba(255,255,255,0.82);
    max-width: 780px;
    font-size: 15px;
    line-height: 1.65;
}
.filter-card {
    padding: 24px;
    margin-bottom: 24px;
}
.filter-title {
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0 0 18px;
    font-size: 17px;
    font-weight: 900;
    color: var(--kpi-ink);
}
.filter-title:before {
    content: "";
    width: 10px;
    height: 28px;
    border-radius: 999px;
    background: linear-gradient(180deg, var(--kpi-blue), #14b8a6);
    box-shadow: 0 8px 18px rgba(37, 99, 235, .24);
}
.filter-grid {
    display: grid;
    grid-template-columns: repeat(6, minmax(0, 1fr));
    gap: 16px;
    align-items: end;
}
.filter-field { min-width: 0; }
.filter-field label {
    display: block;
    margin-bottom: 7px;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .03em;
    color: #475467;
}
.filter-field select,
.filter-field input {
    width: 100%;
    height: 44px;
    border: 1px solid rgba(148, 163, 184, 0.34);
    border-radius: 10px;
    padding: 0 12px;
    background: rgba(248, 250, 252, 0.86);
    color: var(--kpi-ink);
    font-weight: 650;
    outline: none;
    transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}
.filter-field select:focus,
.filter-field input:focus {
    border-color: rgba(37, 99, 235, .58);
    background: #fff;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, .11);
}
.filter-actions {
    display: flex;
    gap: 10px;
    align-items: end;
}
.btn-apply,
.btn-reset {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 86px;
    height: 44px;
    border: 1px solid transparent;
    border-radius: 10px;
    padding: 0 18px;
    font-weight: 800;
    cursor: pointer;
    transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
}
.btn-apply {
    background: linear-gradient(135deg, #2563eb, #14b8a6);
    color: #fff;
    box-shadow: 0 16px 32px rgba(37, 99, 235, .22);
}
.btn-reset {
    border-color: rgba(148, 163, 184, 0.32);
    background: rgba(255,255,255,.82);
    color: #344054;
}
.btn-apply:hover,
.btn-reset:hover { transform: translateY(-1px); }
.metric-grid {
    display: grid;
    grid-template-columns: repeat(4, minmax(0, 1fr));
    gap: 18px;
    margin-bottom: 24px;
}
.metric-card {
    position: relative;
    overflow: hidden;
    min-height: 154px;
    padding: 22px;
    transition: transform .2s ease, box-shadow .2s ease;
}
.metric-card:hover,
.chart-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
}
.metric-card:before {
    content: "";
    position: absolute;
    top: 18px;
    right: 18px;
    width: 46px;
    height: 46px;
    border-radius: 14px;
    opacity: .16;
}
.metric-card.blue:before { background: var(--kpi-blue); }
.metric-card.green:before { background: #16a34a; }
.metric-card.orange:before { background: #f59e0b; }
.metric-card.red:before { background: var(--kpi-red); }
.metric-card.blue { border-left: 4px solid var(--kpi-blue); }
.metric-card.green { border-left: 4px solid #16a34a; }
.metric-card.orange { border-left: 4px solid #f59e0b; }
.metric-card.red { border-left: 4px solid var(--kpi-red); }
.metric-icon {
    position: absolute;
    top: 18px;
    right: 18px;
    width: 46px;
    height: 46px;
    border-radius: 14px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    z-index: 1;
}
.metric-card.blue .metric-icon {
    color: var(--kpi-blue);
    background: rgba(37, 99, 235, .1);
}
.metric-card.green .metric-icon {
    color: #16a34a;
    background: rgba(22, 163, 74, .1);
}
.metric-card.orange .metric-icon {
    color: #f59e0b;
    background: rgba(245, 158, 11, .12);
}
.metric-card.red .metric-icon {
    color: var(--kpi-red);
    background: rgba(220, 38, 38, .1);
}
.metric-label {
    position: relative;
    font-size: 11px;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: var(--kpi-muted);
}
.metric-value {
    position: relative;
    margin-top: 12px;
    font-size: clamp(28px, 2.3vw, 38px);
    font-weight: 900;
    line-height: 1;
    color: var(--kpi-ink);
}
.metric-sub {
    position: relative;
    margin-top: 12px;
    max-width: 260px;
    font-size: 13px;
    line-height: 1.45;
    color: var(--kpi-muted);
}
.chart-grid {
    display: grid;
    grid-template-columns: minmax(0, 1.2fr) minmax(0, 1fr);
    gap: 22px;
    margin-bottom: 22px;
}
.chart-grid.equal {
    grid-template-columns: repeat(2, minmax(0, 1fr));
}
.chart-grid.equal .chart-card:only-child {
    grid-column: 1 / -1;
}
.chart-card {
    min-width: 0;
    padding: 24px;
    transition: transform .2s ease, box-shadow .2s ease;
}
.chart-card h4 {
    margin: 0 0 16px;
    font-size: 17px;
    font-weight: 900;
    color: var(--kpi-ink);
}
.chart-wrap {
    position: relative;
    height: 330px;
}
.chart-note {
    margin-top: 14px;
    padding-top: 12px;
    border-top: 1px solid rgba(148, 163, 184, .18);
    font-size: 12px;
    line-height: 1.5;
    color: var(--kpi-muted);
}
/* ── Premium loading overlay ────────────────────────────── */
.kpi-loading-overlay {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(15,23,42,.55);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 9999;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}
.kpi-loading-overlay.active { display: flex; }

/* Glass card */
.kpi-loader-card {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.18);
    border-radius: 20px;
    padding: 36px 44px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 22px;
    position: relative;
    overflow: hidden;
    animation: kpi-fadeUp .4s ease-out;
}
/* shimmer sweep */
.kpi-loader-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(105deg, transparent 40%, rgba(255,255,255,.15) 50%, transparent 60%);
    animation: kpi-shimmer 2s ease-in-out infinite;
}
@keyframes kpi-shimmer {
    0%   { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
@keyframes kpi-fadeUp {
    from { opacity: 0; transform: translateY(18px); }
    to   { opacity: 1; transform: translateY(0); }
}

/* Animated bars */
.kpi-bars {
    display: flex;
    align-items: flex-end;
    gap: 6px;
    height: 38px;
}
.kpi-bars span {
    width: 6px;
    border-radius: 3px;
    background: linear-gradient(180deg, #60a5fa, #2563eb);
    animation: kpi-bar-pulse 1s ease-in-out infinite alternate;
}
.kpi-bars span:nth-child(1) { height: 60%; animation-delay: 0s; }
.kpi-bars span:nth-child(2) { height: 100%; animation-delay: .15s; }
.kpi-bars span:nth-child(3) { height: 40%; animation-delay: .3s; }
.kpi-bars span:nth-child(4) { height: 80%; animation-delay: .45s; }
.kpi-bars span:nth-child(5) { height: 55%; animation-delay: .6s; }
@keyframes kpi-bar-pulse {
    0%   { transform: scaleY(1); opacity: .6; }
    100% { transform: scaleY(1.5); opacity: 1; }
}

/* Status text */
.kpi-loader-text {
    font-size: 14px;
    font-weight: 600;
    color: rgba(255,255,255,.9);
    letter-spacing: .03em;
}
.kpi-loader-sub {
    font-size: 12px;
    color: rgba(255,255,255,.55);
}
.btn-apply:disabled { opacity: .6; cursor: not-allowed; }
@media (max-width: 1200px) {
    .filter-grid { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .metric-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
}
@media (max-width: 768px) {
    .checklist-kpi-page { padding: 14px; }
    .hero-card,
    .filter-card,
    .chart-card {
        border-radius: 16px;
    }
    .hero-card {
        min-height: auto;
        padding: 24px 20px;
        margin-bottom: 18px;
    }
    .filter-card,
    .chart-card {
        padding: 18px;
    }
    .filter-grid,
    .metric-grid,
    .chart-grid,
    .chart-grid.equal { grid-template-columns: 1fr; }
    .filter-actions {
        width: 100%;
    }
    .btn-apply,
    .btn-reset {
        flex: 1;
    }
    .metric-grid {
        gap: 14px;
        margin-bottom: 18px;
    }
    .metric-card {
        min-height: auto;
        padding: 20px;
    }
    .chart-grid {
        gap: 18px;
        margin-bottom: 18px;
    }
    .chart-wrap { height: 280px; }
}
@media (max-width: 480px) {
    .checklist-kpi-page { padding: 10px; }
    .hero-title { font-size: 24px; }
    .hero-sub { font-size: 14px; }
    .filter-actions {
        flex-direction: column;
    }
    .btn-apply,
    .btn-reset {
        width: 100%;
    }
    .chart-wrap { height: 250px; }
    .kpi-loader-card {
        width: calc(100% - 32px);
        padding: 28px 24px;
    }
}
</style>
</head>
<body>
<?php include_once('../../inc/nav.php'); ?>

<div class="main-content">
    <div class="checklist-kpi-page">
        <div class="hero-card">
            <h1 class="hero-title" style="color: #fff;">Checklist KPI Dashboard</h1>
            <div class="hero-sub">Track checklist performance with cleaner filters, status breakdowns, and chart-based visibility across inspectors, clients, checklist types, and monthly volume.</div>
        </div>

        <div class="filter-card">
            <h3 class="filter-title"><i class="icofont-filter"></i> Filter Checklist KPI</h3>
            <div class="filter-grid">
                <div class="filter-field">
                    <label>Inspector</label>
                    <select id="filter-inspector"><option value="">All Inspectors</option></select>
                </div>
                <div class="filter-field">
                    <label>Client</label>
                    <select id="filter-client"><option value="">All Clients</option></select>
                </div>
                <div class="filter-field">
                    <label>Checklist Type</label>
                    <select id="filter-type"><option value="">All Types</option></select>
                </div>
                <div class="filter-field">
                    <label>From Date</label>
                    <input type="date" id="filter-date-from">
                </div>
                <div class="filter-field">
                    <label>To Date</label>
                    <input type="date" id="filter-date-to">
                </div>
                <div class="filter-field">
                    <label>Year</label>
                    <select id="filter-year"><option value="">All Years</option></select>
                </div>
                <div class="filter-field">
                    <label>Expiry Date</label>
                    <input type="date" id="filter-expiry">
                </div>
                <div class="filter-actions">
                    <button class="btn-apply" id="apply-filters">Apply</button>
                    <button class="btn-reset" id="reset-filters">Reset</button>
                </div>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card blue">
                <div class="metric-icon"><i class="icofont-listing-box"></i></div>
                <div class="metric-label">Total Checklists</div>
                <div class="metric-value" id="metric-total">0</div>
                <div class="metric-sub">Overall checklist count in the selected range</div>
            </div>
            <div class="metric-card green">
                <div class="metric-icon"><i class="icofont-check-circled"></i></div>
                <div class="metric-label">Completed</div>
                <div class="metric-value" id="metric-completed">0</div>
                <div class="metric-sub">Checklists linked to completed projects</div>
            </div>
            <div class="metric-card orange">
                <div class="metric-icon"><i class="icofont-clock-time"></i></div>
                <div class="metric-label">Pending</div>
                <div class="metric-value" id="metric-pending">0</div>
                <div class="metric-sub">Open checklist workload still pending</div>
            </div>
            <div class="metric-card red">
                <div class="metric-icon"><i class="icofont-warning-alt"></i></div>
                <div class="metric-label">Expired</div>
                <div class="metric-value" id="metric-expired">0</div>
                <div class="metric-sub">Checklists with expired next inspection date</div>
            </div>
        </div>

        <div class="metric-grid">
            <div class="metric-card blue">
                <div class="metric-icon"><i class="icofont-ui-check"></i></div>
                <div class="metric-label">Active</div>
                <div class="metric-value" id="metric-active">0</div>
                <div class="metric-sub">Still valid and active within current period</div>
            </div>
            <div class="metric-card green">
                <div class="metric-icon"><i class="icofont-users-alt-4"></i></div>
                <div class="metric-label">Clients</div>
                <div class="metric-value" id="metric-clients">0</div>
                <div class="metric-sub">Distinct clients covered by filtered results</div>
            </div>
            <div class="metric-card orange">
                <div class="metric-icon"><i class="icofont-worker"></i></div>
                <div class="metric-label">Inspectors</div>
                <div class="metric-value" id="metric-inspectors">0</div>
                <div class="metric-sub">Inspectors represented in filtered checklist data</div>
            </div>
            <div class="metric-card red">
                <div class="metric-icon"><i class="icofont-site-map"></i></div>
                <div class="metric-label">Checklist Types</div>
                <div class="metric-value" id="metric-types">0</div>
                <div class="metric-sub">Different checklist categories in the result set</div>
            </div>
        </div>

        <div class="chart-grid">
            <div class="chart-card">
                <h4>Monthly Checklist Volume</h4>
                <div class="chart-wrap"><canvas id="monthlyChecklistChart"></canvas></div>
                <div class="chart-note">Checklist creation volume over time for the active filters.</div>
            </div>
            <div class="chart-card">
                <h4>Status Breakdown</h4>
                <div class="chart-wrap"><canvas id="statusChecklistChart"></canvas></div>
                <div class="chart-note">Pie chart view of completed, pending, active, and expired checklist states.</div>
            </div>
        </div>

        <div class="chart-grid equal">
            <div class="chart-card">
                <h4>Top Inspectors</h4>
                <div class="chart-wrap"><canvas id="inspectorChecklistChart"></canvas></div>
                <div class="chart-note">Bar chart showing which inspectors handled the most checklists.</div>
            </div>
            <div class="chart-card">
                <h4>Top Clients</h4>
                <div class="chart-wrap"><canvas id="clientChecklistChart"></canvas></div>
                <div class="chart-note">Bar chart showing checklist volume by client.</div>
            </div>
        </div>

        <div class="chart-grid equal">
            <div class="chart-card">
                <h4>Checklist Type Mix</h4>
                <div class="chart-wrap"><canvas id="typeChecklistChart"></canvas></div>
                <div class="chart-note">Pie-style breakdown of checklist types in the filtered result set.</div>
            </div>
        </div>
    </div>
</div>

<div class="kpi-loading-overlay" id="kpi-loading">
    <div class="kpi-loader-card">
        <div class="kpi-bars"><span></span><span></span><span></span><span></span><span></span></div>
        <div class="kpi-loader-text">Crunching your data...</div>
        <div class="kpi-loader-sub">Applying filters &amp; building charts</div>
    </div>
</div>

<?php include_once('../../inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="../../assets/plugins/chartjs/Chart.min.js"></script>
<script>
var monthlyChecklistChart;
var statusChecklistChart;
var inspectorChecklistChart;
var clientChecklistChart;
var typeChecklistChart;
var _kpiXhr = null;          // track in-flight AJAX so we can abort
var _kpiDebounce = null;     // debounce timer

/* ── helpers ──────────────────────────────────────────────── */
function showKpiLoading() {
    document.getElementById('kpi-loading').classList.add('active');
    document.getElementById('apply-filters').disabled = true;
}
function hideKpiLoading() {
    document.getElementById('kpi-loading').classList.remove('active');
    document.getElementById('apply-filters').disabled = false;
}

function getChecklistFilters() {
    return {
        filter_inspector: $('#filter-inspector').val(),
        filter_client:    $('#filter-client').val(),
        filter_type:      $('#filter-type').val(),
        filter_date_from: $('#filter-date-from').val(),
        filter_date_to:   $('#filter-date-to').val(),
        filter_year:      $('#filter-year').val(),
        filter_expiry:    $('#filter-expiry').val()
    };
}

function animateMetric(id, value) {
    value = parseInt(value, 10) || 0;
    var el = document.getElementById(id);
    if (!el) return;
    var current = 0;
    var step = Math.max(1, Math.ceil(value / 30));
    var timer = setInterval(function() {
        current = Math.min(current + step, value);
        el.textContent = current.toLocaleString();
        if (current >= value) clearInterval(timer);
    }, 20);
}

/* ── load filter dropdowns ────────────────────────────────── */
function loadChecklistFilterOptions() {
    $.ajax({
        url:      'fetch_checklist_filter_options.php',
        type:     'GET',
        dataType: 'json',
        cache:    false,
        success: function(res) {
            if (!res) return;
            (res.inspectors || []).forEach(function(item) {
                $('#filter-inspector').append('<option value="' + $('<span>').text(item).html() + '">' + $('<span>').text(item).html() + '</option>');
            });
            (res.clients || []).forEach(function(item) {
                $('#filter-client').append('<option value="' + $('<span>').text(item).html() + '">' + $('<span>').text(item).html() + '</option>');
            });
            (res.years || []).forEach(function(item) {
                $('#filter-year').append('<option value="' + item + '">' + item + '</option>');
            });
            (res.types || []).forEach(function(item) {
                $('#filter-type').append('<option value="' + $('<span>').text(item).html() + '">' + $('<span>').text(item).html() + '</option>');
            });
        },
        error: function() {
            console.error('Failed to load filter options');
        }
    });
}

/* ── load dashboard data ──────────────────────────────────── */
function loadChecklistDashboard() {
    // Abort any in-flight request
    if (_kpiXhr && _kpiXhr.readyState !== 4) _kpiXhr.abort();

    showKpiLoading();

    _kpiXhr = $.ajax({
        url:      'fetch_checklist_dashboard_data.php',
        type:     'GET',
        data:     getChecklistFilters(),
        dataType: 'json',
        cache:    false,
        success: function(res) {
            if (!res || res.error) {
                console.error('Dashboard API error:', res && res.error ? res.error : 'empty response');
                hideKpiLoading();
                return;
            }
            updateSummary(res.summary || {});
            renderMonthlyChart(res.monthly_chart || {});
            renderStatusChart(res.status_chart || {});
            renderInspectorChart(res.inspector_chart || {});
            renderClientChart(res.client_chart || {});
            renderTypeChart(res.type_chart || {});
            hideKpiLoading();
        },
        error: function(xhr, status) {
            if (status === 'abort') return;   // intentional abort
            console.error('Dashboard request failed:', status);
            hideKpiLoading();
        }
    });
}

/* ── debounced version for auto-trigger filters ───────────── */
function loadChecklistDashboardDebounced() {
    clearTimeout(_kpiDebounce);
    _kpiDebounce = setTimeout(loadChecklistDashboard, 300);
}

/* ── render helpers ───────────────────────────────────────── */
function updateSummary(summary) {
    animateMetric('metric-total',      summary.total || 0);
    animateMetric('metric-completed',  summary.completed || 0);
    animateMetric('metric-pending',    summary.pending || 0);
    animateMetric('metric-active',     summary.active || 0);
    animateMetric('metric-expired',    summary.expired || 0);
    animateMetric('metric-clients',    summary.clients || 0);
    animateMetric('metric-inspectors', summary.inspectors || 0);
    animateMetric('metric-types',      summary.checklist_types || 0);
}

function renderMonthlyChart(data) {
    var ctx = document.getElementById('monthlyChecklistChart').getContext('2d');
    if (monthlyChecklistChart) monthlyChecklistChart.destroy();
    monthlyChecklistChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels || [],
            datasets: [{
                label: 'Checklists',
                data: data.values || [],
                backgroundColor: '#2563eb',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{ gridLines: { display: false } }],
                yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
            }
        }
    });
}

function renderStatusChart(data) {
    var ctx = document.getElementById('statusChecklistChart').getContext('2d');
    if (statusChecklistChart) statusChecklistChart.destroy();
    statusChecklistChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: data.labels || [],
            datasets: [{
                data: data.values || [],
                backgroundColor: ['#16a34a', '#f59e0b', '#0ea5e9', '#ef4444'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom' }
        }
    });
}

function renderInspectorChart(data) {
    var ctx = document.getElementById('inspectorChecklistChart').getContext('2d');
    if (inspectorChecklistChart) inspectorChecklistChart.destroy();
    inspectorChecklistChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels || [],
            datasets: [{
                label: 'Checklists',
                data: data.values || [],
                backgroundColor: '#14b8a6',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{ ticks: { maxRotation: 35, minRotation: 0 }, gridLines: { display: false } }],
                yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
            }
        }
    });
}

function renderClientChart(data) {
    var ctx = document.getElementById('clientChecklistChart').getContext('2d');
    if (clientChecklistChart) clientChecklistChart.destroy();
    clientChecklistChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels || [],
            datasets: [{
                label: 'Checklists',
                data: data.values || [],
                backgroundColor: '#7c3aed',
                borderRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { display: false },
            scales: {
                xAxes: [{ ticks: { maxRotation: 35, minRotation: 0 }, gridLines: { display: false } }],
                yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }]
            }
        }
    });
}

function renderTypeChart(data) {
    var ctx = document.getElementById('typeChecklistChart').getContext('2d');
    if (typeChecklistChart) typeChecklistChart.destroy();
    typeChecklistChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: data.labels || [],
            datasets: [{
                data: data.values || [],
                backgroundColor: ['#2563eb', '#16a34a', '#f59e0b', '#ef4444', '#7c3aed', '#0ea5e9', '#14b8a6', '#ea580c', '#ec4899', '#94a3b8'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: { position: 'bottom' },
            cutoutPercentage: 55
        }
    });
}

/* ── event wiring ─────────────────────────────────────────── */
$(function() {
    loadChecklistFilterOptions();
    loadChecklistDashboard();

    $('#apply-filters').on('click', function() {
        loadChecklistDashboard();
    });

    $('#reset-filters').on('click', function() {
        $('#filter-inspector').val('');
        $('#filter-client').val('');
        $('#filter-type').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        $('#filter-year').val('');
        $('#filter-expiry').val('');
        loadChecklistDashboard();
    });

    // Debounced auto-apply on filter change
    $('#filter-inspector, #filter-client, #filter-type, #filter-year').on('change', function() {
        loadChecklistDashboardDebounced();
    });

    // Date inputs: fire on change (user picks a date)
    $('#filter-date-from, #filter-date-to, #filter-expiry').on('change', function() {
        loadChecklistDashboardDebounced();
    });
});
</script>
</body>
</html>
