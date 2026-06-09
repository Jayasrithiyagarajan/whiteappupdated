<?php 
include_once('../inc/function.php');
include '../file/config.php';

// Check login
$logged_in_user = $_SESSION['username'] ?? null;
$user_role = $_SESSION['role'] ?? null;

if (!$logged_in_user) {
    header("Location: ../index.php");
    exit;
}

// ✅ Fetch all customer survey records
$sql = "
    SELECT 
        id, project_id, client_name, contact_person, email, telephone, 
        survey_date, status, evaluated_by, comments, qualification_card, 
        response_time, ppe, aramco_standards, overall_satisfaction
    FROM customer_survey_report
    ORDER BY survey_date DESC
";
$result = $conn->query($sql);

$total_surveys = $result->num_rows;
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Customer Survey List</title>

<!-- DataTables & FontAwesome Styles -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="../assets/css/style.css">

<style>
/* ===== PREMIUM DASHBOARD UI ===== */
body {
    background: #eef1f6;
    font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial;
}
.main-content {
    background: linear-gradient(180deg, #f6f8fb 0%, #eef1f6 100%);
    min-height: 100vh;
    padding-bottom: 50px;
}
.kpi-card {
    background: #ffffff;
    border-radius: 14px;
    padding: 24px;
    box-shadow: 0 10px 25px rgba(0,0,0,.05);
    transition: .25s ease;
    border: 1px solid #f0f2f7;
}
.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 18px 35px rgba(0,0,0,.08);
}
.kpi-card h6 {
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #6b7280;
    margin-bottom: 8px;
}
.kpi-card h2 {
    font-size: 30px;
    font-weight: 700;
}
.text-blue { color: #2563eb; }

.filter-section {
    background: #ffffff;
    border-radius: 14px;
    padding: 22px;
    box-shadow: 0 10px 25px rgba(0,0,0,.05);
    border: 1px solid #f0f2f7;
    margin-bottom: 24px;
}
.btn-primary-custom {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    font-weight: 600;
    color: white !important;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}
.btn-primary-custom:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
}

.card-box {
    background: #ffffff;
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,.05);
    border: 1px solid #f0f2f7;
    overflow: hidden;
}

/* DATATABLE PREMIUM */
table.dataTable {
    border-collapse: separate !important;
    border-spacing: 0;
    width: 100% !important;
}
.dataTables_wrapper .dataTables_filter input {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 6px 10px;
    outline: none;
}
.dataTables_wrapper .dataTables_filter input:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.08);
}
.dataTables_wrapper .dt-buttons .btn {
    border-radius: 8px !important;
    margin-right: 6px;
    border: 1px solid #e5e7eb;
    background: white;
    color: #374151;
    font-weight: 500;
    padding: 6px 12px;
}
.dataTables_wrapper .dt-buttons .btn:hover {
    background: #f8fafc;
    color: #2563eb;
    border-color: #2563eb;
}

table.dataTable thead th {
    background: #f9fafb;
    border-bottom: 1px solid #e5e7eb !important;
    font-size: 13px;
    font-weight: 600;
    color: #374151;
    padding: 14px 12px;
}
table.dataTable tbody tr {
    transition: background 0.2s;
}
table.dataTable tbody tr:hover {
    background: #f8fafc;
}
table.dataTable tbody td {
    padding: 14px 12px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
}

.action-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 8px;
    color: white;
    transition: all 0.2s;
    margin: 0 2px;
    border: none;
    cursor: pointer;
}
.btn-info-custom { background: #0ea5e9; }
.btn-info-custom:hover { background: #0284c7; color: white; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(14, 165, 233, 0.3); }

.btn-danger-custom { background: #ef4444; }
.btn-danger-custom:hover { background: #dc2626; color: white; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(239, 68, 68, 0.3); }

.badge-custom {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    display: inline-block;
}
.badge-new { background: #e0f2fe; color: #0369a1; }
.badge-existing { background: #dcfce7; color: #15803d; }

.filter-select {
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    outline: none;
    font-weight: 500;
    color: #475569;
}
.filter-select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,.08);
}
</style>
</head>

<body>

<?php 
if (file_exists('../inc/nav.php')) {
    include_once('../inc/nav.php'); 
}
?>

<div class="main-content d-flex flex-column">
<div class="container-fluid mt-4">

    <!-- KPI STATS -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="kpi-card">
                <h6>Total Surveys</h6>
                <h2 class="text-blue"><?= $total_surveys; ?></h2>
            </div>
        </div>
    </div>

    <!-- HEADER / FILTER -->
    <div class="filter-section">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <h5 class="m-0" style="font-weight: 600; color: #1e293b;">Customer Survey List</h5>
            
            <div class="d-flex align-items-center mt-3 mt-sm-0 gap-3" style="gap: 15px;">
                <!-- Status Filter Dropdown -->
                <select id="status-filter" class="filter-select">
                    <option value="">All Statuses</option>
                    <option value="new">New Client</option>
                    <option value="existing">Existing Client</option>
                </select>
            </div>
        </div>
    </div>

    <!-- DATA TABLE -->
    <div class="card-box">
        <div class="table-responsive">
            <table id="survey-table" class="display nowrap text-nowrap w-100">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Project ID</th>
                        <th>Client Name</th>
                        <th>Contact Person</th>
                        <th>Email</th>
                        <th>Telephone</th>
                        <th>Survey Date</th>
                        <th>Status</th>
                        <th>Evaluated By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        $count = 1;
                        while ($row = $result->fetch_assoc()) {
                            ?>
                            <tr>
                                <td><?= $count++; ?></td>
                                <td class="font-weight-bold text-blue">#<?= htmlspecialchars($row['project_id']); ?></td>
                                <td style="font-weight: 500; color: #1e293b;"><?= htmlspecialchars($row['client_name']); ?></td>
                                <td style="color: #475569;"><?= htmlspecialchars($row['contact_person']); ?></td>
                                <td style="color: #475569;"><?= htmlspecialchars($row['email']); ?></td>
                                <td style="color: #475569;"><?= htmlspecialchars($row['telephone']); ?></td>
                                <td style="color: #475569;"><?= date("d M Y", strtotime($row['survey_date'])); ?></td>
                                <td>
                                    <span class="badge-custom 
                                        <?= (strtolower($row['status']) == 'new') ? 'badge-new' : 'badge-existing'; ?>">
                                        <?= ucfirst($row['status']); ?>
                                    </span>
                                </td>
                                <td><?= htmlspecialchars($row['evaluated_by']); ?></td>
                                <td>
                                    <a href="../document/download_customer_survey.php?project_id=<?= $row['project_id']; ?>" 
                                       class="action-btn btn-info-custom" target="_blank" title="Download PDF">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    <?php if ($user_role === 'admin') { ?>
                                        <button type="button" 
                                                class="action-btn btn-danger-custom"
                                                onclick="deleteSurvey('<?= $row['project_id']; ?>')" title="Delete Survey">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        // DataTable will handle empty state, but we can leave this here just in case.
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</div>

<?php include_once('../inc/footer.php'); ?>

<!-- Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- DataTable + Export -->
<script>
$(document).ready(function() {
    var table = $('#survey-table').DataTable({
        dom: 'Bfrtip',
        pageLength: 10,
        responsive: true,
        scrollX: true,
        autoWidth: false,
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="fas fa-file-excel mr-1"></i> Export Excel',
                className: 'btn',
                title: 'Customer Survey List',
                exportOptions: { columns: ':visible' }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fas fa-file-csv mr-1"></i> Export CSV',
                className: 'btn',
                title: 'Customer Survey List',
                exportOptions: { columns: ':visible' }
            }
        ],
        order: [[6, "desc"]], // Order by Survey Date desc
        language: {
            search: "",
            searchPlaceholder: "Search surveys..."
        }
    });

    // Custom filtering for Status
    $('#status-filter').on('change', function() {
        var selected = $(this).val();
        table.column(7).search(selected).draw();
    });
});
</script>

<!-- Delete Survey -->
<script>
function deleteSurvey(projectId) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`delete_survey.php?project_id=${projectId}`)
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Deleted!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(err => {
                Swal.fire('Error', 'An unexpected error occurred.', 'error');
            });
        }
    });
}
</script>

</body>
</html>
