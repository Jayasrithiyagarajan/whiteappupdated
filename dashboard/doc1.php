<?php
include_once('../file/config.php');
include_once('../inc/function.php');

/* ================= AUTH CHECK ================= */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'document controller') {
    header("Location: ../index.php");
    exit();
}

/* ================= DASHBOARD STATS ================= */
$stats = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT 
        COUNT(*) AS total_projects,
        SUM(project_status='Pending') AS pending_projects,
        SUM(project_status='Completed') AS completed_projects
    FROM project_info
"));

/* ================= PAGINATION ================= */
$perPage = 6;
$page    = max(1, (int)($_GET['page'] ?? 1));
$offset  = ($page - 1) * $perPage;

$totalRows = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) total FROM project_info WHERE project_status='Pending'")
)['total'];

$totalPages = ceil($totalRows / $perPage);

/* ================= DATA ================= */
$pendingProjects = mysqli_query($conn, "
    SELECT project_no, customer_name, equipment_type,
           equipment_location, inspector_name, creation_date
    FROM project_info
    WHERE project_status='Pending'
    ORDER BY creation_date DESC
    LIMIT $perPage OFFSET $offset
");

$completedProjects = mysqli_query($conn, "
    SELECT project_no, customer_name
    FROM project_info
    WHERE project_status='Completed'
    ORDER BY creation_date DESC
    LIMIT 4
");

$notifications = mysqli_query($conn, "
    SELECT project_no, notification_message, created_at
    FROM project_notifications
    WHERE document_controller='pending'
    ORDER BY created_at DESC
    LIMIT 5
");
$notificationCount = mysqli_num_rows($notifications);

$news = mysqli_query($conn, "
    SELECT news_text, created_at
    FROM news
    ORDER BY created_at DESC
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Document Controller Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="../assets/css/main.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

<style>
/* ================= DASHBOARD ================= */
.kpi-card{
    background:#fff;
    border-radius:8px;
    padding:20px;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
    display:flex;
    justify-content:space-between;
    align-items:center;
}
.kpi-info h3{margin:0;font-size:26px}
.kpi-info span{font-size:14px;color:#777}
.kpi-icon{
    width:52px;height:52px;
    border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    color:#fff;font-size:20px;
}

.panel{
    background:#fff;
    border-radius:8px;
    padding:20px;
    box-shadow:0 4px 12px rgba(0,0,0,.05);
}
.panel-title{
    font-size:16px;
    font-weight:600;
    margin-bottom:15px;
}

.notification-item{
    padding:10px 0;
    border-bottom:1px solid #eee;
}
.notification-item:last-child{border-bottom:none}
.notification-item small{color:#888}

.table-dashboard thead th{
    background:#f6f7fb;
    font-weight:600;
}
.status-pill{
    padding:4px 12px;
    border-radius:20px;
    font-size:12px;
    color:#fff;
    background:#f0ad4e;
}

.pagination a{
    padding:6px 12px;
    border:1px solid #ddd;
    border-radius:4px;
    margin:0 2px;
    text-decoration:none;
}
.pagination a.active{
    background:#4f46e5;
    color:#fff;
    border-color:#4f46e5;
}
</style>
</head>

<body>

<div class="main-content">
<div class="container-fluid">

<!-- ================= KPI ROW ================= -->
<div class="row mb-30">

    <div class="col-xl-4 col-md-6">
        <div class="kpi-card">
            <div class="kpi-info">
                <span>Total Projects</span>
                <h3><?= $stats['total_projects'] ?></h3>
            </div>
            <div class="kpi-icon" style="background:#4f46e5">
                <i class="fa fa-folder"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="kpi-card">
            <div class="kpi-info">
                <span>Pending Projects</span>
                <h3><?= $stats['pending_projects'] ?></h3>
            </div>
            <div class="kpi-icon" style="background:#f0ad4e">
                <i class="fa fa-clock"></i>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6">
        <div class="kpi-card">
            <div class="kpi-info">
                <span>Completed Projects</span>
                <h3><?= $stats['completed_projects'] ?></h3>
            </div>
            <div class="kpi-icon" style="background:#5cb85c">
                <i class="fa fa-check"></i>
            </div>
        </div>
    </div>

</div>

<!-- ================= PANELS ================= -->
<div class="row">

<!-- Notifications -->
<div class="col-xl-6 mb-30">
    <div class="panel">
        <div class="panel-title">Notifications</div>

        <?php if($notificationCount): while($n=mysqli_fetch_assoc($notifications)): ?>
            <div class="notification-item">
                <strong><?= htmlspecialchars($n['notification_message']) ?></strong><br>
                <small>
                    Project <?= $n['project_no'] ?> •
                    <?= date('d M Y, h:i A',strtotime($n['created_at'])) ?>
                </small>
            </div>
        <?php endwhile; else: ?>
            <p class="text-muted">No notifications</p>
        <?php endif; ?>
    </div>
</div>

<!-- News -->
<div class="col-xl-6 mb-30">
    <div class="panel">
        <div class="panel-title">Latest News</div>
        <?php while($n=mysqli_fetch_assoc($news)): ?>
            <div class="notification-item">
                <?= htmlspecialchars($n['news_text']) ?><br>
                <small><?= date('d M Y',strtotime($n['created_at'])) ?></small>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Completed -->
<div class="col-xl-6 mb-30">
    <div class="panel">
        <div class="panel-title">Completed Projects</div>
        <?php while($c=mysqli_fetch_assoc($completedProjects)): ?>
            <div class="notification-item">
                <strong>#<?= $c['project_no'] ?></strong> —
                <?= htmlspecialchars($c['customer_name']) ?>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Pending Table -->
<div class="col-xl-12 mb-30">
    <div class="panel">
        <div class="panel-title">Pending Projects</div>

        <div class="table-responsive">
        <table class="table table-dashboard">
            <thead>
                <tr>
                    <th>Project</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Status</th>
                    <th>Equipment</th>
                    <th>Location</th>
                    <th>Inspector</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php if(mysqli_num_rows($pendingProjects)): while($p=mysqli_fetch_assoc($pendingProjects)): ?>
                <tr>
                    <td>#<?= $p['project_no'] ?></td>
                    <td><?= date('d M Y',strtotime($p['creation_date'])) ?></td>
                    <td><?= htmlspecialchars($p['customer_name']) ?></td>
                    <td><span class="status-pill">Pending</span></td>
                    <td><?= htmlspecialchars($p['equipment_type']) ?></td>
                    <td><?= htmlspecialchars($p['equipment_location']) ?></td>
                    <td><?= htmlspecialchars($p['inspector_name']) ?></td>
                    <td>
                        <a href="../job/job-details.php?id=<?= $p['project_no'] ?>" class="btn btn-sm btn-primary">
                            View
                        </a>
                    </td>
                </tr>
            <?php endwhile; else: ?>
                <tr><td colspan="8" class="text-center">No records found</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
        </div>

        <div class="pagination text-center mt-3">
            <?php for($i=1;$i<=$totalPages;$i++): ?>
                <a href="?page=<?= $i ?>" class="<?= $i==$page?'active':'' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
</div>

</div>
</div>
</div>

</body>
</html>

<?php include_once('../inc/footer.php'); ?>
