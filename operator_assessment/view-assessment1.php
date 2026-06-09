<?php
// session_start();
include_once('../inc/function.php');

// Get assessment ID from URL
$assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($assessment_id === 0) {
    $_SESSION['error_msg'] = "Invalid assessment ID";
    header("Location: assessment-list.php");
    exit();
}

// Fetch assessment details with all related data
$sql = "SELECT 
            oa.*,
            c.customer_name as client_name,
            c.email as client_email,
            c.mobile as client_mobile,
            nu.username as inspector_name,
            nu.email as inspector_email
        FROM operator_assessments oa
        LEFT JOIN customers c ON oa.client_id = c.cus_id
        LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
        WHERE oa.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment = $result->fetch_assoc();

if (!$assessment) {
    $_SESSION['error_msg'] = "Assessment not found";
    header("Location: assessment-list.php");
    exit();
}

// Fetch equipment details
$equipment_sql = "SELECT * FROM operator_equipment WHERE assessment_id = ? ORDER BY equipment_number ASC";
$equipment_stmt = $conn->prepare($equipment_sql);
$equipment_stmt->bind_param("i", $assessment_id);
$equipment_stmt->execute();
$equipment_result = $equipment_stmt->get_result();

// Fetch documents
$docs_sql = "SELECT * FROM operator_documents WHERE assessment_id = ? ORDER BY document_type ASC";
$docs_stmt = $conn->prepare($docs_sql);
$docs_stmt->bind_param("i", $assessment_id);
$docs_stmt->execute();
$docs_result = $docs_stmt->get_result();
?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-6">
                        <h4 class="pl-2 pt-3 pb-2 font-20">View Operator Assessment</h4>
                    </div>
                    <div class="col-6 text-right">
                        <a href="./assessment-list.php" class="btn btn-secondary">Back to List</a>
                        <button onclick="window.print()" class="btn btn-primary">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Assessment Header -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Assessment Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Assessment No:</th>
                                <td><strong><?php echo htmlspecialchars($assessment['assessment_no']); ?></strong></td>
                            </tr>
                            <tr>
                                <th>Date:</th>
                                <td><?php echo date('d-M-Y', strtotime($assessment['date'])); ?></td>
                            </tr>
                            <tr>
                                <th>Operator Name:</th>
                                <td><?php echo htmlspecialchars($assessment['operator_name']); ?></td>
                            </tr>
                            <tr>
                                <th>IQAMA/Passport No:</th>
                                <td><?php echo htmlspecialchars($assessment['operator_id_passport']); ?></td>
                            </tr>
                            <tr>
                                <th>License Number:</th>
                                <td><?php echo htmlspecialchars($assessment['license_number'] ?? 'N/A'); ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Client:</th>
                                <td><?php echo htmlspecialchars($assessment['client_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Location:</th>
                                <td><?php echo htmlspecialchars($assessment['location']); ?></td>
                            </tr>
                            <tr>
                                <th>Operating Location:</th>
                                <td><?php echo htmlspecialchars($assessment['operating_location']); ?></td>
                            </tr>
                            <tr>
                                <th>Inspector:</th>
                                <td><?php echo htmlspecialchars($assessment['inspector_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <?php
                                    $status = $assessment['status'];
                                    $badge_class = '';
                                    switch ($status) {
                                        case 'PENDING':
                                            $badge_class = 'badge-warning';
                                            break;
                                        case 'IN_PROGRESS':
                                            $badge_class = 'badge-info';
                                            break;
                                        case 'COMPLETED':
                                            $badge_class = 'badge-success';
                                            break;
                                    }
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>">
                                        <?php echo htmlspecialchars($status); ?>
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <?php if ($assessment['date_of_assessment'] || $assessment['date_of_expiry']): ?>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Date of Assessment:</th>
                                <td><?php echo $assessment['date_of_assessment'] ? date('d-M-Y', strtotime($assessment['date_of_assessment'])) : 'N/A'; ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-6">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Date of Expiry:</th>
                                <td><?php echo $assessment['date_of_expiry'] ? date('d-M-Y', strtotime($assessment['date_of_expiry'])) : 'N/A'; ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Exam Results -->
        <?php if ($assessment['exam_status'] !== 'NOT_STARTED'): ?>
        <div class="card mb-4">
            <div class="card-header <?php echo $assessment['exam_status'] === 'PASSED' ? 'bg-success' : 'bg-danger'; ?> text-white">
                <h5 class="mb-0"><i class="fas fa-clipboard-check"></i> Written Exam Results</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 text-center">
                        <h1 class="display-3 mb-0 <?php echo $assessment['exam_status'] === 'PASSED' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $assessment['exam_status'] === 'PASSED' ? '✓' : '✗'; ?>
                        </h1>
                        <h4 class="<?php echo $assessment['exam_status'] === 'PASSED' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $assessment['exam_status']; ?>
                        </h4>
                    </div>
                    <div class="col-lg-9">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Score:</th>
                                <td>
                                    <h4 class="mb-0">
                                        <span class="badge badge-<?php echo $assessment['exam_status'] === 'PASSED' ? 'success' : 'danger'; ?> badge-lg">
                                            <?php echo $assessment['exam_score']; ?>/100
                                        </span>
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <th>Exam Taken:</th>
                                <td><?php echo $assessment['exam_taken_at'] ? date('d-M-Y H:i', strtotime($assessment['exam_taken_at'])) : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Attempts:</th>
                                <td><?php echo $assessment['exam_attempts']; ?></td>
                            </tr>
                            <tr>
                                <th>Action:</th>
                                <td>
                                    <a href="exam-result.php?id=<?php echo $assessment_id; ?>" class="btn btn-primary">
                                        <i class="fas fa-chart-bar"></i> View Detailed Results
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Hand Signals Results -->
        <?php if ($assessment['signals_status'] !== 'NOT_STARTED'): ?>
        <div class="card mb-4">
            <div class="card-header <?php echo $assessment['signals_status'] === 'PASSED' ? 'bg-success' : 'bg-danger'; ?> text-white">
                <h5 class="mb-0"><i class="fas fa-hand-paper"></i> Hand Signals Test Results</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-3 text-center">
                        <h1 class="display-3 mb-0 <?php echo $assessment['signals_status'] === 'PASSED' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $assessment['signals_status'] === 'PASSED' ? '✓' : '✗'; ?>
                        </h1>
                        <h4 class="<?php echo $assessment['signals_status'] === 'PASSED' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $assessment['signals_status']; ?>
                        </h4>
                    </div>
                    <div class="col-lg-9">
                        <table class="table table-borderless">
                            <tr>
                                <th width="30%">Score:</th>
                                <td>
                                    <h4 class="mb-0">
                                        <span class="badge badge-<?php echo $assessment['signals_status'] === 'PASSED' ? 'success' : 'danger'; ?> badge-lg">
                                            <?php echo round($assessment['signals_score'], 0); ?>%
                                        </span>
                                    </h4>
                                </td>
                            </tr>
                            <tr>
                                <th>Passed / Failed:</th>
                                <td>
                                    <span class="text-success font-weight-bold"><?php echo $assessment['signals_passed']; ?> Passed</span> / 
                                    <span class="text-danger font-weight-bold"><?php echo $assessment['signals_failed']; ?> Failed</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Test Date:</th>
                                <td><?php echo $assessment['signals_tested_at'] ? date('d-M-Y H:i', strtotime($assessment['signals_tested_at'])) : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <th>Attempts:</th>
                                <td><?php echo $assessment['signals_attempts']; ?></td>
                            </tr>
                            <tr>
                                <th>Action:</th>
                                <td>
                                    <a href="signals-result.php?id=<?php echo $assessment_id; ?>" class="btn btn-primary">
                                        <i class="fas fa-eye"></i> View Detailed Results
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Download Card (Only if Completed) -->
        <?php if ($assessment['status'] === 'COMPLETED'): ?>
        <div class="card mb-4">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0"><i class="fas fa-id-card"></i> Download Card</h5>
            </div>
            <div class="card-body text-center">
                <h5 class="card-title">Operator Certification Card</h5>
                <p class="card-text">The assessment is complete. You can now view and download the operator's ID card.</p>
                <a href="../operator_card/view-card.php?id=<?php echo $assessment_id; ?>" target="_blank" class="btn btn-primary btn-lg">
                    <i class="fas fa-external-link-alt"></i> View & Print Card
                </a>
            </div>
        </div>
        <?php endif; ?>

        <!-- Equipment Details -->
        <?php if ($equipment_result->num_rows > 0): ?>
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Equipment Details</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th>Equipment No</th>
                                <th>Type</th>
                                <th>Manufacturer</th>
                                <th>Model</th>
                                <th>Capacity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($equipment = $equipment_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $equipment['equipment_number']; ?></td>
                                <td><?php echo htmlspecialchars($equipment['equipment_type'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($equipment['manufacturer']); ?></td>
                                <td><?php echo htmlspecialchars($equipment['model']); ?></td>
                                <td><?php echo htmlspecialchars($equipment['capacity']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Documents -->
        <?php if ($docs_result->num_rows > 0): ?>
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Uploaded Documents</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Document Type</th>
                                <th>Original Filename</th>
                                <th>Uploaded Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($doc = $docs_result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <?php 
                                    $doc_labels = [
                                        'IQAMA_PASSPORT' => 'IQAMA/Passport',
                                        'LICENSE' => 'Heavy Equipment License',
                                        'PHOTO' => 'Operator Photo',
                                        'MEDICAL_CERT' => 'Medical Certificate',
                                        'PREVIOUS_CERT' => 'Previous Certificate',
                                        'ADDITIONAL' => 'Additional Document'
                                    ];
                                    echo $doc_labels[$doc['document_type']] ?? $doc['document_type'];
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($doc['original_filename']); ?></td>
                                <td><?php echo date('d-M-Y H:i', strtotime($doc['uploaded_at'])); ?></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($doc['file_path']); ?>" 
                                       class="btn btn-sm btn-primary" target="_blank">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<style>

/* ===== GLOBAL ===== */
body{
    background:#f4f6f9;
    font-size:14px;
}

.main-content{
    padding:15px;
}

/* ===== CARD DESIGN ===== */
.card{
    border:none;
    border-radius:10px;
    box-shadow:0 2px 10px rgba(0,0,0,0.05);
    overflow:hidden;
}

.card-header{
    font-size:15px;
    font-weight:600;
    letter-spacing:.3px;
    padding:12px 18px;
}

.card-body{
    padding:18px;
}

/* ===== HEADER SECTION ===== */
.font-20{
    font-weight:600;
    color:#2c3e50;
}

/* ===== TABLES ===== */
.table{
    margin-bottom:0;
}

.table th{
    font-weight:600;
    color:#495057;
    white-space:nowrap;
}

.table td{
    color:#333;
}

.table-borderless td{
    padding:6px 8px;
}

.table-bordered{
    border-radius:6px;
    overflow:hidden;
}

/* ===== BUTTONS ===== */
.btn{
    border-radius:6px;
    font-size:13px;
    padding:6px 12px;
}

.btn-lg{
    padding:10px 18px;
    font-size:15px;
}

/* ===== BADGES (Modern Look) ===== */
.badge{
    padding:6px 12px;
    font-size:12px;
    font-weight:600;
    border-radius:30px;
    letter-spacing:.3px;
}

.badge-warning{
    background:#fff3cd;
    color:#856404;
}

.badge-info{
    background:#d1ecf1;
    color:#0c5460;
}

.badge-success{
    background:#d4edda;
    color:#155724;
}

.badge-danger{
    background:#f8d7da;
    color:#721c24;
}

/* ===== RESULT ICON BIG SIZE ===== */
.display-3{
    font-size:70px;
    font-weight:700;
}

/* ===== DOWNLOAD CARD CENTER ===== */
.card-body.text-center h5{
    font-weight:600;
}

/* ===== RESPONSIVE IMPROVEMENTS ===== */
@media (max-width:992px){

    .col-lg-6{
        margin-bottom:10px;
    }

    .display-3{
        font-size:50px;
    }

    .btn-lg{
        width:100%;
    }
}

@media (max-width:768px){

    h4.font-20{
        font-size:18px;
    }

    .card-body{
        padding:14px;
    }

    .table th{
        width:40%;
    }

}

/* ===== PRINT OPTIMIZATION ===== */
@media print{

    body{
        background:#fff;
    }

    .btn,
    .card-header.bg-primary,
    .card-header.bg-dark,
    .card-header.bg-info,
    .card-header.bg-warning{
        display:none !important;
    }

    .card{
        box-shadow:none;
        border:1px solid #ddd;
    }

    .main-content{
        padding:0;
    }
}

</style>