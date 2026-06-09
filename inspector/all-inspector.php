<?php
// session_start();
include_once('../inc/function.php');
include_once('../file/config.php');

/* ================= PAGINATION CONFIG ================= */
$limit = 10; // records per page
$page  = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max(1, $page);
$offset = ($page - 1) * $limit;

/* ================= TOTAL COUNT ================= */
$countSql  = "SELECT COUNT(*) AS total FROM inspectors";
$countRes  = $conn->query($countSql);
$totalRows = $countRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);

/* ================= FETCH DATA ================= */
$sql = "SELECT * FROM inspectors ORDER BY id DESC LIMIT ?, ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $offset, $limit);
$stmt->execute();
$result = $stmt->get_result();

/* ================= KPI DATA ================= */
$kpiTotal = $totalRows;

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Inspector List</title>

<!-- DataTables & FontAwesome Styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/premium-nav.css">

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

.inspector-glass {
    position: relative;
    min-height: calc(100vh - 110px);
    padding: 10px 10px 48px;
    overflow: hidden;
}

.inspector-glass:before {
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

.action-section,
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
.table-responsive {
    overflow-x: auto;
}

table {
    width: 100% !important;
    border-collapse: separate !important;
    border-spacing: 0 10px !important;
}

table thead th {
    padding: 14px 12px !important;
    border: 0 !important;
    background: rgba(241, 245, 249, 0.78) !important;
    color: #334155;
    font-size: 11px;
    font-weight: 850;
    letter-spacing: .02em;
    text-transform: uppercase;
}

table tbody tr {
    background: rgba(255, 255, 255, 0.62);
    box-shadow: 0 10px 26px rgba(15, 23, 42, 0.05);
    transition: all 0.2s ease;
}

table tbody tr:hover {
    background: rgba(255, 255, 255, 0.9);
    transform: scale(1.002);
}

table tbody td {
    padding: 14px 12px !important;
    border-top: 1px solid rgba(226, 232, 240, 0.58);
    border-bottom: 1px solid rgba(226, 232, 240, 0.58);
    color: #475569;
    vertical-align: middle;
    font-size: 14px;
    font-weight: 600;
}

table tbody td:first-child {
    border-left: 1px solid rgba(226, 232, 240, 0.58);
    border-radius: 14px 0 0 14px;
}

table tbody td:last-child {
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

.btn-view { background: #0ea5e9; }
.btn-edit { background: #6366f1; }
.btn-key { background: #f59e0b; }
.btn-trash { background: #ef4444; }

.action-btn:hover {
    transform: translateY(-2px);
    filter: brightness(1.1);
    box-shadow: 0 8px 16px rgba(0,0,0,0.1);
}

.badge-active {
    background: #dcfce7;
    color: #166534;
    padding: 5px 12px;
    border-radius: 99px;
    font-size: 11px;
    font-weight: 800;
}

/* PREMIUM PAGINATION */
.pagination-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 24px;
    padding-top: 20px;
    border-top: 1px solid rgba(226, 232, 240, 0.6);
}

.pagination-info {
    font-size: 14px;
    font-weight: 700;
    color: #64748b;
}

.pagination {
    display: flex;
    gap: 8px;
    list-style: none;
    margin: 0;
}

.page-link {
    min-width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.6);
    border: 1px solid rgba(226, 232, 240, 0.8);
    color: #475569;
    font-weight: 800;
    text-decoration: none;
    transition: all 0.2s;
}

.page-item.active .page-link {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
    box-shadow: 0 8px 20px rgba(37, 99, 235, 0.25);
}

.page-item.disabled .page-link {
    opacity: 0.5;
    pointer-events: none;
}

.page-link:hover:not(.active) {
    background: #fff;
    border-color: #2563eb;
    color: #2563eb;
}

/* MODAL STYLING */
.modal-content {
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.5);
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    box-shadow: 0 40px 100px rgba(15, 23, 42, 0.2);
}

.modal-header {
    border-bottom: 1px solid rgba(226, 232, 240, 0.6);
    padding: 24px;
}

.modal-title {
    font-weight: 850;
    color: #111827;
}

.modal-body {
    padding: 24px;
}

.form-control {
    border-radius: 12px;
    border: 1px solid rgba(148, 163, 184, 0.3);
    padding: 12px 16px;
    font-weight: 600;
    background: rgba(255, 255, 255, 0.7);
}

.form-control:focus {
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    border-color: #2563eb;
}

@media(max-width: 768px) {
    .page-hero {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
    }
    .page-title {
        flex-direction: column;
    }
    .pagination-wrapper {
        flex-direction: column;
        gap: 16px;
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

<div class="main-content d-flex flex-column inspector-glass">
<div class="container-fluid mt-4">

    <!-- PAGE HERO -->
    <div class="page-hero">
        <div class="page-title">
            <span class="title-icon"><i class="icofont-users-social"></i></span>
            <div>
                <h2>Inspector Directory</h2>
                <p>Manage inspector profiles, reset passwords, and monitor system access.</p>
            </div>
        </div>
        <div>
            <a href="./create-inspector.php" class="btn-primary-pre">
                <i class="fas fa-plus"></i> Create New Inspector
            </a>
        </div>
    </div>

    <!-- KPI STATS -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="kpi-card">
                <h6>Total Inspectors</h6>
                <h2><?= $kpiTotal; ?></h2>
            </div>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="card-box">
        <div class="table-responsive">
            <table class="w-100">
                <thead>
                <tr>
                    <th class="text-center">ID</th>
                    <th>Inspector Name</th>
                    <th>Email Address</th>
                    <th>Mobile</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <?php
                        $folder = strtolower(str_replace(' ', '_', $row['inspector_name']));
                        $photo  = "./uploads/$folder/images/profile_image.jpg";
                        if (!file_exists($photo)) {
                            $photo = "../assets/img/img-placeholder.png";
                        }
                        ?>
                        <tr>
                            <td class="text-center font-weight-bold" style="color:#2563eb;">#<?= $row['inspector_id']; ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div style="width:38px; height:38px; border-radius:12px; overflow:hidden; margin-right:12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: 2px solid #fff;">
                                        <img src="<?= $photo; ?>" style="width:100%; height:100%; object-fit:cover;">
                                    </div>
                                    <span style="font-weight: 700; color: #111827;"><?= htmlspecialchars($row['inspector_name']); ?></span>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($row['email']); ?></td>
                            <td><?= htmlspecialchars($row['mobile']); ?></td>
                            <td><?= htmlspecialchars($row['address']); ?></td>
                            <td><span class="badge-active">Active</span></td>
                            <td class="text-center">
                                <a href="index.php?id=<?= $row['id']; ?>" class="action-btn btn-view" title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="edit-inspector.php?id=<?= $row['id']; ?>" class="action-btn btn-edit" title="Edit Inspector">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button type="button" 
                                        class="action-btn btn-key reset-password-btn" 
                                        data-inspector-id="<?= $row['inspector_id']; ?>"
                                        data-inspector-name="<?= htmlspecialchars($row['inspector_name']); ?>"
                                        title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <a href="delete-inspector.php?id=<?= $row['id']; ?>"
                                   class="action-btn btn-trash"
                                   onclick="return confirm('Delete this inspector?')" title="Delete Inspector">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center" style="padding: 40px;">
                            <div style="color: #94a3b8;"><i class="fas fa-user-slash mb-3" style="font-size: 32px; opacity:0.5;"></i><br>No inspectors registered in the system.</div>
                        </td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- PAGINATION -->
        <?php if ($totalPages > 1): ?>
        <div class="pagination-wrapper">
            <div class="pagination-info">
                Showing <?= min($offset+1, $totalRows); ?> &ndash; <?= min($offset+$limit, $totalRows); ?> of <?= $totalRows; ?>
            </div>
            
            <ul class="pagination">
                <li class="page-item <?= ($page<=1)?'disabled':''; ?>">
                    <a class="page-link" href="?page=<?= $page-1; ?>"><i class="fas fa-chevron-left"></i></a>
                </li>

                <?php
                $start = max(1, $page-2);
                $end   = min($totalPages, $page+2);
                for($i=$start;$i<=$end;$i++):
                ?>
                <li class="page-item <?= ($i==$page)?'active':''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= ($page>=$totalPages)?'disabled':''; ?>">
                    <a class="page-link" href="?page=<?= $page+1; ?>"><i class="fas fa-chevron-right"></i></a>
                </li>
            </ul>
        </div>
        <?php endif; ?>
    </div>

</div>
</div>

<!-- ================= PASSWORD RESET MODAL ================= -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-shield-alt" style="color: #2563eb; margin-right: 8px;"></i> Reset Password
                </h5>
                <button type="button" class="close" data-dismiss="modal" style="outline:none;">&times;</button>
            </div>
            <div class="modal-body">
                <form id="resetPasswordForm">
                    <input type="hidden" id="reset_inspector_id" name="inspector_id">
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted mb-2">INSPECTOR NAME</label>
                        <input type="text" class="form-control" id="inspector_name_display" readonly style="background:#f1f5f9;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="small font-weight-bold text-muted mb-2">NEW PASSWORD</label>
                        <input type="password" class="form-control" id="new_password" required minlength="6" placeholder="Enter new secure password">
                    </div>
                    <div class="form-group mb-4">
                        <label class="small font-weight-bold text-muted mb-2">CONFIRM PASSWORD</label>
                        <input type="password" class="form-control" id="confirm_password" required minlength="6" placeholder="Repeat new password">
                    </div>
                    <div id="password_error" class="alert alert-danger p-2 small" style="display:none; border-radius:10px;"></div>
                </form>
            </div>
            <div class="modal-footer" style="border:none; padding-top:0;">
                <button type="button" class="btn btn-light font-weight-bold" data-dismiss="modal" style="border-radius:12px; padding:10px 20px;">Cancel</button>
                <button type="button" class="btn-primary-pre" id="confirmResetPassword" style="min-height:44px; font-size:14px;">Update Password</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    // Open modal and populate data
    $('.reset-password-btn').on('click', function() {
        var inspectorId = $(this).data('inspector-id');
        var inspectorName = $(this).data('inspector-name');
        
        $('#reset_inspector_id').val(inspectorId);
        $('#inspector_name_display').val(inspectorName);
        $('#new_password').val('');
        $('#confirm_password').val('');
        $('#password_error').hide();
        
        $('#resetPasswordModal').modal('show');
    });
    
    // Handle password reset
    $('#confirmResetPassword').on('click', function() {
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();
        var inspectorId = $('#reset_inspector_id').val();
        
        $('#password_error').hide();
        
        // Validation
        if (newPassword.length < 6) {
            $('#password_error').text('Password must be at least 6 characters long.').show();
            return;
        }
        
        if (newPassword !== confirmPassword) {
            $('#password_error').text('Passwords do not match.').show();
            return;
        }
        
        // Disable button during request
        var btn = $('#confirmResetPassword');
        var originalText = btn.html();
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Resetting...');
        
        // AJAX request
        $.ajax({
            url: 'reset-inspector-password.php',
            type: 'POST',
            data: {
                inspector_id: inspectorId,
                new_password: newPassword
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#resetPasswordModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    $('#password_error').text(response.message).show();
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function() {
                $('#password_error').text('An error occurred. Please try again.').show();
                btn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>

<?php include_once('../inc/footer.php'); ?>
</body>
</html>
