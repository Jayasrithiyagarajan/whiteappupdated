<?php
// Ensure session is started
include_once('../file/config.php');
include_once('../inc/function.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
   header("Location: ../index.php");
   exit();
}

// Check if the user has the 'quality controller' role
if ($_SESSION['role'] !== 'quality controller') {
   header("Location: ../index.php");
   exit();
}

// Query to get total projects count
$result_total_projects = mysqli_query($conn, "SELECT COUNT(*) AS total_projects FROM project_info");
$total_projects = mysqli_fetch_assoc($result_total_projects)['total_projects'];

// Query to get total pending QC checks
$result_pending_qc = mysqli_query($conn, "SELECT COUNT(*) AS total_pending_qc FROM project_info WHERE project_status = 'Pending'");
$total_pending_qc = mysqli_fetch_assoc($result_pending_qc)['total_pending_qc'];

// Query to get total completed QC checks
$result_completed_qc = mysqli_query($conn, "SELECT COUNT(*) AS total_completed_qc FROM project_info WHERE project_status = 'Completed'");
$total_completed_qc = mysqli_fetch_assoc($result_completed_qc)['total_completed_qc'];
$completion_rate = $total_projects > 0 ? round(($total_completed_qc / $total_projects) * 100) : 0;

// Query to get Average QC Turnaround Time
$result_turnaround = mysqli_query($conn, "
    SELECT AVG(TIMESTAMPDIFF(HOUR, pi.creation_date, qcr.reviewed_at)) AS avg_turnaround
    FROM project_info pi
    JOIN qc_controller_reviews qcr ON pi.project_no = qcr.project_no
    WHERE pi.project_status = 'Completed'
    AND qcr.review_status = 'Completed'
");
$avg_turnaround = mysqli_fetch_assoc($result_turnaround)['avg_turnaround'] ?? 0;
$avg_turnaround_display = ($avg_turnaround > 0) ? round($avg_turnaround, 1) . ' hrs' : 'N/A';

// Query for Chart Data (Monthly QC Projects)

// Query for Chart Data (Monthly QC Projects)
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

// Query for Urgent Projects (Pending > 48 Hours)
$urgent_query = "SELECT *, TIMESTAMPDIFF(HOUR, creation_date, NOW()) as hours_elapsed 
                 FROM project_info 
                 WHERE project_status = 'Pending' 
                 AND creation_date < DATE_SUB(NOW(), INTERVAL 48 HOUR)
                 ORDER BY creation_date ASC LIMIT 5";
$urgent_res = mysqli_query($conn, $urgent_query);
?>

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
    }

    .header-actions {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        justify-content: flex-end;
        gap: 10px;
    }

    .filter-btn,
    .btn-details,
    .btn-export {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 38px;
        border: 1px solid rgba(226, 232, 240, 0.92);
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.78);
        color: #334155;
        font-size: 13px;
        font-weight: 700;
        box-shadow: 0 10px 24px rgba(15, 23, 42, 0.06);
        transition: all 0.2s ease;
    }

    .filter-btn,
    .btn-export {
        padding: 10px 14px;
    }

    .btn-details {
        padding: 8px 12px;
        white-space: nowrap;
    }

    .filter-btn:hover,
    .btn-details:hover,
    .btn-export:hover {
        color: #ffffff;
        border-color: transparent;
        background: linear-gradient(135deg, var(--glass-blue), var(--glass-cyan));
        box-shadow: 0 14px 30px rgba(37, 99, 235, 0.18);
        text-decoration: none;
    }

    .modern-card {
        position: relative;
        height: 100%;
        margin-bottom: 30px;
        padding: 24px;
        border: 1px solid var(--glass-line);
        border-radius: 16px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.84), rgba(255, 255, 255, 0.58));
        box-shadow: 0 18px 42px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--glass-shadow);
    }

    .stat-card-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
    }

    .stat-icon-circle {
        width: 56px;
        height: 56px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        font-size: 22px;
    }

    .bg-purple-light { background: rgba(112, 72, 232, 0.13); color: var(--glass-violet); }
    .bg-orange-light { background: rgba(245, 158, 11, 0.16); color: #b45309; }
    .bg-blue-light { background: rgba(37, 99, 235, 0.13); color: var(--glass-blue); }
    .bg-green-light { background: rgba(20, 184, 166, 0.15); color: #0f766e; }

    .stat-value {
        margin: 8px 0 2px;
        color: var(--glass-ink);
        font-size: 30px;
        font-weight: 800;
        line-height: 1;
    }

    .stat-label {
        margin: 0;
        color: var(--glass-muted);
        font-size: 13px;
        font-weight: 700;
        letter-spacing: 0.4px;
        text-transform: uppercase;
    }

    .trend-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        border-radius: 8px;
        color: #047857;
        background: rgba(16, 185, 129, 0.15);
        font-size: 12px;
        font-weight: 800;
    }

    .card-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        margin-bottom: 22px;
    }

    .card-heading h4 {
        margin: 0;
        color: var(--glass-ink);
        font-size: 18px;
        font-weight: 800;
        letter-spacing: 0;
    }

    .soft-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 7px 10px;
        border-radius: 8px;
        background: rgba(37, 99, 235, 0.1);
        color: #1d4ed8;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .soft-pill.danger {
        background: rgba(239, 68, 68, 0.12);
        color: #b91c1c;
    }

    .list-scroll {
        max-height: 400px;
        overflow-y: auto;
        padding-right: 4px;
    }

    .list-item-premium {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 15px 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
    }

    .list-item-premium:last-child {
        border-bottom: none;
    }

    .list-icon {
        width: 42px;
        height: 42px;
        border-radius: 14px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 auto;
        background: rgba(37, 99, 235, 0.1);
        color: var(--glass-blue);
        font-size: 17px;
    }

    .news-card-mini {
        margin-bottom: 14px;
        padding: 15px;
        border: 1px solid rgba(226, 232, 240, 0.72);
        border-radius: 12px;
        background: rgba(248, 250, 252, 0.82);
        transition: transform 0.2s ease, border-color 0.2s ease;
    }

    .news-card-mini:hover {
        transform: translateY(-2px);
        border-color: rgba(37, 99, 235, 0.24);
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
        color: var(--glass-muted);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        background: transparent;
    }

    .modern-table tbody tr {
        background: rgba(255, 255, 255, 0.86);
        box-shadow: 0 8px 24px rgba(15, 23, 42, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .modern-table tbody tr:hover {
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

    .badge-premium {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 28px;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 800;
        white-space: nowrap;
    }

    .badge-soft-success { background: rgba(16, 185, 129, 0.16); color: #047857; }
    .badge-soft-danger { background: rgba(239, 68, 68, 0.13); color: #b91c1c; }
    .badge-soft-warning { background: rgba(245, 158, 11, 0.16); color: #b45309; }
    .badge-soft-info { background: rgba(37, 99, 235, 0.12); color: #1d4ed8; }

    #qcChart {
        height: 100%;
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

    @media (max-width: 991.98px) {
        .dashboard-glass {
            padding: 0 4px 28px;
        }

        .section-header {
            flex-direction: column;
            padding: 20px;
        }

        .header-actions {
            justify-content: flex-start;
            width: 100%;
        }
    }

    @media (max-width: 575.98px) {
        .page-title h2 {
            font-size: 24px;
        }

        .modern-card {
            padding: 18px;
        }

        .stat-card-inner {
            align-items: flex-start;
        }

        .stat-value {
            font-size: 26px;
        }
    }
</style>

<div class="main-content dashboard-glass">
    <div class="container-fluid">
        <div class="section-header animate-up">
            <div class="page-title">
                <h2>Quality Control Dashboard</h2>
                <span class="page-subtitle">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>. Track QC workload, notifications, and recent project movement.</span>
            </div>
            <div class="header-actions">
                <button class="filter-btn" type="button">
                    <i class="far fa-calendar"></i> <?php echo date('M j, Y'); ?>
                </button>
                <a href="../job/export_jobs1.php" class="btn-export">
                    <i class="fa-solid fa-file-export"></i> Export
                </a>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="modern-card animate-up" style="animation-delay: 0.1s">
                    <div class="stat-card-inner">
                        <div>
                            <p class="stat-label">Total Projects</p>
                            <div class="stat-value"><?php echo number_format($total_projects); ?></div>
                            <span class="trend-badge"><i class="fa-solid fa-layer-group"></i> Portfolio</span>
                        </div>
                        <div class="stat-icon-circle bg-purple-light">
                            <i class="fa-solid fa-folder-tree"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="modern-card animate-up" style="animation-delay: 0.2s">
                    <div class="stat-card-inner">
                        <div>
                            <p class="stat-label">Pending QC</p>
                            <div class="stat-value"><?php echo number_format($total_pending_qc); ?></div>
                            <span class="trend-badge" style="color:#b45309; background:rgba(245,158,11,.16);"><i class="fa-solid fa-clock"></i> Needs Review</span>
                        </div>
                        <div class="stat-icon-circle bg-orange-light">
                            <i class="fa-solid fa-hourglass-start"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="modern-card animate-up" style="animation-delay: 0.3s">
                    <div class="stat-card-inner">
                        <div>
                            <p class="stat-label">Completed Checks</p>
                            <div class="stat-value"><?php echo number_format($total_completed_qc); ?></div>
                            <span class="trend-badge"><i class="fa-solid fa-chart-line"></i> <?php echo $completion_rate; ?>% closed</span>
                        </div>
                        <div class="stat-icon-circle bg-green-light">
                            <i class="fa-solid fa-circle-check"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="modern-card animate-up" style="animation-delay: 0.4s">
                    <div class="stat-card-inner">
                        <div>
                            <p class="stat-label">Avg. Turnaround</p>
                            <div class="stat-value"><?php echo $avg_turnaround_display; ?></div>
                            <span class="trend-badge" style="color:#1d4ed8; background:rgba(37,99,235,0.12);"><i class="fa-solid fa-bolt"></i> Speed</span>
                        </div>
                        <div class="stat-icon-circle bg-blue-light">
                            <i class="fa-solid fa-stopwatch"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8">
                <div class="modern-card animate-up" style="animation-delay: 0.4s">
                    <div class="card-heading">
                        <h4>QC Performance Trend</h4>
                        <span class="soft-pill"><i class="fa-solid fa-chart-area"></i> Last 6 Months</span>
                    </div>
                    <div id="qcChart"></div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="modern-card animate-up" style="animation-delay: 0.5s">
                    <div class="card-heading">
                        <h4>QC Notifications</h4>
                        <?php 
                        $qc_notif_count_query = "SELECT COUNT(*) AS count FROM project_notifications WHERE quality_controller = 'pending'";
                        $qc_notif_count = mysqli_fetch_assoc(mysqli_query($conn, $qc_notif_count_query))['count'];
                        if($qc_notif_count > 0): ?>
                            <span class="soft-pill danger"><?php echo $qc_notif_count; ?> New</span>
                        <?php else: ?>
                            <span class="soft-pill"><i class="fa-solid fa-check"></i> Clear</span>
                        <?php endif; ?>
                    </div>
                    <div class="list-scroll">
                        <?php 
                        $notif_query = "SELECT * FROM project_notifications WHERE quality_controller = 'pending' ORDER BY created_at DESC LIMIT 6";
                        $notif_res = mysqli_query($conn, $notif_query);
                        if(mysqli_num_rows($notif_res) > 0):
                            while($notif = mysqli_fetch_assoc($notif_res)): 
                                // Extract and clean the message
                                $message = $notif['Notification_message'];
                                // Remove certificate numbers in parentheses
                                $cleanMessage = preg_replace('/\s*\(.*?\)/', '', $message);
                                ?>
                                <div class="list-item-premium">
                                    <div class="list-icon">
                                        <i class="fa-solid fa-bell"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-dark mb-1"><?php echo htmlspecialchars($notif['project_no']); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($cleanMessage); ?></div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <div class="text-primary small"><?php echo date('M d, g:i A', strtotime($notif['created_at'])); ?></div>
                                            <a href="../job/job-details.php?id=<?php echo $notif['project_no']; ?>" class="btn-details" style="padding: 4px 10px; font-size: 11px; min-height: 28px;">
                                                Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile;
                        else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-check-double fa-3x mb-3 opacity-25"></i>
                                <p class="mb-0">No new notifications</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-xl-4">
                <div class="modern-card animate-up" style="animation-delay: 0.6s">
                    <div class="card-heading">
                        <h4>Urgent Projects Monitoring</h4>
                        <span class="soft-pill danger"><i class="fa-solid fa-triangle-exclamation"></i> > 48h</span>
                    </div>
                    <div class="list-scroll">
                        <?php 
                        if(mysqli_num_rows($urgent_res) > 0):
                            while($urgent = mysqli_fetch_assoc($urgent_res)): ?>
                                <div class="list-item-premium">
                                    <div class="list-icon" style="background: rgba(239, 68, 68, 0.1); color: var(--glass-red);">
                                        <i class="fa-solid fa-clock"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="font-weight-bold text-dark mb-1">#<?php echo str_pad($urgent['project_no'], 5, '0', STR_PAD_LEFT); ?></div>
                                        <div class="text-muted small"><?php echo htmlspecialchars($urgent['customer_name']); ?></div>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="badge-premium badge-soft-danger" style="font-size: 10px;">
                                                <?php echo $urgent['hours_elapsed']; ?> hrs ago
                                            </span>
                                            <a href="../job/job-details.php?id=<?php echo $urgent['project_no']; ?>" class="btn-details" style="padding: 4px 10px; font-size: 11px; min-height: 28px;">
                                                Review
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            <?php endwhile;
                        else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fa-solid fa-circle-check fa-3x mb-3 opacity-25" style="color: var(--glass-cyan);"></i>
                                <p class="mb-0">All projects are on track</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-xl-8">
                <div class="modern-card animate-up" style="animation-delay: 0.7s">
                    <div class="card-heading">
                        <h4>Recent Projects Monitoring</h4>
                        <a href="../job/export_jobs1.php" class="btn-export"><i class="fa-solid fa-file-export"></i> Export All</a>
                    </div>
                    <div class="table-responsive">
                        <table id="job-table" class="modern-table">
                            <thead>
                                <tr>
                                    <th>Project ID</th>
                                    <th>Customer</th>
                                    <th>Cert Status</th>
                                    <th>QC Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $recent_query = "SELECT * FROM project_info ORDER BY creation_date DESC LIMIT 10";
                                $recent_res = mysqli_query($conn, $recent_query);
                                while($row = mysqli_fetch_assoc($recent_res)): 
                                    $qc_status = $row['project_status'];
                                    $qc_class = ($qc_status == 'Completed') ? 'badge-soft-success' : 'badge-soft-warning';
                                    
                                    $cert_status = $row['certificatestatus'];
                                    $cert_class = ($cert_status == 'Certificate Created') ? 'badge-soft-info' : 'badge-soft-danger';
                                ?>
                                    <tr>
                                        <td class="font-weight-bold">#<?php echo str_pad($row['project_no'], 5, '0', STR_PAD_LEFT); ?></td>
                                        <td>
                                            <div class="font-weight-600 text-dark"><?php echo htmlspecialchars($row['customer_name']); ?></div>
                                            <div class="small text-muted"><?php echo date('d M Y', strtotime($row['creation_date'])); ?></div>
                                        </td>
                                        <td><span class="badge-premium <?php echo $cert_class; ?>"><?php echo $cert_status; ?></span></td>
                                        <td><span class="badge-premium <?php echo $qc_class; ?>"><?php echo $qc_status; ?></span></td>
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
        // Mark all QC notifications as read (keeping original logic if needed)
        document.querySelector('.mark-qc-read')?.addEventListener('click', function(e) {
            e.preventDefault();
            fetch('mark_qc_notifications_read.php', { method: 'POST' })
            .then(res => res.json())
            .then(data => { if(data.success) location.reload(); });
        });

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
            }
        };

        var chart = new ApexCharts(document.querySelector("#qcChart"), options);
        chart.render();
    });
</script>

<?php
include_once('../inc/footer.php');
?>
