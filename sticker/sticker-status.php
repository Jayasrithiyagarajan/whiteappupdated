<?php 
include_once('../inc/function.php');
include '../file/config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logged_in_user = $_SESSION['username'] ?? null;
if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sticker Status Dashboard</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/premium-nav.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
            --bg-body: #f8fafc;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius-xl: 16px;
            --radius-lg: 12px;
            --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        }

        body {
            background: var(--bg-body);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-main);
            margin: 0;
        }

        .dashboard-container {
            padding: 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* Welcome Banner */
        .welcome-banner {
            background: var(--primary-gradient);
            border-radius: var(--radius-xl);
            padding: 32px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.3);
        }

        .welcome-text h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .welcome-text p {
            margin: 8px 0 0;
            opacity: 0.9;
            font-size: 14px;
        }

        .btn-refresh {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            backdrop-filter: blur(4px);
        }

        .btn-refresh:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Quick Actions */
        .quick-actions {
            background: var(--surface);
            border-radius: var(--radius-xl);
            padding: 24px;
            display: flex;
            gap: 16px;
            margin-bottom: 24px;
            border: 1px solid var(--border);
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            transition: transform 0.2s;
        }

        .action-btn:hover { transform: translateY(-2px); }

        .btn-create { background: #6366f1; color: white; }
        .btn-view { background: white; color: #6366f1; border: 1px solid #6366f1; }
        .btn-projects { background: white; color: #10b981; border: 1px solid #10b981; }

        /* KPI Cards */
        .kpi-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .kpi-card {
            background: var(--surface);
            padding: 24px;
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: box-shadow 0.3s;
        }

        .kpi-card:hover { box-shadow: var(--shadow); }

        .kpi-info span {
            font-size: 12px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .kpi-info h2 {
            margin: 8px 0;
            font-size: 28px;
            font-weight: 800;
        }

        .kpi-info .kpi-sub {
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 600;
        }

        .kpi-icon-box {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        /* KPI Specific Colors */
        .kpi-total .kpi-icon-box { background: #e0e7ff; color: #6366f1; }
        .kpi-total .kpi-sub { color: #10b981; }

        .kpi-active .kpi-icon-box { background: #ffedd5; color: #f59e0b; }
        .kpi-active .kpi-sub { color: #f97316; }

        .kpi-pass .kpi-icon-box { background: #ecfeff; color: #06b6d4; }
        .kpi-pass .kpi-sub { color: #0891b2; }

        .kpi-fail .kpi-icon-box { background: #fee2e2; color: #ef4444; }
        .kpi-fail .kpi-sub { color: #dc2626; }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 24px;
        }

        .card {
            background: var(--surface);
            border-radius: var(--radius-xl);
            border: 1px solid var(--border);
            padding: 24px;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .card-header h3 {
            margin: 0;
            font-size: 18px;
            font-weight: 700;
        }

        .btn-view-all {
            color: #6366f1;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            padding: 6px 16px;
            border: 1px solid #e0e7ff;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .btn-view-all:hover { background: #f5f7ff; }

        /* Table Styling */
        .recent-table {
            width: 100%;
            border-collapse: collapse;
        }

        .recent-table th {
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 0.5px;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border);
        }

        .recent-table td {
            padding: 16px;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }

        .inspector-cell {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 600;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #475569;
            overflow: hidden;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .badge-success { background: #dcfce7; color: #15803d; }
        .badge-danger { background: #fee2e2; color: #b91c1c; }
        .badge-warning { background: #fef3c7; color: #92400e; }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .icon-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border);
            color: #6366f1;
            transition: all 0.2s;
        }

        .icon-btn:hover { background: #f8fafc; border-color: #6366f1; }

        /* Reviews Sidebar */
        .activity-item {
            margin-bottom: 24px;
        }

        .activity-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 600;
        }

        .activity-val { font-weight: 700; }

        .progress-container {
            height: 6px;
            background: #f1f5f9;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            border-radius: 10px;
        }

        .bar-blue { background: #3b82f6; }
        .bar-orange { background: #f97316; }
        .bar-green { background: #10b981; }

        @media (max-width: 1024px) {
            .content-grid { grid-template-columns: 1fr; }
        }

        /* ===== PREMIUM STATUS UI ===== */
        body {
            background:
                radial-gradient(circle at 18% 10%, rgba(20, 184, 166, .20), transparent 30%),
                radial-gradient(circle at 78% 36%, rgba(59, 130, 246, .18), transparent 34%),
                linear-gradient(135deg, #eef8f7 0%, #f6f8fc 46%, #e8eef9 100%);
            color: #101827;
        }

        .sticker-premium {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            background:
                radial-gradient(circle at 8% 4%, rgba(45, 212, 191, .18), transparent 28%),
                radial-gradient(circle at 86% 38%, rgba(14, 165, 233, .14), transparent 30%),
                linear-gradient(135deg, rgba(240, 253, 250, .82), rgba(242, 247, 255, .9));
            padding: 42px 28px 56px 28px;
        }

        .sticker-premium::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(90deg, rgba(255, 255, 255, .55), transparent 38%),
                radial-gradient(circle at 70% 65%, rgba(15, 118, 110, .12), transparent 26%);
        }

        .sticker-premium .dashboard-container {
            position: relative;
            z-index: 1;
            max-width: none;
            padding: 0;
        }

        .sticker-premium .welcome-banner,
        .sticker-premium .quick-actions,
        .sticker-premium .kpi-card,
        .sticker-premium .card {
            border: 1px solid rgba(255, 255, 255, .76);
            background: linear-gradient(145deg, rgba(255, 255, 255, .88), rgba(246, 251, 255, .72));
            box-shadow: 0 22px 60px rgba(15, 23, 42, .12);
            backdrop-filter: blur(16px);
        }

        .sticker-premium .welcome-banner {
            position: relative;
            overflow: hidden;
            min-height: 138px;
            padding: 30px 34px;
            border-radius: 24px;
            color: #0f172a;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .12);
        }

        .sticker-premium .welcome-banner::after {
            content: "";
            position: absolute;
            top: -74px;
            right: 7%;
            width: 210px;
            height: 210px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(37, 99, 235, .12), rgba(20, 184, 166, .18));
        }

        .sticker-premium .welcome-text {
            position: relative;
            z-index: 1;
            padding-left: 88px;
        }

        .sticker-premium .welcome-text::before {
            content: "\f02c";
            font-family: "Font Awesome 6 Free";
            font-weight: 900;
            position: absolute;
            left: 0;
            top: 50%;
            width: 68px;
            height: 68px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transform: translateY(-50%);
            border-radius: 22px;
            color: #2563eb;
            font-size: 28px;
            background: linear-gradient(135deg, rgba(37, 99, 235, .12), rgba(20, 184, 166, .18));
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .76), 0 18px 35px rgba(14, 165, 233, .12);
        }

        .sticker-premium .welcome-text h1 {
            margin-bottom: 8px;
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 900;
            letter-spacing: 0;
            color: #0f172a;
        }

        .sticker-premium .welcome-text p {
            color: #5f708b;
            font-size: 16px;
            opacity: 1;
        }

        .sticker-premium .btn-refresh {
            position: relative;
            z-index: 1;
            min-height: 48px;
            border: 0;
            border-radius: 14px;
            padding: 12px 20px;
            color: #fff;
            font-weight: 800;
            background: linear-gradient(135deg, #2563eb, #12b8b6);
            box-shadow: 0 16px 34px rgba(37, 99, 235, .25);
        }

        .sticker-premium .btn-refresh:hover {
            transform: translateY(-1px);
            background: linear-gradient(135deg, #1d4ed8, #0faaa8);
        }

        .sticker-premium .quick-actions {
            flex-direction: column;
            border-radius: 22px;
            padding: 22px 24px;
        }

        .sticker-premium .quick-actions > span {
            color: #64748b !important;
            letter-spacing: .45px;
            text-transform: uppercase;
        }

        .sticker-premium .quick-actions > div {
            flex-wrap: wrap;
        }

        .sticker-premium .action-btn {
            min-height: 48px;
            border: 1px solid rgba(203, 213, 225, .75);
            border-radius: 14px;
            padding: 12px 18px;
            box-shadow: 0 12px 24px rgba(15, 23, 42, .08);
        }

        .sticker-premium .btn-create {
            border: 0;
            color: #fff;
            background: linear-gradient(135deg, #2563eb, #12b8b6);
        }

        .sticker-premium .btn-view {
            color: #1d4ed8;
            background: rgba(255, 255, 255, .72);
        }

        .sticker-premium .btn-projects {
            color: #0f766e;
            background: rgba(255, 255, 255, .72);
        }

        .sticker-premium .kpi-row {
            grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
            gap: 24px;
        }

        .sticker-premium .kpi-card {
            position: relative;
            overflow: hidden;
            min-height: 152px;
            border-radius: 24px;
            padding: 28px 30px;
        }

        .sticker-premium .kpi-card::after {
            content: "";
            position: absolute;
            top: -50px;
            right: -42px;
            width: 138px;
            height: 138px;
            border-radius: 50%;
            background: rgba(37, 99, 235, .10);
        }

        .sticker-premium .kpi-info,
        .sticker-premium .kpi-icon-box {
            position: relative;
            z-index: 1;
        }

        .sticker-premium .kpi-info span {
            color: #62728a;
            font-weight: 900;
            letter-spacing: .6px;
        }

        .sticker-premium .kpi-info h2 {
            margin: 10px 0;
            font-size: 42px;
            font-weight: 900;
            color: #0f172a;
        }

        .sticker-premium .kpi-icon-box {
            width: 56px;
            height: 56px;
            border-radius: 18px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .76);
        }

        .sticker-premium .kpi-total .kpi-icon-box { background: rgba(37, 99, 235, .12); color: #2563eb; }
        .sticker-premium .kpi-active .kpi-icon-box { background: rgba(245, 158, 11, .14); color: #d97706; }
        .sticker-premium .kpi-pass .kpi-icon-box { background: rgba(20, 184, 166, .16); color: #0f766e; }
        .sticker-premium .kpi-fail .kpi-icon-box { background: rgba(239, 68, 68, .13); color: #dc2626; }

        .sticker-premium .content-grid {
            gap: 24px;
        }

        .sticker-premium .card {
            border-radius: 24px;
            padding: 24px;
        }

        .sticker-premium .card-header h3 {
            font-size: 20px;
            font-weight: 900;
            color: #111827;
        }

        .sticker-premium .btn-view-all {
            border: 0;
            border-radius: 12px;
            color: #fff;
            background: linear-gradient(135deg, #0f172a, #334155);
            box-shadow: 0 12px 24px rgba(15, 23, 42, .16);
        }

        .sticker-premium .recent-table {
            border-collapse: separate;
            border-spacing: 0;
            overflow: hidden;
            border-radius: 16px;
        }

        .sticker-premium .recent-table th {
            padding: 16px 14px;
            background: #f4f8fb;
            border-bottom: 1px solid rgba(203, 213, 225, .7);
            color: #334155;
            font-weight: 900;
        }

        .sticker-premium .recent-table td {
            padding: 14px;
            border-bottom: 1px solid rgba(226, 232, 240, .9);
            color: #243044;
            background: rgba(255, 255, 255, .68);
            vertical-align: middle;
        }

        .sticker-premium .recent-table tbody tr:hover td {
            background: rgba(236, 253, 245, .58);
        }

        .sticker-premium .avatar {
            width: 42px;
            height: 42px;
            border-radius: 14px;
            background: linear-gradient(135deg, #dbeafe, #ccfbf1);
            color: #1d4ed8;
            font-weight: 900;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .8);
        }

        .sticker-premium .badge {
            border-radius: 999px;
            padding: 6px 11px;
            letter-spacing: .35px;
        }

        .sticker-premium .badge-success { background: rgba(16, 185, 129, .14); color: #047857; }
        .sticker-premium .badge-danger { background: rgba(239, 68, 68, .13); color: #b91c1c; }
        .sticker-premium .badge-warning { background: rgba(245, 158, 11, .15); color: #92400e; }

        .sticker-premium .icon-btn {
            border: 1px solid rgba(203, 213, 225, .8);
            border-radius: 11px;
            color: #2563eb;
            background: rgba(255, 255, 255, .78);
            box-shadow: 0 10px 20px rgba(15, 23, 42, .08);
            text-decoration: none;
        }

        .sticker-premium .icon-btn:hover {
            color: #fff;
            border-color: transparent;
            background: linear-gradient(135deg, #2563eb, #12b8b6);
        }

        .sticker-premium .activity-item {
            padding: 16px;
            border: 1px solid rgba(226, 232, 240, .78);
            border-radius: 16px;
            background: rgba(255, 255, 255, .56);
        }

        .sticker-premium .activity-label {
            color: #334155;
        }

        .sticker-premium .progress-container {
            height: 9px;
            background: rgba(226, 232, 240, .78);
        }

        .sticker-premium .progress-bar {
            box-shadow: 0 8px 18px rgba(15, 23, 42, .12);
        }

        .sticker-premium .bar-blue { background: linear-gradient(90deg, #2563eb, #06b6d4); }
        .sticker-premium .bar-orange { background: linear-gradient(90deg, #f59e0b, #f97316); }
        .sticker-premium .bar-green { background: linear-gradient(90deg, #10b981, #14b8a6); }

        @media (max-width: 992px) {
            .sticker-premium {
                padding: 24px 14px 42px;
            }

            .sticker-premium .welcome-banner {
                flex-direction: column;
                align-items: flex-start;
            }

            .sticker-premium .btn-refresh {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .sticker-premium .welcome-text {
                padding-left: 0;
                padding-top: 76px;
            }

            .sticker-premium .welcome-text::before {
                top: 0;
                transform: none;
            }

            .sticker-premium .quick-actions > div,
            .sticker-premium .action-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body>

<?php include_once('../inc/nav.php'); ?>

<div class="main-content sticker-premium">
    <div class="dashboard-container">
        
        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <div class="welcome-text">
                <h1 id="welcome-msg">Welcome back, Loading...</h1>
                <p>Manage all stickers and users from your dedicated status dashboard.</p>
            </div>
            <button class="btn-refresh" onclick="fetchData()">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <span style="font-size: 14px; font-weight: 700; color: var(--text-muted); width: 100%; display: block; margin-bottom: 12px;">Quick Actions</span>
            <div style="display: flex; gap: 16px; width: 100%;">
                <a href="add-sticker.php" class="action-btn btn-create">
                    <i class="fas fa-plus"></i> Create Sticker
                </a>
                <a href="sticker-list.php" class="action-btn btn-view">
                    <i class="fas fa-th-list"></i> View All Stickers
                </a>
                <a href="../job/index.php" class="action-btn btn-projects">
                    <i class="fas fa-briefcase"></i> Projects
                </a>
            </div>
        </div>

        <!-- KPI Grid -->
        <div class="kpi-row">
            <div class="kpi-card kpi-total">
                <div class="kpi-info">
                    <span>Total Stickers</span>
                    <h2 id="kpi-total">0</h2>
                    <div class="kpi-sub">
                        <i class="fas fa-running"></i> All Stickers
                    </div>
                </div>
                <div class="kpi-icon-box">
                    <i class="fas fa-id-card"></i>
                </div>
            </div>

            <div class="kpi-card kpi-active">
                <div class="kpi-info">
                    <span>Active Stickers</span>
                    <h2 id="kpi-active">0</h2>
                    <div class="kpi-sub">
                        <i class="fas fa-thumbs-up"></i> Currently Active
                    </div>
                </div>
                <div class="kpi-icon-box">
                    <i class="fas fa-check-double"></i>
                </div>
            </div>

            <div class="kpi-card kpi-pass">
                <div class="kpi-info">
                    <span>Pass Stickers</span>
                    <h2 id="kpi-pass">0</h2>
                    <div class="kpi-sub">
                        <i class="fas fa-check"></i> Approved Equipment
                    </div>
                </div>
                <div class="kpi-icon-box">
                    <i class="fas fa-shield-check"></i>
                </div>
            </div>

            <div class="kpi-card kpi-fail">
                <div class="kpi-info">
                    <span>Fail Stickers</span>
                    <h2 id="kpi-fail">0</h2>
                    <div class="kpi-sub">
                        <i class="fas fa-times"></i> Needs Attention
                    </div>
                </div>
                <div class="kpi-icon-box">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid">
            
            <!-- Recent Stickers Table -->
            <div class="card">
                <div class="card-header">
                    <h3>Recent Stickers</h3>
                    <a href="sticker-list.php" class="btn-view-all">View All</a>
                </div>
                <div style="overflow-x: auto;">
                    <table class="recent-table">
                        <thead>
                            <tr>
                                <th>Inspector</th>
                                <th>Sticker No</th>
                                <th>Result</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="recent-stickers-list">
                            <!-- JS populated -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Activity Sidebar -->
            <div class="card">
                <div class="card-header">
                    <h3>Reviews & Activity</h3>
                </div>
                
                <div class="activity-item">
                    <div class="activity-label">
                        <span>Total Reviews</span>
                        <span class="activity-val" id="total-reviews">0</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar bar-blue" id="progress-reviews" style="width: 0%"></div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-label">
                        <span>Pending Reviews</span>
                        <span class="activity-val" id="pending-reviews">0</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar bar-orange" id="progress-pending" style="width: 0%"></div>
                    </div>
                </div>

                <div class="activity-item">
                    <div class="activity-label">
                        <span>Completed Projects</span>
                        <span class="activity-val" id="completed-projects">0</span>
                    </div>
                    <div class="progress-container">
                        <div class="progress-bar bar-green" id="progress-projects" style="width: 0%"></div>
                    </div>
                </div>

                <div style="margin-top: 40px; text-align: center;">
                    <img src="../assets/img/analytics.svg" alt="Analytics" style="width: 80%; opacity: 0.5;">
                </div>
            </div>

        </div>

    </div>
</div>


<?php include_once('../inc/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<!-- <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script> -->
<script>
    function fetchData() {
        $.ajax({
            url: 'fetch_sticker_status_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // Update Welcome Message
                $('#welcome-msg').text(`Welcome back, ${data.userName}!`);

                // Update KPIs
                animateValue('kpi-total', data.kpis.total);
                animateValue('kpi-active', data.kpis.active);
                animateValue('kpi-pass', data.kpis.pass);
                animateValue('kpi-fail', data.kpis.fail);

                // Update Activity
                $('#total-reviews').text(data.activity.totalReviews);
                $('#pending-reviews').text(data.activity.pendingReviews);
                $('#completed-projects').text(data.activity.completedProjects);

                $('#progress-reviews').css('width', data.activity.reviewProgress + '%');
                $('#progress-pending').css('width', data.activity.pendingProgress + '%');
                $('#progress-projects').css('width', data.activity.projectProgress + '%');

                // Update Recent Table
                let tableHtml = '';
                data.recent.forEach(item => {
                    tableHtml += `
                        <tr>
                            <td>
                                <div class="inspector-cell">
                                    <div class="avatar">${item.initial}</div>
                                    ${item.inspector}
                                </div>
                            </td>
                            <td>${item.sticker_no}</td>
                            <td><span class="badge ${item.resClass}">${item.result}</span></td>
                            <td><span class="badge ${item.statusClass}">${item.status}</span></td>
                            <td style="color: var(--text-muted)">${item.date}</td>
                            <td>
                                <div class="action-btns">
                                    <a href="download.php?sticker_start_no=${item.sticker_no}" class="icon-btn" title="View Details">
                                        <i class="fas fa-search"></i>
                                    </a>
                                    <a href="download.php?sticker_start_no=${item.sticker_no}" class="icon-btn" title="View Analytics">
                                        <i class="fas fa-chart-bar"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    `;
                });
                $('#recent-stickers-list').html(tableHtml);
            }
        });
    }

    function animateValue(id, value) {
        const obj = document.getElementById(id);
        const target = parseInt(value.replace(/,/g, ''));
        let current = 0;
        const duration = 1000;
        const stepTime = 20;
        const increment = target / (duration / stepTime);

        const timer = setInterval(() => {
            current += increment;
            if (current >= target) {
                obj.innerText = value;
                clearInterval(timer);
            } else {
                obj.innerText = Math.floor(current).toLocaleString();
            }
        }, stepTime);
    }

    $(document).ready(function() {
        fetchData();
    });
</script>

</body>
</html>
