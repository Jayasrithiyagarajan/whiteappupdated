<?php
include('../file/config.php');
include_once('../inc/function.php');
?>

<style>
.user-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,.06);
}
.table thead th {
    background: #f8f9fa;
}
.pagination {
    flex-wrap: wrap;
    gap: 6px;
}
.page-item .page-link {
    border-radius: 8px !important;
    border: none;
    color: #4b5563;
    background: #f3f4f6;
    font-weight: 600;
    padding: 8px 14px;
    transition: all 0.2s ease;
    margin: 0 2px;
}
.page-item.active .page-link {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    box-shadow: 0 4px 10px rgba(102, 126, 234, 0.3);
}
.page-item.disabled .page-link {
    background: #f8fafc;
    color: #94a3b8;
    cursor: not-allowed;
}
.page-item:not(.active):not(.disabled) .page-link:hover {
    background: #e2e8f0;
    color: #1e293b;
    transform: translateY(-1px);
}
#pageInfo {
    font-weight: 600;
    color: #64748b;
    font-size: 0.95rem;
}
/* Role count cards */
.role-count-cards {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 20px;
}
.role-count-card {
    flex: 1 1 120px;
    min-width: 110px;
    border-radius: 10px;
    padding: 12px 16px;
    color: #fff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 8px rgba(0,0,0,.12);
    transition: transform .15s;
}
.role-count-card:hover { transform: translateY(-2px); }
.role-count-card .rcc-number {
    font-size: 1.9rem;
    font-weight: 700;
    line-height: 1;
}
.role-count-card .rcc-label {
    font-size: .75rem;
    margin-top: 4px;
    text-transform: uppercase;
    letter-spacing: .04em;
    opacity: .92;
}
.rcc-total  { background: linear-gradient(135deg,#667eea,#764ba2); }
.rcc-admin  { background: linear-gradient(135deg,#f093fb,#f5576c); }
.rcc-insp   { background: linear-gradient(135deg,#4facfe,#00f2fe); }
.rcc-rev    { background: linear-gradient(135deg,#43e97b,#38f9d7); }
.rcc-qc     { background: linear-gradient(135deg,#fa709a,#fee140); }
.rcc-dc     { background: linear-gradient(135deg,#a18cd1,#fbc2eb); }
</style>

<div class="main-content d-flex flex-column flex-md-row">
<div class="container-fluid mt-3">

<div class="user-card p-4">

<!-- Header -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
    <h4>👤 User Management</h4>
    <div class="d-flex gap-2">
        <a id="exportBtn" href="export-users.php" class="btn btn-success btn-sm">⬇ Export</a>
        <a href="create-user.php" class="btn btn-primary btn-sm">＋ Create</a>
    </div>
</div>

<!-- Role Count Cards -->
<div class="role-count-cards" id="roleCountCards">
    <div class="role-count-card rcc-total">
        <span class="rcc-number" id="cnt-total">–</span>
        <span class="rcc-label">Total Users</span>
    </div>
    <div class="role-count-card rcc-admin">
        <span class="rcc-number" id="cnt-admin">–</span>
        <span class="rcc-label">Admin</span>
    </div>
    <div class="role-count-card rcc-insp">
        <span class="rcc-number" id="cnt-inspector">–</span>
        <span class="rcc-label">Inspector</span>
    </div>
    <div class="role-count-card rcc-rev">
        <span class="rcc-number" id="cnt-reviewer">–</span>
        <span class="rcc-label">Reviewer</span>
    </div>
    <div class="role-count-card rcc-qc">
        <span class="rcc-number" id="cnt-qc">–</span>
        <span class="rcc-label">Quality Ctrl</span>
    </div>
    <div class="role-count-card rcc-dc">
        <span class="rcc-number" id="cnt-dc">–</span>
        <span class="rcc-label">Doc Ctrl</span>
    </div>
</div>

<!-- Filters -->
<div class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" id="search" class="form-control"
               placeholder="Search name, email, phone">
    </div>

    <div class="col-md-3">
        <select id="role" class="form-control">
            <option value="">All Roles</option>
            <option value="admin">Admin</option>
            <option value="inspector">Inspector</option>
            <option value="reviewer">Reviewer</option>
            <option value="quality controller">Quality Controller</option>
            <option value="document controller">Document Controller</option>
        </select>
    </div>

    <div class="col-md-2">
        <select id="limit" class="form-control">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
            <option value="50">50 / page</option>
        </select>
    </div>
</div>

<!-- Table -->
<div class="table-responsive">
<table class="table table-hover align-middle">
<thead>
<tr>
    <th>#</th>
    <th>User ID</th>
    <th>Username</th>
    <th>Email</th>
    <th>Mobile</th>
    <th>Role</th>
    <th>Status</th>
    <th>Actions</th>
</tr>
</thead>

<tbody id="userData">
<tr>
    <td colspan="8" class="text-center">Loading...</td>
</tr>
</tbody>
</table>
</div>

<!-- Pagination -->
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mt-3">
    <div id="pageInfo"></div>
    <nav>
        <ul class="pagination pagination-sm mb-0" id="pagination"></ul>
    </nav>
</div>

</div>
</div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title">🔐 Change Password</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <div class="modal-body">
        <input type="hidden" id="userId">

        <div class="form-group">
          <label>Username</label>
          <input type="text" id="username" class="form-control" readonly>
        </div>

        <div class="form-group">
          <label>New Password</label>
          <input type="password" id="newPassword" class="form-control">
        </div>

        <div class="form-group">
          <label>Confirm Password</label>
          <input type="password" id="confirmPassword" class="form-control">
        </div>

        <div id="pwdMsg" class="text-danger small"></div>
      </div>

      <div class="modal-footer">
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="savePassword">Update</button>
      </div>

    </div>
  </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
let page = 1;
let typingTimer;

/* Load users */
function loadUsers() {
    $.post('ajax-users.php', {
        page: page,
        search: $('#search').val(),
        role: $('#role').val(),
        limit: $('#limit').val()
    }, function(res) {
        $('#userData').html(res.rows);
        $('#pagination').html(res.pagination);
        $('#pageInfo').html(res.info);
        /* Update role count cards */
        if (res.roleCounts) {
            $('#cnt-total').text(res.roleCounts.total);
            $('#cnt-admin').text(res.roleCounts.admin);
            $('#cnt-inspector').text(res.roleCounts.inspector);
            $('#cnt-reviewer').text(res.roleCounts.reviewer);
            $('#cnt-qc').text(res.roleCounts['quality controller']);
            $('#cnt-dc').text(res.roleCounts['document controller']);
        }
    }, 'json');

    $('#exportBtn').attr(
        'href',
        'export-users.php?search=' + encodeURIComponent($('#search').val()) +
        '&role=' + encodeURIComponent($('#role').val())
    );
}

/* Debounced search */
$('#search').on('keyup', function () {
    clearTimeout(typingTimer);
    typingTimer = setTimeout(() => {
        page = 1;
        loadUsers();
    }, 400);
});

$('#role, #limit').on('change', function () {
    page = 1;
    loadUsers();
});

/* Pagination click */
$(document).on('click', '.page-link', function(e) {
    e.preventDefault();
    page = $(this).data('page');
    loadUsers();
});

/* Open change password modal */
$(document).on('click', '.changePwdBtn', function () {
    $('#userId').val($(this).data('id'));
    $('#username').val($(this).data('name'));
    $('#newPassword').val('');
    $('#confirmPassword').val('');
    $('#pwdMsg').text('');
    $('#changePasswordModal').modal('show');
});

/* Save password */
$('#savePassword').click(function () {

    let userId = $('#userId').val();
    let newPwd = $('#newPassword').val();
    let confirmPwd = $('#confirmPassword').val();

    if (newPwd.length < 6) {
        $('#pwdMsg').text('Password must be at least 6 characters');
        return;
    }

    if (newPwd !== confirmPwd) {
        $('#pwdMsg').text('Passwords do not match');
        return;
    }

    $('#savePassword').prop('disabled', true);

    $.post('ajax-change-password.php', {
        user_id: userId,
        password: newPwd
    }, function (res) {
        if (res === 'success') {
            $('#changePasswordModal').modal('hide');
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Password updated successfully',
                    timer: 2000,
                    showConfirmButton: false,
                    backdrop: `rgba(0,0,123,0.4)`
                });
            } else {
                alert('Password updated successfully');
            }
        } else {
            $('#pwdMsg').text(res);
        }
        $('#savePassword').prop('disabled', false);
    });
});

/* Initial load */
loadUsers();
</script>
