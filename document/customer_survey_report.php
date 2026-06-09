<?php
// Add this at the VERY top of your PHP file
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once('../file/config.php');

$project_id = isset($_GET['project_id']) ? htmlspecialchars($_GET['project_id']) : '';

if (empty($project_id)) {
    die("<h3 style='color:red; text-align:center;'>❌ Project ID missing!</h3>");
}

// ✅ Check if survey already exists for this project
$stmt = $conn->prepare("SELECT id FROM customer_survey_report WHERE project_id = ?");
$stmt->bind_param("s", $project_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "
    <div style='text-align:center; margin-top:50px;'>
        <h2>✅ Survey Already Submitted</h2>
        <p>You have already completed the customer survey for Project ID <strong>{$project_id}</strong>.</p>
    </div>";
    exit();
}

// ✅ Fetch project details
$project_query = "SELECT customer_name, customer_email, customer_mobile, inspector_name 
                  FROM project_info 
                  WHERE project_no = ?";
$project_stmt = $conn->prepare($project_query);
$project_stmt->bind_param("s", $project_id);
$project_stmt->execute();
$project_result = $project_stmt->get_result();
$project_data = $project_result->fetch_assoc();

// ✅ Fetch from checklist_results table
$checklist_query = "SELECT client_name, client_phone, client_signature 
                    FROM checklist_results 
                    WHERE project_no = ?";
$checklist_stmt = $conn->prepare($checklist_query);
$checklist_stmt->bind_param("s", $project_id);
$checklist_stmt->execute();
$checklist_result = $checklist_stmt->get_result();
$checklist_data = $checklist_result->fetch_assoc();

// ✅ Check if signature file exists
$signature_path = "uploads/{$project_id}.png";
$signature_exists = file_exists($signature_path);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Satisfaction Survey - CIMS</title>
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f5f5;
            padding: 20px;
            font-family: Helvetica, Arial, sans-serif;
            color: #000;
        }
        .survey-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
        }
        .header { border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 15px; }
        .main-title { text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; text-transform: uppercase; color: #2c3e50; }
        .customer-table, .question-table, .signature-table {
            width: 100%; border-collapse: collapse; margin-bottom: 20px;
        }
        .customer-table td, .question-table td, .question-table th, .signature-table td {
            border: 1px solid #000; padding: 10px; font-size: 14px;
        }
        .question-table th { background: #dbe4f0; font-weight: bold; text-align: center; }
        .question-text { text-align: left; font-weight: bold; }
        .radio-group { display: flex; justify-content: center; gap: 15px; }
        .remarks-input { width: 100%; border: none; padding: 8px; font-size: 14px; background: transparent; }
        .head6 { font-weight: bold; background-color: #dbe4f0; width: 25%; }
        .btn-submit { background: #28a745; border: none; padding: 12px 40px; font-size: 16px; font-weight: bold; }
        .btn-submit:hover { background: #218838; }
        .project-info { background: #f8f9fa; padding: 15px; border-radius: 5px; margin-bottom: 20px; border-left: 4px solid #007bff; }
        .signature-display { 
            max-height: 120px; 
            border: 1px solid #ccc; 
            border-radius: 4px; 
            padding: 5px;
            background: #f9f9f9;
        }
        .no-signature { 
            color: #6c757d; 
            font-style: italic; 
            padding: 10px;
            background: #f8f9fa;
            border: 1px dashed #ccc;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="main-content d-flex flex-column flex-md-row">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12">
                    <div class="survey-container">
                        <form action="save_survey.php" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="project_id" value="<?php echo $project_id; ?>">

                            <div class="header">
                                <div class="project-info mt-3">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Project ID:</strong> <?php echo $project_id; ?>
                                        </div>
                                        <div class="col-md-6 text-md-end">
                                            <strong>Date:</strong> <?php echo date('F d, Y'); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="main-title">CUSTOMER SATISFACTION SURVEY</div>

                            <!-- Customer Info -->
                            <table class="customer-table mb-4">
                                <tr>
                                    <td class="head6">CUSTOMER/CLIENT NAME</td>
                                    <td colspan="2">
                                        <input type="text" class="form-control" name="client_name" 
                                               value="<?php echo htmlspecialchars($project_data['customer_name'] ?? ''); ?>" required>
                                    </td>
                                    <td class="head6">SURVEY DATE</td>
                                    <td>
                                        <input type="date" class="form-control" name="survey_date" 
                                               value="<?php echo date('Y-m-d'); ?>" required>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="head6">CONTACT PERSON</td>
                                    <td colspan="2">
                                        <input type="text" class="form-control" name="contact_person" 
                                               value="<?php echo htmlspecialchars($checklist_data['client_name'] ?? ''); ?>">
                                    </td>
                                    <td class="head6">EMAIL</td>
                                    <td>
                                        <input type="email" class="form-control" name="email" 
                                               value="<?php echo htmlspecialchars($project_data['customer_email'] ?? ''); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="head6">YEARS OF BUSINESS</td>
                                    <td colspan="2">
                                        <input type="text" class="form-control" name="years_business" placeholder="e.g., 5 years">
                                    </td>
                                    <td class="head6">TEL. NO</td>
                                    <td>
                                        <input type="text" class="form-control" name="telephone" 
                                               value="<?php echo htmlspecialchars($checklist_data['client_phone'] ?? ($project_data['customer_mobile'] ?? '')); ?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="head6">CLIENT STATUS</td>
                                    <td colspan="2" class="text-center">
                                        <label class="me-3"><input type="radio" name="status" value="new"> NEW CLIENT</label>
                                    </td>
                                    <td colspan="2" class="text-center">
                                        <label><input type="radio" name="status" value="existing"> EXISTING CLIENT</label>
                                    </td>
                                </tr>
                            </table>

                            <!-- Survey Questions -->
                            <h5 class="mb-3" style="color: #2c3e50;">Please rate our services:</h5>
                            <table class="question-table">
                                <thead>
                                    <tr>
                                        <th width="5%">NO</th>
                                        <th width="55%">QUESTIONS</th>
                                        <th width="20%">RESPONSE</th>
                                        <th width="20%">REMARKS</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td class="question-text">Inspector's attention to safety procedures?</td>
                                        <td>
                                            <div class="radio-group">
                                                <label><input type="radio" name="qualification_card" value="yes" required> Yes</label>
                                                <label><input type="radio" name="qualification_card" value="no"> No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="remarks-input" name="qualification_remarks" placeholder="Any remarks..."></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td class="question-text">Was the inspector thorough and effective?</td>
                                        <td>
                                            <div class="radio-group">
                                                <label><input type="radio" name="response_time" value="yes" required> Yes</label>
                                                <label><input type="radio" name="response_time" value="no"> No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="remarks-input" name="response_remarks" placeholder="Any remarks..."></td>
                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td class="question-text">Did the inspector arrive on time?</td>
                                        <td>
                                            <div class="radio-group">
                                                <label><input type="radio" name="ppe" value="yes" required> Yes</label>
                                                <label><input type="radio" name="ppe" value="no"> No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="remarks-input" name="ppe_remarks" placeholder="Any remarks..."></td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td class="question-text">Inspector's professionalism and communication?</td>
                                        <td>
                                            <div class="radio-group">
                                                <label><input type="radio" name="aramco_standards" value="yes" required> Yes</label>
                                                <label><input type="radio" name="aramco_standards" value="no"> No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="remarks-input" name="aramco_remarks" placeholder="Any remarks..."></td>
                                    </tr>
                                    <tr>
                                        <td>5</td>
                                        <td class="question-text">Overall satisfaction (coordination, reply, quality, etc.)</td>
                                        <td>
                                            <div class="radio-group">
                                                <label><input type="radio" name="overall_satisfaction" value="yes" required> Yes</label>
                                                <label><input type="radio" name="overall_satisfaction" value="no"> No</label>
                                            </div>
                                        </td>
                                        <td><input type="text" class="remarks-input" name="overall_satisfaction_remarks" placeholder="Any remarks..."></td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="mb-4">
                                <label class="form-label">ADDITIONAL COMMENTS / SUGGESTIONS FOR IMPROVEMENT</label>
                                <textarea class="form-control" name="comments" rows="4" placeholder="Please share any additional feedback or suggestions..."></textarea>
                            </div>

                            <!-- Signature Section -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">EVALUATED BY</label>
                                    <input type="text" class="form-control" name="evaluated_by" 
                                           value="<?php echo htmlspecialchars($checklist_data['client_name'] ?? ''); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">CLIENT SIGNATURE</label>
                                    <div class="mt-2">
                                        <?php if ($signature_exists): ?>
                                            <div class="text-center">
                                                <p class="mb-2"><small>Existing Signature:</small></p>
                                                <img src="<?php echo $signature_path; ?>" alt="Client Signature" 
                                                     class="signature-display">
                                                <input type="hidden" name="signature_file" value="<?php echo $signature_path; ?>">
                                            </div>
                                        <?php else: ?>
                                            <div class="no-signature text-center">
                                                <i class="fas fa-signature me-2"></i>
                                                No signature found for this project
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-submit btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>Submit Survey
                                </button>
                                <a href="../job/job-details.php?id=<?php echo $project_id; ?>" class="btn btn-secondary btn-lg ms-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </form>

                        <div class="footer mt-4">
                            <table class="customer-table">
                                <tr>
                                    <td><b>FRM.2801</b></td>
                                    <td><b>This document is property of CIMS and confidential. Do not share without approval.</b></td>
                                    <td><b>Page 1 of 1</b></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="<?php echo $url; ?>assets/js/jquery.min.js"></script>
<script src="<?php echo $url; ?>assets/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        let allAnswered = true;
        const errorMessages = [];
        const clientStatus = form.querySelectorAll('input[name="status"]');
        const clientStatusAnswered = Array.from(clientStatus).some(radio => radio.checked);
        if (!clientStatusAnswered) {
            allAnswered = false;
            errorMessages.push('Please select client status (New Client or Existing Client)');
        }
        const questionGroups = ['qualification_card','response_time','ppe','aramco_standards','overall_satisfaction'];
        questionGroups.forEach(group => {
            const radios = form.querySelectorAll(`input[name="${group}"]`);
            const answered = Array.from(radios).some(radio => radio.checked);
            if (!answered) {
                allAnswered = false;
                const questionNumber = questionGroups.indexOf(group) + 1;
                errorMessages.push(`Please answer question ${questionNumber}`);
            }
        });
        const evaluatedBy = form.querySelector('input[name="evaluated_by"]');
        if (!evaluatedBy.value.trim()) {
            allAnswered = false;
            errorMessages.push('Please enter your name in "Evaluated By" field');
        }
        if (!allAnswered) {
            e.preventDefault();
            alert('Please complete the following required fields:\n\n• ' + errorMessages.join('\n• '));
        }
    });
});
</script>
</body>
</html>