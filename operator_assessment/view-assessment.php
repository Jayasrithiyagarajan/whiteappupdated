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
        <div class="card bg-transparent pb-3 border-0 shadow-none animate-fade-in">
            <div class="card-body bg-transparent px-0">
                <div class="row align-items-center">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <h4 class="font-weight-bold mb-0" style="color: #2d3748; font-size: 1.75rem;">View Operator Assessment</h4>
                    </div>
                    <div class="col-12 col-md-6 text-md-right">
                        <a href="./assessment-list.php" class="btn btn-premium btn-secondary-gradient mb-2 mb-md-0 mr-md-2">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <button onclick="window.print()" class="btn btn-premium btn-primary-gradient mb-2 mb-md-0">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Assessment Header -->
        <div class="card premium-card mb-4 animate-fade-in delay-1">
            <div class="card-header premium-header header-primary">
                <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Assessment Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-borderless premium-table">
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
                    </div>
                    <div class="col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-borderless premium-table">
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
                                                $badge_class = 'bg-soft-warning';
                                                break;
                                            case 'IN_PROGRESS':
                                                $badge_class = 'bg-soft-info';
                                                break;
                                            case 'COMPLETED':
                                                $badge_class = 'bg-soft-success';
                                                break;
                                        }
                                        ?>
                                        <span class="badge badge-premium <?php echo $badge_class; ?>">
                                            <?php echo htmlspecialchars($status); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Training Program:</th>
                                    <td><?php echo htmlspecialchars($assessment['training_program'] ?? 'N/A'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <?php if ($assessment['date_of_assessment'] || $assessment['date_of_expiry']): ?>
                <div class="row mt-3">
                    <div class="col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-borderless premium-table">
                                <tr>
                                    <th width="40%">Date of Assessment:</th>
                                    <td><?php echo $assessment['date_of_assessment'] ? date('d-M-Y', strtotime($assessment['date_of_assessment'])) : 'N/A'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="table-responsive">
                            <table class="table table-borderless premium-table">
                                <tr>
                                    <th width="40%">Date of Expiry:</th>
                                    <td><?php echo $assessment['date_of_expiry'] ? date('d-M-Y', strtotime($assessment['date_of_expiry'])) : 'N/A'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Exam Results -->
        <?php if ($assessment['exam_status'] !== 'NOT_STARTED'): ?>
        <div class="card premium-card mb-4 animate-fade-in delay-2">
            <div class="card-header premium-header <?php echo $assessment['exam_status'] === 'PASSED' ? 'header-success' : 'header-danger'; ?>">
                <h5 class="mb-0"><i class="fas fa-clipboard-check mr-2"></i> Written Exam Results</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-3 text-center mb-4 mb-lg-0">
                        <div class="status-circle <?php echo $assessment['exam_status'] === 'PASSED' ? 'status-passed' : 'status-failed'; ?>">
                            <h1 class="display-3 mb-0">
                                <?php echo $assessment['exam_status'] === 'PASSED' ? '✓' : '✗'; ?>
                            </h1>
                        </div>
                        <h4 class="mt-3 font-weight-bold <?php echo $assessment['exam_status'] === 'PASSED' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $assessment['exam_status']; ?>
                        </h4>
                    </div>
                    <div class="col-lg-9">
                        <div class="table-responsive">
                            <table class="table table-borderless premium-table">
                                <tr>
                                    <th width="30%">Score:</th>
                                    <td>
                                        <h4 class="mb-0">
                                            <span class="badge badge-premium <?php echo $assessment['exam_status'] === 'PASSED' ? 'bg-soft-success' : 'bg-soft-danger'; ?> badge-lg">
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
                                        <a href="exam-result.php?id=<?php echo $assessment_id; ?>" class="btn btn-premium btn-primary-gradient mt-2">
                                            <i class="fas fa-chart-bar mr-1"></i> View Detailed Results
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Hand Signals Results -->
        <?php if ($assessment['signals_status'] !== 'NOT_STARTED'): ?>
        <div class="card premium-card mb-4 animate-fade-in delay-3">
            <div class="card-header premium-header <?php echo $assessment['signals_status'] === 'PASSED' ? 'header-success' : 'header-danger'; ?>">
                <h5 class="mb-0"><i class="fas fa-hand-paper mr-2"></i> Hand Signals Test Results</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-lg-3 text-center mb-4 mb-lg-0">
                        <div class="status-circle <?php echo $assessment['signals_status'] === 'PASSED' ? 'status-passed' : 'status-failed'; ?>">
                            <h1 class="display-3 mb-0">
                                <?php echo $assessment['signals_status'] === 'PASSED' ? '✓' : '✗'; ?>
                            </h1>
                        </div>
                        <h4 class="mt-3 font-weight-bold <?php echo $assessment['signals_status'] === 'PASSED' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo $assessment['signals_status']; ?>
                        </h4>
                    </div>
                    <div class="col-lg-9">
                        <div class="table-responsive">
                            <table class="table table-borderless premium-table">
                                <tr>
                                    <th width="30%">Score:</th>
                                    <td>
                                        <h4 class="mb-0">
                                            <span class="badge badge-premium <?php echo $assessment['signals_status'] === 'PASSED' ? 'bg-soft-success' : 'bg-soft-danger'; ?> badge-lg">
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
                                        <a href="signals-result.php?id=<?php echo $assessment_id; ?>" class="btn btn-premium btn-primary-gradient mt-2">
                                            <i class="fas fa-eye mr-1"></i> View Detailed Results
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Download Card (Only if Completed) -->
        <?php if ($assessment['status'] === 'COMPLETED'): ?>
        <div class="card premium-card mb-4 animate-fade-in delay-4">
            <div class="card-header premium-header header-dark">
                <h5 class="mb-0"><i class="fas fa-id-card mr-2"></i> Download Card</h5>
            </div>
            <div class="card-body text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-address-card text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
                </div>
                <h4 class="card-title font-weight-bold">Operator Certification Card</h4>
                <p class="card-text text-muted mb-4">The assessment is complete. You can now view and download the operator's ID card.</p>
                <div class="d-flex justify-content-center flex-wrap gap-3">
                    <a href="../operator_card/view-card.php?id=<?php echo $assessment_id; ?>" target="_blank" class="btn btn-premium btn-primary-gradient btn-lg m-2">
                        <i class="fas fa-external-link-alt mr-2"></i> View & Print Card
                    </a>
                    <a href="download-certificate.php?id=<?php echo $assessment_id; ?>" target="_blank" class="btn btn-premium btn-success-gradient btn-lg m-2">
                        <i class="fas fa-file-pdf mr-2"></i> Download Certificate
                    </a>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Equipment Details -->
        <?php if ($equipment_result->num_rows > 0): ?>
        <div class="card premium-card mb-4 animate-fade-in delay-4">
            <div class="card-header premium-header header-info">
                <h5 class="mb-0"><i class="fas fa-cogs mr-2"></i> Equipment Details</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table premium-table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-top-0 pl-4">Equipment No</th>
                                <th class="border-top-0">Type</th>
                                <th class="border-top-0">Manufacturer</th>
                                <th class="border-top-0">Model</th>
                                <th class="border-top-0">Capacity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($equipment = $equipment_result->fetch_assoc()): ?>
                            <tr>
                                <td class="pl-4 font-weight-bold text-primary"><?php echo $equipment['equipment_number']; ?></td>
                                <td><?php echo htmlspecialchars($equipment['equipment_type'] ?? 'N/A'); ?></td>
                                <td><?php echo htmlspecialchars($equipment['manufacturer']); ?></td>
                                <td><?php echo htmlspecialchars($equipment['model']); ?></td>
                                <td><span class="badge badge-premium bg-soft-info"><?php echo htmlspecialchars($equipment['capacity']); ?></span></td>
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
        <div class="card premium-card mb-4 animate-fade-in delay-4">
            <div class="card-header premium-header header-warning">
                <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i> Uploaded Documents</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table premium-table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-top-0 pl-4">Document Type</th>
                                <th class="border-top-0">Original Filename</th>
                                <th class="border-top-0">Uploaded Date</th>
                                <th class="border-top-0 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($doc = $docs_result->fetch_assoc()): ?>
                            <tr>
                                <td class="pl-4 font-weight-bold">
                                    <?php 
                                    $doc_labels = [
                                        'IQAMA_PASSPORT' => '<i class="fas fa-passport mr-2 text-primary"></i> IQAMA/Passport',
                                        'LICENSE' => '<i class="fas fa-id-card mr-2 text-success"></i> Heavy Equipment License',
                                        'PHOTO' => '<i class="fas fa-image mr-2 text-info"></i> Operator Photo',
                                        'MEDICAL_CERT' => '<i class="fas fa-notes-medical mr-2 text-danger"></i> Medical Certificate',
                                        'PREVIOUS_CERT' => '<i class="fas fa-certificate mr-2 text-warning"></i> Previous Certificate',
                                        'ADDITIONAL' => '<i class="fas fa-file mr-2 text-secondary"></i> Additional Document'
                                    ];
                                    echo $doc_labels[$doc['document_type']] ?? '<i class="fas fa-file mr-2 text-secondary"></i> ' . $doc['document_type'];
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($doc['original_filename']); ?></td>
                                <td class="text-muted"><?php echo date('d-M-Y H:i', strtotime($doc['uploaded_at'])); ?></td>
                                <td class="text-center">
                                    <a href="<?php echo htmlspecialchars($doc['file_path']); ?>" 
                                       class="btn btn-sm btn-premium btn-primary-gradient" target="_blank">
                                        <i class="fas fa-download mr-1"></i> Download
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
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap');

/* Main Wrapper Styling */
.main-content {
    font-family: 'Outfit', sans-serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    min-height: calc(100vh - 60px);
    padding: 2rem 0;
}

/* Premium Card Style (Glassmorphism) */
.premium-card {
    background: rgba(255, 255, 255, 0.85);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 20px;
    box-shadow: 0 10px 40px 0 rgba(31, 38, 135, 0.08);
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    overflow: hidden;
}

.premium-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 50px 0 rgba(31, 38, 135, 0.15);
}

.premium-header {
    background: transparent;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    padding: 1.5rem 1.5rem 1rem 1.5rem;
}

.premium-header h5 {
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    letter-spacing: 0.5px;
}

/* Gradients for text headers */
.header-primary h5 { background: linear-gradient(120deg, #2b4162 0%, #fa9c1b 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.header-success h5 { background: linear-gradient(120deg, #11998e 0%, #38ef7d 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.header-danger h5 { background: linear-gradient(120deg, #eb3349 0%, #f45c43 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.header-dark h5 { background: linear-gradient(120deg, #232526 0%, #414345 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.header-info h5 { background: linear-gradient(120deg, #2193b0 0%, #6dd5ed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
.header-warning h5 { background: linear-gradient(120deg, #f2994a 0%, #f2c94c 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }

/* Status Circles */
.status-circle {
    width: 120px;
    height: 120px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.status-passed {
    background: linear-gradient(135deg, #e0f2f1 0%, #b2dfdb 100%);
    border: 4px solid #4db6ac;
}
.status-passed h1 { color: #00897b; }

.status-failed {
    background: linear-gradient(135deg, #fbe9e7 0%, #ffccbc 100%);
    border: 4px solid #e64a19;
}
.status-failed h1 { color: #d84315; }

/* Buttons */
.btn-premium {
    border-radius: 50px;
    padding: 0.6rem 1.5rem;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.btn-primary-gradient { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); color: white !important; }
.btn-primary-gradient:hover { background: linear-gradient(135deg, #00f2fe 0%, #4facfe 100%); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,242,254,0.4); }

.btn-secondary-gradient { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white !important; }
.btn-secondary-gradient:hover { background: linear-gradient(135deg, #764ba2 0%, #667eea 100%); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(118,75,162,0.4); }

.btn-success-gradient { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); color: white !important; }
.btn-success-gradient:hover { background: linear-gradient(135deg, #38ef7d 0%, #11998e 100%); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(56,239,125,0.4); }

/* Badges */
.badge-premium {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

.bg-soft-warning { background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; }
.bg-soft-info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
.bg-soft-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
.bg-soft-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

/* Tables */
.premium-table {
    background: transparent;
    margin-bottom: 0;
}
.premium-table th {
    font-weight: 600;
    color: #4a5568;
    border-top: none;
    border-bottom: 1px solid rgba(0,0,0,0.05) !important;
}
.premium-table td {
    color: #2d3748;
    vertical-align: middle;
    border-top: 1px solid rgba(0,0,0,0.03);
}

.table-hover tbody tr:hover {
    background-color: rgba(255,255,255,0.6);
}

/* Animations */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in { opacity: 0; animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

.delay-1 { animation-delay: 0.1s; }
.delay-2 { animation-delay: 0.2s; }
.delay-3 { animation-delay: 0.3s; }
.delay-4 { animation-delay: 0.4s; }

/* Print Styles */
@media print {
    body, .main-content {
        background: white !important;
    }
    .btn, .card-header, .animate-fade-in {
        animation: none !important;
        opacity: 1 !important;
        transform: none !important;
    }
    .btn, .card-header {
        display: none !important;
    }
    .premium-card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
    }
}
</style>
