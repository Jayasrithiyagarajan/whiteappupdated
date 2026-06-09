<?php
include_once('../inc/function.php');
include_once('../file/config.php');

/* ================= PAGINATION CONFIG ================= */
$limit = 10; 
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

$url = 'http://localhost/whiteappupdated/';
?>

<!-- Modern UI Styles -->
<link rel="stylesheet" href="../assets/css/modern_ui.css">

<style>
    .inspector-avatar-full {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        object-fit: cover;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    .action-btn {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: 0.3s;
        border: 1px solid #efefef;
        background: #fff;
        color: #555;
        margin-right: 5px;
    }
    .action-btn:hover {
        background: var(--primary-purple);
        color: #fff;
        transform: translateY(-2px);
        border-color: var(--primary-purple);
    }
    .action-btn-danger:hover {
        background: var(--danger-red);
        border-color: var(--danger-red);
    }
    .action-btn-warning:hover {
        background: var(--secondary-orange);
        border-color: var(--secondary-orange);
    }
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
    }
    .custom-pagination {
        display: flex;
        gap: 8px;
    }
    .custom-pagination a, .custom-pagination span {
        padding: 8px 16px;
        border-radius: 10px;
        background: #fff;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        font-weight: 600;
        text-decoration: none;
        transition: 0.3s;
    }
    .custom-pagination a:hover {
        background: #f7fafc;
        border-color: #cbd5e0;
    }
    .custom-pagination a.active {
        background: var(--primary-purple);
        color: #fff;
        border-color: var(--primary-purple);
    }
</style>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- Header -->
        <div class="modern-welcome animate-slide-up" style="animation-delay: 0.1s;">
            <div class="welcome-text">
                <!-- <span style="color: var(--text-secondary); font-size: 14px; font-weight: 600; display: block; margin-bottom: 5px;">HUMAN RESOURCES</span> -->
                <h1>Inspector <span class="premium-accent">Directory</span></h1>
                <p class="text-muted mb-0 mt-2">Manage and oversee all certified inspectors in the system.</p>
            </div>
            <div class="quick-action-btns">
                <a href="./create-inspector.php" class="btn btn-primary shadow-sm">
                    <i class="fas fa-plus mr-2"></i> Add New Inspector
                </a>
            </div>
        </div>

        <!-- KPI Summary -->
        <div class="row">
            <div class="col-xl-3 col-md-6 animate-slide-up" style="animation-delay: 0.2s;">
                <div class="modern-card">
                    <div class="stat-card-inner">
                        <div>
                            <span class="stat-label">Total Personnel</span>
                            <div class="stat-value"><?= number_format($totalRows) ?></div>
                            <span class="trend-badge trend-up">Active Team</span>
                        </div>
                        <div class="stat-icon-circle bg-purple-light">
                             <i class="fas fa-user-tie"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Directory Table -->
        <div class="row">
            <div class="col-12 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="modern-card">
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Inspector Info</th>
                                    <th>Contact Details</th>
                                    <th>Location</th>
                                    <th>Account Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <?php
                                $folder = strtolower(str_replace(' ', '_', $row['inspector_name']));
                                $photo  = "./uploads/$folder/images/profile_image.jpg";
                                if (!file_exists($photo)) {
                                    $photo = $url . "assets/img/img-placeholder.png";
                                }
                                ?>
                                <tr>
                                    <td class="font-weight-bold" style="color: #a0aec0;">#<?= $row['inspector_id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="<?= $photo; ?>" class="inspector-avatar-full mr-3">
                                            <div>
                                                <div style="font-weight: 700; color: #2d3748;"><?= htmlspecialchars($row['inspector_name']); ?></div>
                                                <div style="font-size: 12px; color: #718096;">Certified Inspector</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="font-size: 13px; font-weight: 600; color: #4a5568;"><i class="far fa-envelope mr-2 text-muted"></i><?= htmlspecialchars($row['email']); ?></div>
                                        <div style="font-size: 12px; color: #718096; margin-top: 4px;"><i class="fas fa-phone mr-2 text-muted"></i><?= htmlspecialchars($row['mobile']); ?></div>
                                    </td>
                                    <td>
                                        <div style="font-size: 13px; color: #4a5568;"><i class="fas fa-map-marker-alt mr-2 text-muted"></i><?= htmlspecialchars($row['address']); ?></div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-active">Enabled</span>
                                    </td>
                                    <td class="text-right">
                                        <div class="d-flex justify-content-end">
                                            <a href="index.php?id=<?= $row['id']; ?>" class="action-btn" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="edit-inspector.php?id=<?= $row['id']; ?>" class="action-btn" title="Edit Info">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button type="button" 
                                                    class="action-btn action-btn-warning reset-password-btn" 
                                                    data-inspector-id="<?= $row['inspector_id']; ?>"
                                                    data-inspector-name="<?= htmlspecialchars($row['inspector_name']); ?>"
                                                    title="Reset Password">
                                                <i class="fas fa-key"></i>
                                            </button>
                                            <a href="delete-inspector.php?id=<?= $row['id']; ?>"
                                               class="action-btn action-btn-danger"
                                               onclick="return confirm('Delete this inspector?')"
                                               title="Delete Record">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5">
                                        <img src="../assets/img/img-placeholder.png" style="width: 80px; opacity: 0.3; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;">
                                        <h5 class="text-muted">No inspectors found in the database.</h5>
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                    <div class="pagination-container">
                        <div class="text-muted" style="font-size: 14px; font-weight: 600;">
                            Showing <span class="premium-accent"><?= min($offset+1, $totalRows); ?></span> to <span class="premium-accent"><?= min($offset+$limit, $totalRows); ?></span> of <?= $totalRows; ?> results
                        </div>
                        <div class="custom-pagination">
                            <a href="?page=<?= $page-1; ?>" class="<?= ($page<=1)?'disabled':''; ?>"><i class="fas fa-chevron-left"></i></a>
                            
                            <?php
                            $start = max(1, $page-2);
                            $end   = min($totalPages, $page+2);
                            for($i=$start;$i<=$end;$i++):
                            ?>
                            <a href="?page=<?= $i; ?>" class="<?= ($i==$page)?'active':''; ?>"><?= $i; ?></a>
                            <?php endfor; ?>
                            
                            <a href="?page=<?= $page+1; ?>" class="<?= ($page>=$totalPages)?'disabled':''; ?>"><i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ================= PASSWORD RESET MODAL ================= -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" style="border-radius: 20px; border: none; box-shadow: 0 25px 50px rgba(0,0,0,0.2);">
            <div class="modal-header" style="border: none; padding: 30px 30px 10px;">
                <h4 class="modal-title font-weight-bold" style="color: #1a202c;">Reset Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px 30px 30px;">
                <form id="resetPasswordForm">
                    <input type="hidden" id="reset_inspector_id" name="inspector_id">
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color: #4a5568; font-size: 14px;">Personnel Name</label>
                        <input type="text" class="form-control" id="inspector_name_display" readonly 
                               style="background: #f7fafc; border: 1px solid #edf2f7; border-radius: 12px; font-weight: 600;">
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color: #4a5568; font-size: 14px;">New Secure Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password" required minlength="6"
                               placeholder="********" style="border-radius: 12px; height: 50px; border: 2px solid #edf2f7;">
                        <small class="form-text text-muted mt-2">Minimum 6 characters recommended.</small>
                    </div>
                    <div class="form-group mb-4">
                        <label class="font-weight-bold" style="color: #4a5568; font-size: 14px;">Confirm Password</label>
                        <input type="password" class="form-control" id="confirm_password" required minlength="6"
                               placeholder="********" style="border-radius: 12px; height: 50px; border: 2px solid #edf2f7;">
                    </div>
                    <div id="password_error" class="alert alert-danger" style="display:none; border-radius: 12px;"></div>
                    <div id="password_success" class="alert alert-success" style="display:none; border-radius: 12px;"></div>
                    
                    <button type="button" class="btn btn-primary btn-block p-3" id="confirmResetPassword" 
                            style="border-radius: 15px; font-weight: 700; font-size: 16px; margin-top: 20px;">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.reset-password-btn').on('click', function() {
        var inspectorId = $(this).data('inspector-id');
        var inspectorName = $(this).data('inspector-name');
        
        $('#reset_inspector_id').val(inspectorId);
        $('#inspector_name_display').val(inspectorName);
        $('#new_password').val('');
        $('#confirm_password').val('');
        $('#password_error').hide();
        $('#password_success').hide();
        
        $('#resetPasswordModal').modal('show');
    });
    
    $('#confirmResetPassword').off('click').on('click', function() {
        var newPassword = $('#new_password').val();
        var confirmPassword = $('#confirm_password').val();
        var inspectorId = $('#reset_inspector_id').val();
        
        $('#password_error').hide();
        $('#password_success').hide();
        
        if (newPassword.length < 6) {
            $('#password_error').text('Password must be at least 6 characters long.').show();
            return;
        }
        
        if (newPassword !== confirmPassword) {
            $('#password_error').text('Passwords do not match.').show();
            return;
        }
        
        $('#confirmResetPassword').prop('disabled', true).text('Updating...');
        
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
                    $('#password_success').text(response.message).show();
                    setTimeout(function() {
                        $('#resetPasswordModal').modal('hide');
                        location.reload();
                    }, 1500);
                } else {
                    $('#password_error').text(response.message).show();
                    $('#confirmResetPassword').prop('disabled', false).text('Update Password');
                }
            },
            error: function() {
                $('#password_error').text('An error occurred. Please try again.').show();
                $('#confirmResetPassword').prop('disabled', false).text('Update Password');
            }
        });
    });
});
</script>

<?php include_once('../inc/footer.php'); ?>
