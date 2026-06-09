<?php
include_once('../inc/function.php');

// 🔒 Allow ONLY admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>
        alert('Access denied. Admins only.');
        window.location.href = '../index.php';
    </script>";
    exit;
}


// Generate new assessment number
$result = $conn->query("SELECT assessment_no FROM operator_assessments ORDER BY id DESC LIMIT 1");
$row = $result->fetch_assoc();
if ($row && isset($row['assessment_no'])) {
    $last_assessment_no = $row['assessment_no'];
    $last_number = (int)substr($last_assessment_no, 9); // Remove 'CIMS-OAT-'
    $numeric_id = $last_number + 1;
} else {
    $numeric_id = 1;
}
$new_assessment_no = 'CIMS-OAT-' . str_pad($numeric_id, 3, '0', STR_PAD_LEFT);

// Fetch clients
$clients_query = "SELECT cus_id, customer_name FROM customers ORDER BY customer_name ASC";
$clients_result = $conn->query($clients_query);

// Fetch inspectors
$inspectors_query = "SELECT user_id, username FROM new_users WHERE role = 'inspector' ORDER BY username ASC";
$inspectors_result = $conn->query($inspectors_query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Create Operator Assessment</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="../assets/css/style.css">
<link rel="stylesheet" href="../assets/css/premium-nav.css">
<link rel="stylesheet" href="../assets/plugins/select2/select2.min.css">

<style>
/* ===== PREMIUM GLASSMORPHISM UI ===== */
body {
    background:
        radial-gradient(circle at 14% 8%, rgba(37, 99, 235, 0.12), transparent 30%),
        radial-gradient(circle at 92% 6%, rgba(20, 184, 166, 0.1), transparent 28%),
        linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
    font-family: "Inter", system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
    color: #1e293b;
}

.main-content {
    min-height: calc(100vh - 110px);
    padding: 30px 10px 60px;
}

.page-hero {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 18px;
    margin-bottom: 28px;
    padding: 26px 28px;
    border-radius: 22px;
    border: 1px solid rgba(255, 255, 255, 0.64);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48));
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}

.page-title {
    display: flex;
    align-items: center;
    gap: 16px;
}

.page-title .title-icon {
    width: 54px;
    height: 54px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 16px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.16), rgba(20, 184, 166, 0.14));
    color: #2563eb;
    box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 12px 24px rgba(15, 23, 42, 0.08);
    font-size: 24px;
}

.page-title h2 {
    margin: 0;
    color: #111827;
    font-size: 26px;
    font-weight: 850;
}

.assessment-glass-card {
    border: 1px solid rgba(255, 255, 255, 0.64);
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48));
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.12);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
    border-radius: 28px;
    padding: 45px;
    max-width: 1100px;
    margin: 0 auto;
}

.form-group label {
    font-weight: 800;
    color: #475569;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 12px;
    display: block;
}

/* Premium Input Styling */
.input-group-premium {
    background: rgba(255, 255, 255, 0.7);
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 16px;
    display: flex;
    align-items: center;
    transition: all 0.2s ease;
    overflow: hidden;
}

.input-group-premium:focus-within {
    border-color: #2563eb;
    background: #fff;
    box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
}

.input-group-text-pre {
    padding: 0 20px;
    color: #94a3b8;
    font-size: 18px;
}

.form-control-pre {
    border: none !important;
    background: transparent !important;
    padding: 14px 20px 14px 0 !important;
    width: 100%;
    outline: none !important;
    font-size: 1rem !important;
    font-weight: 600 !important;
    color: #1e293b !important;
}

/* Select2 Overrides */
.select2-container--default .select2-selection--single {
    background: transparent !important;
    border: none !important;
    height: 52px !important;
    display: flex !important;
    align-items: center !important;
}

.select2-container--default .select2-selection--single .select2-selection__rendered {
    font-weight: 600 !important;
    color: #1e293b !important;
    padding-left: 0 !important;
}

.select2-container--default .select2-selection--single .select2-selection__arrow {
    height: 52px !important;
}

/* Select2 Premium Theme Overrides */
.select2-dropdown {
    background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    border: 1px solid rgba(255, 255, 255, 0.6) !important;
    border-radius: 18px !important;
    box-shadow: 0 24px 60px rgba(15, 23, 42, 0.16) !important;
    overflow: hidden !important;
    margin-top: 8px !important;
    animation: dropdownFade 0.3s ease;
    z-index: 9999 !important;
}

@keyframes dropdownFade {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.select2-results__option {
    padding: 12px 16px !important;
    font-size: 14px !important;
    font-weight: 600 !important;
    color: #475569 !important;
}

.select2-results__option--highlighted[aria-selected] {
    background: linear-gradient(135deg, #2563eb 0%, #16a3d8 100%) !important;
    color: #fff !important;
}

.select2-search--dropdown {
    padding: 12px !important;
}

.select2-search--dropdown .select2-search__field {
    border-radius: 10px !important;
    border: 1px solid rgba(148, 163, 184, 0.24) !important;
    padding: 10px 14px !important;
    background: #f8fafc !important;
}

/* Radio Button Styling */
.radio-group-pre {
    display: flex;
    gap: 20px;
    background: rgba(255, 255, 255, 0.5);
    padding: 12px 20px;
    border-radius: 16px;
    border: 1px solid rgba(148, 163, 184, 0.2);
}

.radio-group-pre label {
    margin: 0;
    display: flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    font-weight: 700;
    text-transform: none;
    letter-spacing: normal;
    font-size: 14px;
}

.radio-group-pre input[type="radio"] {
    width: 18px;
    height: 18px;
    accent-color: #2563eb;
}

.btn-primary-pre {
    background: linear-gradient(135deg, #2563eb 0%, #16a3d8 100%);
    color: white !important;
    border: none;
    border-radius: 16px;
    padding: 16px 40px;
    font-size: 1.05rem;
    font-weight: 800;
    transition: all 0.3s;
    box-shadow: 0 16px 32px rgba(37, 99, 235, 0.24);
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 10px;
    text-decoration: none;
}

.btn-primary-pre:hover {
    transform: translateY(-2px);
    box-shadow: 0 22px 42px rgba(37, 99, 235, 0.3);
}

.btn-view-list {
    background: rgba(255, 255, 255, 0.8);
    color: #475569 !important;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 10px 20px;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-view-list:hover {
    background: #fff;
    color: #2563eb !important;
    border-color: #2563eb;
}

.checkbox-pre {
    display: flex;
    align-items: center;
    gap: 12px;
    background: rgba(34, 197, 94, 0.08);
    padding: 16px 24px;
    border-radius: 18px;
    border: 1px solid rgba(34, 197, 94, 0.2);
    margin-top: 20px;
}

.checkbox-pre label {
    margin: 0;
    text-transform: none;
    letter-spacing: normal;
    font-weight: 700;
    color: #166534;
}

.checkbox-pre input {
    width: 20px;
    height: 20px;
    accent-color: #22c55e;
}
</style>
</head>
<body>

<?php 
if (file_exists('../inc/nav.php')) {
    include_once('../inc/nav.php'); 
}
?>

<div class="main-content">
    <div class="container-fluid">
        
        <!-- PAGE HERO -->
        <div class="page-hero">
            <div class="page-title">
                <span class="title-icon"><i class="fas fa-user-graduate"></i></span>
                <h2>Create Operator Assessment</h2>
            </div>
            <a href="./assessment-list.php" class="btn-view-list">
                <i class="fas fa-list-ul mr-2"></i> View Assessment List
            </a>
        </div>

        <!-- FORM CARD -->
        <div class="assessment-glass-card">
            <form action="add-assessment.php" method="POST" id="assessmentForm">
                <div class="row">
                    <!-- LEFT COLUMN -->
                    <div class="col-lg-6 pr-lg-4">
                        <div class="form-group mb-4">
                            <label>Assessment Number</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-barcode"></i></div>
                                <input type="text" class="form-control-pre" name="assessment_no" 
                                       value="<?php echo htmlspecialchars($new_assessment_no); ?>" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group mb-4">
                            <label>Assessment Date</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-calendar-alt"></i></div>
                                <input type="date" class="form-control-pre" name="date" 
                                       value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>Full Name (As per Passport/ID)</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-user"></i></div>
                                <input type="text" class="form-control-pre" name="operator_name" 
                                       placeholder="Enter operator's full name" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>IQAMA / Passport Number</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-id-card"></i></div>
                                <input type="text" class="form-control-pre" name="operator_id_passport" 
                                       placeholder="Enter identification number" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>Client / Company</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-building"></i></div>
                                <select class="form-control-pre select2-basic" name="client_id" required>
                                    <option value="">Select Client</option>
                                    <?php while ($client = $clients_result->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($client['cus_id']); ?>">
                                            <?php echo htmlspecialchars($client['customer_name']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col-lg-6 pl-lg-4">
                        <div class="form-group mb-4">
                            <label>Primary Location</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-map-marker-alt"></i></div>
                                <input type="text" class="form-control-pre" name="location" 
                                       placeholder="Enter site location" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>Operating Environment</label>
                            <div class="radio-group-pre">
                                <label>
                                    <input type="radio" name="operating_location" value="ONSHORE" required> 
                                    <span>Onshore</span>
                                </label>
                                <label>
                                    <input type="radio" name="operating_location" value="OFFSHORE" required> 
                                    <span>Offshore</span>
                                </label>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>Training Program</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-book-reader"></i></div>
                                <input type="text" class="form-control-pre" name="training_program" 
                                       placeholder="e.g. Defensive Driving Awareness" required>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>Equipment Assessments Count</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-cogs"></i></div>
                                <select class="form-control-pre select2-basic" name="no_of_equipment" required>
                                    <option value="">Select Quantity</option>
                                    <option value="1">1 Equipment</option>
                                    <option value="2">2 Equipments</option>
                                    <option value="3">3 Equipments</option>
                                    <option value="4">4 Equipments</option>
                                    <option value="5">5 Equipments</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label>Assigned Inspector</label>
                            <div class="input-group-premium">
                                <div class="input-group-text-pre"><i class="fas fa-user-check"></i></div>
                                <select class="form-control-pre select2-basic" name="inspector_id" required>
                                    <option value="">Select Inspector</option>
                                    <?php mysqli_data_seek($inspectors_result, 0); ?>
                                    <?php while ($inspector = $inspectors_result->fetch_assoc()): ?>
                                        <option value="<?php echo htmlspecialchars($inspector['user_id']); ?>">
                                            <?php echo htmlspecialchars($inspector['username']); ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CONFIRMATION -->
                <div class="checkbox-pre">
                    <input type="checkbox" name="info_correct" id="info_correct" required>
                    <label for="info_correct">I confirm that all provided details are accurate and verified.</label>
                </div>

                <!-- SUBMIT -->
                <div class="text-center mt-5">
                    <button type="submit" class="btn-primary-pre" name="create_assessment">
                        <i class="fas fa-plus-circle"></i> Initialize Assessment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../assets/plugins/select2/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2-basic').select2({
        width: '100%'
    });

    // Form Validation Polish
    $('#assessmentForm').on('submit', function(e) {
        let valid = true;
        $(this).find('input[required], select[required]').each(function() {
            if (!$(this).val()) {
                valid = false;
                $(this).closest('.input-group-premium').css('border-color', '#ef4444');
            } else {
                $(this).closest('.input-group-premium').css('border-color', '');
            }
        });

        if (!valid) {
            e.preventDefault();
            alert("Please complete all required fields.");
        }
    });
});
</script>

</body>
</html>
