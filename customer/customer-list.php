<?php
include_once('../inc/function.php');
include '../file/config.php';

$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}

$count = $conn->query("SELECT COUNT(*) total FROM customers")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Customer List</title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/fonts/icofont/icofont.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/premium-directory.css">
    <link rel="stylesheet" href="../assets/css/premium-nav.css">

<style>
.status-badge {
    display: inline-flex;
    align-items: center;
    min-height: 24px;
    padding: 4px 12px !important;
    border-radius: 999px;
    font-size: 12px !important;
    font-weight: 700;
    line-height: 1.2;
}

/* Action Icons with Proper Spacing */
.action-icons a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    background: #f0f4f8;
    color: #475569;
    margin-right: 6px !important;   /* Increased spacing */
    transition: all 0.2s;
}

.action-icons a:hover {
    background: #e0f2fe;
    color: #0284c7;
    transform: translateY(-1px);
}

.action-icons a:last-child {
    margin-right: 0;
}
</style>
</head>
<body>

<?php include_once('../inc/nav.php'); ?>

<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <!-- Hero Section -->
    <div class="directory-hero">
        <div class="directory-title">
            <span class="title-icon"><i class="icofont-users"></i></span>
            <div>
                <h2>Customer List</h2>
                <p>Manage customer profiles, contact details, references, and portal access from one polished workspace.</p>
            </div>
        </div>
        
        <div class="hero-actions">
            <!-- KPI -->
            <div class="hero-stat">
                <strong id="stats-total"><?php echo $count; ?></strong>
                <span>Total Customers</span>
            </div>

            <!-- New Customer Button -->
            <a href="create-customer.php" class="btn btn-primary" style="margin-left: 16px; align-self: center;">
                <i class="icofont-plus"></i> New Customer
            </a>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>Customer Filters</h5>
                <p>Refine by name, city, representative, or date added</p>
            </div>
            <button class="filter-toggle" type="button" onclick="clearFilters()">
                <i class="icofont-refresh"></i> Reset
            </button>
        </div>

        <div class="filter-row">
            <div class="filter-item">
                <label>Customer Name</label>
                <input type="text" id="filter-name" placeholder="Search name...">
            </div>
            <div class="filter-item">
                <label>City</label>
                <input type="text" id="filter-city" placeholder="City">
            </div>
            <div class="filter-item">
                <label>Representative</label>
                <input type="text" id="filter-rep" placeholder="Rep Name">
            </div>
            <div class="filter-item">
                <label>Date Added</label>
                <input type="date" id="filter-date">
            </div>
            <div class="filter-item">
                <button class="btn-clear" onclick="clearFilters()">
                    <i class="icofont-close"></i> Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-box">
        <div class="table-panel-header">
            <div class="table-title">
                <h5>Customer Records</h5>
                <p>View and manage all registered customers</p>
            </div>
            <div class="table-tools">
                <div class="directory-search">
                    <i class="icofont-search-1"></i>
                    <input type="search" id="customer-search" placeholder="Search by name, email, phone or city...">
                </div>
                <div id="table-buttons"></div>
            </div>
        </div>
        <div class="table-shell">
            <table id="customerTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Date</th>
                        <th>Rep</th>
                        <th>Reference</th>
                        <th>Notes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
</div>

<!-- Password Reset Modal -->
<div id="passwordResetModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; justify-content:center; align-items:center;">
    <div style="background:#fff; padding:2rem; border-radius:12px; width:100%; max-width:400px; box-shadow:0 10px 25px rgba(0,0,0,0.2);">
        <h4 style="margin-top:0; color:#0f172a;">Reset Password</h4>
        <p style="color:#64748b; font-size:0.95rem;">Enter a new password for <strong id="modalCustomerName"></strong>.</p>
        
        <input type="hidden" id="modalCusId">
        
        <div style="margin-bottom:1.2rem;">
            <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.5rem;">New Password</label>
            <input type="password" id="newPassword" class="form-control" style="width:100%; padding:12px; border:1px solid #e5e7eb; border-radius:8px;">
        </div>
        
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button onclick="closeModal()" style="padding:10px 18px; border:1px solid #e5e7eb; background:#fff; border-radius:8px; cursor:pointer;">Cancel</button>
            <button onclick="submitPasswordReset()" style="padding:10px 18px; border:none; background:#2563eb; color:#fff; border-radius:8px; cursor:pointer;">Save Password</button>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
// Modal Functions
function openResetModal(cusId, customerName) {
    $('#modalCusId').val(cusId);
    $('#modalCustomerName').text(customerName);
    $('#newPassword').val('');
    $('#passwordResetModal').css('display', 'flex');
}

function closeModal() {
    $('#passwordResetModal').hide();
}

function submitPasswordReset() {
    const cusId = $('#modalCusId').val();
    const newPass = $('#newPassword').val();

    if (!newPass) {
        alert("Please enter a password.");
        return;
    }

    $.ajax({
        url: 'reset-customer-password.php',
        type: 'POST',
        data: { cus_id: cusId, new_password: newPass },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'success') {
                alert(response.message);
                closeModal();
            } else {
                alert("Error: " + response.message);
            }
        }
    });
}

$(window).on('click', function(e) {
    if ($(e.target).is('#passwordResetModal')) closeModal();
});

var customerTable;

$(document).ready(function() {
    customerTable = $('#customerTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[1, 'desc']],

        ajax: {
            url: 'fetch-customers.php',
            type: 'POST',
            data: function(d) {
                d.filter_name = $('#filter-name').val();
                d.filter_city = $('#filter-city').val();
                d.filter_rep  = $('#filter-rep').val();
                d.filter_date = $('#filter-date').val();
            }
        },

        dom: 'Brtip',
        buttons: [
            {
                text: 'Export CSV',
                className: 'btn btn-secondary',
                action: function() {
                    window.location.href = 'export-customers.php';
                }
            },
            'copy', 'print'
        ],

        columns: [
            {data: 'checkbox', orderable: false, width: '40px'},
            {data: 'cus_id'},
            {data: 'customer_name'},
            {data: 'email'},
            {data: 'mobile'},
            {data: 'city'},
            {data: 'address', visible: false}, 
            {data: 'date_of_adding'},
            {data: 'rep_name'},
            {data: 'reference_by'},
            {data: 'notes', visible: false},
            {data: 'actions', orderable: false}
        ],

        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }
    });

    $('#customer-search').on('input', function() {
        customerTable.search(this.value).draw();
    });

    $('#filter-name, #filter-city, #filter-rep, #filter-date').on('change keyup', function() {
        customerTable.ajax.reload();
    });
});

function clearFilters() {
    $('#filter-name, #filter-city, #filter-rep, #filter-date, #customer-search').val('');
    customerTable.search('').draw();
    customerTable.ajax.reload();
}
</script>

</body>
</html>