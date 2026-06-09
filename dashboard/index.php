<?php
ob_start();
include_once('../file/config.php');
include_once('../inc/function.php');

// Check if the user is logged in
$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}

// Cache directory setup
$cache_dir = '../cache/';
if (!file_exists($cache_dir)) {
    mkdir($cache_dir, 0755, true); 
}

// Enable caching (5 minutes)
$cache_time = 300;
$cache_file = $cache_dir . 'dashboard_cache_v2_' . md5($logged_in_user . $user_role) . '.json';

$use_cache = false;
if (file_exists($cache_file) && (time() - filemtime($cache_file) < $cache_time)) {
    $cached_data = json_decode(file_get_contents($cache_file), true);
    if (is_array($cached_data)) {
        extract($cached_data);
        $use_cache = true;
    }
}

if (!$use_cache) {
    $counts_query = mysqli_query($conn, "
    SELECT 
        COUNT(*) AS total_projects,
        
        -- Lifting
        SUM(equipment_type = 'Lifting Equipment') AS lifting_total,
        SUM(equipment_type = 'Lifting Equipment' AND project_status != 'Completed') AS lifting_active,
        SUM(equipment_type = 'Lifting Equipment' AND project_status = 'Completed') AS lifting_closed,

        -- NDT
        SUM(equipment_type = 'NDT Equipment') AS ndt_total,
        SUM(equipment_type = 'NDT Equipment' AND project_status != 'Completed') AS ndt_active,
        SUM(equipment_type = 'NDT Equipment' AND project_status = 'Completed') AS ndt_closed,

        -- TR
        SUM(checklist_status = 'Created' AND report_status = 'Generated' AND review_status = 'Pending') AS tr_pending,
        SUM(review_status = 'Completed') AS tr_closed,

        -- DC
        SUM(checklist_status = 'Created' AND report_status = 'Generated' AND review_status = 'Completed' AND certificatestatus = 'pending') AS dc_pending,
        SUM(certificatestatus = 'Certificate Created') AS dc_closed,

        -- QC
        SUM(certificatestatus = 'Certificate Created' AND project_status = 'Pending') AS qc_pending,
        SUM(project_status = 'Completed') AS qc_closed,

        -- Operator Cards
        (SELECT COUNT(*) FROM operator_cards) AS op_total,
        (SELECT COUNT(*) FROM operator_cards WHERE expiry_date >= CURDATE()) AS op_active,
        (SELECT COUNT(*) FROM operator_cards WHERE expiry_date < CURDATE()) AS op_expired,

        (SELECT COUNT(*) FROM customers) AS total_customers
    FROM project_info
");
    $counts = mysqli_fetch_assoc($counts_query);
    
    // Handle nulls
    foreach($counts as $key => $val) {
        if($val === null) $counts[$key] = 0;
    }

    extract($counts);

    $result_recent_projects = mysqli_query($conn, "
        SELECT project_no, customer_name, project_status, creation_date, inspector_name 
        FROM project_info 
        ORDER BY creation_date DESC 
        LIMIT 7
    ");

    $data_to_cache = array_merge($counts, [
        'recent_projects' => mysqli_fetch_all($result_recent_projects, MYSQLI_ASSOC)
    ]);

    if (is_writable($cache_dir)) {
        file_put_contents($cache_file, json_encode($data_to_cache));
    }
    
    $recent_projects = $data_to_cache['recent_projects'];
}

// Survey Data for Charts
$survey_query = mysqli_query($conn, "SELECT qualification_card, response_time, ppe, aramco_standards, overall_satisfaction FROM customer_survey_report");
$survey_data = mysqli_fetch_all($survey_query, MYSQLI_ASSOC);

$chart_data = [
    'overall_satisfaction' => ['yes' => 0, 'no' => 0],
    'qualification_card' => ['yes' => 0, 'no' => 0]
];

foreach ($survey_data as $row) {
    foreach ($chart_data as $key => &$counts_arr) {
        $response = strtolower(trim($row[$key]));
        if ($response === 'yes') $counts_arr['yes']++;
        elseif ($response === 'no') $counts_arr['no']++;
    }
}

// ✅ Trend Data (Last 9 Months)
$trend_query = mysqli_query($conn, "
    SELECT DATE_FORMAT(creation_date, '%b') as month, COUNT(*) as count 
    FROM project_info 
    WHERE creation_date >= DATE_SUB(NOW(), INTERVAL 9 MONTH)
    GROUP BY DATE_FORMAT(creation_date, '%Y-%m') 
    ORDER BY creation_date ASC
");
$trend_data = [];
if ($trend_query) {
    $trend_data = mysqli_fetch_all($trend_query, MYSQLI_ASSOC);
}
$trend_labels = array_column($trend_data, 'month');
$trend_counts = array_column($trend_data, 'count');

// Fallback if no data
if(empty($trend_labels)) {
    $trend_labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May'];
    $trend_counts = [0, 0, 0, 0, 0];
}
// Equipment Type Chart (All projects, admin view)
$equip_query = mysqli_query($conn, "
    SELECT equipment_type, COUNT(*) as cnt
    FROM project_info
    WHERE equipment_type IS NOT NULL AND equipment_type != ''
    GROUP BY equipment_type
    ORDER BY cnt DESC
");
$equip_labels = [];
$equip_counts = [];
if ($equip_query) {
    while ($row = mysqli_fetch_assoc($equip_query)) {
        $equip_labels[] = ucwords($row['equipment_type']);
        $equip_counts[] = (int)$row['cnt'];
    }
}

// Checklist Type Chart (All projects, admin view)
$checklist_query = mysqli_query($conn, "
    SELECT checklist_type, COUNT(*) as cnt
    FROM project_info
    WHERE checklist_type IS NOT NULL AND checklist_type != ''
    GROUP BY checklist_type
    ORDER BY cnt DESC
");
$cl_labels = [];
$cl_counts = [];
if ($checklist_query) {
    while ($row = mysqli_fetch_assoc($checklist_query)) {
        $cl_labels[] = ucwords(str_replace(['-','_'], ' ', $row['checklist_type']));
        $cl_counts[] = (int)$row['cnt'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Performance Summary</title>
    
    <link rel="stylesheet" href="../assets/css/minified/main.min.css">
    <link rel="stylesheet" href="../assets/css/modern_ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --glass-ink: #111827;
            --glass-muted: #6b7280;
            --glass-line: rgba(255, 255, 255, 0.62);
            --glass-border: rgba(148, 163, 184, 0.24);
            --glass-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
            --glass-blue: #2563eb;
            --glass-cyan: #14b8a6;
            --glass-violet: #7048e8;
            --glass-amber: #f59e0b;
        }

        body {
            background:
                radial-gradient(circle at 16% 8%, rgba(20, 184, 166, 0.16), transparent 30%),
                radial-gradient(circle at 88% 4%, rgba(112, 72, 232, 0.13), transparent 28%),
                linear-gradient(135deg, #f7fafc 0%, #eef4fb 52%, #f8fafc 100%);
        }

        .dashboard-glass {
            position: relative;
            min-height: calc(100vh - 110px);
            padding: 6px 10px 42px;
            overflow: hidden;
        }

        .dashboard-glass:before,
        .dashboard-glass:after {
            content: "";
            position: fixed;
            z-index: -1;
            border-radius: 999px;
            filter: blur(4px);
            pointer-events: none;
        }

        .dashboard-glass:before {
            width: 360px;
            height: 360px;
            right: 4%;
            top: 120px;
            background: rgba(37, 99, 235, 0.1);
        }

        .dashboard-glass:after {
            width: 300px;
            height: 300px;
            left: 18%;
            bottom: 8%;
            background: rgba(20, 184, 166, 0.11);
        }

        .dashboard-glass .container-fluid {
            max-width: 1680px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 18px;
            margin-bottom: 30px;
            padding: 24px;
            border: 1px solid var(--glass-line);
            border-radius: 18px;
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.44));
            box-shadow: 0 20px 48px rgba(15, 23, 42, 0.08);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }

        .page-title h2 {
            margin-bottom: 8px;
            color: var(--glass-ink);
            font-size: clamp(26px, 2vw, 36px);
            font-weight: 800;
            letter-spacing: 0;
            text-transform: none;
        }

        .page-subtitle {
            color: var(--glass-muted);
            font-size: 15px;
            line-height: 1.45;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }

        .filter-btn,
        .dashboard-glass .btn-light {
            min-height: 44px;
            padding: 10px 16px;
            border: 1px solid rgba(148, 163, 184, 0.24);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.66);
            color: #475569;
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.06);
            font-weight: 700;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
        }

        .filter-btn:hover,
        .dashboard-glass .btn-light:hover {
            transform: translateY(-1px);
            border-color: rgba(37, 99, 235, 0.24);
            box-shadow: 0 18px 36px rgba(37, 99, 235, 0.12);
        }

        .modern-card {
            position: relative;
            min-height: 100%;
            margin-bottom: 0;
            padding: 28px;
            border: 1px solid var(--glass-line);
            border-radius: 22px;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.46));
            box-shadow: var(--glass-shadow);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            overflow: hidden;
            transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
        }

        .modern-card:before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: inherit;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.68), transparent 42%),
                radial-gradient(circle at top right, rgba(37, 99, 235, 0.08), transparent 34%);
            pointer-events: none;
        }

        .modern-card > * {
            position: relative;
            z-index: 1;
        }

        .modern-card:hover {
            transform: translateY(-4px);
            border-color: rgba(20, 184, 166, 0.3);
            box-shadow: 0 30px 70px rgba(15, 23, 42, 0.16);
        }

        .modern-card h4,
        .modern-card h5 {
            color: var(--glass-ink);
            letter-spacing: 0;
            text-transform: none;
        }

        .stat-icon-circle,
        .modern-card [style*="width: 50px"][style*="border-radius: 50%"] {
            width: 60px !important;
            height: 60px !important;
            border-radius: 18px !important;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.86), 0 16px 30px rgba(15, 23, 42, 0.1);
        }

        .bg-purple-light {
            background: linear-gradient(135deg, rgba(112, 72, 232, 0.18), rgba(112, 72, 232, 0.08)) !important;
            color: var(--glass-violet) !important;
        }

        .bg-orange-light {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.18), rgba(245, 158, 11, 0.08)) !important;
            color: var(--glass-amber) !important;
        }

        .bg-blue-light {
            background: linear-gradient(135deg, rgba(37, 99, 235, 0.16), rgba(20, 184, 166, 0.08)) !important;
            color: var(--glass-blue) !important;
        }

        .kpi-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            margin-bottom: 16px;
            padding: 12px 0;
            border-bottom: 1px solid rgba(148, 163, 184, 0.18);
            color: #475569;
            font-size: 14px;
        }

        .kpi-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        .kpi-label {
            font-weight: 700;
        }

        .kpi-value {
            color: var(--glass-ink);
            font-weight: 800;
        }

        .badge-kpi {
            min-width: 36px;
            padding: 6px 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
        }

        .badge-pending { background: rgba(245, 158, 11, 0.16); color: #b45309; }
        .badge-closed { background: rgba(16, 185, 129, 0.18); color: #047857; }
        .badge-active { background: rgba(37, 99, 235, 0.16); color: #1d4ed8; }
        .badge-expired { background: rgba(239, 68, 68, 0.16); color: #b91c1c; }

        .chart-container-lg {
            height: 340px;
        }

        .side-stat-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-top: 14px;
            padding: 16px;
            border: 1px solid rgba(148, 163, 184, 0.16);
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.5);
        }

        .side-stat-icon {
            width: 42px;
            height: 42px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            border-radius: 13px;
            color: #fff;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.12);
        }

        .modern-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .modern-table thead th {
            padding: 0 16px 8px;
            color: #64748b;
            font-size: 12px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .04em;
            border: 0;
        }

        .modern-table tbody tr {
            background: rgba(255, 255, 255, 0.56);
            box-shadow: 0 10px 28px rgba(15, 23, 42, 0.05);
        }

        .modern-table tbody td {
            padding: 14px 16px;
            color: #334155;
            border-top: 1px solid rgba(226, 232, 240, 0.58);
            border-bottom: 1px solid rgba(226, 232, 240, 0.58);
            vertical-align: middle;
        }

        .modern-table tbody td:first-child {
            border-left: 1px solid rgba(226, 232, 240, 0.58);
            border-radius: 14px 0 0 14px;
        }

        .modern-table tbody td:last-child {
            border-right: 1px solid rgba(226, 232, 240, 0.58);
            border-radius: 0 14px 14px 0;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            min-height: 28px;
            padding: 6px 11px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .status-pending { background: rgba(245, 158, 11, 0.16); color: #b45309; }
        .status-active { background: rgba(16, 185, 129, 0.16); color: #047857; }
        .status-review { background: rgba(37, 99, 235, 0.14); color: #1d4ed8; }

        .dashboard-glass .container-fluid > .row {
            margin-bottom: 42px;
            row-gap: 30px;
        }

        .dashboard-glass .container-fluid > .row:last-child {
            margin-bottom: 0;
        }

        .dashboard-glass .section-header + .row {
            margin-top: 0;
        }

        @media (max-width: 1199px) {
            .dashboard-glass {
                padding: 0 2px 34px;
            }

            .modern-card {
                padding: 24px;
            }

            .dashboard-glass .container-fluid > .row {
                margin-bottom: 34px;
                row-gap: 24px;
            }
        }

        @media (max-width: 767px) {
            .dashboard-glass {
                padding-bottom: 26px;
            }

            .section-header {
                flex-direction: column;
                margin-bottom: 22px;
                padding: 20px;
                border-radius: 16px;
            }

            .header-actions {
                width: 100%;
                justify-content: stretch;
            }

            .filter-btn {
                flex: 1 1 140px;
                justify-content: center;
            }

            .modern-card {
                padding: 20px;
                border-radius: 18px;
            }

            .dashboard-glass .container-fluid > .row {
                margin-bottom: 28px;
                row-gap: 20px;
            }

            .chart-container-lg {
                height: 280px;
            }

            .modern-card .d-flex.justify-content-between {
                align-items: flex-start !important;
                gap: 14px;
            }

            .modern-table {
                min-width: 760px;
            }
        }

        @media (max-width: 479px) {
            .page-title h2 {
                font-size: 24px;
            }

            .page-subtitle {
                font-size: 14px;
            }

            .filter-btn {
                flex-basis: 100%;
            }

            .stat-icon-circle,
            .modern-card [style*="width: 50px"][style*="border-radius: 50%"] {
                width: 52px !important;
                height: 52px !important;
                border-radius: 15px !important;
            }

            .kpi-row {
                font-size: 13px;
            }
        }
    </style>
</head>
<body>

<div class="main-content dashboard-glass">
    <div class="container-fluid">
        
        <!-- Header -->
        <div class="section-header">
            <div class="page-title">
                <h2>Performance Summary</h2>
                <span class="page-subtitle">Access user profile reporting and key metrics.</span>
            </div>
            <div class="header-actions">
                 <button class="filter-btn">
                     <i class="far fa-calendar"></i> This Month <i class="fas fa-chevron-down ml-2" style="font-size: 10px;"></i>
                 </button>
                 <button class="filter-btn ml-2">
                     <i class="fas fa-filter"></i> Filter
                 </button>
            </div>
        </div>

        <!-- Row 1: Key Metrics -->
        <div class="row">
            
            <!-- 1. Lifting -->
            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.1s;">
                <div class="modern-card">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stat-icon-circle bg-purple-light mr-3">
                             <i class="fa-solid fa-weight-hanging"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold">Lifting</h5>
                    </div>
                    
                    <div class="kpi-row">
                        <span class="kpi-label">Total</span>
                        <span class="kpi-value counter" data-target="<?= $lifting_total ?>">0</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Active</span>
                        <span class="badge-kpi badge-active counter" data-target="<?= $lifting_active ?>">0</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Closed</span>
                        <span class="badge-kpi badge-closed counter" data-target="<?= $lifting_closed ?>">0</span>
                    </div>
                </div>
            </div>

            <!-- 2. NDT -->
            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.2s;">
                <div class="modern-card">
                    <div class="d-flex align-items-center mb-3">
                         <div class="stat-icon-circle bg-orange-light mr-3">
                             <i class="fa-solid fa-microscope"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold">NDT</h5>
                    </div>

                    <div class="kpi-row">
                        <span class="kpi-label">Total</span>
                        <span class="kpi-value counter" data-target="<?= $ndt_total ?>">0</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Active</span>
                        <span class="badge-kpi badge-active counter" data-target="<?= $ndt_active ?>">0</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Closed</span>
                        <span class="badge-kpi badge-closed counter" data-target="<?= $ndt_closed ?>">0</span>
                    </div>
                </div>
            </div>

            <!-- 3. TR, DC, QC -->
            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="modern-card">
                    <div class="d-flex align-items-center mb-3">
                         <div class="stat-icon-circle bg-blue-light mr-3">
                             <i class="fa-solid fa-users-cog"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold">Dept Status</h5>
                    </div>

                    <div class="kpi-row">
                        <span class="kpi-label">TR (Pending/Closed)</span>
                        <span class="kpi-value">
                            <span class="text-warning"><?= $tr_pending ?></span> / <span class="text-success"><?= $tr_closed ?></span>
                        </span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">DC (Pending/Closed)</span>
                        <span class="kpi-value">
                            <span class="text-warning"><?= $dc_pending ?></span> / <span class="text-success"><?= $dc_closed ?></span>
                        </span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">QC (Pending/Closed)</span>
                        <span class="kpi-value">
                            <span class="text-warning"><?= $qc_pending ?></span> / <span class="text-success"><?= $qc_closed ?></span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- 4. Operator Card -->
            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.4s;">
                <div class="modern-card">
                    <div class="d-flex align-items-center mb-3">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: #ffeaea; color: #ff6b6b; display: flex; align-items: center; justify-content: center; font-size: 20px; margin-right: 15px;">
                             <i class="fa-solid fa-id-card"></i>
                        </div>
                        <h5 class="mb-0 font-weight-bold">Operator Card</h5>
                    </div>

                     <div class="kpi-row">
                        <span class="kpi-label">Total</span>
                        <span class="kpi-value counter" data-target="<?= $op_total ?>">0</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Active</span>
                        <span class="badge-kpi badge-active counter" data-target="<?= $op_active ?>">0</span>
                    </div>
                    <div class="kpi-row">
                        <span class="kpi-label">Expired</span>
                        <span class="badge-kpi badge-expired counter" data-target="<?= $op_expired ?>">0</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 2: Charts Area -->
        <div class="row">
            <!-- Main Trend Chart -->
            <div class="col-xl-8 col-lg-7 animate-slide-up" style="animation-delay: 0.5s;">
                <div class="modern-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 style="font-size: 18px; font-weight: 700;">Project Trends</h4>
                        <button class="btn btn-sm btn-light">Weekly <i class="fas fa-chevron-down"></i></button>
                    </div>
                    <div class="chart-container-lg">
                        <canvas id="mainTrendChart"></canvas>
                    </div>
                    <div class="row mt-4 text-center">
                         <div class="col-4">
                             <h5 class="mb-0 font-weight-bold">120</h5>
                             <small class="text-muted">New Projects</small>
                         </div>
                         <div class="col-4">
                             <h5 class="mb-0 font-weight-bold">235</h5>
                             <small class="text-muted">Completed</small>
                         </div>
                         <div class="col-4">
                             <h5 class="mb-0 font-weight-bold">12</h5>
                             <small class="text-muted">Cancelled</small>
                         </div>
                    </div>
                </div>
            </div>

            <!-- Side Stats (Survey) -->
            <div class="col-xl-4 col-lg-5 animate-slide-up" style="animation-delay: 0.6s;">
                 <div class="modern-card">
                     <h4 style="font-size: 18px; font-weight: 700; mb-4">Customer Satisfaction</h4>
                     
                     <div class="text-center my-4">
                         <div style="height: 180px; position: relative;">
                             <canvas id="satisfactionChart"></canvas>
                             <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); text-align: center;">
                                 <h3 class="mb-0">98%</h3>
                                 <small class="text-muted">Positive</small>
                             </div>
                         </div>
                     </div>

                     <div class="side-stat-row">
                         <div class="d-flex align-items-center">
                             <div class="side-stat-icon" style="background: #7058f8;">
                                 <i class="fas fa-shield-alt"></i>
                             </div>
                             <div>
                                 <h6 class="mb-0">Safety Awareness</h6>
                                 <small class="text-muted">High Compliance</small>
                             </div>
                         </div>
                         <div class="font-weight-bold">95%</div>
                     </div>

                     <div class="side-stat-row">
                         <div class="d-flex align-items-center">
                             <div class="side-stat-icon" style="background: #ff9f43;">
                                 <i class="fas fa-clock"></i>
                             </div>
                             <div>
                                 <h6 class="mb-0">Response Time</h6>
                                 <small class="text-muted">Within SLA</small>
                             </div>
                         </div>
                         <div class="font-weight-bold">88%</div>
                     </div>

                 </div>
            </div>
        </div>

        <!-- Equipment Type Chart -->
        <div class="row">
            <div class="col-12 animate-slide-up" style="animation-delay: 0.65s;">
                <div class="modern-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 style="font-size: 18px; font-weight: 700;">Equipment Type Breakdown</h4>
                        <span style="font-size: 13px; color: #888;">All Projects &mdash; Total: <strong><?= array_sum($equip_counts) ?></strong></span>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center">
                            <div style="position: relative; height: 260px;">
                                <canvas id="equipTypeChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <?php
                                $palette = ['#4F46E5','#10B981','#F59E0B','#EF4444','#0EA5E9','#8B5CF6','#14B8A6','#F97316','#EC4899'];
                                $total_equip = array_sum($equip_counts);
                                foreach ($equip_labels as $i => $label):
                                    $color = $palette[$i % count($palette)];
                                    $pct = $total_equip > 0 ? round(($equip_counts[$i] / $total_equip) * 100, 1) : 0;
                                ?>
                                <div class="col-md-6 mb-3">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:12px; height:12px; border-radius:3px; background:<?= $color ?>; flex-shrink:0;"></div>
                                        <div style="flex:1; font-size:13px; color:#555; font-weight:600;"><?= htmlspecialchars($label) ?></div>
                                        <div style="font-size:13px; font-weight:700; color:#333;"><?= $equip_counts[$i] ?></div>
                                        <div style="font-size:11px; color:#999;"><?= $pct ?>%</div>
                                    </div>
                                    <div style="height:4px; background:#f0f0f0; border-radius:4px; margin-top:5px;">
                                        <div style="height:4px; width:<?= $pct ?>%; background:<?= $color ?>; border-radius:4px;"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($equip_labels)): ?>
                                    <div class="col-12 text-muted text-center py-4">No equipment data available.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Checklist Type Chart -->
        <div class="row">
            <div class="col-12 animate-slide-up" style="animation-delay: 0.7s;">
                <div class="modern-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 style="font-size: 18px; font-weight: 700;">Checklist Type Breakdown</h4>
                        <span style="font-size: 13px; color: #888;">All Projects &mdash; Total: <strong><?= array_sum($cl_counts) ?></strong></span>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-md-5">
                            <div style="position: relative; height: 300px;">
                                <canvas id="checklistTypeChart"></canvas>
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="row">
                                <?php
                                $cl_palette = ['#4F46E5','#10B981','#F59E0B','#EF4444','#0EA5E9','#8B5CF6','#14B8A6','#F97316','#EC4899','#06B6D4','#84CC16','#F43F5E'];
                                $cl_total = array_sum($cl_counts);
                                foreach ($cl_labels as $i => $label):
                                    $color = $cl_palette[$i % count($cl_palette)];
                                    $pct = $cl_total > 0 ? round(($cl_counts[$i] / $cl_total) * 100, 1) : 0;
                                ?>
                                <div class="col-md-6 mb-3">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <div style="width:12px; height:12px; border-radius:3px; background:<?= $color ?>; flex-shrink:0;"></div>
                                        <div style="flex:1; font-size:13px; color:#555; font-weight:600; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="<?= htmlspecialchars($label) ?>"><?= htmlspecialchars($label) ?></div>
                                        <div style="font-size:13px; font-weight:700; color:#333;"><?= $cl_counts[$i] ?></div>
                                        <div style="font-size:11px; color:#999;"><?= $pct ?>%</div>
                                    </div>
                                    <div style="height:4px; background:#f0f0f0; border-radius:4px; margin-top:5px;">
                                        <div style="height:4px; width:<?= $pct ?>%; background:<?= $color ?>; border-radius:4px;"></div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                                <?php if (empty($cl_labels)): ?>
                                    <div class="col-12 text-muted text-center py-4">No checklist data available.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Row 3: Recent Activity Table -->
        <div class="row">
            <div class="col-12 animate-slide-up" style="animation-delay: 0.7s;">
                <div class="modern-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 style="font-size: 18px; font-weight: 700;">Recent Projects</h4>
                        <a href="#" class="text-primary font-weight-bold" style="font-size: 14px;">View All</a>
                    </div>
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Project ID</th>
                                    <th>Customer</th>
                                    <th>Inspector</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_projects as $proj): ?>
                                <tr>
                                    <td class="font-weight-bold">#<?= $proj['project_no'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 30px; height: 30px; background: #eee; border-radius: 50%; margin-right: 10px;"></div>
                                            <?= htmlspecialchars($proj['customer_name']) ?>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($proj['inspector_name'] ?? 'Unassigned') ?></td>
                                    <td><?= date('d M, Y', strtotime($proj['creation_date'])) ?></td>
                                    <td>
                                        <?php if($proj['project_status'] == 'Pending'): ?>
                                            <span class="status-badge status-pending">Pending</span>
                                        <?php elseif($proj['project_status'] == 'Completed'): ?>
                                            <span class="status-badge status-active">Completed</span>
                                        <?php else: ?>
                                            <span class="status-badge status-review"><?= $proj['project_status'] ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><i class="fas fa-ellipsis-h text-muted" style="cursor: pointer;"></i></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
// Mock Data for Trend Chart
const ctxTrend = document.getElementById('mainTrendChart').getContext('2d');
const gradient = ctxTrend.createLinearGradient(0, 0, 0, 400);
gradient.addColorStop(0, 'rgba(94, 181, 247, 0.3)');
gradient.addColorStop(1, 'rgba(94, 181, 247, 0.0)');

new Chart(ctxTrend, {
    type: 'line',
    data: {
        labels: <?= json_encode($trend_labels) ?>,
        datasets: [{
            label: 'Projects',
            data: <?= json_encode($trend_counts) ?>,
            borderColor: '#5eb5f7',
            backgroundColor: gradient,
            borderWidth: 3,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: '#5eb5f7',
            pointRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { borderDash: [5, 5], color: '#f0f0f0' },
                ticks: { display: false }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Satisfaction Doughnut
const ctxSat = document.getElementById('satisfactionChart').getContext('2d');
new Chart(ctxSat, {
    type: 'doughnut',
    data: {
        labels: ['Good', 'Bad'],
        datasets: [{
            data: [<?= $chart_data['overall_satisfaction']['yes'] ?>, <?= $chart_data['overall_satisfaction']['no'] ?>],
            backgroundColor: ['#7058f8', '#eee'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '80%',
        plugins: { legend: { display: false } }
    }
});

// Equipment Type Doughnut
const equipLabels = <?= json_encode($equip_labels) ?>;
const equipCounts = <?= json_encode($equip_counts) ?>;
const equipColors = ['#4F46E5','#10B981','#F59E0B','#EF4444','#0EA5E9','#8B5CF6','#14B8A6','#F97316','#EC4899'];

if (document.getElementById('equipTypeChart')) {
    const ctxEquip = document.getElementById('equipTypeChart').getContext('2d');
    new Chart(ctxEquip, {
        type: 'doughnut',
        data: {
            labels: equipLabels,
            datasets: [{
                data: equipCounts,
                backgroundColor: equipColors.slice(0, equipLabels.length),
                borderWidth: 2,
                borderColor: '#fff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((ctx.parsed / total) * 100).toFixed(1) : 0;
                            return ` ${ctx.label}: ${ctx.parsed} (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Checklist Type Horizontal Bar Chart
const clLabels = <?= json_encode($cl_labels) ?>;
const clCounts = <?= json_encode($cl_counts) ?>;
const clPalette = ['#4F46E5','#10B981','#F59E0B','#EF4444','#0EA5E9','#8B5CF6','#14B8A6','#F97316','#EC4899','#06B6D4','#84CC16','#F43F5E'];

if (document.getElementById('checklistTypeChart')) {
    const ctxCl = document.getElementById('checklistTypeChart').getContext('2d');
    new Chart(ctxCl, {
        type: 'bar',
        data: {
            labels: clLabels,
            datasets: [{
                label: 'Projects',
                data: clCounts,
                backgroundColor: clPalette.slice(0, clLabels.length),
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                            const pct = total > 0 ? ((ctx.parsed.x / total) * 100).toFixed(1) : 0;
                            return ` ${ctx.parsed.x} projects (${pct}%)`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: '#f0f0f0' },
                    ticks: { precision: 0 }
                },
                y: {
                    grid: { display: false },
                    ticks: { font: { size: 12, weight: '600' }, color: '#555' }
                }
            }
        }
    });
}

// Number Counter Animation
const counters = document.querySelectorAll('.counter');
counters.forEach(counter => {
    const target = +counter.getAttribute('data-target');
    const duration = 1000; 
    const step = target / (duration / 16);
    
    let current = 0;
    const updateCount = () => {
        current += step;
        if(current < target) {
            counter.innerText = Math.ceil(current);
            requestAnimationFrame(updateCount);
        } else {
            counter.innerText = target;
        }
    };
    updateCount();
});
</script>

<?php
ob_end_flush();
include_once('../inc/footer.php');
?>
</body>
</html>
