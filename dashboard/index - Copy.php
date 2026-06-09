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
$cache_file = $cache_dir . 'dashboard_cache_' . md5($logged_in_user . $user_role) . '.json';

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
        SUM(project_status = 'Pending') AS total_pending_projects,
        SUM(checklist_status = 'Created' AND report_status = 'Generated' AND review_status = 'Pending') AS total_pending_reviewer,
        SUM(checklist_status != 'Created' OR report_status != 'Generated') AS total_pending_inspector,
        SUM(checklist_status = 'Created' AND report_status = 'Generated' AND review_status = 'Completed' AND certificatestatus = 'pending') AS total_pending_dc,
        SUM(certificatestatus = 'Certificate Created' AND project_status = 'Pending') AS total_pending_qc,
        (SELECT COUNT(*) FROM customers) AS total_customers
    FROM project_info
");
    $counts = mysqli_fetch_assoc($counts_query);
    
    extract($counts);

    $result_recent_projects = mysqli_query($conn, "
        SELECT project_no, customer_name, project_status, creation_date, inspector_name 
        FROM project_info 
        ORDER BY creation_date DESC 
        LIMIT 7
    ");

    $data_to_cache = [
        'total_projects' => $total_projects,
        'total_pending_projects' => $total_pending_projects,
        'total_pending_reviewer' => $total_pending_reviewer,
        'total_pending_inspector' => $total_pending_inspector,
        'total_pending_dc' => $total_pending_dc,
        'total_pending_qc' => $total_pending_qc,
        'total_customers' => $total_customers,
        'recent_projects' => mysqli_fetch_all($result_recent_projects, MYSQLI_ASSOC)
    ];

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
    foreach ($chart_data as $key => &$counts) {
        $response = strtolower(trim($row[$key]));
        if ($response === 'yes') $counts['yes']++;
        elseif ($response === 'no') $counts['no']++;
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
</head>
<body>

<div class="main-content">
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
            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.1s;">
                <div class="modern-card stat-card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-circle bg-purple-light">
                             <i class="fa-solid fa-diagram-project"></i>
                        </div>
                        <div class="text-right">
                             <div class="stat-label">Total Projects</div>
                             <h3 class="stat-value counter" data-target="<?= $total_projects ?>">0</h3>
                             <span class="trend-badge trend-up"><i class="fas fa-arrow-up"></i> +12%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.2s;">
                <div class="modern-card stat-card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-circle bg-orange-light">
                             <i class="fa-solid fa-spinner"></i>
                        </div>
                        <div class="text-right">
                             <div class="stat-label">Pending</div>
                             <h3 class="stat-value counter" data-target="<?= $total_pending_projects ?>">0</h3>
                             <span class="trend-badge" style="color:#ff9f43; background:rgba(255,159,67,0.15)">-2.4%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="modern-card stat-card">
                    <div class="stat-card-inner">
                        <div class="stat-icon-circle bg-blue-light">
                             <i class="fa-solid fa-user-check"></i>
                        </div>
                        <div class="text-right">
                             <div class="stat-label">Reviewer Pending</div>
                             <h3 class="stat-value counter" data-target="<?= $total_pending_reviewer ?>">0</h3>
                             <span class="trend-badge trend-up"><i class="fas fa-arrow-up"></i> +8%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.4s;">
                <div class="modern-card stat-card">
                    <div class="stat-card-inner">
                         <div style="width: 50px; height: 50px; border-radius: 50%; background: #ffeaea; color: #ff6b6b; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                             <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <div class="text-right">
                             <div class="stat-label">Inspector Pending</div>
                             <h3 class="stat-value counter" data-target="<?= $total_pending_inspector ?>">0</h3>
                             <span class="trend-badge trend-up">+5%</span>
                        </div>
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