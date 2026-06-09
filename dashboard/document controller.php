<?php
include_once('../file/config.php');
include_once('../inc/function.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
   header("Location: ../index.php");
   exit();
}

// Check if the user has the 'document controller' role
if ($_SESSION['role'] !== 'document controller') {
   // Redirect to a default page or show an error
   header("Location: ../index.php");
   exit();
}

// Query to get total projects count
$result_projects = mysqli_query($conn, "SELECT COUNT(*) AS total_projects FROM project_info");
$total_projects = mysqli_fetch_assoc($result_projects)['total_projects'];

// Query to get total pending projects count
$result_pending_projects = mysqli_query($conn, "SELECT COUNT(*) AS pending_projects FROM project_info WHERE project_status = 'Pending'");
$pending_projects = mysqli_fetch_assoc($result_pending_projects)['pending_projects'];

// Query for completed jobs
$res_comp_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM project_info WHERE project_status = 'Completed'");
$completed_projects = mysqli_fetch_assoc($res_comp_count)['total'];

// Query for equipment types count (as 4th stat)
$res_equip_count = mysqli_query($conn, "SELECT COUNT(DISTINCT equipment_type) as total FROM project_info WHERE equipment_type IS NOT NULL AND equipment_type != ''");
$total_equipment_types = mysqli_fetch_assoc($res_equip_count)['total'];

// Query to get recent projects with their status
$query_recent_projects = "SELECT project_no, customer_name, project_status, creation_date 
                          FROM project_info 
                          ORDER BY creation_date DESC 
                          LIMIT 6";
$result_recent_projects = mysqli_query($conn, $query_recent_projects);

$query_completed = "SELECT project_no, customer_name, project_status 
                    FROM project_info 
                    WHERE project_status = 'Completed' 
                    ORDER BY creation_date DESC 
                    LIMIT 4";
$result_completed = mysqli_query($conn, $query_completed);


// Query for Chart Data (Monthly Projects)
$chart_query = "SELECT DATE_FORMAT(creation_date, '%b') as month, COUNT(*) as count 
                FROM project_info 
                WHERE creation_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month 
                ORDER BY creation_date ASC";
$chart_result = mysqli_query($conn, $chart_query);
$months = [];
$counts = [];
while($row = mysqli_fetch_assoc($chart_result)) {
    $months[] = $row['month'];
    $counts[] = (int)$row['count'];
}

// Query for Equipment Type Chart
$equip_query = "SELECT equipment_type, COUNT(*) as cnt 
                FROM project_info 
                WHERE equipment_type IS NOT NULL AND equipment_type != ''
                GROUP BY equipment_type 
                ORDER BY cnt DESC";
$equip_result = mysqli_query($conn, $equip_query);
$equip_labels = [];
$equip_counts = [];
while($row = mysqli_fetch_assoc($equip_result)) {
    $equip_labels[] = ucwords($row['equipment_type']);
    $equip_counts[] = (int)$row['cnt'];
}

// Fetch all projects for Calendar
$calendar_query = "SELECT project_no, creation_date FROM project_info";
$calendar_result = mysqli_query($conn, $calendar_query);
$calendar_events = [];
while($row = mysqli_fetch_assoc($calendar_result)) {
    $calendar_events[] = [
        'title' => $row['project_no'],
        'start' => $row['creation_date'],
        'allDay' => true
    ];
}

// Query to get latest news
$news_query = "SELECT * FROM news ORDER BY created_at DESC";
$news_result = mysqli_query($conn, $news_query);


// Query to get notifications for document controller
$query_notifications = "SELECT id, project_no, notification_message, created_at 
                        FROM project_notifications 
                        WHERE document_controller = 'pending' OR document_controller IS NOT NULL
                        ORDER BY created_at DESC 
                        LIMIT 5";
$result_notifications = mysqli_query($conn, $query_notifications);
$unread_count = mysqli_num_rows($result_notifications);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Controller Dashboard</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/premium-nav.css">
    
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --success-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
            --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
            --danger-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
            --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            --card-shadow: 0 10px 20px rgba(0,0,0,0.05);
            --premium-shadow: 0 15px 35px rgba(0,0,0,0.1);
            --primary: #4f46e5;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --radius-lg: 20px;
            --font: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: #f8f9fa;
            color: var(--text-main);
            font-family: var(--font);
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .main-content {
            padding: 30px 20px;
        }

        /* Hero Section with Shimmer */
        .hero-section {
            background: var(--primary-gradient);
            border-radius: 20px;
            padding: 40px;
            color: white;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: var(--premium-shadow);
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(
                to right,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent
            );
            transform: skewX(-25deg);
            animation: shimmer 6s infinite;
        }

        @keyframes shimmer {
            0% { left: -100%; }
            20% { left: 150%; }
            100% { left: 150%; }
        }

        .hero-section::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }

        .hero-section h1, .hero-section p {
            position: relative;
            z-index: 1;
        }

        /* Modern Stats Cards with Hover Effects */
        .stats-card {
            border: none;
            border-radius: 15px;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            background: white;
            box-shadow: var(--card-shadow);
            height: 100%;
            overflow: hidden;
            cursor: pointer;
        }

        .stats-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: var(--premium-shadow);
        }

        .stats-card .card-body {
            padding: 25px;
            display: flex;
            align-items: center;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 20px;
            font-size: 24px;
            color: white;
            transition: transform 0.3s ease;
        }

        .stats-card:hover .icon-box {
            transform: rotate(10deg) scale(1.1);
            animation: pulse-border 1.5s infinite;
        }

        @keyframes pulse-border {
            0% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }

        .bg-p { background: var(--primary-gradient); }
        .bg-s { background: var(--success-gradient); }
        .bg-w { background: var(--warning-gradient); }
        .bg-i { background: var(--info-gradient); }

        .stats-card h2 {
            font-size: 28px;
            margin-bottom: 0;
            font-weight: 700;
            color: #2d3748;
        }

        .stats-card p {
            color: #718096;
            margin-bottom: 5px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Premium Card Hover */
        .premium-card {
            border-radius: 15px;
            border: none;
            box-shadow: var(--card-shadow);
            background: white;
            margin-bottom: 30px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .premium-card:hover {
            box-shadow: var(--premium-shadow);
        }

        .premium-card .card-header {
            background: transparent;
            border-bottom: 1px solid #edf2f7;
            padding: 20px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .premium-card .card-header h4 {
            margin: 0;
            font-weight: 700;
            color: #2d3748;
        }

        .premium-card .card-body {
            padding: 25px;
        }

        /* Notifications & News (adapted) */
        .list-item-premium {
            padding: 15px;
            border-bottom: 1px solid #edf2f7;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s;
        }

        .list-item-premium:hover { 
            background: #f7fafc; 
            padding-left: 20px;
        }
        .list-item-premium:last-child { border-bottom: none; }

        .list-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 18px;
            background: #bee3f8;
            color: #2c5282;
            transition: all 0.3s ease;
        }

        .list-item-premium:hover .list-icon {
            transform: rotate(15deg);
        }

        .news-card-premium {
            padding: 20px;
            border-radius: 15px;
            color: white;
            margin-bottom: 15px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .news-card-premium:hover {
            transform: translateY(-5px);
        }

        /* Premium Table */
        .modern-table thead th {
            background: #fdfdfd;
            color: #4a5568;
            font-weight: 600;
            padding: 15px 25px;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }

        .modern-table tbody tr {
            transition: background 0.3s ease;
        }

        .modern-table tbody tr:hover {
            background: #f8fafc;
        }

        .modern-table tbody td {
            padding: 18px 25px;
            color: #4a5568;
            border-bottom: 1px solid #edf2f7;
        }

        /* Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-up { animation: fadeInUp 0.6s cubic-bezier(0.23, 1, 0.32, 1) forwards; }

        /* Badge Styles */
        .badge-premium {
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 11px;
            transition: all 0.3s ease;
        }
        .badge-premium:hover {
            filter: brightness(0.9);
            transform: scale(1.05);
        }
        .badge-soft-success { background: #c6f6d5; color: #22543d; }
        .badge-soft-warning { background: #feebc8; color: #744210; }
        .badge-soft-info { background: #bee3f8; color: #2a4365; }

        /* Calendar Styles */
        .calendar-mark {
            width: 10px;
            height: 10px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: block;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(102, 126, 234, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(102, 126, 234, 0); }
        }
        #calendar { margin-top: 10px; }

        /* Button Animations */
        .btn-primary {
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        .btn-primary:active {
            transform: scale(0.95);
        }

        /* ===== PREMIUM DOCUMENT CONTROLLER UI ===== */
        body {
            background:
                radial-gradient(circle at 18% 10%, rgba(20, 184, 166, .20), transparent 30%),
                radial-gradient(circle at 78% 36%, rgba(59, 130, 246, .18), transparent 34%),
                linear-gradient(135deg, #eef8f7 0%, #f6f8fc 46%, #e8eef9 100%);
            color: #101827;
        }

        .document-premium {
            position: relative;
            overflow: hidden;
            min-height: 100vh;
            padding: 42px 28px 56px 28px;
            background:
                radial-gradient(circle at 8% 4%, rgba(45, 212, 191, .18), transparent 28%),
                radial-gradient(circle at 86% 38%, rgba(14, 165, 233, .14), transparent 30%),
                linear-gradient(135deg, rgba(240, 253, 250, .82), rgba(242, 247, 255, .9));
        }

        .document-premium::before {
            content: "";
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(90deg, rgba(255, 255, 255, .55), transparent 38%),
                radial-gradient(circle at 70% 65%, rgba(15, 118, 110, .12), transparent 26%);
        }

        .document-premium > .container-fluid {
            position: relative;
            z-index: 1;
        }

        .document-premium .hero-section,
        .document-premium .stats-card,
        .document-premium .premium-card {
            border: 1px solid rgba(255, 255, 255, .76);
            background: linear-gradient(145deg, rgba(255, 255, 255, .88), rgba(246, 251, 255, .72));
            box-shadow: 0 22px 60px rgba(15, 23, 42, .12);
            backdrop-filter: blur(16px);
        }

        .document-premium .hero-section {
            min-height: 150px;
            padding: 30px 34px;
            border-radius: 24px;
            color: #0f172a;
            box-shadow: 0 24px 70px rgba(15, 23, 42, .12);
        }

        .document-premium .hero-section::before {
            display: none;
        }

        .document-premium .hero-section::after {
            top: -80px;
            right: 7%;
            width: 220px;
            height: 220px;
            background: linear-gradient(135deg, rgba(37, 99, 235, .12), rgba(20, 184, 166, .18));
        }

        .document-premium .hero-section h1 {
            margin-bottom: 8px;
            font-size: clamp(28px, 3vw, 42px);
            font-weight: 900;
            letter-spacing: 0;
            color: #0f172a;
        }

        .document-premium .hero-section p,
        .document-premium .hero-section .opacity-75 {
            color: #5f708b;
            opacity: 1;
        }

        .document-premium .hero-section .col-md-8 {
            position: relative;
            padding-left: 90px;
        }

        .document-premium .hero-section .col-md-8::before {
            content: "\f07c";
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

        .document-premium .stats-card {
            position: relative;
            overflow: hidden;
            min-height: 152px;
            border-radius: 24px;
            cursor: default;
        }

        .document-premium .stats-card::after {
            content: "";
            position: absolute;
            top: -50px;
            right: -42px;
            width: 138px;
            height: 138px;
            border-radius: 50%;
            background: rgba(37, 99, 235, .10);
        }

        .document-premium .stats-card:hover,
        .document-premium .premium-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 28px 70px rgba(15, 23, 42, .14);
        }

        .document-premium .stats-card .card-body {
            position: relative;
            z-index: 1;
            padding: 28px 30px;
        }

        .document-premium .icon-box {
            width: 58px;
            height: 58px;
            flex: 0 0 58px;
            border-radius: 18px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .76);
        }

        .document-premium .bg-p { background: rgba(37, 99, 235, .12); color: #2563eb; }
        .document-premium .bg-s { background: rgba(20, 184, 166, .16); color: #0f766e; }
        .document-premium .bg-w { background: rgba(245, 158, 11, .14); color: #d97706; }
        .document-premium .bg-i { background: rgba(14, 165, 233, .14); color: #0284c7; }

        .document-premium .stats-card p {
            color: #62728a;
            font-weight: 900;
            letter-spacing: .6px;
        }

        .document-premium .stats-card h2 {
            font-size: 42px;
            font-weight: 900;
            color: #0f172a;
        }

        .document-premium .premium-card {
            overflow: hidden;
            border-radius: 24px;
            margin-bottom: 30px;
        }

        .document-premium .premium-card .card-header {
            padding: 22px 26px;
            border-bottom: 1px solid rgba(226, 232, 240, .78);
            background: rgba(255, 255, 255, .38);
        }

        .document-premium .premium-card .card-header h4 {
            font-size: 20px;
            font-weight: 900;
            color: #111827;
        }

        .document-premium .premium-card .card-body {
            padding: 24px 26px;
        }

        .document-premium .project-queue {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .document-premium .project-queue-item {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            gap: 14px;
            align-items: center;
            padding: 16px 18px;
            border: 1px solid rgba(226, 232, 240, .8);
            border-radius: 16px;
            background: rgba(255, 255, 255, .62);
            box-shadow: 0 12px 28px rgba(15, 23, 42, .06);
        }

        .document-premium .project-queue-item:hover {
            transform: translateY(-2px);
            background: rgba(236, 253, 245, .58);
            box-shadow: 0 18px 36px rgba(15, 23, 42, .10);
        }

        .document-premium .project-queue-main {
            min-width: 0;
        }

        .document-premium .project-queue-meta {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 8px;
            color: #64748b;
            font-weight: 800;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .45px;
        }

        .document-premium .project-queue-title {
            margin: 0 0 10px;
            color: #0f172a;
            font-weight: 900;
            font-size: 19px;
            line-height: 1.2;
        }

        .document-premium .project-queue-customer {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin: 0;
            color: #52627a;
        }

        .document-premium .project-queue-customer strong {
            color: #334155;
        }

        .document-premium .project-queue-status {
            display: flex;
            justify-content: flex-end;
            min-width: 108px;
        }

        .document-premium .project-queue-index {
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-right: 10px;
            border-radius: 12px;
            color: #2563eb;
            background: linear-gradient(135deg, #dbeafe, #ccfbf1);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, .76);
        }

        .document-premium .list-item-premium {
            border-bottom: 1px solid rgba(226, 232, 240, .78);
        }

        .document-premium .list-item-premium:hover {
            background: rgba(236, 253, 245, .58);
        }

        .document-premium .list-icon {
            border-radius: 14px;
            color: #2563eb;
            background: linear-gradient(135deg, #dbeafe, #ccfbf1);
        }

        .document-premium .news-card-premium {
            border-radius: 18px;
            box-shadow: 0 18px 38px rgba(15, 23, 42, .12);
        }

        .document-premium .modern-table {
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }

        .document-premium .modern-table thead th {
            padding: 16px 18px;
            border: 0;
            border-bottom: 1px solid rgba(203, 213, 225, .7);
            background: #f4f8fb;
            color: #334155;
            font-weight: 900;
        }

        .document-premium .modern-table tbody td {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(226, 232, 240, .9);
            color: #243044;
            background: rgba(255, 255, 255, .58);
            vertical-align: middle;
        }

        .document-premium .modern-table tbody tr:hover td {
            background: rgba(236, 253, 245, .58);
        }

        .document-premium .badge-premium {
            border-radius: 999px;
            padding: 7px 12px;
            font-weight: 900;
            letter-spacing: .35px;
        }

        .document-premium .badge-soft-success { background: rgba(16, 185, 129, .14); color: #047857; }
        .document-premium .badge-soft-warning { background: rgba(245, 158, 11, .15); color: #92400e; }
        .document-premium .badge-soft-info { background: rgba(14, 165, 233, .14); color: #0369a1; }

        .document-premium .btn-primary,
        .document-premium .btn-outline-primary {
            border: 0;
            border-radius: 12px;
            color: #fff;
            font-weight: 800;
            background: linear-gradient(135deg, #2563eb, #12b8b6);
            box-shadow: 0 12px 24px rgba(37, 99, 235, .20);
        }

        .document-premium .btn-outline-primary:hover,
        .document-premium .btn-primary:hover {
            color: #fff;
            background: linear-gradient(135deg, #1d4ed8, #0faaa8);
        }

        .document-premium .empty-state {
            padding: 36px 20px;
            text-align: center;
            color: #64748b;
        }

        .document-premium .empty-state i {
            display: block;
            margin-bottom: 12px;
            color: #94a3b8;
            font-size: 34px;
        }

        .document-premium #performanceChart,
        .document-premium #equipmentChart,
        .document-premium #calendar {
            min-height: 330px;
        }

        .document-premium .fc-toolbar h2 {
            color: #0f172a;
            font-weight: 900;
            font-size: 20px;
        }

        .document-premium .fc-button {
            border: 0 !important;
            border-radius: 10px !important;
            background: #0f172a !important;
            box-shadow: none !important;
        }

        @media(max-width: 992px) {
            .document-premium {
                padding: 24px 14px 42px;
            }

            .document-premium .hero-section .col-md-8 {
                padding-left: 0;
                padding-top: 82px;
            }

            .document-premium .hero-section .col-md-8::before {
                top: 0;
                transform: none;
            }

            .document-premium .hero-section h1 {
                font-size: 30px;
            }

            .document-premium .hero-section .lead {
                font-size: 15px;
            }

            .document-premium .stats-card h2 {
                font-size: 34px;
            }

            .document-premium .content-grid,
            .document-premium .row {
                row-gap: 0;
            }
        }

        @media(max-width: 576px) {
            body {
                overflow-x: hidden;
            }

            .document-premium {
                padding: 16px 10px 34px;
            }

            .document-premium > .container-fluid {
                padding-left: 0;
                padding-right: 0;
            }

            .document-premium .hero-section,
            .document-premium .premium-card .card-body,
            .document-premium .premium-card .card-header {
                padding-left: 16px;
                padding-right: 16px;
            }

            .document-premium .hero-section {
                min-height: auto;
                margin-bottom: 18px;
                padding-top: 20px;
                padding-bottom: 20px;
                border-radius: 18px;
            }

            .document-premium .hero-section .col-md-8 {
                padding-top: 62px;
            }

            .document-premium .hero-section .col-md-8::before {
                width: 50px;
                height: 50px;
                border-radius: 16px;
                font-size: 22px;
            }

            .document-premium .hero-section h1 {
                font-size: 24px;
                line-height: 1.15;
            }

            .document-premium .hero-section .lead,
            .document-premium .hero-section p {
                font-size: 13px;
                line-height: 1.45;
            }

            .document-premium .hero-section h4 {
                font-size: 15px;
            }

            .document-premium .stats-card .card-body {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
                padding: 18px;
            }

            .document-premium .stats-card {
                min-height: 126px;
                border-radius: 18px;
            }

            .document-premium .stats-card::after {
                width: 108px;
                height: 108px;
            }

            .document-premium .icon-box {
                width: 46px;
                height: 46px;
                flex-basis: 46px;
                border-radius: 15px;
                font-size: 20px;
            }

            .document-premium .stats-card p {
                margin-bottom: 4px;
                font-size: 11px;
            }

            .document-premium .stats-card h2 {
                font-size: 28px;
            }

            .document-premium .premium-card {
                margin-bottom: 18px;
                border-radius: 18px;
            }

            .document-premium .premium-card .card-header {
                align-items: flex-start;
                flex-direction: column;
                gap: 10px;
                padding-top: 16px;
                padding-bottom: 14px;
            }

            .document-premium .premium-card .card-header h4 {
                font-size: 17px;
                line-height: 1.25;
            }

            .document-premium .premium-card .card-body {
                padding-top: 16px;
                padding-bottom: 16px;
            }

            .document-premium #performanceChart,
            .document-premium #equipmentChart,
            .document-premium #calendar {
                min-height: 260px;
            }

            .document-premium .project-queue {
                gap: 10px;
            }

            .document-premium .project-queue-item {
                grid-template-columns: 1fr;
                gap: 10px;
                padding: 14px;
                border-radius: 14px;
            }

            .document-premium .project-queue-meta {
                align-items: center;
                margin-bottom: 7px;
                font-size: 10px;
            }

            .document-premium .project-queue-index {
                width: 28px;
                height: 28px;
                margin-right: 4px;
                border-radius: 10px;
                font-size: 12px;
            }

            .document-premium .project-queue-title {
                margin-bottom: 8px;
                font-size: 16px;
                word-break: break-word;
            }

            .document-premium .project-queue-customer {
                font-size: 13px;
                line-height: 1.35;
            }

            .document-premium .project-queue-status {
                justify-content: flex-start;
                min-width: 0;
            }

            .document-premium .badge-premium {
                padding: 6px 10px;
                font-size: 10px;
            }

            .document-premium .list-item-premium {
                align-items: flex-start;
                flex-direction: column;
                gap: 12px;
                padding: 14px;
            }

            .document-premium .list-item-premium .d-flex {
                align-items: flex-start !important;
                width: 100%;
            }

            .document-premium .list-item-premium .justify-content-between {
                align-items: flex-start !important;
                flex-direction: column;
                gap: 4px;
            }

            .document-premium .list-item-premium .ml-3 {
                margin-left: 0 !important;
                width: 100%;
            }

            .document-premium .list-item-premium .btn {
                width: 100%;
            }

            .document-premium .list-icon {
                width: 38px;
                height: 38px;
                flex: 0 0 38px;
                border-radius: 12px;
                font-size: 15px;
            }

            .document-premium .news-card-premium {
                padding: 16px;
                border-radius: 15px;
            }

            .document-premium .news-card-premium p {
                font-size: 13px;
                line-height: 1.45;
            }

            .document-premium .modern-table thead th,
            .document-premium .modern-table tbody td {
                padding: 12px;
                font-size: 12px;
                white-space: nowrap;
            }

            .document-premium .btn-primary,
            .document-premium .btn-outline-primary {
                width: 100%;
                padding: 9px 12px;
                font-size: 12px;
            }

            .document-premium .fc-toolbar {
                display: flex;
                flex-direction: column;
                gap: 10px;
            }

            .document-premium .fc-toolbar .fc-left,
            .document-premium .fc-toolbar .fc-center,
            .document-premium .fc-toolbar .fc-right {
                float: none;
                text-align: left;
                width: 100%;
            }

            .document-premium .fc-toolbar h2 {
                font-size: 16px;
            }

            .document-premium .fc-button {
                padding: 5px 8px !important;
                font-size: 11px !important;
            }
        }

        @media(max-width: 380px) {
            .document-premium .hero-section h1 {
                font-size: 22px;
            }

            .document-premium .stats-card h2 {
                font-size: 25px;
            }

            .document-premium .project-queue-title {
                font-size: 15px;
            }

            .document-premium .premium-card .card-header h4 {
                font-size: 16px;
            }
        }
    </style>
    <link rel="stylesheet" href="<?php echo $url; ?>assets/plugins/fullcalendar/fullcalendar.min.css">
<body>

<div class="main-content document-premium">
    <div class="container-fluid">
        <!-- Hero Welcome -->
        <div class="row">
            <div class="col-12">
                <div class="hero-section animate-up">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 font-weight-bold mb-2">Workspace Overview</h1>
                            <p class="lead mb-0">Welcome back! Here's an overview of your projects and latest updates.</p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <h4 class="mb-0"><?php echo date('l, F j, Y'); ?></h4>
                            <p class="opacity-75">Document Controller Dashboard</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card animate-up" style="animation-delay: 0.1s">
                    <div class="card-body">
                        <div class="icon-box bg-p">
                            <i class="fa-solid fa-folder-tree"></i>
                        </div>
                        <div>
                            <p>Total Projects</p>
                            <h2><?php echo number_format($total_projects); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card animate-up" style="animation-delay: 0.2s">
                    <div class="card-body">
                        <div class="icon-box bg-w">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <div>
                            <p>Pending Review</p>
                            <h2><?php echo number_format($pending_projects); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card animate-up" style="animation-delay: 0.3s">
                    <div class="card-body">
                        <div class="icon-box bg-s">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                        <div>
                            <p>Completed Jobs</p>
                            <h2><?php echo number_format($completed_projects); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card animate-up" style="animation-delay: 0.4s">
                    <div class="card-body">
                        <div class="icon-box bg-i">
                            <i class="fa-solid fa-gears"></i>
                        </div>
                        <div>
                            <p>Equipment Types</p>
                            <h2><?php echo number_format($total_equipment_types); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="premium-card animate-up" style="animation-delay: 0.45s">
                    <div class="card-header">
                        <h4>Project Performance</h4>
                    </div>
                    <div class="card-body">
                        <div id="performanceChart"></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="premium-card animate-up" style="animation-delay: 0.5s">
                    <div class="card-header">
                        <h4>Equipment Breakdown</h4>
                    </div>
                    <div class="card-body">
                        <div id="equipmentChart"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Projects Section -->
            <div class="col-xl-4">
                <div class="premium-card animate-up" style="animation-delay: 0.6s">
                    <div class="card-header">
                        <h4>Recent Project Queue</h4>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <ul class="project-queue">
                            <?php 
                            mysqli_data_seek($result_recent_projects, 0);
                            $projectIndex = 1;
                            if(mysqli_num_rows($result_recent_projects) > 0):
                                while ($row = mysqli_fetch_assoc($result_recent_projects)): 
                                    $status = htmlspecialchars($row['project_status']);
                                    $badgeClass = ($status == 'Pending') ? 'badge-soft-warning' : 'badge-soft-success';
                            ?>
                                <li class="project-queue-item">
                                    <div class="project-queue-main">
                                        <span class="project-queue-meta">
                                            <span class="project-queue-index"><?php echo $projectIndex; ?></span>
                                            <?php echo date('d M, Y', strtotime($row['creation_date'])); ?>
                                        </span>
                                        <h4 class="project-queue-title">Project #<?php echo htmlspecialchars($row['project_no']); ?></h4>
                                        <p class="project-queue-customer">
                                            <strong>Customer:</strong>
                                            <span><?php echo htmlspecialchars($row['customer_name']); ?></span>
                                        </p>
                                    </div>
                                    <div class="project-queue-status">
                                        <span class="badge-premium <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                                    </div>
                                </li>
                            <?php $projectIndex++; endwhile;
                            else: ?>
                                <div class="empty-state">
                                    <i class="fa-solid fa-folder-open"></i>
                                    <p>No recent projects</p>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Notifications & News -->
            <div class="col-xl-8">
                <div class="premium-card animate-up" style="animation-delay: 0.7s">
                    <div class="card-header">
                        <h4>Notifications</h4>
                        <?php if($unread_count > 0): ?>
                            <span class="badge badge-danger"><?php echo $unread_count; ?> New</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-0">
                        <div style="max-height: 400px; overflow-y: auto;">
                            <?php 
                            mysqli_data_seek($result_notifications, 0);
                            if(mysqli_num_rows($result_notifications) > 0):
                                while($notif = mysqli_fetch_assoc($result_notifications)): 
                            ?>
                                <div class="list-item-premium">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="list-icon">
                                            <i class="fas fa-bell"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="font-weight-bold text-dark"><?php echo htmlspecialchars($notif['project_no']); ?></div>
                                                <small class="text-muted"><?php echo date('M d, g:i A', strtotime($notif['created_at'])); ?></small>
                                            </div>
                                            <div class="text-muted small"><?php echo htmlspecialchars($notif['notification_message']); ?></div>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <a href="../job/job-details.php?id=<?php echo $notif['project_no']; ?>" class="btn btn-sm btn-primary">View</a>
                                    </div>
                                </div>
                            <?php endwhile;
                            else: ?>
                                <div class="empty-state">
                                    <i class="fa-solid fa-bell-slash"></i>
                                    <p>No new notifications</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Latest News -->
                <div class="premium-card animate-up" style="animation-delay: 0.8s">
                    <div class="card-header">
                        <h4>Latest News & Updates</h4>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php
                            $news_colors = ['bg-p', 'bg-s', 'bg-w', 'bg-i'];
                            $idx = 0;
                            mysqli_data_seek($news_result, 0);
                            while ($news_row = mysqli_fetch_assoc($news_result)) {
                                if ($idx >= 2) break;
                                ?>
                                <div class="col-md-6">
                                    <div class="news-card-premium <?php echo $news_colors[$idx % 4]; ?>">
                                        <p class="mb-2"><?php echo htmlspecialchars($news_row['news_text']); ?></p>
                                        <small><i class="fa-solid fa-clock mr-1"></i> <?php echo date("F j, Y", strtotime($news_row['created_at'])); ?></small>
                                    </div>
                                </div>
                                <?php $idx++;
                            } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Projects Table -->
        <div class="row">
            <div class="col-12">
                <div class="premium-card animate-up" style="animation-delay: 0.9s">
                    <div class="card-header">
                        <h4>Detailed Project List</h4>
                        <a href="../job/overall-job-list.php" class="btn btn-sm btn-outline-primary">View All Projects</a>
                    </div>
                    <div class="table-responsive">
                        <table class="table modern-table">
                            <thead>
                                <tr>
                                    <th>Project ID</th>
                                    <th>Status</th>
                                    <th>Customer</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                mysqli_data_seek($result_recent_projects, 0);
                                while ($row = mysqli_fetch_assoc($result_recent_projects)):
                                    $status = $row["project_status"];
                                    $status_class = ($status === "Completed") ? 'badge-soft-success' : 'badge-soft-warning';
                                ?>
                                    <tr>
                                        <td class="font-weight-bold">#<?php echo htmlspecialchars($row["project_no"]); ?></td>
                                        <td><span class="badge-premium <?php echo $status_class; ?>"><?php echo $status; ?></span></td>
                                        <td><?php echo htmlspecialchars($row["customer_name"]); ?></td>
                                        <td>
                                            <a href="../job/job-details.php?id=<?php echo $row['project_no']; ?>" class="btn btn-sm btn-primary">Details</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar Section -->
        <div class="row">
            <div class="col-12">
                <div class="premium-card animate-up" style="animation-delay: 1s">
                    <div class="card-header">
                        <h4>Project Calendar</h4>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<!-- Scripts -->
<script src="<?php echo $url; ?>assets/plugins/apex/apexcharts.min.js"></script>
<script src="<?php echo $url; ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo $url; ?>assets/plugins/fullcalendar/fullcalendar.min.js"></script>

<script>
$(document).ready(function() {
    if (typeof ApexCharts !== 'undefined') {
        // Performance Chart (Area Chart)
        var performanceOptions = {
            series: [{
                name: 'Projects',
                data: <?php echo json_encode($counts); ?>
            }],
            chart: {
                height: 350,
                type: 'area',
                toolbar: { show: false },
                zoom: { enabled: false }
            },
            colors: ['#667eea'],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 3 },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.05,
                    stops: [20, 100, 100, 100]
                }
            },
            xaxis: {
                categories: <?php echo json_encode($months); ?>,
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: { show: false },
            grid: {
                borderColor: '#edf2f7',
                strokeDashArray: 4,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: false } }
            },
            tooltip: {
                theme: 'light',
                y: {
                    formatter: function(value) { return value + " projects" }
                }
            },
            noData: {
                text: 'No project data',
                align: 'center',
                verticalAlign: 'middle',
                style: { color: '#718096', fontSize: '14px' }
            }
        };

        var perfChart = new ApexCharts(document.querySelector("#performanceChart"), performanceOptions);
        perfChart.render();

        // Equipment Breakdown (Donut Chart)
        var equipOptions = {
            series: <?php echo json_encode($equip_counts); ?>,
            labels: <?php echo json_encode($equip_labels); ?>,
            chart: {
                type: 'donut',
                height: 350,
                toolbar: { show: false }
            },
            colors: ['#4F46E5','#10B981','#F59E0B','#EF4444','#0EA5E9','#8B5CF6','#14B8A6','#F97316','#EC4899'],
            legend: {
                position: 'bottom',
                fontSize: '13px',
                fontWeight: 600,
                labels: { colors: '#4a5568' }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '65%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total',
                                formatter: function(w) {
                                    return w.globals.seriesTotals.reduce(function(a, b) { return a + b; }, 0);
                                }
                            }
                        }
                    }
                }
            },
            noData: {
                text: 'No equipment data',
                align: 'center',
                verticalAlign: 'middle',
                style: { color: '#718096', fontSize: '14px' }
            }
        };

        var equipChart = new ApexCharts(document.querySelector("#equipmentChart"), equipOptions);
        equipChart.render();
    }

    if ($.fn.fullCalendar) {
        // Project Calendar
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            events: <?php echo json_encode($calendar_events); ?>,
            eventRender: function(event, element) {
                element.html('<span class="calendar-mark"></span>');
                element.attr('title', 'Project #' + event.title);
            },
            height: 'auto',
            eventLimit: true
        });
    }

    // Clear Notifications logic
    document.querySelector('.mark-all-read')?.addEventListener('click', function(e) {
        e.preventDefault();
        if(!confirm('Are you sure you want to clear all notifications?')) return;
        
        fetch('mark_notifications_read.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    });
});
</script>

</body>
</html>
