<?php
// session_start();
include_once('../inc/function.php');

// ============================
// Session & Access Control
// ============================
$logged_in_user = $_SESSION['username'] ?? null;
$userRole       = $_SESSION['role'] ?? null;

// Allow only admin or inspector
if (!$logged_in_user || !in_array($userRole, ['admin', 'inspector'])) {
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
?>

<!-- ========================= -->
<!-- Main Content -->
<!-- ========================= -->
<!-- ========================= -->
<!-- Main Content -->
<!-- ========================= -->
<div class="main-content">
<div class="container-fluid">

<!-- ================= HEADER ================= -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">

        <div>
            <h4 class="mb-1 font-weight-bold">
                <i class="fas fa-clipboard-list text-primary mr-2"></i>
                Operator Assessment List
            </h4>
            <small class="text-muted">Manage and track all operator assessments</small>
        </div>

        <?php if ($userRole === 'admin'): ?>
        <a href="./create-assessment.php" class="btn btn-primary mt-2 mt-md-0">
            <i class="fas fa-plus mr-1"></i> Create Assessment
        </a>
        <?php endif; ?>

    </div>
</div>

<!-- ================= ALERTS ================= -->
<?php if (isset($_SESSION['success_msg'])): ?>
<div class="alert alert-success shadow-sm">
    <i class="fas fa-check-circle mr-2"></i>
    <?= $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
</div>
<?php endif; ?>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="alert alert-danger shadow-sm">
    <i class="fas fa-times-circle mr-2"></i>
    <?= $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
</div>
<?php endif; ?>

<!-- ================= TABLE ================= -->
<div class="card border-0 shadow-sm">
<div class="card-body">

<div class="table-responsive">
<table id="assessmentTable" class="table table-hover align-middle modern-table">

<thead class="thead-light">
<tr>
<th>Assessment</th>
<th>Date</th>
<th>Operator</th>
<th>ID / Passport</th>
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

<td>
    <strong class="text-primary">
        <?= htmlspecialchars($row['assessment_no']); ?>
    </strong>
</td>

<td>
    <span class="text-muted">
        <?= date('d M Y', strtotime($row['date'])); ?>
    </span>
</td>

<td class="font-weight-500">
    <?= htmlspecialchars($row['operator_name']); ?>
</td>

<td><?= htmlspecialchars($row['operator_id_passport']); ?></td>

<td><?= htmlspecialchars($row['client_name'] ?? 'N/A'); ?></td>

<td><?= htmlspecialchars($row['location']); ?></td>

<td>
    <span class="badge badge-light px-3 py-2">
        <?= htmlspecialchars($row['inspector_name']); ?>
    </span>
</td>

<td>
<span class="badge badge-pill badge-info px-3 py-2">
<?= $row['status']; ?>
</span>
</td>

<td>
<span class="badge badge-pill badge-warning px-3 py-2">
<?= $row['exam_status']; ?>
</span>
</td>

<td>
<span class="badge badge-pill badge-secondary px-3 py-2">
<?= $row['signals_status']; ?>
</span>
</td>

<td class="text-center">

<a href="view-assessment.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-outline-info action-btn">
<i class="fas fa-eye"></i>
</a>

<?php if ($userRole === 'inspector'): ?>
<a href="fill-assessment.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-outline-primary action-btn">
<i class="fas fa-edit"></i>
</a>
<?php endif; ?>

<?php if ($userRole === 'admin'): ?>
<a href="delete-assessment.php?id=<?= $row['id']; ?>"
class="btn btn-sm btn-outline-danger action-btn"
onclick="return confirm('Delete this assessment?')">
<i class="fas fa-trash"></i>
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
</div>

<?php include_once('../inc/footer.php'); ?>

<style>

    /* ===============================
   Modern Table Styling
================================*/
.modern-table thead th{
    font-size:13px;
    letter-spacing:.3px;
    text-transform:uppercase;
    border-top:none;
}

.modern-table tbody tr{
    transition:all .15s ease;
}

.modern-table tbody tr:hover{
    background:#f8fbff;
    transform:scale(1.002);
}

.font-weight-500{
    font-weight:500;
}

/* ===============================
   Action Buttons
================================*/
.action-btn{
    margin:2px;
    border-radius:8px;
    transition:.2s;
}

.action-btn:hover{
    transform:translateY(-1px);
}

/* ===============================
   Card Polish
================================*/
.card{
    border-radius:14px;
}

.badge{
    font-weight:500;
    font-size:12px;
}
</style>

<!-- ========================= -->
<!-- DataTable -->
<!-- ========================= -->
<script>
$(document).ready(function () {
    $('#assessmentTable').DataTable({
        pageLength: 25,
        order: [[0, 'desc']]
    });
});
</script>
