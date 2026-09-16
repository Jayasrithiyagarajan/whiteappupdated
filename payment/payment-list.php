<?php
include_once('../inc/function.php');
include '../file/config.php';

$logged_in_user = $_SESSION['username'] ?? null;
$user_role      = $_SESSION['role'] ?? null;

if (!$logged_in_user || !in_array($user_role, ['admin', 'inspector'])) {
    header("Location: ../index.php");
    exit;
}

// Initial KPI Stats (Default to 2026 for fast load)
$defaultYear = '2026';
$yearWhere   = " creation_date >= '$defaultYear-01-01 00:00:00' AND creation_date <= '$defaultYear-12-31 23:59:59'";

if ($user_role === 'inspector') {
    $whereClause = " WHERE inspector_name = '" . $conn->real_escape_string($logged_in_user) . "' AND LOWER(payment_mode) = 'cash' AND $yearWhere";
} else {
    $whereClause = " WHERE $yearWhere";
}
$totalProjects = $conn->query("SELECT COUNT(*) as cnt FROM project_info $whereClause")->fetch_assoc()['cnt'] ?? 0;

if ($user_role === 'inspector') {
    $creditProjects = 0;
    $cashProjects   = $totalProjects;
} else {
    $creditQuery    = "SELECT COUNT(*) as cnt FROM project_info WHERE LOWER(payment_mode) = 'credit' AND $yearWhere";
    $creditProjects = $conn->query($creditQuery)->fetch_assoc()['cnt'] ?? 0;

    $cashQuery      = "SELECT COUNT(*) as cnt FROM project_info WHERE LOWER(payment_mode) = 'cash' AND $yearWhere";
    $cashProjects   = $conn->query($cashQuery)->fetch_assoc()['cnt'] ?? 0;
}

// Distinct Client Names for Dropdown Filter
$clients = [];
$cRes1 = $conn->query("SELECT DISTINCT customer_name FROM customers WHERE customer_name IS NOT NULL AND customer_name != '' ORDER BY customer_name ASC");
if ($cRes1) {
    while ($row = $cRes1->fetch_assoc()) {
        $clients[] = $row['customer_name'];
    }
}
$cRes2 = $conn->query("SELECT DISTINCT customer_name FROM project_info WHERE customer_name IS NOT NULL AND customer_name != '' ORDER BY customer_name ASC");
if ($cRes2) {
    while ($row = $cRes2->fetch_assoc()) {
        if (!in_array($row['customer_name'], $clients)) {
            $clients[] = $row['customer_name'];
        }
    }
}
sort($clients);

// Distinct Inspector Names for Dropdown Filter
$inspectors = [];
$iRes1 = $conn->query("SELECT DISTINCT inspector_name FROM inspectors WHERE inspector_name IS NOT NULL AND inspector_name != '' ORDER BY inspector_name ASC");
if ($iRes1) {
    while ($row = $iRes1->fetch_assoc()) {
        $inspectors[] = $row['inspector_name'];
    }
}
$iRes2 = $conn->query("SELECT DISTINCT inspector_name FROM project_info WHERE inspector_name IS NOT NULL AND inspector_name != '' ORDER BY inspector_name ASC");
if ($iRes2) {
    while ($row = $iRes2->fetch_assoc()) {
        if (!in_array($row['inspector_name'], $inspectors)) {
            $inspectors[] = $row['inspector_name'];
        }
    }
}
sort($inspectors);

// Distinct Equipment Types for Dropdown Filter
$equipTypes = [];
$etRes = $conn->query("SELECT DISTINCT equipment_type FROM project_info WHERE equipment_type IS NOT NULL AND equipment_type != '' ORDER BY equipment_type ASC");
if ($etRes) {
    while ($row = $etRes->fetch_assoc()) {
        $equipTypes[] = $row['equipment_type'];
    }
}

// Distinct Inspection Types for Dropdown Filter
$inspectionTypes = [];
$itRes = $conn->query("SELECT DISTINCT inspection_type FROM project_info WHERE inspection_type IS NOT NULL AND inspection_type != '' ORDER BY inspection_type ASC");
if ($itRes) {
    while ($row = $itRes->fetch_assoc()) {
        $inspectionTypes[] = $row['inspection_type'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment List - <?php echo ucfirst($user_role); ?></title>
    
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/fonts/icofont/icofont.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/premium-directory.css">
    <link rel="stylesheet" href="../assets/css/premium-nav.css">

<style>
.table-shell {
    padding: 16px;
    background: #ffffff;
    border-radius: 8px;
}

/* Highlight rows that have filled/saved payment details in payment_list */
table.dataTable tbody tr.row-payment-saved,
table.dataTable tbody tr.row-payment-saved > td {
    background-color: #f0fdf4 !important;
}

table.dataTable tbody tr.row-payment-saved:hover,
table.dataTable tbody tr.row-payment-saved:hover > td {
    background-color: #dcfce7 !important;
}

.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 99999;
    padding: 12px 20px;
    border-radius: 8px;
    color: #fff;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: none;
}
.toast-success { background-color: #10b981; }
.toast-error { background-color: #ef4444; }

/* Modal Styling */
.custom-modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.5);
    z-index: 99999;
    justify-content: center;
    align-items: center;
}

.custom-modal-card {
    background: #fff;
    width: 100%;
    max-width: 520px;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

/* Year Tabs Styling */
.year-tabs-wrapper {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    flex-wrap: wrap;
    gap: 12px;
}

.year-tabs-nav {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.year-tab-btn {
    padding: 8px 18px;
    border-radius: 8px;
    border: 1px solid #cbd5e1;
    background: #ffffff;
    color: #475569;
    font-weight: 700;
    font-size: 14px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.2s ease;
}

.year-tab-btn:hover {
    background: #f1f5f9;
    color: #0f172a;
    border-color: #94a3b8;
}

.year-tab-btn.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #2563eb;
    box-shadow: 0 4px 10px rgba(37, 99, 235, 0.25);
}

.table-shell {
    overflow-x: auto !important;
    width: 100%;
}

/* Fixed Width & Word Wrap for Equipment ID, Location, Client */
table.dataTable th {
    white-space: nowrap !important;
    vertical-align: middle !important;
}

table.dataTable td {
    vertical-align: middle !important;
}

table.dataTable th.col-equip-id,
table.dataTable td.col-equip-id {
    min-width: 130px !important;
    max-width: 150px !important;
    width: 130px !important;
    white-space: normal !important;
    word-break: normal !important;
    overflow-wrap: break-word !important;
}

table.dataTable th.col-location,
table.dataTable td.col-location {
    min-width: 140px !important;
    max-width: 170px !important;
    width: 140px !important;
    white-space: normal !important;
    word-break: normal !important;
    overflow-wrap: break-word !important;
}

table.dataTable th.col-client,
table.dataTable td.col-client {
    min-width: 180px !important;
    max-width: 220px !important;
    width: 180px !important;
    white-space: normal !important;
    word-break: normal !important;
    overflow-wrap: break-word !important;
}

/* Full Width Container Layout */
.overall-jobs-directory,
.overall-jobs-directory .container-fluid {
    max-width: 100% !important;
    width: 100% !important;
    padding-left: 16px !important;
    padding-right: 16px !important;
}

.directory-hero,
.filter-section,
.card-box,
.table-shell {
    width: 100% !important;
    max-width: 100% !important;
}

table.dataTable {
    width: 100% !important;
}

/* Modal Button Custom Styling */
.btn-save-modal {
    background: #16a34a !important;
    color: #ffffff !important;
    border: 1px solid #15803d !important;
    padding: 8px 20px !important;
    border-radius: 6px !important;
    font-weight: 700 !important;
    font-size: 14px !important;
    display: inline-flex !important;
    align-items: center !important;
    gap: 6px !important;
    cursor: pointer !important;
    box-shadow: 0 2px 6px rgba(22, 163, 74, 0.3) !important;
    transition: all 0.2s ease !important;
}

.btn-save-modal:hover {
    background: #15803d !important;
    color: #ffffff !important;
    box-shadow: 0 4px 10px rgba(22, 163, 74, 0.4) !important;
    transform: translateY(-1px) !important;
}

.btn-cancel-modal {
    background: #ffffff !important;
    color: #475569 !important;
    border: 1px solid #cbd5e1 !important;
    padding: 8px 18px !important;
    border-radius: 6px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
}

.btn-cancel-modal:hover {
    background: #f1f5f9 !important;
    color: #0f172a !important;
    border-color: #94a3b8 !important;
}
</style>
</head>
<body>

<?php include_once('../inc/nav.php'); ?>

<!-- Toast Notification -->
<div id="toastNotification" class="toast-notification toast-success">
    <span id="toastMessage">Saved successfully</span>
</div>

<div class="main-content d-flex flex-column overall-jobs-directory">
<div class="container-fluid mt-4">

    <!-- Hero Section -->
    <div class="directory-hero">
        <div class="directory-title">
            <span class="title-icon"><i class="icofont-credit-card"></i></span>
            <div>
                <h2>Payment List <?php if ($user_role === 'inspector') echo '(Cash Projects)'; ?></h2>
                <p><?php echo ($user_role === 'inspector') ? 'View assigned Cash payment projects and record payment details.' : 'Overview and management of project IDs, client names, inspector details, and payment modes.'; ?></p>
            </div>
        </div>
        
        <div class="hero-actions">
            <!-- KPI Stats -->
            <div class="hero-stat">
                <strong id="stats-total"><?php echo number_format($totalProjects); ?></strong>
                <span>Total Projects</span>
            </div>
            <?php if ($user_role === 'admin'): ?>
            <div class="hero-stat is-active">
                <strong id="stats-credit"><?php echo number_format($creditProjects); ?></strong>
                <span>Credit</span>
            </div>
            <?php endif; ?>
            <div class="hero-stat">
                <strong id="stats-cash"><?php echo number_format($cashProjects); ?></strong>
                <span>Cash</span>
            </div>
        </div>
    </div>

    <!-- Year Navigation Tabs -->
    <div class="year-tabs-wrapper">
        <div class="year-tabs-nav">
            <button type="button" class="year-tab-btn active" data-year="2026">
                <i class="icofont-calendar"></i> 2026
            </button>
            <button type="button" class="year-tab-btn" data-year="2025">
                <i class="icofont-calendar"></i> 2025
            </button>
            <button type="button" class="year-tab-btn" data-year="2024">
                <i class="icofont-calendar"></i> 2024
            </button>
            <button type="button" class="year-tab-btn" data-year="all">
                <i class="icofont-globe"></i> All Years
            </button>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="filter-section">
        <div class="section-heading">
            <div>
                <h5>Payment Filters</h5>
                <p>Filter records by year, client, inspector, equipment ID, location, sticker no, equipment type, or date range</p>
            </div>
            <button class="filter-toggle" type="button" onclick="clearFilters()">
                <i class="icofont-refresh"></i> Reset Filters
            </button>
        </div>

        <div class="filter-row">
            <div class="filter-item">
                <label>Year</label>
                <select id="filter-year" class="form-control">
                    <option value="2026" selected>2026</option>
                    <option value="2025">2025</option>
                    <option value="2024">2024</option>
                    <option value="all">All Years</option>
                </select>
            </div>
            <?php if ($user_role === 'admin'): ?>
            <div class="filter-item">
                <label>Payment Mode</label>
                <select id="filter-payment-mode" class="form-control">
                    <option value="all">All Payment Modes</option>
                    <option value="Credit">Credit</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>
            <?php else: ?>
                <input type="hidden" id="filter-payment-mode" value="Cash">
            <?php endif; ?>

            <div class="filter-item">
                <label>Client Name</label>
                <select id="filter-client" class="form-control">
                    <option value="all">All Clients</option>
                    <?php
                    foreach ($clients as $cName) {
                        echo "<option value='" . htmlspecialchars($cName) . "'>" . htmlspecialchars($cName) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <?php if ($user_role === 'admin'): ?>
            <div class="filter-item">
                <label>Inspector Name</label>
                <select id="filter-inspector" class="form-control">
                    <option value="all">All Inspectors</option>
                    <?php
                    foreach ($inspectors as $iName) {
                        echo "<option value='" . htmlspecialchars($iName) . "'>" . htmlspecialchars($iName) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <?php else: ?>
                <input type="hidden" id="filter-inspector" value="">
            <?php endif; ?>

            <div class="filter-item">
                <label>Equipment Type</label>
                <select id="filter-equip-type" class="form-control">
                    <option value="all">All Equipment Types</option>
                    <?php
                    foreach ($equipTypes as $etName) {
                        echo "<option value='" . htmlspecialchars($etName) . "'>" . htmlspecialchars($etName) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="filter-item">
                <label>Inspection Type</label>
                <select id="filter-inspection-type" class="form-control">
                    <option value="all">All Inspection Types</option>
                    <?php
                    foreach ($inspectionTypes as $itName) {
                        echo "<option value='" . htmlspecialchars($itName) . "'>" . htmlspecialchars($itName) . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="filter-item">
                <label>Equipment ID</label>
                <input type="text" id="filter-equip-id" class="form-control" placeholder="Search equipment ID...">
            </div>

            <div class="filter-item">
                <label>Location</label>
                <input type="text" id="filter-location" class="form-control" placeholder="Search location...">
            </div>

            <div class="filter-item">
                <label>Sticker No</label>
                <input type="text" id="filter-sticker-no" class="form-control" placeholder="Search sticker no...">
            </div>

            <div class="filter-item">
                <label>Date From</label>
                <input type="date" id="filter-date-from" class="form-control">
            </div>
            <div class="filter-item">
                <label>Date To</label>
                <input type="date" id="filter-date-to" class="form-control">
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="card-box">
        <div class="table-panel-header">
            <div class="table-title">
                <h5>Payment Records</h5>
                <p>Project payment information extracted from project_info table</p>
            </div>
            <div class="table-tools">
                <div class="directory-search">
                    <i class="icofont-search-1"></i>
                    <input type="search" id="payment-search" placeholder="Search Project ID, Client, Equipment ID...">
                </div>
                <div id="table-buttons"></div>
            </div>
        </div>

        <div class="table-shell">
            <table id="paymentTable" class="display nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Project ID</th>
                        <th>Actions</th>
                        <th>Payment Mode</th>
                        <th>Creation Date</th>
                        <th>Equipment ID</th>
                        <th>Location</th>
                        <th>Client Name</th>
                        <th>Inspector Name</th>
                        <th>Sticker No</th>
                        <th>Equipment Type</th>
                        <th>Inspection Type</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

</div>
</div>

<!-- Popup Form Modal: Payment List Details -->
<div id="paymentDetailModal" class="custom-modal-overlay">
    <div class="custom-modal-card">
        <h4 style="margin-top:0; color:#0f172a; font-weight:700;"><i class="icofont-wallet text-primary"></i> Payment Details Form</h4>
        <p style="color:#64748b; font-size:13px; margin-bottom:16px;">Fill in payment details for project <strong id="modalDetailProjectNo" class="text-primary"></strong></p>
        
        <form id="paymentDetailForm">
            <input type="hidden" id="modalDetailProjectInput" name="project_no">
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:4px;">No of Equipment</label>
                    <input type="number" min="0" id="formNoOfEquipment" name="no_of_equipment" class="form-control" placeholder="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:4px;">Total Amount</label>
                    <input type="number" step="0.01" min="0" id="formTotalAmount" name="total_amount" class="form-control" placeholder="0.00" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:4px;">Travel Charge</label>
                    <input type="number" step="0.01" min="0" id="formTravelCharge" name="travel_charge" class="form-control" placeholder="0.00">
                </div>
                <div class="col-md-6 mb-3">
                    <label style="display:block; font-size:13px; font-weight:600; margin-bottom:4px;">Voucher No</label>
                    <input type="text" id="formBroucherNo" name="broucher_no" class="form-control" placeholder="Enter Voucher No">
                </div>
            </div>

            <div class="mb-3">
                <label style="display:block; font-size:13px; font-weight:600; margin-bottom:4px;">Remarks</label>
                <select id="formRemarks" name="remarks" class="form-control" style="font-weight:600;">
                    <option value="Self">Self</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="mb-3" id="formOtherInspectorGroup" style="display:none;">
                <label style="display:block; font-size:13px; font-weight:600; margin-bottom:4px;">Select Inspector Name <span class="text-danger">*</span></label>
                <select id="formOtherInspectorSelect" name="other_inspector_name" class="form-control" style="font-weight:600;">
                    <option value="">Select Inspector</option>
                </select>
            </div>

            <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                <button type="submit" class="btn-save-modal"><i class="icofont-save"></i> Save Payment Details</button>
                <button type="button" onclick="closePaymentDetailModal()" class="btn-cancel-modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Payment Mode Modal (For Admin) -->
<?php if ($user_role === 'admin'): ?>
<div id="editPaymentModal" class="custom-modal-overlay">
    <div class="custom-modal-card">
        <h4 style="margin-top:0; color:#0f172a; font-weight:700;"><i class="icofont-edit text-primary"></i> Edit Payment Mode</h4>
        <p style="color:#64748b; font-size:14px;">Update payment mode for project <strong id="modalProjectNo" class="text-primary"></strong></p>
        
        <input type="hidden" id="modalProjectInput">
        
        <div style="margin-bottom:1.5rem;">
            <label style="display:block; font-size:13px; font-weight:600; margin-bottom:6px;">Select Payment Mode</label>
            <select id="modalPaymentModeSelect" class="form-control" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; font-weight:600;">
                <option value="Credit">Credit</option>
                <option value="Cash">Cash</option>
            </select>
        </div>
        
        <div style="display:flex; justify-content:flex-end; gap:10px;">
            <button type="button" onclick="submitPaymentModeUpdate()" class="btn-save-modal">Save Changes</button>
            <button type="button" onclick="closeEditModal()" class="btn-cancel-modal">Cancel</button>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include_once('../inc/footer.php'); ?>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
var paymentTable;

function showToast(message, isSuccess = true) {
    var toast = $('#toastNotification');
    $('#toastMessage').text(message);
    toast.removeClass('toast-success toast-error').addClass(isSuccess ? 'toast-success' : 'toast-error');
    toast.fadeIn(300);
    setTimeout(function() {
        toast.fadeOut(400);
    }, 3000);
}

function loadInspectorsList() {
    $.ajax({
        url: 'fetch-inspectors.php',
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success' && res.inspectors) {
                var select = $('#formOtherInspectorSelect');
                select.html('<option value="">Select Inspector</option>');
                $.each(res.inspectors, function(i, name) {
                    select.append('<option value="' + name + '">' + name + '</option>');
                });
            }
        }
    });
}

function openPaymentDetailModal(data) {
    $('#modalDetailProjectInput').val(data.project);
    $('#modalDetailProjectNo').text(data.project);
    $('#formNoOfEquipment').val(data.equip || '');
    $('#formTotalAmount').val(data.total || '');
    $('#formTravelCharge').val(data.travel || '');
    $('#formBroucherNo').val(data.broucher || '');
    
    var remarksVal = data.remarks || 'Self';
    $('#formRemarks').val(remarksVal);

    if (remarksVal === 'Other') {
        $('#formOtherInspectorGroup').show();
        $('#formOtherInspectorSelect').val(data.otherinsp || '');
    } else {
        $('#formOtherInspectorGroup').hide();
        $('#formOtherInspectorSelect').val('');
    }

    $('#paymentDetailModal').css('display', 'flex');
}

function closePaymentDetailModal() {
    $('#paymentDetailModal').hide();
}

function refreshPaymentStats() {
    if (paymentTable) {
        paymentTable.ajax.reload(null, false);
    }
}

function updatePaymentMode(projectNo, newMode) {
    $.ajax({
        url: 'update-payment-mode.php',
        type: 'POST',
        data: { project_no: projectNo, payment_mode: newMode },
        dataType: 'json',
        success: function(res) {
            if (res.status === 'success') {
                showToast(res.message, true);
                paymentTable.ajax.reload(null, false);
                refreshPaymentStats();
            } else {
                showToast('Error: ' + res.message, false);
            }
        },
        error: function() {
            showToast('Failed to update payment mode.', false);
        }
    });
}

function openEditModal(projectNo, currentMode) {
    $('#modalProjectInput').val(projectNo);
    $('#modalProjectNo').text(projectNo);
    $('#modalPaymentModeSelect').val(currentMode);
    $('#editPaymentModal').css('display', 'flex');
}

function closeEditModal() {
    $('#editPaymentModal').hide();
}

function submitPaymentModeUpdate() {
    var projectNo = $('#modalProjectInput').val();
    var newMode = $('#modalPaymentModeSelect').val();
    closeEditModal();
    updatePaymentMode(projectNo, newMode);
}

$(document).ready(function() {
    loadInspectorsList();

    paymentTable = $('#paymentTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        pageLength: 25,
        lengthMenu: [10, 25, 50, 100],
        order: [[4, 'desc']],
        columnDefs: [
            { targets: [0, 1, 2, 3, 4, 8], className: 'all' },
            { targets: 5, className: 'all col-equip-id' },
            { targets: 6, className: 'all col-location' },
            { targets: 7, className: 'all col-client' },
            { targets: [9, 10, 11], className: 'none' }
        ],

        ajax: {
            url: 'fetch-payments.php',
            type: 'POST',
            data: function(d) {
                d.filter_year            = $('#filter-year').val();
                d.filter_payment_mode    = $('#filter-payment-mode').val();
                d.filter_client          = $('#filter-client').val();
                d.filter_inspector       = $('#filter-inspector').val();
                d.filter_equip_id        = $('#filter-equip-id').val();
                d.filter_location        = $('#filter-location').val();
                d.filter_sticker_no      = $('#filter-sticker-no').val();
                d.filter_equip_type      = $('#filter-equip-type').val();
                d.filter_inspection_type = $('#filter-inspection-type').val();
                d.filter_date_from       = $('#filter-date-from').val();
                d.filter_date_to         = $('#filter-date-to').val();
            }
        },

        dom: 'Brtip',
        buttons: [
            {
                text: '<i class="icofont-download"></i> Export CSV',
                className: 'btn btn-secondary',
                action: function() {
                    var query = $.param({
                        filter_year:            $('#filter-year').val(),
                        filter_payment_mode:    $('#filter-payment-mode').val(),
                        filter_client:          $('#filter-client').val(),
                        filter_inspector:       $('#filter-inspector').val(),
                        filter_equip_id:        $('#filter-equip-id').val(),
                        filter_location:        $('#filter-location').val(),
                        filter_sticker_no:      $('#filter-sticker-no').val(),
                        filter_equip_type:      $('#filter-equip-type').val(),
                        filter_inspection_type: $('#filter-inspection-type').val(),
                        filter_date_from:       $('#filter-date-from').val(),
                        filter_date_to:         $('#filter-date-to').val(),
                        search:                 $('#payment-search').val()
                    });
                    window.location.href = 'export-payments.php?' + query;
                }
            },
            'copy', 'print'
        ],

        columns: [
            { data: 'sn', orderable: false, width: '40px' },
            { data: 'project_no' },
            { data: 'actions', orderable: false },
            { data: 'payment_mode', orderable: false },
            { data: 'creation_date' },
            { data: 'equipment_id' },
            { data: 'equipment_location' },
            { data: 'customer_name' },
            { data: 'inspector_name' },
            { data: 'sticker_no' },
            { data: 'equipment_type' },
            { data: 'inspection_type' }
        ],

        createdRow: function(row, data, dataIndex) {
            if (data.is_saved) {
                $(row).addClass('row-payment-saved');
            }
        },

        initComplete: function() {
            this.api().buttons().container().appendTo('#table-buttons');
        }
    });

    paymentTable.on('xhr', function(e, settings, json) {
        if (json && json.stats) {
            $('#stats-total').text(json.stats.total);
            if ($('#stats-credit').length) $('#stats-credit').text(json.stats.credit);
            $('#stats-cash').text(json.stats.cash);
        }
    });

    // Year Tab Click Event
    $(document).on('click', '.year-tab-btn', function() {
        $('.year-tab-btn').removeClass('active').css({ 'background': '#ffffff', 'color': '#475569', 'border-color': '#cbd5e1' });
        $(this).addClass('active').css({ 'background': '#2563eb', 'color': '#ffffff', 'border-color': '#2563eb' });
        var yr = $(this).data('year');
        $('#filter-year').val(yr);
        paymentTable.ajax.reload();
        refreshPaymentStats();
    });

    // Year Select Change Event
    $('#filter-year').on('change', function() {
        var yr = $(this).val();
        $('.year-tab-btn').removeClass('active').css({ 'background': '#ffffff', 'color': '#475569', 'border-color': '#cbd5e1' });
        $('.year-tab-btn[data-year="' + yr + '"]').addClass('active').css({ 'background': '#2563eb', 'color': '#ffffff', 'border-color': '#2563eb' });
        paymentTable.ajax.reload();
        refreshPaymentStats();
    });

    // Toggle Inspector Select depending on Remarks value
    $('#formRemarks').on('change', function() {
        if ($(this).val() === 'Other') {
            $('#formOtherInspectorGroup').slideDown(200);
        } else {
            $('#formOtherInspectorGroup').slideUp(200);
            $('#formOtherInspectorSelect').val('');
        }
    });

    // Open Payment Detail Form Modal
    $('#paymentTable').on('click', '.btn-open-payment-form', function() {
        var btn = $(this);
        openPaymentDetailModal({
            project: btn.data('project'),
            equip: btn.data('equip'),
            total: btn.data('total'),
            travel: btn.data('travel'),
            broucher: btn.data('broucher'),
            remarks: btn.data('remarks'),
            otherinsp: btn.data('otherinsp')
        });
    });

    // Submit Payment Detail Form
    $('#paymentDetailForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();

        $.ajax({
            url: 'save-payment-detail.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(res) {
                if (res.status === 'success') {
                    showToast(res.message, true);
                    closePaymentDetailModal();
                    paymentTable.ajax.reload(null, false);
                } else {
                    showToast(res.message, false);
                }
            },
            error: function() {
                showToast('Failed to save payment details.', false);
            }
        });
    });

    // Quick Inline Select Event Handler (Admin)
    $('#paymentTable').on('change', '.quick-payment-select', function() {
        var projectNo = $(this).data('project');
        var newMode = $(this).val();
        updatePaymentMode(projectNo, newMode);
    });

    // Edit Modal Trigger Event Handler (Admin)
    $('#paymentTable').on('click', '.btn-edit-mode', function() {
        var projectNo = $(this).data('project');
        var currentMode = $(this).data('mode');
        openEditModal(projectNo, currentMode);
    });

    $('#payment-search').on('input', function() {
        paymentTable.search(this.value).draw();
    });

    $('#filter-payment-mode, #filter-client, #filter-inspector, #filter-equip-type, #filter-inspection-type, #filter-date-from, #filter-date-to').on('change', function() {
        paymentTable.ajax.reload();
    });

    $('#filter-equip-id, #filter-location, #filter-sticker-no').on('keyup change', function() {
        paymentTable.ajax.reload();
    });

    $(window).on('click', function(e) {
        if ($(e.target).is('#paymentDetailModal')) closePaymentDetailModal();
        if ($(e.target).is('#editPaymentModal')) closeEditModal();
    });
});

function clearFilters() {
    $('#filter-year').val('2026');
    $('.year-tab-btn').removeClass('active').css({ 'background': '#ffffff', 'color': '#475569', 'border-color': '#cbd5e1' });
    $('.year-tab-btn[data-year="2026"]').addClass('active').css({ 'background': '#2563eb', 'color': '#ffffff', 'border-color': '#2563eb' });
    $('#filter-payment-mode').val('<?php echo ($user_role === "inspector") ? "Cash" : "all"; ?>');
    $('#filter-client, #filter-inspector, #filter-equip-type, #filter-inspection-type').val('all');
    $('#filter-equip-id, #filter-location, #filter-sticker-no, #filter-date-from, #filter-date-to, #payment-search').val('');
    paymentTable.search('').draw();
    paymentTable.ajax.reload();
    refreshPaymentStats();
}
</script>

</body>
</html>
