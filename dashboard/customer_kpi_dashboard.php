<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once('../file/config.php');

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'customer') {
    header("Location: ../index.php");
    exit;
}

// Fetch customer details from database
$customer_name = $_SESSION['username'];
$sql = "SELECT * FROM customers WHERE customer_name = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $customer_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
    $cus_name = $customer['customer_name'];
    $profilePhoto = !empty($customer['profile_photo']) ? $customer['profile_photo'] : $url . 'assets/img/media/profile-pic.jpg';
    $signaturePhoto = !empty($customer['signature_photo']) ? $customer['signature_photo'] : '';
    $email = $customer['email'];
    $mobile = $customer['mobile'];
    $address = $customer['address'];
    $city = $customer['city'];
    $rep_name = $customer['rep_name'];
    $created_at = date('F j, Y', strtotime($customer['created_at']));
    $cus_id = $customer['cus_id'];
} else {
    echo "Customer not found";
    exit();
}

include_once('../inc/customer-option.php');
?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

/* Apply modern font to main-content3 container */
.main-content3 {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    background: #f8fafc;
    padding: 30px 0;
}

/* Card Styling */
.main-content3 .card {
    background: #ffffff;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    border-radius: 20px !important;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.03), 0 8px 10px -6px rgba(0, 0, 0, 0.03) !important;
    overflow: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    margin-bottom: 24px;
}

/* Cover Image Styling */
.main-content3 .cover-img {
    position: relative;
    border-radius: 20px 20px 0 0;
    overflow: hidden;
    height: 220px;
}
.main-content3 .cover-img img.w-100 {
    height: 100%;
    object-fit: cover;
    filter: brightness(0.95);
}

/* Upload Button Styling */
.main-content3 .upload-button {
    position: absolute;
    bottom: 16px;
    right: 16px;
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.5);
    padding: 8px 16px;
    border-radius: 12px;
    font-size: 13px;
    font-weight: 600;
    color: #1e293b;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}
.main-content3 .upload-button:hover {
    background: #ffffff;
    transform: translateY(-1px);
}
.main-content3 .upload-button input.file-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

/* User Profile Nav Card */
.main-content3 .user-profile-nav {
    padding: 24px;
    background: #ffffff;
    border-radius: 20px;
}

/* Profile Info */
.main-content3 .profile-info {
    position: relative;
}
.main-content3 .profile-pic {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    border: 3px solid #ffffff;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-top: 0px !important;
    background: #ffffff;
    z-index: 2;
    transition: transform 0.3s ease;
}
.main-content3 .profile-pic img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.main-content3 .profile-info h3 {
    font-size: 22px;
    font-weight: 700;
    color: #0f172a;
    margin: 0 !important;
}

/* Navigation Tabs */
.main-content3 .profile-nav-tabs {
    border-bottom: none !important;
    gap: 8px;
    display: flex;
    align-items: center;
}
.main-content3 .profile-nav-tabs li {
    margin: 0;
}
.main-content3 .profile-nav-tabs li .chat {
    background-color: transparent !important;
    width: auto !important;
    height: auto !important;
    display: inline-flex !important;
}
.main-content3 .profile-nav-tabs a {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    height: 42px !important;
    line-height: 1.2 !important;
    padding: 0 20px !important;
    border-radius: 12px !important;
    font-size: 14px;
    font-weight: 600;
    color: #64748b !important;
    transition: all 0.2s ease;
    border: none !important;
    background: transparent;
    text-decoration: none;
}
.main-content3 .profile-nav-tabs a:hover {
    color: #4f46e5 !important;
    background: rgba(79, 70, 229, 0.05) !important;
}
.main-content3 .profile-nav-tabs a.active, 
.main-content3 .profile-nav-tabs a.p_nav-link.active {
    color: #ffffff !important;
    background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
}

/* ============================================================
   CUSTOMER KPI DASHBOARD (Premium UI)
   ============================================================ */
.ckpi-main {
    padding: 0;
    background: transparent;
    margin-top: 30px;
}

/* ── Page Header ── */
.ckpi-page-header {
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
.ckpi-page-header .hdr-left h4 {
    margin: 0; font-size: 22px; font-weight: 800; color: #fff; letter-spacing: -.3px;
}
.ckpi-page-header .hdr-left p {
    margin: 4px 0 0; font-size: 13px; color: #94a3b8;
}
.ckpi-page-header .hdr-left h4 i {
    background: linear-gradient(135deg, #38bdf8, #0284c7);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    margin-right: 12px;
}

/* ── Filter Bar ── */
.ckpi-filter-bar {
    display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
}
.ckpi-filter-bar input[type="date"] {
    padding: 9px 14px;
    border: 1px solid rgba(255,255,255,.15); border-radius: 8px;
    font-size: 13px; color: #e2e8f0;
    background: rgba(255,255,255,.08); backdrop-filter: blur(4px);
    transition: all .2s;
}
.ckpi-filter-bar input[type="date"]:focus {
    outline: none; border-color: #38bdf8; background: rgba(255,255,255,.12);
}
.btn-ckpi-filter {
    padding: 9px 24px;
    background: linear-gradient(135deg, #0284c7, #38bdf8);
    color: #fff; border: none; border-radius: 8px; font-size: 13px; font-weight: 700;
    cursor: pointer; transition: all .25s; box-shadow: 0 4px 14px rgba(2,132,199,.3);
}
.btn-ckpi-filter:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(2,132,199,.4); }
.btn-ckpi-reset {
    padding: 9px 20px;
    background: rgba(255,255,255,.1); color: #e2e8f0; border: 1px solid rgba(255,255,255,.15);
    border-radius: 8px; font-size: 13px; cursor: pointer; transition: all .2s;
}
.btn-ckpi-reset:hover { background: rgba(255,255,255,.18); }

/* ── KPI Cards ── */
.ckpi-cards-row {
    display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px;
}
@media (max-width: 1200px) { .ckpi-cards-row { grid-template-columns: repeat(2, 1fr); } }
@media (max-width: 576px)  { .ckpi-cards-row { grid-template-columns: 1fr; } }

.ckpi-stat-card {
    background: #fff; border-radius: 16px; padding: 24px 22px;
    border-left: 5px solid; box-shadow: 0 4px 15px rgba(0,0,0,.04);
    display: flex; justify-content: space-between; align-items: flex-start;
    transition: all .3s cubic-bezier(.25,.8,.25,1); position: relative; overflow: hidden;
}
.ckpi-stat-card::after {
    content: ''; position: absolute; top: 0; right: 0; width: 90px; height: 90px;
    border-radius: 0 0 0 90px; opacity: .04;
}
.ckpi-stat-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,.08); }

.ckpi-stat-card.c-blue  { border-color: #3b82f6; }
.ckpi-stat-card.c-blue::after  { background: #3b82f6; }
.ckpi-stat-card.c-teal  { border-color: #14b8a6; }
.ckpi-stat-card.c-teal::after  { background: #14b8a6; }
.ckpi-stat-card.c-purple { border-color: #8b5cf6; }
.ckpi-stat-card.c-purple::after { background: #8b5cf6; }
.ckpi-stat-card.c-orange { border-color: #f97316; }
.ckpi-stat-card.c-orange::after { background: #f97316; }

.csc-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .8px; color: #64748b; margin-bottom: 8px; }
.csc-value { font-size: 32px; font-weight: 800; color: #1e293b; line-height: 1; margin: 6px 0; }

.csc-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;
}
.csc-icon.bg-blue   { background: linear-gradient(135deg, #eff6ff, #dbeafe); color: #3b82f6; }
.csc-icon.bg-teal   { background: linear-gradient(135deg, #f0fdfa, #ccfbf1); color: #14b8a6; }
.csc-icon.bg-purple { background: linear-gradient(135deg, #f5f3ff, #ede9fe); color: #8b5cf6; }
.csc-icon.bg-orange { background: linear-gradient(135deg, #fff7ed, #ffedd5); color: #f97316; }

/* ── Project Status Overview Summary ── */
.ckpi-project-summary {
    background: #fff; border-radius: 16px; padding: 20px 24px; margin-bottom: 24px;
    box-shadow: 0 2px 10px rgba(0,0,0,.04); border: 1px solid #f1f5f9;
}
.ckpi-summary-grid {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;
}
@media (max-width: 768px) { .ckpi-summary-grid { grid-template-columns: 1fr; } }

.cpi-sum-item {
    background: #f8fafc; border-radius: 12px; padding: 16px; text-align: center; border: 1px solid #e2e8f0;
}
.cpi-sum-val { font-size: 18px; font-weight: 800; margin-bottom: 4px; }
.cpi-sum-label { font-size: 11px; color: #64748b; text-transform: uppercase; letter-spacing: .5px; font-weight: 700; }
.cpi-sum-val.c-emerald { color: #10b981; }
.cpi-sum-val.c-amber   { color: #f59e0b; }
.cpi-sum-val.c-rose    { color: #f43f5e; }

/* ── Charts ── */
.ckpi-chart-row { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; margin-bottom: 24px; }
@media (max-width: 900px) { .ckpi-chart-row { grid-template-columns: 1fr; } }

.ckpi-chart-card {
    background: #fff; border-radius: 16px; padding: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,.03); border: 1px solid #f1f5f9; transition: all .3s;
}
.ckpi-chart-card:hover { box-shadow: 0 8px 25px rgba(0,0,0,.06); }
.ccc-header { display: flex; align-items: center; margin-bottom: 16px; gap: 10px; }
.ccc-header h6 { font-size: 15px; font-weight: 800; color: #1e293b; margin: 0; }
.ccc-header i { font-size: 16px; color: #3b82f6; }

/* ── Loader & No Data ── */
.ckpi-loader { position: fixed; inset: 0; background: rgba(255,255,255,.8); backdrop-filter: blur(5px); display: none; align-items: center; justify-content: center; z-index: 9999; }
.ckpi-spinner { width: 50px; height: 50px; border: 4px solid #e2e8f0; border-top-color: #3b82f6; border-radius: 50%; animation: ckpiSpin .8s linear infinite; }
@keyframes ckpiSpin { to { transform: rotate(360deg); } }
.ckpi-no-data { min-height: 250px; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; font-size: 14px; gap: 10px; }
.ckpi-no-data i { font-size: 36px; opacity: .3; }
</style>

<div class="main-content3">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card mx-2 mx-lg-4 mx-xl-5">
                    <!-- User Profile Image -->
                    <div class="user-profile-img">
                        <div class="cover-img">
                            <img src="<?php echo $url; ?>assets/img/media/cover.jpg" class="w-100" alt="">
                            <div class="upload-button">
                                <img src="<?php echo $url; ?>assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                <span>Upload Photo</span>
                                <input class="file-input" type="file" id="fileUpload3" accept="image/*">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mx-2 mx-lg-4 mx-xl-5">
                    <div class="card mt-1">
                        <!-- User Profile Nav -->
                        <div class="user-profile-nav d-flex justify-content-xl-between align-items-xl-center flex-column flex-xl-row">
                            <div class="profile-info d-flex align-items-center">
                                <div class="profile-pic mr-3">
                                    <img src="<?php echo $profilePhoto . '?v=' . time(); ?>" alt="Profile Picture">
                                </div>
                                <div>
                                    <h3><?php echo htmlspecialchars($cus_name); ?></h3>
                                    <p class="font-14">Customer</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-center mt-3 mt-xl-0">
                                <ul class="nav profile-nav-tabs">
                                    <li>
                                        <a class="pr-0 pl-2 pl-xl-30">
                                            <span class="chat">
                                                <img src="<?php echo $url; ?>assets/img/svg/chat-icon.svg" alt="" class="svg">
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="p_nav-link has-before" href="../dashboard/customer_new.php">About</a>
                                    </li>
                                    <li>
                                        <a class="p_nav-link has-before" href="../customer/project.php?cusid=<?php echo urlencode($customer['cus_id']); ?>">Projects</a>
                                    </li>
                                    <li>
                                        <a class="p_nav-link has-before" href="../customer/operator_assessments.php?cusid=<?php echo urlencode($customer['cus_id']); ?>">Operator assessments</a>
                                    </li>
                                    <li>
                                        <a class="active p_nav-link has-before" href="../dashboard/customer_kpi_dashboard.php">KPI Analytics</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    
                    <div class="ckpi-loader" id="ckpiLoader"><div class="ckpi-spinner"></div></div>

                    <div class="ckpi-main">

                        <!-- Header -->
                        <div class="ckpi-page-header">
                            <div class="hdr-left">
                                <h4><i class="fas fa-chart-line"></i>Your KPI Dashboard</h4>
                                <p>Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! Here are your consolidated metrics.</p>
                            </div>
                            <div class="ckpi-filter-bar">
                                <input type="date" id="ckpi_date_from" title="From Date">
                                <input type="date" id="ckpi_date_to"   title="To Date">
                                <button class="btn-ckpi-filter" onclick="loadCustomerKPI()">
                                    <i class="fas fa-filter"></i> Apply
                                </button>
                                <button class="btn-ckpi-reset" onclick="resetCustomerKPI()">
                                    <i class="fas fa-undo"></i> Reset
                                </button>
                            </div>
                        </div>

                        <!-- 4 Core Stat Cards -->
                        <div class="ckpi-cards-row">
                            <div class="ckpi-stat-card c-blue">
                                <div>
                                    <div class="csc-label">Total Projects</div>
                                    <div class="csc-value" id="cval_projects">0</div>
                                </div>
                                <div class="csc-icon bg-blue"><i class="fas fa-briefcase"></i></div>
                            </div>
                            <div class="ckpi-stat-card c-teal">
                                <div>
                                    <div class="csc-label">Total Checklists</div>
                                    <div class="csc-value" id="cval_checklists">0</div>
                                </div>
                                <div class="csc-icon bg-teal"><i class="fas fa-clipboard-check"></i></div>
                            </div>
                            <div class="ckpi-stat-card c-purple">
                                <div>
                                    <div class="csc-label">Certificates</div>
                                    <div class="csc-value" id="cval_certificates">0</div>
                                </div>
                                <div class="csc-icon bg-purple"><i class="fas fa-certificate"></i></div>
                            </div>
                            <div class="ckpi-stat-card c-orange">
                                <div>
                                    <div class="csc-label">Total Stickers</div>
                                    <div class="csc-value" id="cval_stickers">0</div>
                                </div>
                                <div class="csc-icon bg-orange"><i class="fas fa-tags"></i></div>
                            </div>
                        </div>

                        <!-- Project Breakdown Summary -->
                        <div class="ckpi-project-summary">
                            <div class="ccc-header" style="margin-bottom:12px;">
                                <i class="fas fa-tasks"></i><h6>Project Status Overview</h6>
                            </div>
                            <div class="ckpi-summary-grid">
                                <div class="cpi-sum-item">
                                    <div class="cpi-sum-val c-emerald" id="cval_comp">0</div>
                                    <div class="cpi-sum-label">Completed</div>
                                </div>
                                <div class="cpi-sum-item">
                                    <div class="cpi-sum-val c-amber" id="cval_inprog">0</div>
                                    <div class="cpi-sum-label">In Progress</div>
                                </div>
                                <div class="cpi-sum-item">
                                    <div class="cpi-sum-val c-rose" id="cval_pend">0</div>
                                    <div class="cpi-sum-label">Pending</div>
                                </div>
                            </div>
                        </div>

                        <!-- Charts Row 1 -->
                        <div class="ckpi-chart-row">
                            <div class="ckpi-chart-card">
                                <div class="ccc-header"><i class="fas fa-chart-pie"></i><h6>Project Status Distribution</h6></div>
                                <div id="chart_status" style="min-height:300px"></div>
                            </div>
                            <div class="ckpi-chart-card">
                                <div class="ccc-header"><i class="fas fa-chart-area"></i><h6>Monthly Project Flow</h6></div>
                                <div id="chart_monthly" style="min-height:300px"></div>
                            </div>
                        </div>

                        <!-- Charts Row 2 -->
                        <div class="ckpi-chart-row">
                            <div class="ckpi-chart-card">
                                <div class="ccc-header"><i class="fas fa-award"></i><h6>Certificates Provided</h6></div>
                                <div id="chart_certs" style="min-height:300px"></div>
                            </div>
                            <div class="ckpi-chart-card">
                                <div class="ccc-header"><i class="fas fa-cogs"></i><h6>Equipment Breakdown</h6></div>
                                <div id="chart_equip" style="min-height:300px"></div>
                            </div>
                        </div>

                        <!-- Charts Row 3 -->
                        <div class="ckpi-chart-row">
                            <div class="ckpi-chart-card" style="grid-column: 1 / -1; max-width: 800px; margin: 0 auto; width: 100%;">
                                <div class="ccc-header"><i class="fas fa-tasks"></i><h6>Checklist Distribution</h6></div>
                                <div id="chart_checklists" style="min-height:320px"></div>
                            </div>
                        </div>

                    </div> <!-- /.ckpi-main -->
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<script>
var reqClient = null;
var chStatus, chMonthly, chCerts, chEquip, chChecks;
var isClearing = false;
var dbTimer = null;

// Palette
var CKPI = {
    blue: '#3b82f6', teal: '#14b8a6', purple: '#8b5cf6', orange: '#f97316',
    emerald: '#10b981', amber: '#f59e0b', rose: '#f43f5e', sky: '#0ea5e9'
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
    $(sel).html('<div class="ckpi-no-data"><i class="fas fa-folder-open"></i><span>No data available for selected period</span></div>');
}

$(document).ready(function() {
    loadCustomerKPI();
    $('#ckpi_date_from, #ckpi_date_to').on('change', function() {
        if (isClearing) return;
        clearTimeout(dbTimer);
        dbTimer = setTimeout(loadCustomerKPI, 300);
    });
});

function resetCustomerKPI() {
    isClearing = true;
    $('#ckpi_date_from').val('');
    $('#ckpi_date_to').val('');
    isClearing = false;
    loadCustomerKPI();
}

function loadCustomerKPI() {
    var params = {
        date_from: $('#ckpi_date_from').val(),
        date_to:   $('#ckpi_date_to').val()
    };
    $('#ckpiLoader').css('display', 'flex');

    if (reqClient && reqClient.readyState !== 4) reqClient.abort();
    reqClient = $.ajax({
        url: '../kpi/fetch_customer_kpi_data.php',
        type: 'GET',
        data: params,
        dataType: 'json'
    }).done(function(res) {
        if (res.error) { alert(res.error); return; }
        var k = res.kpi || {};
        
        // Updates metrics
        initAnimNumber('cval_projects', k.total_projects || 0);
        initAnimNumber('cval_checklists', k.total_checklists || 0);
        initAnimNumber('cval_certificates', k.total_certificates || 0);
        initAnimNumber('cval_stickers', k.total_stickers || 0);

        initAnimNumber('cval_comp', k.completed_projects || 0);
        initAnimNumber('cval_inprog', k.in_progress_projects || 0);
        initAnimNumber('cval_pend', k.pending_projects || 0);

        // Renders charts
        drawStatusChart(res.project_status || {});
        drawMonthlyChart(res.monthly_trend || {});
        drawCertsChart(res.certificate_distribution || {});
        drawEquipChart(res.equipment_types || {});
        drawChecklistChart(res.checklist_distribution || {});

    }).always(function() {
        $('#ckpiLoader').hide();
    });
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
        plotOptions: { pie: { donut: { size: '70%', labels: { show: true, total: { show: true, label: 'Projects' } } } } },
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

// ── 3. Certificates Horizontal ──
function drawCertsChart(d) {
    chCerts = safeDestroy(chCerts, '#chart_certs');
    if(!d.labels || !d.labels.length) { renderNoDataMsg('#chart_certs'); return; }

    chCerts = new ApexCharts(document.querySelector('#chart_certs'), {
        chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [{ name: 'Issued', data: d.data }],
        xaxis: { categories: d.labels },
        colors: [CKPI.purple],
        plotOptions: { bar: { horizontal: true, borderRadius: 5, dataLabels: { position: 'center' } } },
        dataLabels: { enabled: true, style: { colors: ['#fff'] } }
    });
    chCerts.render();
}

// ── 4. Equipment Column ──
function drawEquipChart(d) {
    chEquip = safeDestroy(chEquip, '#chart_equip');
    if(!d.labels || !d.labels.length) { renderNoDataMsg('#chart_equip'); return; }

    chEquip = new ApexCharts(document.querySelector('#chart_equip'), {
        chart: { type: 'bar', height: 300, toolbar: { show: false }, fontFamily: 'inherit' },
        series: [{ name: 'Count', data: d.data }],
        xaxis: { categories: d.labels, labels: { style: { fontSize: '10px' } } },
        colors: [CKPI.teal],
        plotOptions: { bar: { columnWidth: '50%', borderRadius: 5 } },
        dataLabels: { enabled: true, offsetY: -20, style: { colors: ['#1e293b'] } }
    });
    chEquip.render();
}

// ── 5. Checklist Pie ──
function drawChecklistChart(d) {
    chChecks = safeDestroy(chChecks, '#chart_checklists');
    var total = (d.data || []).reduce((a,b) => a+b, 0);
    if(total === 0) { renderNoDataMsg('#chart_checklists'); return; }

    chChecks = new ApexCharts(document.querySelector('#chart_checklists'), {
        chart: { type: 'pie', height: 320, fontFamily: 'inherit' },
        series: d.data, labels: d.labels,
        colors: [CKPI.blue, CKPI.teal, CKPI.purple, CKPI.orange, CKPI.sky, CKPI.amber, CKPI.emerald, CKPI.rose],
        dataLabels: { enabled: true },
        legend: { position: 'right' }
    });
    chChecks.render();
}
</script>
