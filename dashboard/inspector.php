<?php
include_once('../file/config.php');
include_once('../inc/function.php');

// Check if the user is logged in
$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user || $user_role !== 'inspector') {
    header("Location: ../index.php");
    exit;
}

// Query to get total projects count for the logged-in inspector
$result_projects = mysqli_query($conn, "SELECT COUNT(*) AS total_projects FROM project_info WHERE inspector_name = '$logged_in_user'");
$total_projects = mysqli_fetch_assoc($result_projects)['total_projects'];

// Query to get total pending projects count for the logged-in inspector
$result_pending_projects = mysqli_query($conn, "SELECT COUNT(*) AS total_pending_projects FROM project_info WHERE inspector_name = '$logged_in_user' AND project_status = 'Pending'");
$total_pending_projects = mysqli_fetch_assoc($result_pending_projects)['total_pending_projects'];

// Query to get total pending checklist count for the logged-in inspector
$result_pending_checklist = mysqli_query($conn, "SELECT COUNT(*) AS total_pending_checklist FROM project_info WHERE inspector_name = '$logged_in_user' AND checklist_status = 'Pending'");
$total_pending_checklist = mysqli_fetch_assoc($result_pending_checklist)['total_pending_checklist'];

// Query to get total pending report count for the logged-in inspector
$result_pending_report = mysqli_query($conn, "SELECT COUNT(*) AS total_pending_report FROM project_info WHERE inspector_name = '$logged_in_user' AND report_status = 'Pending'");
$total_pending_report = mysqli_fetch_assoc($result_pending_report)['total_pending_report'];

// Fetch recent projects with their status for the logged-in inspector
$query_recent_projects = "SELECT project_no, customer_name, project_status, creation_date 
                          FROM project_info 
                          WHERE inspector_name = '$logged_in_user'
                          ORDER BY creation_date DESC LIMIT 5";

$result_recent_projects = mysqli_query($conn, $query_recent_projects);

// Fetch ongoing projects for the logged-in inspector
$query_ongoing_projects = "SELECT project_no, customer_name, project_status 
                           FROM project_info 
                           WHERE inspector_name = '$logged_in_user' AND project_status = 'Pending' 
                           ORDER BY creation_date DESC 
                           LIMIT 4";

$result_ongoing_projects = mysqli_query($conn, $query_ongoing_projects);

// Query for Chart Data (Monthly Projects)
$chart_query = "SELECT DATE_FORMAT(creation_date, '%b') as month, COUNT(*) as count 
                FROM project_info 
                WHERE inspector_name = '$logged_in_user' 
                AND creation_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY month 
                ORDER BY creation_date ASC";
$chart_result = mysqli_query($conn, $chart_query);
$months = [];
$counts = [];
while($row = mysqli_fetch_assoc($chart_result)) {
    $months[] = $row['month'];
    $counts[] = (int)$row['count'];
}

// Fetch all notifications for the inspector
$query_notifications = "SELECT project_no FROM project_notifications 
                        WHERE inspector_name = '$logged_in_user'";

$result_notifications = mysqli_query($conn, $query_notifications);

while ($row = mysqli_fetch_assoc($result_notifications)) {
    $project_no = $row['project_no'];

    // Check project status from project_info table
    $status_query = "SELECT project_status FROM project_info WHERE project_no = '$project_no'";
    $status_result = mysqli_query($conn, $status_query);
    $status_row = mysqli_fetch_assoc($status_result);

    // If project is completed, delete the notification
    if ($status_row && $status_row['project_status'] == "Completed") {
        $delete_query = "DELETE FROM project_notifications WHERE project_no = '$project_no'";
        mysqli_query($conn, $delete_query);
    }
}

// Now fetch only active notifications
$query_notifications = "SELECT pn.*, pi.project_status 
                       FROM project_notifications pn
                       JOIN project_info pi ON pn.project_no = pi.project_no
                       WHERE pn.inspector_name = '$logged_in_user' 
                       ORDER BY pn.created_at DESC";

$result_notifications = mysqli_query($conn, $query_notifications);
$notification_count = mysqli_num_rows($result_notifications);

// Fetch all projects for Calendar
$calendar_query = "SELECT project_no, creation_date FROM project_info WHERE inspector_name = '$logged_in_user'";
$calendar_result = mysqli_query($conn, $calendar_query);
$calendar_events = [];
while($row = mysqli_fetch_assoc($calendar_result)) {
    $calendar_events[] = [
        'title' => $row['project_no'],
        'start' => $row['creation_date'],
        'allDay' => true
    ];
}

?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --success-gradient: linear-gradient(135deg, #2af598 0%, #009efd 100%);
        --warning-gradient: linear-gradient(135deg, #f6d365 0%, #fda085 100%);
        --danger-gradient: linear-gradient(135deg, #ff0844 0%, #ffb199 100%);
        --info-gradient: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        --card-shadow: 0 10px 20px rgba(0,0,0,0.05);
        --premium-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }

    .main-content {
        background: #f8f9fa;
        padding-top: 20px;
    }

    /* Hero Section */
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

    .hero-section h1, .hero-section p, .hero-section h4 {
        position: relative;
        z-index: 1;
    }

    /* Modern Stats Cards */
    .stats-card {
        border: none;
        border-radius: 15px;
        transition: all 0.3s ease;
        background: white;
        box-shadow: var(--card-shadow);
        height: 100%;
        overflow: hidden;
    }

    .stats-card:hover {
        transform: translateY(-5px);
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
    }

    .bg-p { background: var(--primary-gradient); }
    .bg-s { background: var(--success-gradient); }
    .bg-w { background: var(--warning-gradient); }
    .bg-d { background: var(--danger-gradient); }
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

    /* Premium Card */
    .premium-card {
        border-radius: 15px;
        border: none;
        box-shadow: var(--card-shadow);
        background: white;
        margin-bottom: 30px;
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

    /* Timeline Styles - Redesigned */
    .timeline {
        list-style: none;
        padding: 0;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e2e8f0;
        border-radius: 10px;
    }

    .timeline .event {
        position: relative;
        padding-left: 55px;
        padding-bottom: 30px;
        transition: all 0.3s ease;
    }

    .timeline .event:hover {
        transform: translateX(5px);
    }

    .timeline .event::before {
        content: none; /* Removed old dot */
    }

    .timeline .timeline-marker {
        position: absolute;
        left: 0;
        top: 0;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        border: 2px solid #edf2f7;
        z-index: 1;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(0,0,0,0.02);
    }

    .timeline .event:hover .timeline-marker {
        border-color: #667eea;
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.2);
        background: #667eea;
        color: white;
    }

    .timeline .marker-pending { color: #f6ad55; border-color: #fbd38d; }
    .timeline .marker-completed { color: #48bb78; border-color: #9ae6b4; }

    .timeline .event .event-date {
        font-size: 11px;
        font-weight: 700;
        color: #718096;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
        display: block;
    }

    .timeline .event h4 {
        margin: 0 0 8px 0;
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
    }

    .timeline .event .event-details {
        background: #f8fafc;
        border-radius: 12px;
        padding: 12px 15px;
        border: 1px solid #edf2f7;
    }

    .timeline .event .event-details p {
        margin-bottom: 4px;
        color: #4a5568;
        font-size: 13px;
    }

    .timeline .event .event-details p:last-child {
        margin-bottom: 0;
    }

    /* Notifications List */
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
    }

    .list-item-premium:last-child {
        border-bottom: none;
    }

    .list-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        font-size: 18px;
        background: #f7fafc;
    }

    .list-icon.correction {
        background: #fed7d7;
        color: #c53030;
    }

    .list-icon.info {
        background: #bee3f8;
        color: #2c5282;
    }

    /* Ongoing Projects Card */
    .project-item {
        padding: 15px;
        border-radius: 10px;
        background: #f7fafc;
        margin-bottom: 15px;
        transition: all 0.3s;
    }

    .project-item:hover {
        background: #edf2f7;
        transform: translateX(5px);
    }

    .project-icon {
        width: 50px;
        height: 50px;
        border-radius: 10px;
        background: var(--primary-gradient);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 20px;
        margin-right: 15px;
    }

    /* Premium Table */
    .modern-table {
        margin-bottom: 0;
    }

    .modern-table thead th {
        background: #fdfdfd;
        color: #4a5568;
        font-weight: 600;
        border-top: none;
        padding: 15px 25px;
        text-transform: uppercase;
        font-size: 12px;
        letter-spacing: 0.5px;
    }

    .modern-table tbody td {
        padding: 18px 25px;
        vertical-align: middle;
        color: #4a5568;
        border-bottom: 1px solid #edf2f7;
    }

    .modern-table tbody tr:last-child td {
        border-bottom: none;
    }

    .modern-table tbody tr:hover {
        background: #f7fafc;
    }

    /* Badge Styles */
    .badge-premium {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 11px;
    }

    .badge-soft-success { background: #c6f6d5; color: #22543d; }
    .badge-soft-danger { background: #fed7d7; color: #822727; }
    .badge-soft-warning { background: #feebc8; color: #744210; }
    .badge-soft-info { background: #bee3f8; color: #2a4365; }

    /* Button Styles */
    .btn-details {
        background: #f7fafc;
        color: #4a5568;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 6px 15px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-details:hover {
        background: var(--primary-gradient);
        color: white;
        border-color: transparent;
    }

    .btn-view {
        background: var(--primary-gradient);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 6px 15px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-view:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        color: white;
    }

    .btn-complete {
        background: var(--success-gradient);
        color: white;
        border: none;
        border-radius: 8px;
        padding: 6px 15px;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-complete:hover {
        transform: scale(1.05);
        box-shadow: 0 5px 15px rgba(42, 245, 152, 0.3);
        color: white;
    }

    /* Chart Container */
    #inspectorChart {
        min-height: 350px;
    }

    /* Animations */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .animate-up {
        animation: fadeInUp 0.5s ease forwards;
    }

    /* Scrollbar Styling */
    .notification-scroll::-webkit-scrollbar {
        width: 6px;
    }

    .notification-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    .notification-scroll::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }

    .notification-scroll::-webkit-scrollbar-thumb:hover {
        background: #764ba2;
    }
    /* Calendar Styles */
    #calendar {
        margin-top: 10px;
    }
    .fc-event {
        cursor: pointer;
        background-color: transparent !important;
        border: none !important;
        display: flex;
        justify-content: center;
        align-items: center;
    }
    .calendar-mark {
        width: 10px;
        height: 10px;
        background: var(--primary-gradient);
        border-radius: 50%;
        display: block;
        box-shadow: 0 0 5px rgba(102, 126, 234, 0.5);
    }
    .fc-day-grid-event .fc-content {
        white-space: normal;
        text-align: center;
    }
    .fc-event .project-tooltip {
        display: none;
        position: absolute;
        bottom: 100%;
        left: 50%;
        transform: translateX(-50%);
        background: #2d3748;
        color: white;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 11px;
        white-space: nowrap;
        z-index: 1000;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .fc-event:hover .project-tooltip {
        display: block;
    }
    .fc-unthemed td.fc-today {
        background: #f0f4ff;
    }
    .fc-header-toolbar h2 {
        font-size: 18px !important;
        font-weight: 700;
        color: #2d3748;
    }
    .fc-button {
        background: white !important;
        border: 1px solid #e2e8f0 !important;
        color: #4a5568 !important;
        box-shadow: none !important;
        text-transform: capitalize !important;
        font-weight: 600 !important;
    }
    .fc-button-active {
        background: var(--primary-gradient) !important;
        color: white !important;
        border-color: transparent !important;
    }

    /* Admin dashboard premium UI alignment */
    :root {
        --glass-ink: #111827;
        --glass-muted: #6b7280;
        --glass-line: rgba(255, 255, 255, 0.62);
        --glass-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
        --glass-blue: #2563eb;
        --glass-cyan: #14b8a6;
        --glass-violet: #7048e8;
        --glass-amber: #f59e0b;
        --glass-red: #ef4444;
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
        background: transparent;
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

    .dashboard-glass .container-fluid > .row {
        margin-bottom: 30px;
        row-gap: 24px;
    }

    .dashboard-glass .container-fluid > .row:last-child {
        margin-bottom: 0;
    }

    .dashboard-glass .row > [class*="col-"] {
        min-width: 0;
    }

    .hero-section {
        margin-bottom: 0;
        padding: 24px;
        border: 1px solid var(--glass-line);
        border-radius: 18px;
        color: var(--glass-ink);
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.44));
        box-shadow: 0 20px 48px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
    }

    .hero-section::after {
        display: none;
    }

    .hero-section h1 {
        color: var(--glass-ink);
        font-size: clamp(26px, 2vw, 36px);
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
    }

    .hero-section p,
    .hero-section h4 {
        color: var(--glass-muted);
    }

    .hero-section h4 {
        color: var(--glass-ink);
        font-weight: 800;
    }

    .stats-card,
    .premium-card {
        height: 100%;
        margin-bottom: 0;
        border: 1px solid var(--glass-line);
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.84), rgba(255, 255, 255, 0.58));
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }

    .stats-card:hover,
    .premium-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--glass-shadow);
    }

    .stats-card .card-body {
        justify-content: space-between;
        gap: 16px;
        padding: 24px;
    }

    .stats-card .card-body > div:last-child {
        order: -1;
    }

    .icon-box {
        width: 56px;
        height: 56px;
        margin-right: 0;
        border-radius: 16px;
        color: var(--glass-blue);
        background: rgba(37, 99, 235, 0.13);
        font-size: 22px;
        flex: 0 0 auto;
    }

    .bg-p { background: rgba(112, 72, 232, 0.13); color: var(--glass-violet); }
    .bg-w { background: rgba(245, 158, 11, 0.16); color: #b45309; }
    .bg-d { background: rgba(239, 68, 68, 0.13); color: #b91c1c; }
    .bg-i { background: rgba(37, 99, 235, 0.13); color: var(--glass-blue); }
    .bg-s { background: rgba(20, 184, 166, 0.15); color: #0f766e; }

    .stats-card p {
        margin: 0;
        color: var(--glass-muted);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .stats-card h2 {
        margin: 8px 0 0;
        color: var(--glass-ink);
        font-size: 30px;
        font-weight: 800;
        line-height: 1;
    }

    .premium-card .card-header {
        gap: 14px;
        min-height: 74px;
        padding: 22px 24px 0;
        border-bottom: 0;
        background: transparent;
    }

    .premium-card .card-header h4 {
        color: var(--glass-ink);
        font-size: 18px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .premium-card .card-body {
        padding: 22px 24px 24px;
    }

    .badge,
    .badge-premium {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 6px 12px;
        border-radius: 8px;
        border: 0;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .badge-soft-success { background: rgba(16, 185, 129, 0.16); color: #047857; }
    .badge-soft-danger,
    .badge-danger { background: rgba(239, 68, 68, 0.13); color: #b91c1c; }
    .badge-soft-warning { background: rgba(245, 158, 11, 0.16); color: #b45309; }
    .badge-soft-info { background: rgba(37, 99, 235, 0.12); color: #1d4ed8; }

    .project-item,
    .timeline .event .event-details,
    .news-card-mini {
        border: 1px solid rgba(226, 232, 240, 0.72);
        border-radius: 12px;
        background: rgba(248, 250, 252, 0.82);
    }

    .project-item:hover {
        background: rgba(255, 255, 255, 0.92);
        transform: translateY(-2px);
    }

    .project-icon {
        border-radius: 14px;
        background: rgba(37, 99, 235, 0.12);
        color: var(--glass-blue);
    }

    .timeline::before {
        background: rgba(148, 163, 184, 0.28);
    }

    .timeline .timeline-marker {
        border-color: rgba(226, 232, 240, 0.95);
        border-radius: 14px;
        background: #ffffff;
    }

    .timeline .event:hover .timeline-marker {
        border-color: rgba(37, 99, 235, 0.24);
        background: rgba(37, 99, 235, 0.1);
        color: var(--glass-blue);
    }

    .timeline .event h4 {
        color: var(--glass-ink);
    }

    .list-item-premium {
        align-items: flex-start;
        gap: 14px;
        padding: 16px 24px;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        background: transparent;
    }

    .list-item-premium:hover {
        background: rgba(248, 250, 252, 0.72);
    }

    .list-icon {
        width: 42px;
        height: 42px;
        margin-right: 14px;
        border-radius: 14px;
        flex: 0 0 auto;
    }

    .list-icon.correction {
        background: rgba(239, 68, 68, 0.13);
        color: #b91c1c;
    }

    .list-icon.info {
        background: rgba(37, 99, 235, 0.12);
        color: #1d4ed8;
    }

    .btn-details,
    .btn-view,
    .btn-complete,
    .premium-card .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        padding: 8px 12px;
        border: 1px solid rgba(226, 232, 240, 0.92);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.78);
        color: #334155;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
        transition: all 0.2s ease;
    }

    .btn-details:hover,
    .btn-view:hover,
    .btn-complete:hover,
    .premium-card .btn-primary:hover {
        color: #ffffff;
        border-color: transparent;
        background: linear-gradient(135deg, var(--glass-blue), var(--glass-cyan));
        box-shadow: 0 14px 30px rgba(37, 99, 235, 0.18);
        transform: translateY(-1px);
        text-decoration: none;
    }

    .modern-table {
        width: 100%;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0 10px;
    }

    .modern-table thead th {
        padding: 12px 15px;
        border: none;
        background: transparent;
        color: var(--glass-muted);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    .modern-table tbody tr {
        background: rgba(255, 255, 255, 0.86);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .modern-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.92);
        transform: translateY(-2px);
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.08);
    }

    .modern-table td {
        padding: 18px 15px;
        border: none;
        color: #475569;
        vertical-align: middle;
    }

    .modern-table td:first-child {
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
    }

    .modern-table td:last-child {
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .notification-scroll::-webkit-scrollbar-thumb {
        background: rgba(37, 99, 235, 0.55);
    }

    #inspectorChart {
        min-height: 350px;
    }

    #calendar {
        padding: 4px;
    }

    .fc-toolbar {
        margin-bottom: 18px;
    }

    .fc-header-toolbar h2 {
        color: var(--glass-ink);
    }

    .fc-button {
        border-radius: 8px !important;
        background: rgba(255, 255, 255, 0.78) !important;
        color: #334155 !important;
    }

    .fc-button-active,
    .fc-button:hover {
        background: linear-gradient(135deg, var(--glass-blue), var(--glass-cyan)) !important;
        color: #ffffff !important;
    }

    .calendar-mark {
        background: var(--glass-cyan);
        box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.13);
    }

    @media (max-width: 991.98px) {
        .dashboard-glass .container-fluid > .row {
            margin-bottom: 24px;
            row-gap: 20px;
        }

        .dashboard-glass {
            padding: 0 4px 28px;
        }

        .hero-section {
            padding: 20px;
        }

        .premium-card .card-header {
            align-items: flex-start;
            flex-wrap: wrap;
            min-height: auto;
            padding: 20px 20px 0;
        }

        .premium-card .card-body {
            padding: 20px;
        }

        .list-item-premium {
            flex-direction: column;
        }
    }

    @media (max-width: 767.98px) {
        .dashboard-glass .container-fluid {
            padding-left: 12px;
            padding-right: 12px;
        }

        .dashboard-glass .container-fluid > .row {
            margin-bottom: 20px;
            row-gap: 18px;
        }

        .stats-card .card-body {
            align-items: flex-start;
            padding: 20px;
        }

        .stats-card h2 {
            font-size: 26px;
        }

        #inspectorChart {
            min-height: 280px;
        }

        .modern-table {
            min-width: 760px;
        }

        .notification-scroll .list-item-premium > .ml-3 {
            width: 100%;
            margin-left: 0 !important;
        }

        .notification-scroll .btn-view,
        .notification-scroll .btn-complete {
            width: 100%;
            margin: 6px 0 0 !important;
        }

        .fc-toolbar.fc-header-toolbar {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .fc-toolbar .fc-left,
        .fc-toolbar .fc-center,
        .fc-toolbar .fc-right {
            float: none;
            width: 100%;
            text-align: left;
        }
    }

    @media (max-width: 575.98px) {
        .hero-section {
            padding: 18px;
        }

        .hero-section h1 {
            font-size: 24px;
        }

        .premium-card .card-header,
        .premium-card .card-body {
            padding-left: 16px;
            padding-right: 16px;
        }

        .premium-card .card-header h4 {
            font-size: 16px;
        }

        .project-item {
            align-items: flex-start !important;
            flex-direction: column;
            gap: 10px;
        }

        #inspectorChart {
            min-height: 250px;
        }
    }
</style>

<link rel="stylesheet" href="<?php echo $url; ?>assets/plugins/fullcalendar/fullcalendar.min.css">

<div class="main-content dashboard-glass">
    <div class="container-fluid">
        <!-- Hero Welcome -->
        <div class="row">
            <div class="col-12">
                <div class="hero-section animate-up">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 font-weight-bold mb-2">Welcome Back, <?php echo $logged_in_user; ?>!</h1>
                            <p class="lead mb-0">Here's an overview of your inspection projects and pending tasks.</p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <h4 class="mb-0"><?php echo date('l, F j, Y'); ?></h4>
                            <p class="opacity-75">Inspector Dashboard</p>
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
                            <i class="fa-solid fa-hourglass-start"></i>
                        </div>
                        <div>
                            <p>Pending Projects</p>
                            <h2><?php echo number_format($total_pending_projects); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card animate-up" style="animation-delay: 0.3s">
                    <div class="card-body">
                        <div class="icon-box bg-d">
                            <i class="fa-solid fa-clipboard-list"></i>
                        </div>
                        <div>
                            <p>Pending Checklists</p>
                            <h2><?php echo number_format($total_pending_checklist); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card animate-up" style="animation-delay: 0.4s">
                    <div class="card-body">
                        <div class="icon-box bg-i">
                            <i class="fa-solid fa-file-alt"></i>
                        </div>
                        <div>
                            <p>Pending Reports</p>
                            <h2><?php echo number_format($total_pending_report); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Chart Section -->
            <div class="col-xl-8">
                <div class="premium-card animate-up" style="animation-delay: 0.5s">
                    <div class="card-header">
                        <h4>Inspection Performance Trend</h4>
                        <div class="badge badge-soft-info">Last 6 Months</div>
                    </div>
                    <div class="card-body">
                        <div id="inspectorChart"></div>
                    </div>
                </div>

            </div>

            <!-- Ongoing Projects -->
            <div class="col-xl-4">
                <div class="premium-card animate-up" style="animation-delay: 0.6s">
                    <div class="card-header">
                        <h4>Ongoing Projects</h4>
                        <?php if($total_pending_projects > 0): ?>
                            <span class="badge badge-soft-warning"><?php echo $total_pending_projects; ?> Active</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <?php 
                        if(mysqli_num_rows($result_ongoing_projects) > 0):
                            while ($row = mysqli_fetch_assoc($result_ongoing_projects)): ?>
                            <div class="project-item d-flex align-items-center">
                                <div class="project-icon">
                                    <i class="fa-solid fa-file-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold text-dark"><?php echo htmlspecialchars($row['project_no']); ?></div>
                                    <div class="small text-muted">Client: <?php echo htmlspecialchars($row['customer_name']); ?></div>
                                </div>
                                <span class="badge badge-soft-warning">Pending</span>
                            </div>
                        <?php endwhile;
                        else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-check-double fa-3x mb-3 opacity-25"></i>
                                <p>No ongoing projects</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Projects Timeline -->
            <div class="col-xl-4">
                <div class="premium-card animate-up" style="animation-delay: 0.7s">
                    <div class="card-header">
                        <h4>Recent Projects Timeline</h4>
                    </div>
                    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
                        <ul class="timeline">
                            <?php 
                            mysqli_data_seek($result_recent_projects, 0);
                            if(mysqli_num_rows($result_recent_projects) > 0):
                                while ($row = mysqli_fetch_assoc($result_recent_projects)): 
                                    $status = htmlspecialchars($row['project_status']);
                                    $isPending = ($status == 'Pending');
                                    $badgeClass = $isPending ? 'badge-soft-warning' : 'badge-soft-success';
                                    $markerClass = $isPending ? 'marker-pending' : 'marker-completed';
                                    $icon = $isPending ? 'fa-clock-rotate-left' : 'fa-circle-check';
                            ?>
                                <li class="event">
                                    <div class="timeline-marker <?php echo $markerClass; ?>">
                                        <i class="fa-solid <?php echo $icon; ?>"></i>
                                    </div>
                                    <span class="event-date"><?php echo date('d M, Y', strtotime($row['creation_date'])); ?></span>
                                    <h4>Project #<?php echo htmlspecialchars($row['project_no']); ?></h4>
                                    <div class="event-details">
                                        <p><strong>Customer:</strong> <?php echo htmlspecialchars($row['customer_name']); ?></p>
                                        <p><strong>Status:</strong> <span class="badge-premium <?php echo $badgeClass; ?>"><?php echo $status; ?></span></p>
                                    </div>
                                </li>
                            <?php endwhile;
                            else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-folder-open fa-3x mb-3 opacity-25"></i>
                                    <p>No recent projects</p>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Notifications Section -->
            <div class="col-xl-8">
                <div class="premium-card animate-up" style="animation-delay: 0.8s">
                    <div class="card-header">
                        <h4>Notifications</h4>
                        <?php if($notification_count > 0): ?>
                            <span class="badge badge-danger"><?php echo $notification_count; ?> New</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-0">
                        <div class="notification-scroll" style="max-height: 500px; overflow-y: auto;">
                            <?php 
                            mysqli_data_seek($result_notifications, 0);
                            if(mysqli_num_rows($result_notifications) > 0):
                                while($notif = mysqli_fetch_assoc($result_notifications)): 
                                    $isCorrection = strpos($notif['Notification_message'], 'Corrections needed') !== false;
                                    $iconClass = $isCorrection ? 'correction' : 'info';
                                    $icon = $isCorrection ? 'fa-exclamation-circle' : 'fa-info-circle';
                            ?>
                                <div class="list-item-premium">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <div class="list-icon <?php echo $iconClass; ?>">
                                            <i class="fas <?php echo $icon; ?>"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <div class="font-weight-bold text-dark"><?php echo htmlspecialchars($notif['project_no']); ?></div>
                                                <small class="text-muted"><?php echo date('M d, g:i A', strtotime($notif['created_at'])); ?></small>
                                            </div>
                                            <div class="text-muted small"><?php echo htmlspecialchars($notif['Notification_message']); ?></div>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <?php if ($isCorrection): ?>
                                            <a href="../job/job-details.php?id=<?php echo $notif['project_no']; ?>" class="btn btn-sm btn-view mr-2">View</a>
                                            <button class="btn btn-sm btn-complete mark-complete-btn" data-project="<?php echo $notif['project_no']; ?>">
                                                <i class="fas fa-check-circle mr-1"></i> Complete
                                            </button>
                                        <?php else: ?>
                                            <a href="../job/job-details.php?id=<?php echo $notif['project_no']; ?>" class="btn btn-sm btn-view">View</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endwhile;
                            else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-check-double fa-3x mb-3 opacity-25"></i>
                                    <p>No new notifications</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Projects Table -->
        <div class="row">
            <div class="col-xl-12">
                <div class="premium-card animate-up" style="animation-delay: 0.9s">
                    <div class="card-header">
                        <h4>Recent Projects</h4>
                        <a href="../job/export_jobs1.php" class="btn btn-sm btn-primary">Export All</a>
                    </div>
                    <div class="table-responsive">
                        <table id="job-table" class="table modern-table">
                            <thead>
                                <tr>
                                    <th>Project ID</th>
                                    <th>Start Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Equip. Type</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_recent = "SELECT * FROM project_info WHERE inspector_name = '$logged_in_user' ORDER BY creation_date DESC LIMIT 10";
                                $result_recent = mysqli_query($conn, $query_recent);

                                if (mysqli_num_rows($result_recent) > 0):
                                    while ($row = mysqli_fetch_assoc($result_recent)):
                                        $status = $row["project_status"];
                                        $status_class = ($status === "Completed") ? 'badge-soft-success' : 'badge-soft-warning';
                                ?>
                                    <tr>
                                        <td class="font-weight-bold">#<?php echo str_pad($row["project_no"], 5, "0", STR_PAD_LEFT); ?></td>
                                        <td>
                                            <div class="small text-muted"><?php echo date("d M Y", strtotime($row["creation_date"])); ?></div>
                                        </td>
                                        <td>
                                            <div class="font-weight-600 text-dark"><?php echo htmlspecialchars($row["customer_name"]); ?></div>
                                        </td>
                                        <td><span class="badge-premium <?php echo $status_class; ?>"><?php echo $status; ?></span></td>
                                        <td><?php echo htmlspecialchars($row["equipment_type"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["equipment_location"]); ?></td>
                                        <td>
                                            <a href="../job/job-details.php?id=<?php echo $row['project_no']; ?>" class="btn-details">
                                                View Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile;
                                else: ?>
                                    <tr><td colspan='7' class='text-center'>No records found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Inspection Calendar -->
            <div class="col-xl-12">
                <div class="premium-card animate-up" style="animation-delay: 0.85s">
                    <div class="card-header">
                        <h4>Inspection Calendar</h4>
                        <div class="small text-muted">Hover over marks to see Project IDs</div>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="<?php echo $url; ?>assets/plugins/apex/apexcharts.min.js"></script>

<?php include_once('../inc/footer.php'); ?>

<script src="<?php echo $url; ?>assets/plugins/moment/moment.min.js"></script>
<script src="<?php echo $url; ?>assets/plugins/fullcalendar/fullcalendar.min.js"></script>
<script>
$(document).ready(function() {
    // ApexCharts Configuration
    var options = {
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
        colors: ['#2563eb'],
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
            borderColor: '#e2e8f0',
            strokeDashArray: 4,
            xaxis: { lines: { show: true } },
            yaxis: { lines: { show: false } }
        },
        tooltip: {
            theme: 'light',
            y: {
                formatter: function(value) {
                    return value + " projects"
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#inspectorChart"), options);
    chart.render();

    // FullCalendar Initialization
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        events: <?php echo json_encode($calendar_events); ?>,
        eventRender: function(event, element) {
            element.html('<span class="calendar-mark"></span><div class="project-tooltip">Project #' + event.title + '</div>');
        },
        height: 'auto',
        eventLimit: true
    });

    // Handle mark complete button click
    $('.mark-complete-btn').click(function() {
        const projectNo = $(this).data('project');
        const btn = $(this);
        
        if (confirm('Are you sure you have completed all corrections for ' + projectNo + '?')) {
            btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-1"></i> Processing...');
            
            $.ajax({
                url: 'mark_corrections_complete.php',
                type: 'POST',
                data: { 
                    project_no: projectNo,
                    inspector: '<?php echo $logged_in_user; ?>'
                },
                success: function(response) {
                    if (response.success) {
                        alert('Reviewer has been notified that corrections are complete.');
                        btn.closest('.list-item-premium').fadeOut();
                    } else {
                        alert('Error: ' + response.message);
                        btn.prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Complete');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                    btn.prop('disabled', false).html('<i class="fas fa-check-circle mr-1"></i> Complete');
                }
            });
        }
    });
});
</script>
