<?php
ob_start();
include_once('../file/config.php');
include_once('../inc/function.php');

/* ================= AUTH & ROLES ================= */
$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}

/* ================= DATA FETCHING ================= */
$counts_query = mysqli_query($conn, "
    SELECT 
        COUNT(*) AS total_projects,
        SUM(equipment_type = 'Lifting Equipment') AS lifting_total,
        SUM(equipment_type = 'NDT Equipment') AS ndt_total,
        SUM(project_status = 'Pending') AS pending_total,
        SUM(project_status = 'Completed') AS completed_total,
        (SELECT COUNT(*) FROM operator_cards) AS op_total,
        (SELECT COUNT(*) FROM customers) AS total_customers
    FROM project_info
");
$counts = mysqli_fetch_assoc($counts_query);
extract($counts);

$workload_query = mysqli_query($conn, "
    SELECT inspector_name, COUNT(*) as project_count 
    FROM project_info 
    WHERE project_status = 'Pending' AND inspector_name IS NOT NULL AND inspector_name != ''
    GROUP BY inspector_name 
    ORDER BY project_count DESC 
    LIMIT 5
");
$workload_data = mysqli_fetch_all($workload_query, MYSQLI_ASSOC);

$trend_query = mysqli_query($conn, "
    SELECT DATE_FORMAT(creation_date, '%b') as month, COUNT(*) as count 
    FROM project_info 
    WHERE creation_date >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
    GROUP BY DATE_FORMAT(creation_date, '%Y-%m') 
    ORDER BY creation_date ASC
");
$trend_data = mysqli_fetch_all($trend_query, MYSQLI_ASSOC);
$trend_labels = array_column($trend_data, 'month');
$trend_counts = array_column($trend_data, 'count');

$alerts = [
    ['type' => 'urgent', 'msg' => 'Project #1204 is overdue by 3 days.'],
    ['type' => 'warning', 'msg' => '5 Inspection reports pending DC review.'],
    ['type' => 'urgent', 'msg' => 'Certification portal maintenance at 10 PM.']
];

$recent_projects = mysqli_fetch_all(mysqli_query($conn, "
    SELECT project_no, customer_name, project_status, creation_date, inspector_name 
    FROM project_info 
    ORDER BY creation_date DESC 
    LIMIT 6
"), MYSQLI_ASSOC);

?>

<!-- Modern UI Styles -->
<link rel="stylesheet" href="../assets/css/modern_ui.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .card-stat-icon {
        font-size: 24px;
        opacity: 0.8;
    }
    .revenue-badge {
        background: rgba(255,255,255,0.2);
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Premium Welcome Header -->
        <div class="modern-welcome animate-slide-up" style="animation-delay: 0.1s;">
            <div class="welcome-text">
                <span style="color: var(--text-secondary); font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">OVERVIEW</span>
                <h1>Welcome back, <span class="premium-accent"><?= htmlspecialchars($logged_in_user) ?></span> 👋</h1>
                <p class="text-muted mb-0">Here's what's happening with your projects today.</p>
            </div>
            <!-- <div class="quick-action-btns">
                <button class="btn btn-outline-primary mr-2"><i class="fas fa-download mr-2"></i> Report</button>
                <button class="btn btn-primary"><i class="fas fa-plus mr-2"></i> New Project</button>
            </div> -->
        </div>

        <!-- KPI Grid -->
        <div class="row mt-4">
            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.2s;">
                <div class="modern-card">
                    <div class="stat-card-inner">
                        <div>
                            <span class="stat-label">Total Projects</span>
                            <div class="stat-value"><?= number_format($total_projects) ?></div>
                            <span class="trend-badge trend-up">+12% from last month</span>
                        </div>
                        <div class="stat-icon-circle bg-purple-light">
                             <i class="fas fa-project-diagram"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.4s;">
                <div class="modern-card">
                    <div class="stat-card-inner">
                        <div>
                            <span class="stat-label">Active Customers</span>
                            <div class="stat-value"><?= number_format($total_customers) ?></div>
                            <span class="trend-badge" style="background: #e6fffa; color: #38b2ac;">Stable growth</span>
                        </div>
                        <div class="stat-icon-circle bg-blue-light">
                             <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.5s;">
                <div class="modern-card">
                    <div class="stat-card-inner">
                        <div>
                            <span class="stat-label">Operator Cards</span>
                            <div class="stat-value"><?= number_format($op_total) ?></div>
                            <span class="trend-badge trend-up">All active</span>
                        </div>
                        <div class="stat-icon-circle bg-orange-light">
                             <i class="fas fa-id-card"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-8 animate-slide-up" style="animation-delay: 0.6s;">
                <div class="modern-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 style="font-size: 18px; font-weight: 800;">Performance Analytics</h4>
                        <div class="btn-group">
                            <button class="btn btn-sm btn-light active">6 Months</button>
                            <button class="btn btn-sm btn-light">1 Year</button>
                        </div>
                    </div>
                    <div style="height: 350px;">
                        <canvas id="mainPerformanceChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-4">
                <div class="modern-card animate-slide-up" style="animation-delay: 0.7s;">
                    <h4 style="font-size: 18px; font-weight: 800; margin-bottom: 20px;">Operational Alerts</h4>
                    <?php foreach($alerts as $alert): ?>
                    <div class="alert-strip alert-strip-<?= $alert['type'] ?>">
                        <i class="fas <?= $alert['type'] == 'urgent' ? 'fa-exclamation-circle' : 'fa-info-circle' ?> mr-3"></i>
                        <span style="font-size: 13px; font-weight: 600;"><?= $alert['msg'] ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>

                <div class="modern-card animate-slide-up" style="animation-delay: 0.8s;">
                    <h4 style="font-size: 18px; font-weight: 800; margin-bottom: 25px;">Inspector Workload</h4>
                    <?php if(empty($workload_data)): ?>
                        <p class="text-muted">No active inspections found.</p>
                    <?php else: ?>
                        <?php foreach($workload_data as $w): 
                            $pct = min(100, ($w['project_count'] / 10) * 100); 
                            $color = $pct > 80 ? '#ff6b6b' : ($pct > 50 ? '#f6ad55' : '#4e73df');
                        ?>
                        <div class="workload-item">
                            <div class="workload-header">
                                <span style="font-weight: 700; color: #4a5568;"><?= htmlspecialchars($w['inspector_name']) ?></span>
                                <span style="font-weight: 800; color: #2d3748;"><?= $w['project_count'] ?> Jobs</span>
                            </div>
                            <div class="workload-bar-bg">
                                <div class="workload-bar-fill" style="width: <?= $pct ?>%; background: <?= $color ?>;"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 animate-slide-up" style="animation-delay: 0.9s;">
                <div class="modern-card">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 style="font-size: 18px; font-weight: 800;">Recent Project Activity</h4>
                        <a href="overall-job-list.php" class="btn btn-sm btn-link font-weight-bold" style="color: var(--primary-purple);">View All Activity <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Ref ID</th>
                                    <th>Customer Entity</th>
                                    <th>Assigned Inspector</th>
                                    <th>Date Logged</th>
                                    <th>Live Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_projects as $proj): ?>
                                <tr>
                                    <td class="font-weight-bold" style="color: var(--primary-purple);">#<?= $proj['project_no'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div style="width: 32px; height: 32px; background: #ebf4ff; color: #3182ce; border-radius: 8px; margin-right: 12px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                                <?= substr($proj['customer_name'], 0, 1) ?>
                                            </div>
                                            <span style="font-weight: 600; color: #2d3748;"><?= htmlspecialchars($proj['customer_name']) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 13px; font-weight: 500;">
                                            <i class="far fa-user-circle mr-1 text-muted"></i> <?= htmlspecialchars($proj['inspector_name'] ?? 'Unassigned') ?>
                                        </div>
                                    </td>
                                    <td><span style="color: #718096; font-size: 13px;"><?= date('M d, Y', strtotime($proj['creation_date'])) ?></span></td>
                                    <td>
                                        <?php 
                                        $statusClass = 'status-review';
                                        if($proj['project_status'] == 'Pending') $statusClass = 'status-pending';
                                        if($proj['project_status'] == 'Completed') $statusClass = 'status-active';
                                        ?>
                                        <span class="status-badge <?= $statusClass ?>"><?= $proj['project_status'] ?></span>
                                    </td>
                                    <td class="text-right">
                                        <button class="btn btn-sm btn-light" style="border-radius: 8px;"><i class="fas fa-eye"></i></button>
                                    </td>
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
$(document).ready(function() {
    const ctxPerf = document.getElementById('mainPerformanceChart').getContext('2d');
    const gradientBlue = ctxPerf.createLinearGradient(0, 0, 0, 400);
    gradientBlue.addColorStop(0, 'rgba(112, 88, 248, 0.4)');
    gradientBlue.addColorStop(1, 'rgba(112, 88, 248, 0.05)');

    new Chart(ctxPerf, {
        type: 'line',
        data: {
            labels: <?= json_encode($trend_labels) ?>,
            datasets: [{
                label: 'Inspections Completed',
                data: <?= json_encode($trend_counts) ?>,
                borderColor: '#7058f8',
                backgroundColor: gradientBlue,
                borderWidth: 4,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: '#7058f8',
                pointRadius: 6,
                pointHoverRadius: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1a202c',
                    padding: 12,
                    titleFont: { size: 14, weight: 'bold' },
                    bodyFont: { size: 13 },
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f7fafc', drawBorder: false },
                    ticks: { color: '#a0aec0', font: { weight: '600' } }
                },
                x: {
                    grid: { display: false },
                    ticks: { color: '#a0aec0', font: { weight: '600' } }
                }
            }
        }
    });
});
</script>

<?php
ob_end_flush();
include_once('../inc/footer.php');
?>
