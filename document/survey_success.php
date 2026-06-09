<?php
// Include database configuration
include_once('../../file/config.php');

$project_id = isset($_GET['project_id']) ? htmlspecialchars($_GET['project_id']) : '';

if (empty($project_id)) {
    die("Project ID missing.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Submitted Successfully</title>
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: #f5f5f5;
            padding: 20px;
            font-family: Helvetica, Arial, sans-serif;
        }
        .success-container {
            max-width: 600px;
            margin: 50px auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 80px;
            color: #28a745;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <h2 class="text-success mb-3">Survey Submitted Successfully!</h2>
            <p class="mb-4">Thank you for taking the time to complete our customer satisfaction survey. Your feedback is valuable to us.</p>
            
            <div class="project-info bg-light p-3 rounded mb-4">
                <h5>Project Details</h5>
                <p class="mb-1"><strong>Project ID:</strong> <?php echo $project_id; ?></p>
                <p class="mb-0"><strong>Submission Date:</strong> <?php echo date('F d, Y'); ?></p>
            </div>
            
            <div class="action-buttons">
                <!--<a href="../project/view.php?id=<?php echo $project_id; ?>" class="btn btn-primary btn-lg">-->
                <!--    <i class="fas fa-arrow-left me-2"></i>Back to Project-->
                <!--</a>-->
                <a href="download_customer_survey.php?project_id=<?php echo $project_id; ?>" class="btn btn-success btn-lg" target="_blank">
                    <i class="fas fa-download me-2"></i>Download PDF
                </a>
            </div>
            
            <div class="mt-4">
                <small class="text-muted">
                    Your survey response has been recorded and will be reviewed by our team.
                </small>
            </div>
        </div>
    </div>
</body>
</html>