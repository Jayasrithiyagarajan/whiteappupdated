<?php
// Ensure session is started
include_once('../file/config.php');
include_once('../inc/function.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
   header("Location: ../index.php");
   exit();
}

// Check if the user has the 'reviewer' role
if ($_SESSION['role'] !== 'reviewer') {
   header("Location: ../index.php");
   exit();
}

// Query to get total projects count
$result_total_projects = mysqli_query($conn, "SELECT COUNT(*) AS total_projects FROM project_info");
$total_projects = mysqli_fetch_assoc($result_total_projects)['total_projects'];

// Query to get total pending reviews
$result_pending_reviews = mysqli_query($conn, "SELECT COUNT(*) AS total_pending_reviews FROM project_info p WHERE p.review_status = 'Pending' AND EXISTS (SELECT 1 FROM checklist_information c WHERE c.project_no = p.project_no) AND EXISTS (SELECT 1 FROM reports r WHERE r.project_no = p.project_no)");
$total_pending_reviews = mysqli_fetch_assoc($result_pending_reviews)['total_pending_reviews'];

// Query to get total completed reviews
$result_completed_reviews = mysqli_query($conn, "SELECT COUNT(*) AS total_completed_reviews FROM project_info WHERE review_status = 'Completed'");
$total_completed_reviews = mysqli_fetch_assoc($result_completed_reviews)['total_completed_reviews'];

// Query to get recent projects requiring reviews
$query_recent_reviews = "SELECT p.project_no, p.customer_name, p.review_status, p.creation_date FROM project_info p WHERE p.review_status = 'Pending' AND EXISTS (SELECT 1 FROM checklist_information c WHERE c.project_no = p.project_no) AND EXISTS (SELECT 1 FROM reports r WHERE r.project_no = p.project_no) ORDER BY p.creation_date DESC LIMIT 5";
$result_recent_reviews = mysqli_query($conn, $query_recent_reviews);

// Fetch notifications for reviewers (notifications meant for any reviewer)
$query_notifications = "SELECT id, project_no, customer_name, notification_message, created_at 
                        FROM project_notifications 
                        WHERE notification_message LIKE '%ready for reviewing%' 
                        AND project_no IN (SELECT p.project_no FROM project_info p WHERE p.review_status = 'Pending' AND EXISTS (SELECT 1 FROM checklist_information c WHERE c.project_no = p.project_no) AND EXISTS (SELECT 1 FROM reports r WHERE r.project_no = p.project_no))
                        ORDER BY created_at DESC";

$result_notifications = mysqli_query($conn, $query_notifications);
$unread_count = mysqli_num_rows($result_notifications);

// Query for Chart Data (Monthly Review Projects)
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

if (isset($_POST['project_no'])) {
   $project_no = mysqli_real_escape_string($conn, $_POST['project_no']);

   // Check if the project is already marked as 'Completed'
   $check_status_query = "SELECT review_status FROM project_info WHERE project_no = '$project_no'";
   $result = mysqli_query($conn, $check_status_query);
   
   if ($result) {
       $row = mysqli_fetch_assoc($result);
       if ($row['review_status'] === 'Completed') {
           // Delete notifications related to this completed project
           $delete_query = "DELETE FROM project_notifications WHERE project_no = '$project_no'";
           if (mysqli_query($conn, $delete_query)) {
               echo "success";
           } else {
               echo "error";
           }
       } else {
           echo "not_completed"; // Project is not marked as 'Completed', so don't delete notifications
       }
   } else {
       echo "error";
   }
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

    .hero-section h1 {
        position: relative;
        z-index: 1;
    }

    .hero-section p {
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

    /* Timeline Styles */
    .timeline {
        list-style: none;
        padding: 0;
        position: relative;
    }

    .timeline::before {
        content: '';
        position: absolute;
        left: 30px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, #667eea, #764ba2);
    }

    .timeline .event {
        position: relative;
        padding-left: 70px;
        padding-bottom: 30px;
    }

    .timeline .event::before {
        content: '';
        position: absolute;
        left: 22px;
        top: 0;
        width: 18px;
        height: 18px;
        border-radius: 50%;
        background: white;
        border: 3px solid #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
    }

    .timeline .event::after {
        content: attr(data-date);
        position: absolute;
        left: 70px;
        top: -5px;
        font-size: 11px;
        color: #718096;
        background: #f7fafc;
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 600;
    }

    .timeline .event h4 {
        margin-top: 25px;
        margin-bottom: 8px;
        font-size: 16px;
        font-weight: 700;
        color: #2d3748;
    }

    .timeline .event p {
        margin-bottom: 5px;
        color: #4a5568;
        font-size: 14px;
    }

    /* Notifications List */
    .list-item-premium {
        padding: 15px 0;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        justify-content: space-between;
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
        color: #667eea;
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

    /* Button Style */
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

    /* Chart Container */
    #reviewChart {
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

    /* ===== PREMIUM REVIEWER DASHBOARD UI ===== */
    body {
        background:
            radial-gradient(circle at 18% 10%, rgba(20, 184, 166, .20), transparent 30%),
            radial-gradient(circle at 78% 36%, rgba(59, 130, 246, .18), transparent 34%),
            linear-gradient(135deg, #eef8f7 0%, #f6f8fc 46%, #e8eef9 100%);
        color: #101827;
    }

    .reviewer-premium {
        position: relative;
        overflow: hidden;
        min-height: 100vh;
        padding: 42px 28px 56px;
        background:
            radial-gradient(circle at 8% 4%, rgba(45, 212, 191, .18), transparent 28%),
            radial-gradient(circle at 86% 38%, rgba(14, 165, 233, .14), transparent 30%),
            linear-gradient(135deg, rgba(240, 253, 250, .82), rgba(242, 247, 255, .9));
    }

    .reviewer-premium::before {
        content: "";
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            linear-gradient(90deg, rgba(255, 255, 255, .55), transparent 38%),
            radial-gradient(circle at 70% 65%, rgba(15, 118, 110, .12), transparent 26%);
    }

    .reviewer-premium > .container-fluid {
        position: relative;
        z-index: 1;
        max-width: 1680px;
    }

    .reviewer-premium .hero-section,
    .reviewer-premium .stats-card,
    .reviewer-premium .premium-card {
        border: 1px solid rgba(255, 255, 255, .76);
        background: linear-gradient(145deg, rgba(255, 255, 255, .88), rgba(246, 251, 255, .72));
        box-shadow: 0 22px 60px rgba(15, 23, 42, .12);
        backdrop-filter: blur(16px);
    }

    .reviewer-premium .hero-section {
        min-height: 150px;
        padding: 30px 34px;
        border-radius: 24px;
        color: #0f172a;
        box-shadow: 0 24px 70px rgba(15, 23, 42, .12);
    }

    .reviewer-premium .hero-section::before {
        display: none;
    }

    .reviewer-premium .hero-section::after {
        top: -80px;
        right: 7%;
        width: 220px;
        height: 220px;
        background: linear-gradient(135deg, rgba(37, 99, 235, .12), rgba(20, 184, 166, .18));
    }

    .reviewer-premium .hero-section h1 {
        margin-bottom: 8px;
        font-size: clamp(28px, 3vw, 42px);
        font-weight: 900;
        letter-spacing: 0;
        color: #0f172a;
    }

    .reviewer-premium .hero-section p,
    .reviewer-premium .hero-section .opacity-75 {
        color: #5f708b;
        opacity: 1;
    }

    .reviewer-premium .hero-section .col-md-8 {
        position: relative;
        padding-left: 90px;
    }

    .reviewer-premium .hero-section .col-md-8::before {
        content: "\f5ad";
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

    .reviewer-premium .stats-card {
        position: relative;
        overflow: hidden;
        min-height: 152px;
        border-radius: 24px;
        cursor: default;
    }

    .reviewer-premium .stats-card::after {
        content: "";
        position: absolute;
        top: -50px;
        right: -42px;
        width: 138px;
        height: 138px;
        border-radius: 50%;
        background: rgba(37, 99, 235, .10);
    }

    .reviewer-premium .stats-card:hover,
    .reviewer-premium .premium-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 28px 70px rgba(15, 23, 42, .14);
    }

    .reviewer-premium .stats-card .card-body {
        position: relative;
        z-index: 1;
        padding: 28px 30px;
    }

    .reviewer-premium .icon-box {
        width: 58px;
        height: 58px;
        flex: 0 0 58px;
        border-radius: 18px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .76);
    }

    .reviewer-premium .bg-p { background: rgba(37, 99, 235, .12); color: #2563eb; }
    .reviewer-premium .bg-s { background: rgba(20, 184, 166, .16); color: #0f766e; }
    .reviewer-premium .bg-w { background: rgba(245, 158, 11, .14); color: #d97706; }
    .reviewer-premium .bg-d { background: rgba(239, 68, 68, .12); color: #dc2626; }

    .reviewer-premium .stats-card p {
        color: #62728a;
        font-weight: 900;
        letter-spacing: .6px;
    }

    .reviewer-premium .stats-card h2 {
        font-size: 42px;
        font-weight: 900;
        color: #0f172a;
    }

    .reviewer-premium .premium-card {
        overflow: hidden;
        border-radius: 24px;
        margin-bottom: 30px;
    }

    .reviewer-premium .premium-card .card-header {
        padding: 22px 26px;
        border-bottom: 1px solid rgba(226, 232, 240, .78);
        background: rgba(255, 255, 255, .38);
    }

    .reviewer-premium .premium-card .card-header h4 {
        font-size: 20px;
        font-weight: 900;
        color: #111827;
    }

    .reviewer-premium .premium-card .card-body {
        padding: 24px 26px;
    }

    .reviewer-premium .timeline {
        display: grid;
        gap: 14px;
        margin: 0;
        padding: 0;
    }

    .reviewer-premium .timeline::before {
        display: none;
    }

    .reviewer-premium .timeline .event {
        display: grid;
        grid-template-columns: 42px minmax(0, 1fr);
        gap: 13px;
        position: relative;
        padding: 16px;
        border: 1px solid rgba(226, 232, 240, .84);
        border-radius: 18px;
        background:
            linear-gradient(135deg, rgba(255, 255, 255, .86), rgba(240, 249, 255, .68));
        box-shadow: 0 14px 32px rgba(15, 23, 42, .07);
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .reviewer-premium .timeline .event:hover {
        transform: translateY(-2px);
        background:
            linear-gradient(135deg, rgba(255, 255, 255, .94), rgba(236, 253, 245, .74));
        box-shadow: 0 18px 40px rgba(15, 23, 42, .11);
    }

    .reviewer-premium .timeline .event::before {
        content: "\f4fc";
        position: static;
        width: 42px;
        height: 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        grid-row: 1 / span 3;
        border: 0;
        border-radius: 14px;
        color: #2563eb;
        background: linear-gradient(135deg, #dbeafe, #ccfbf1);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, .85), 0 12px 24px rgba(37, 99, 235, .10);
        font-family: "Font Awesome 6 Free";
        font-size: 16px;
        font-weight: 900;
    }

    .reviewer-premium .timeline .event::after {
        justify-self: start;
        position: static;
        content: attr(data-date);
        grid-column: 2;
        grid-row: 1;
        color: #64748b;
        background: rgba(255, 255, 255, .72);
        border: 1px solid rgba(226, 232, 240, .8);
        border-radius: 999px;
        padding: 5px 10px;
        font-size: 11px;
        font-weight: 900;
        line-height: 1;
    }

    .reviewer-premium .timeline .event h4 {
        grid-column: 2;
        grid-row: 2;
        margin: 0;
        color: #0f172a;
        font-size: 17px;
        font-weight: 900;
        line-height: 1.2;
        word-break: break-word;
    }

    .reviewer-premium .timeline .event p {
        grid-column: 2;
        margin: 0;
        color: #52627a;
        font-size: 13px;
        line-height: 1.35;
    }

    .reviewer-premium .timeline .event p strong {
        color: #334155;
    }

    .reviewer-premium .timeline .event .badge {
        display: inline-flex;
        align-items: center;
        min-height: 24px;
        margin-left: 4px;
        border-radius: 999px;
        font-size: 10px;
        font-weight: 900;
        letter-spacing: .35px;
    }

    .reviewer-premium .list-item-premium {
        padding: 16px;
        border-bottom: 1px solid rgba(226, 232, 240, .78);
        transition: background .2s ease, transform .2s ease;
    }

    .reviewer-premium .list-item-premium:hover {
        background: rgba(236, 253, 245, .58);
        transform: translateY(-1px);
    }

    .reviewer-premium .list-icon {
        border-radius: 14px;
        color: #2563eb;
        background: linear-gradient(135deg, #dbeafe, #ccfbf1);
    }

    .reviewer-premium .modern-table {
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
        min-width: 760px;
    }

    .reviewer-premium .modern-table thead th {
        padding: 16px 18px;
        border: 0;
        border-bottom: 1px solid rgba(203, 213, 225, .7);
        background: #f4f8fb;
        color: #334155;
        font-weight: 900;
    }

    .reviewer-premium .modern-table tbody td {
        padding: 16px 18px;
        border-bottom: 1px solid rgba(226, 232, 240, .9);
        color: #243044;
        background: rgba(255, 255, 255, .58);
        vertical-align: middle;
    }

    .reviewer-premium .modern-table tbody tr:hover td {
        background: rgba(236, 253, 245, .58);
    }

    .reviewer-premium .badge-premium,
    .reviewer-premium .badge-soft-success,
    .reviewer-premium .badge-soft-warning,
    .reviewer-premium .badge-soft-info {
        border-radius: 999px;
        padding: 7px 12px;
        font-weight: 900;
        letter-spacing: .35px;
    }

    .reviewer-premium .badge-soft-success { background: rgba(16, 185, 129, .14); color: #047857; }
    .reviewer-premium .badge-soft-warning { background: rgba(245, 158, 11, .15); color: #92400e; }
    .reviewer-premium .badge-soft-info { background: rgba(14, 165, 233, .14); color: #0369a1; }

    .reviewer-premium .btn-primary,
    .reviewer-premium .btn-view,
    .reviewer-premium .btn-details {
        border: 0;
        border-radius: 12px;
        color: #fff;
        font-weight: 800;
        background: linear-gradient(135deg, #2563eb, #12b8b6);
        box-shadow: 0 12px 24px rgba(37, 99, 235, .20);
        text-decoration: none;
    }

    .reviewer-premium .btn-details:hover,
    .reviewer-premium .btn-view:hover,
    .reviewer-premium .btn-primary:hover {
        color: #fff;
        background: linear-gradient(135deg, #1d4ed8, #0faaa8);
        transform: translateY(-1px);
    }

    .reviewer-premium #reviewChart {
        min-height: 330px;
    }

    .reviewer-premium .table-responsive {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .reviewer-premium .table-responsive::-webkit-scrollbar {
        height: 8px;
    }

    .reviewer-premium .table-responsive::-webkit-scrollbar-thumb {
        border-radius: 999px;
        background: rgba(20, 184, 166, .55);
    }

    .reviewer-premium img,
    .reviewer-premium canvas,
    .reviewer-premium svg {
        max-width: 100%;
    }

    .reviewer-premium .notification-scroll::-webkit-scrollbar-thumb {
        background: #14b8a6;
    }

    @media(max-width: 1199px) {
        .reviewer-premium .hero-section {
            margin-bottom: 24px;
        }

        .reviewer-premium .premium-card .card-body[style*="max-height"] {
            max-height: 460px !important;
        }
    }

    @media(max-width: 992px) {
        .reviewer-premium {
            padding: 24px 14px 42px;
        }

        .reviewer-premium .hero-section .row {
            row-gap: 16px;
        }

        .reviewer-premium .hero-section .col-md-8 {
            padding-left: 0;
            padding-top: 82px;
        }

        .reviewer-premium .hero-section .col-md-8::before {
            top: 0;
            transform: none;
        }

        .reviewer-premium .hero-section h1 {
            font-size: 30px;
        }

        .reviewer-premium .stats-card h2 {
            font-size: 34px;
        }

        .reviewer-premium #reviewChart {
            min-height: 300px;
        }

        .reviewer-premium .timeline {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media(max-width: 576px) {
        body {
            overflow-x: hidden;
        }

        .reviewer-premium {
            padding: 16px 10px 34px;
        }

        .reviewer-premium > .container-fluid {
            padding-left: 0;
            padding-right: 0;
        }

        .reviewer-premium .hero-section,
        .reviewer-premium .premium-card .card-body,
        .reviewer-premium .premium-card .card-header {
            padding-left: 16px;
            padding-right: 16px;
        }

        .reviewer-premium .hero-section {
            min-height: auto;
            margin-bottom: 18px;
            padding-top: 20px;
            padding-bottom: 20px;
            border-radius: 18px;
        }

        .reviewer-premium .hero-section .col-md-8 {
            padding-top: 62px;
        }

        .reviewer-premium .hero-section .col-md-8::before {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            font-size: 22px;
        }

        .reviewer-premium .hero-section h1 {
            font-size: 24px;
            line-height: 1.15;
        }

        .reviewer-premium .hero-section .lead,
        .reviewer-premium .hero-section p {
            font-size: 13px;
            line-height: 1.45;
        }

        .reviewer-premium .stats-card .card-body {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
            padding: 18px;
        }

        .reviewer-premium .stats-card {
            min-height: 126px;
            border-radius: 18px;
        }

        .reviewer-premium .icon-box {
            width: 46px;
            height: 46px;
            flex-basis: 46px;
            border-radius: 15px;
            font-size: 20px;
        }

        .reviewer-premium .stats-card h2 {
            font-size: 28px;
        }

        .reviewer-premium #reviewChart {
            min-height: 250px;
        }

        .reviewer-premium .premium-card {
            margin-bottom: 18px;
            border-radius: 18px;
        }

        .reviewer-premium .premium-card .card-header {
            align-items: flex-start;
            flex-direction: column;
            gap: 10px;
            padding-top: 16px;
            padding-bottom: 14px;
        }

        .reviewer-premium .premium-card .card-header h4 {
            font-size: 17px;
            line-height: 1.25;
        }

        .reviewer-premium .timeline {
            grid-template-columns: 1fr;
            gap: 10px;
        }

        .reviewer-premium .timeline .event {
            grid-template-columns: 38px minmax(0, 1fr);
            gap: 11px;
            padding: 13px;
            border-radius: 15px;
        }

        .reviewer-premium .timeline .event::before {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            font-size: 14px;
        }

        .reviewer-premium .timeline .event h4 {
            font-size: 15px;
        }

        .reviewer-premium .timeline .event p {
            font-size: 12px;
        }

        .reviewer-premium .list-item-premium {
            align-items: flex-start;
            flex-direction: column;
            gap: 12px;
            padding: 14px;
        }

        .reviewer-premium .list-item-premium .d-flex {
            align-items: flex-start !important;
            width: 100%;
        }

        .reviewer-premium .list-item-premium .btn {
            width: 100%;
        }

        .reviewer-premium .modern-table thead th,
        .reviewer-premium .modern-table tbody td {
            padding: 12px;
            font-size: 12px;
            white-space: nowrap;
        }

        .reviewer-premium .btn-details,
        .reviewer-premium .btn-view,
        .reviewer-premium .btn-primary {
            display: inline-flex;
            justify-content: center;
            width: 100%;
            padding: 9px 12px;
            text-align: center;
        }
    }

    @media(max-width: 380px) {
        .reviewer-premium {
            padding-left: 8px;
            padding-right: 8px;
        }

        .reviewer-premium .hero-section h1 {
            font-size: 22px;
        }

        .reviewer-premium .stats-card h2 {
            font-size: 25px;
        }

        .reviewer-premium .timeline .event {
            grid-template-columns: 1fr;
        }

        .reviewer-premium .timeline .event::before,
        .reviewer-premium .timeline .event::after,
        .reviewer-premium .timeline .event h4,
        .reviewer-premium .timeline .event p {
            grid-column: 1;
        }
    }
</style>

<div class="main-content reviewer-premium">
    <div class="container-fluid">
        <!-- Hero Welcome -->
        <div class="row">
            <div class="col-12">
                <div class="hero-section animate-up">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h1 class="display-5 font-weight-bold mb-2">Welcome Back, <?php echo $_SESSION['username']; ?>!</h1>
                            <p class="lead mb-0">Here's an overview of your review dashboard and pending tasks.</p>
                        </div>
                        <div class="col-md-4 text-md-right mt-3 mt-md-0">
                            <h4 class="mb-0"><?php echo date('l, F j, Y'); ?></h4>
                            <p class="opacity-75">Reviewer Dashboard</p>
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
                            <p>Pending Reviews</p>
                            <h2><?php echo number_format($total_pending_reviews); ?></h2>
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
                            <p>Completed Reviews</p>
                            <h2><?php echo number_format($total_completed_reviews); ?></h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="stats-card animate-up" style="animation-delay: 0.4s">
                    <div class="card-body">
                        <div class="icon-box bg-d">
                            <i class="fa-solid fa-bell"></i>
                        </div>
                        <div>
                            <p>Notifications</p>
                            <h2><?php echo number_format($unread_count); ?></h2>
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
                        <h4>Review Performance Trend</h4>
                        <div class="badge badge-soft-info">Last 6 Months</div>
                    </div>
                    <div class="card-body">
                        <div id="reviewChart"></div>
                    </div>
                </div>
            </div>

            <!-- Recent Reviews Timeline -->
            <div class="col-xl-4">
                <div class="premium-card animate-up" style="animation-delay: 0.6s">
                    <div class="card-header">
                        <h4>Recent Reviews Timeline</h4>
                        <?php if($total_pending_reviews > 0): ?>
                            <span class="badge badge-danger"><?php echo $total_pending_reviews; ?> Pending</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-4" style="max-height: 400px; overflow-y: auto;">
                        <ul class="timeline">
                            <?php 
                            mysqli_data_seek($result_recent_reviews, 0);
                            if(mysqli_num_rows($result_recent_reviews) > 0):
                                while ($row = mysqli_fetch_assoc($result_recent_reviews)): ?>
                                <li class="event" data-date="<?php echo date('d M', strtotime($row['creation_date'])); ?>">
                                    <h4><?php echo htmlspecialchars($row['project_no']); ?></h4>
                                    <p><strong>Customer:</strong> <?php echo htmlspecialchars($row['customer_name']); ?></p>
                                    <p><strong>Status:</strong> <span class="badge badge-soft-warning">Pending</span></p>
                                </li>
                            <?php endwhile;
                            else: ?>
                                <div class="text-center py-5 text-muted">
                                    <i class="fa-solid fa-check-double fa-3x mb-3 opacity-25"></i>
                                    <p>No pending reviews</p>
                                </div>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Notifications Section -->
            <div class="col-xl-4">
                <div class="premium-card animate-up" style="animation-delay: 0.7s">
                    <div class="card-header">
                        <h4>Notifications</h4>
                        <?php if($unread_count > 0): ?>
                            <span class="badge badge-danger"><?php echo $unread_count; ?> New</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-body p-0">
                        <div class="px-4 py-2 notification-scroll" style="max-height: 400px; overflow-y: auto;">
                            <?php 
                            mysqli_data_seek($result_notifications, 0);
                            if(mysqli_num_rows($result_notifications) > 0):
                                while($notif = mysqli_fetch_assoc($result_notifications)): ?>
                                <div class="list-item-premium">
                                    <div class="d-flex align-items-center">
                                        <div class="list-icon">
                                            <i class="fa-solid fa-bell"></i>
                                        </div>
                                        <div>
                                            <div class="font-weight-bold text-dark mb-1"><?php echo htmlspecialchars($notif['notification_message']); ?></div>
                                            <div class="text-muted small">Project: <?php echo htmlspecialchars($notif['project_no']); ?></div>
                                            <div class="text-primary smaller mt-1"><?php echo date('M d, g:i A', strtotime($notif['created_at'])); ?></div>
                                        </div>
                                    </div>
                                    <a href="../job/job-details.php?id=<?php echo $notif['project_no']; ?>" class="btn btn-sm btn-view">View</a>
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

            <!-- Recent Projects Table -->
            <div class="col-xl-8">
                <div class="premium-card animate-up" style="animation-delay: 0.8s">
                    <div class="card-header">
                        <h4>Recent Projects Requiring Review</h4>
                        <a href="../job/export_jobs1.php" class="btn btn-sm btn-primary">Export All</a>
                    </div>
                    <div class="table-responsive">
                        <table id="review-table" class="table modern-table">
                            <thead>
                                <tr>
                                    <th>Project ID</th>
                                    <th>Customer</th>
                                    <th>Inspector</th>
                                    <th>Start Date</th>
                                    <th>Review Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $query_recent = "SELECT p.* FROM project_info p WHERE p.review_status = 'Pending' AND EXISTS (SELECT 1 FROM checklist_information c WHERE c.project_no = p.project_no) AND EXISTS (SELECT 1 FROM reports r WHERE r.project_no = p.project_no) ORDER BY p.creation_date DESC LIMIT 10";
                                $result_recent = mysqli_query($conn, $query_recent);
                                while ($row = mysqli_fetch_assoc($result_recent)):
                                    $review_status = $row['review_status'];
                                    $status_class = ($review_status == 'Completed') ? 'badge-soft-success' : 'badge-soft-warning';
                                ?>
                                    <tr>
                                        <td class="font-weight-bold">#<?php echo str_pad($row['project_no'], 5, "0", STR_PAD_LEFT); ?></td>
                                        <td>
                                            <div class="font-weight-600 text-dark"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                                        </td>
                                        <td>
                                            <div class="font-weight-600 text-dark"><?php echo htmlspecialchars($row['inspector_name']); ?></div>
                                        </td>
                                        <td>
                                            <div class="small text-muted"><?php echo date('d M Y', strtotime($row['creation_date'])); ?></div>
                                        </td>
                                        <td><span class="badge-premium <?php echo $status_class; ?>"><?php echo htmlspecialchars($review_status); ?></span></td>
                                        <td>
                                            <a href="../job/job-details.php?id=<?php echo $row['project_no']; ?>" class="btn-details">
                                                Review Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="<?php echo $url; ?>assets/plugins/apex/apexcharts.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
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
        colors: ['#6045E2'],
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
                formatter: function(value) {
                    return value + " projects"
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#reviewChart"), options);
    chart.render();

    // Delete notification functionality
    document.querySelectorAll('.delete-notification').forEach(button => {
        button.addEventListener('click', function() {
            let projectId = this.getAttribute('data-project-id');

            fetch("delete_notifications.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `project_no=${projectId}`
            })
            .then(response => response.text())
            .then(data => {
                console.log("Server Response:", data);
                if (data === "success") {
                    alert("Notifications deleted successfully!");
                    this.closest('li').remove();
                } else {
                    alert("Error: " + data);
                }
            });
        });
    });
});
</script>

<?php include_once('../inc/footer.php'); ?>
