<?php
// session_start();
include_once('../inc/function.php');
include_once('../file/config.php');

// ============================
// Session & Access Control
// ============================
$logged_in_user = $_SESSION['username'] ?? null;
$userRole       = $_SESSION['role'] ?? null;

// Allow only admin or inspector
if (!$logged_in_user || !in_array($userRole, ['admin', 'inspector', 'document controller'])) {
    header("Location: ../index.php");
    exit;
}

// ============================
// Build SQL Query
// ============================
$sql = "SELECT 
            oa.id,
            oa.assessment_no,
            oa.date,
            oa.operator_name,
            oa.operator_id_passport,
            c.customer_name AS client_name,
            oa.location,
            oa.operating_location,
            oa.no_of_equipment,
            nu.username AS inspector_name,
            oa.status,
            oa.exam_status,
            oa.exam_score,
            oa.signals_status,
            oa.signals_score,
            oa.created_at
        FROM operator_assessments oa
        LEFT JOIN customers c ON oa.client_id = c.cus_id
        LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id";

// Inspector sees only assigned assessments
if ($userRole === 'inspector') {
    $sql .= " WHERE nu.username = ?";
}

$sql .= " ORDER BY oa.id DESC";

$stmt = $conn->prepare($sql);

if ($userRole === 'inspector') {
    $stmt->bind_param("s", $logged_in_user);
}

$stmt->execute();
$result = $stmt->get_result();

$total_assessments = $result->num_rows;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Operator Assessment List</title>
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/premium-nav.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
   <style>
/* ===== PREMIUM DASHBOARD UI ===== */
body {
    background:
        radial-gradient(circle at 14% 8%, rgba(20, 184, 166, 0.16), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(37, 99, 235, 0.13), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
    font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    color: #111827;
}

.assessment-glass {
    position: relative;
    min-height: calc(100vh - 110px);
    padding: 10px 10px 48px;
    overflow: hidden;
}

.assessment-glass:before {
    content: "";
    position: fixed;
    right: 4%;
    top: 140px;
    width: 360px;
    height: 360px;
    border-radius: 999px;
    background: rgba(20, 184, 166, 0.1);
    filter: blur(40px);
    pointer-events: none;
    z-index: -1;
}

.container-fluid {
    max-width: 1600px;
}

.page-hero,
.kpi-card,
.action-section,
.card-box {
    border: 1px solid rgba(255, 255, 255, 0.64);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48));
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.page-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 28px;
    padding: 26px 28px;
    border-radius: 22px;
    overflow: hidden;
}

.page-title {
    display: flex;
    align-items: center;
    gap: 16px;
    min-width: 0;
}

.page-title .title-icon {
    width: 58px;
    height: 58px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 18px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.16), rgba(20, 184, 166, 0.14));
    color: #2563eb;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 16px 32px rgba(15, 23, 42, 0.1);
    font-size: 24px;
    flex: 0 0 auto;
}

.page-title h2 {
    margin-bottom: 8px;
    color: #111827;
    font-size: clamp(22px, 2vw, 30px);
    font-weight: 800;
}

.page-title p {
    margin: 0;
    color: #64748b;
    font-size: 14px;
    line-height: 1.45;
}

.kpi-card {
    position: relative;
    min-height: 120px;
    padding: 24px;
    border-radius: 20px;
    overflow: hidden;
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
}

.kpi-card:hover {
    transform: translateY(-4px);
    border-color: rgba(20, 184, 166, 0.32);
    box-shadow: 0 30px 70px rgba(15, 23, 42, 0.16);
}

.kpi-card h6 {
    position: relative;
    margin-bottom: 8px;
    color: #64748b;
    font-size: 12px;
    font-weight: 800;
    letter-spacing: .04em;
    text-transform: uppercase;
}

.kpi-card h2 {
    position: relative;
    margin: 0;
    font-size: 32px;
    font-weight: 850;
    color: #2563eb;
}

.card-box {
    margin-bottom: 32px;
    padding: 24px;
    border-radius: 22px;
}

.btn-primary-pre {
    min-height: 46px;
    padding: 12px 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 0;
    border-radius: 13px;
    font-weight: 800;
    background: linear-gradient(135deg, #2563eb 0%, #16a3d8 52%, #14b8a6 100%);
    color: #fff !important;
    box-shadow: 0 18px 34px rgba(37, 99, 235, 0.24);
    transition: transform .2s ease, box-shadow .2s ease;
    text-decoration: none;
    gap: 8px;
}

.btn-primary-pre:hover {
    transform: translateY(-1px);
    box-shadow: 0 22px 42px rgba(20, 184, 166, 0.2);
}

/* TABLE STYLING */
table.dataTable {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0 10px !important;
}

table.dataTable thead th {
    padding: 14px 12px !important;
    border: 0 !important;
    background: rgba(241, 245, 249, 0.78) !important;
    color: #334155;
    font-size: 11px;
    font-weight: 850;
    letter-spacing: .02em;
    text-transform: uppercase;
}

table.dataTable tbody tr {
    background: rgba(255, 255, 255, 0.62);
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
    transition: all 0.2s ease;
}

table.dataTable tbody tr:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: scale(1.002);
}

table.dataTable tbody td {
    padding: 14px 12px !important;
    border-top: 1px solid rgba(226, 232, 240, 0.58);
    border-bottom: 1px solid rgba(226, 232, 240, 0.58);
    color: #475569;
    vertical-align: middle;
    font-size: 14px;
    font-weight: 600;
}

table.dataTable tbody td:first-child {
    border-left: 1px solid rgba(226, 232, 240, 0.58);
    border-radius: 14px 0 0 14px;
}

table.dataTable tbody td:last-child {
    border-right: 1px solid rgba(226, 232, 240, 0.58);
    border-radius: 0 14px 14px 0;
}

/* ACTION BUTTONS */
.action-btn {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    margin: 0 3px;
    color: #fff !important;
}

.btn-info-custom { background: #0ea5e9; }
.btn-edit-custom { background: #6366f1; }
.btn-danger-custom { background: #ef4444; }
.btn-success-custom { background: #22c55e; }

.action-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

/* BADGES */
.badge-custom {
    padding: 5px 12px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 800;
    display: inline-block;
}

.badge-success-custom { background: #dcfce7; color: #166534; }
.badge-info-custom { background: #e0f2fe; color: #0369a1; }
.badge-warning-custom { background: #fef3c7; color: #b45309; }
.badge-secondary-custom { background: #f1f5f9; color: #475569; }

/* DATATABLE OVERRIDES */
.dataTables_wrapper .dataTables_filter input {
    border-radius: 12px;
    border: 1px solid rgba(148, 163, 184, 0.3);
    padding: 10px 14px;
    background: rgba(255, 255, 255, 0.7);
    outline: none;
    font-weight: 600;
}

.dataTables_wrapper .dataTables_paginate .paginate_button {
    border-radius: 12px !important;
    border: 1px solid rgba(226, 232, 240, 0.8) !important;
    background: rgba(255, 255, 255, 0.6) !important;
    color: #475569 !important;
    font-weight: 800;
    margin-left: 6px;
}

.dataTables_wrapper .dataTables_paginate .paginate_button.current {
    background: #2563eb !important;
    color: white !important;
    border-color: #2563eb !important;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
}

.dt-buttons .btn {
    border-radius: 12px !important;
    background: #fff !important;
    border: 1px solid #e2e8f0 !important;
    font-weight: 700 !important;
    padding: 8px 16px !important;
    font-size: 13px !important;
}

@media(max-width: 768px) {
    .page-hero {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
}
</style>
</head>
<body>

<?php 
if (file_exists('../inc/nav.php')) {
    include_once('../inc/nav.php'); 
}
?>

<div class="main-content d-flex flex-column assessment-glass">
<div class="container-fluid mt-4">

    <!-- PAGE HERO -->
    <div class="page-hero">
        <div class="page-title">
            <span class="title-icon"><i class="icofont-graduate-alt"></i></span>
            <div>
                <h2>Operator Assessments</h2>
                <p>Monitor competency evaluations, exam results, and certificate status for all operators.</p>
            </div>
        </div>
        <?php if ($userRole === 'admin'): ?>
            <div>
                <a href="./create-assessment.php" class="btn-primary-pre">
                    <i class="fas fa-plus"></i> New Assessment
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- KPI STATS -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="kpi-card">
                <h6>Total Assessments</h6>
                <h2 id="stats-total"><?php echo $total_assessments; ?></h2>
            </div>
        </div>
    </div>

    <!-- Messages -->
    <?php if (isset($_SESSION['success_msg'])): ?>
        <div class="alert alert-success" style="border-radius: 12px; border: none; background: #dcfce7; color: #16a34a; font-weight: 600;">
            <i class="fas fa-check-circle mr-2"></i> <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_msg'])): ?>
        <div class="alert alert-danger" style="border-radius: 12px; border: none; background: #fee2e2; color: #dc2626; font-weight: 600;">
            <i class="fas fa-exclamation-circle mr-2"></i> <?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
        </div>
    <?php endif; ?>

    <!-- DATA TABLE -->
    <div class="card-box">
        <div class="table-responsive">
            <table id="assessmentTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>Assmnt No</th>
                        <th>Date</th>
                        <th>Operator Name</th>
                        <th>Client</th>
                        <th>Location</th>
                        <th>Inspector</th>
                        <th>Status</th>
                        <th>Exam</th>
                        <th>Signals</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><strong style="color: #2563eb;"><?= htmlspecialchars($row['assessment_no']); ?></strong></td>
                        <td><?= date('d-M-Y', strtotime($row['date'])); ?></td>
                        <td>
                            <span style="font-weight: 700; color: #111827;"><?= htmlspecialchars($row['operator_name']); ?></span>
                            <div class="small text-muted"><?= htmlspecialchars($row['operator_id_passport']); ?></div>
                        </td>
                        <td><?= htmlspecialchars($row['client_name'] ?? 'N/A'); ?></td>
                        <td><?= htmlspecialchars($row['location']); ?></td>
                        <td><?= htmlspecialchars($row['inspector_name']); ?></td>

                        <td>
                            <span class="badge-custom <?= strtolower($row['status']) === 'completed' ? 'badge-success-custom' : 'badge-info-custom' ?>">
                                <?= $row['status']; ?>
                            </span>
                        </td>
                        <td><span class="badge-custom badge-warning-custom"><?= $row['exam_status']; ?></span></td>
                        <td><span class="badge-custom badge-secondary-custom"><?= $row['signals_status']; ?></span></td>

                        <td class="text-center">
                            <a href="view-assessment.php?id=<?= $row['id']; ?>" class="action-btn btn-info-custom" title="View">
                                <i class="fas fa-eye"></i>
                            </a>

                            <?php if ($userRole === 'inspector'): ?>
                                <a href="fill-assessment.php?id=<?= $row['id']; ?>" class="action-btn btn-edit-custom" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            <?php endif; ?>

                            <?php if ($userRole === 'admin'): ?>
                                <a href="delete-assessment.php?id=<?= $row['id']; ?>"
                                   class="action-btn btn-danger-custom"
                                   onclick="return confirm('Delete this assessment?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            <?php endif; ?>

                            <?php if (strtoupper($row['status']) === 'COMPLETED'): ?>
                                <a href="download-certificate.php?id=<?= $row['id']; ?>" class="action-btn btn-success-custom" target="_blank" title="Download Certificate">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                                <a href="../operator_card/view-card.php?id=<?= $row['id']; ?>" class="action-btn" style="background: #eab308;" target="_blank" title="Operator Card">
                                    <i class="fas fa-id-card"></i>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<?php include_once('../inc/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
$(document).ready(function() {
    $('#assessmentTable').DataTable({
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[0, 'desc']], 
        dom: '<"d-flex flex-wrap justify-content-between align-items-center mb-4"Bf>rt<"d-flex flex-wrap justify-content-between align-items-center mt-4"lip>',
        language: {
            paginate: {
                previous: "<i class='fas fa-chevron-left'></i>",
                next: "<i class='fas fa-chevron-right'></i>"
            },
            search: "_INPUT_",
            searchPlaceholder: "Search assessments..."
        },
        buttons: [
            {
                extend: 'csv',
                text: '<i class="fas fa-file-csv mr-2"></i> Export CSV',
                className: 'btn'
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print mr-2"></i> Print',
                className: 'btn'
            }
        ]
    });
});
</script>

</body>
</html>
