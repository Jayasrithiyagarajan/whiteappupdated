<?php
include_once('../../file/config.php');

// Ensure both project_no is provided
if (isset($_GET['project_no'])) {
    $project_no = $_GET['project_no'];

    // Query to fetch project info
    $query_project = "SELECT * FROM project_info WHERE project_no = ?";
    $stmt_project = $conn->prepare($query_project);
    $stmt_project->bind_param("s", $project_no);
    $stmt_project->execute();
    $result_project = $stmt_project->get_result();

    if ($result_project && $result_project->num_rows > 0) {
        $project_data = $result_project->fetch_assoc();
    }
    else {
        echo "Project not found!";
        exit;
    }
    $stmt_project->close();

    // Query to fetch checklist information
    $query_checklist = "SELECT * FROM checklist_information WHERE project_no = ?";
    $stmt_checklist = $conn->prepare($query_checklist);
    $stmt_checklist->bind_param("s", $project_no);
    $stmt_checklist->execute();
    $result_checklist = $stmt_checklist->get_result();

    if ($result_checklist && $result_checklist->num_rows > 0) {
        $checklist_data = $result_checklist->fetch_assoc();
    }
    else {
        echo "Checklist not found!";
        exit;
    }
    $stmt_checklist->close();

    // Fetch checklist results
    $query_results = "SELECT * FROM checklist_results WHERE project_no = ?";
    $stmt_results = $conn->prepare($query_results);
    $stmt_results->bind_param("s", $project_no);
    $stmt_results->execute();
    $result_results = $stmt_results->get_result();

    if ($result_results && $result_results->num_rows > 0) {
        $results_data = $result_results->fetch_assoc();
    }
    else {
        echo "Checklist results not found!";
        exit;
    }
    $stmt_results->close();

}
else {
    echo "Project ID is required!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Preview - <?php echo htmlspecialchars($project_no); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #277bbe;
            --secondary-color: #f8f9fa;
            --accent-color: #007bff;
            --text-dark: #333;
            --text-light: #666;
            --border-radius: 12px;
            --card-shadow: 0 8px 30px rgba(0,0,0,0.05);
        }

        body {
            background-color: #f4f7f6;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: var(--text-dark);
        }

        .preview-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }

        .premium-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            border: none;
            overflow: hidden;
            border-top: 5px solid var(--primary-color);
        }

        .card-header-premium {
            background: white;
            padding: 30px 40px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-body-premium {
            padding: 40px;
        }

        .checklist-title {
            font-weight: 700;
            color: var(--primary-color);
            margin: 0;
            font-size: 1.5rem;
            text-transform: uppercase;
        }

        .project-badge {
            background: rgba(39, 123, 190, 0.1);
            color: var(--primary-color);
            padding: 6px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 24px;
            margin-bottom: 40px;
        }

        .info-item {
            padding: 15px;
            background: var(--secondary-color);
            border-radius: 8px;
            transition: transform 0.2s;
        }

        .info-item:hover {
            transform: translateY(-2px);
        }

        .info-label {
            font-size: 0.75rem;
            color: var(--text-light);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 4px;
            font-weight: 600;
        }

        .info-value {
            font-size: 1rem;
            color: var(--text-dark);
            font-weight: 600;
        }

        .action-bar {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
        }

        .btn-premium {
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .btn-download {
            background-color: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-download:hover {
            background-color: #1e5e91;
            box-shadow: 0 4px 15px rgba(39, 123, 190, 0.3);
            color: white;
        }

        .btn-back {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-back:hover {
            background-color: var(--primary-color);
            color: white;
        }

        .status-passed {
            color: #28a745;
            font-weight: bold;
        }

        .status-failed {
            color: #dc3545;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
            .card-header-premium {
                flex-direction: column;
                text-align: center;
                gap: 15px;
            }
        }
    </style>
</head>
<body>

<div class="preview-container">
    <div class="premium-card">
        <div class="card-header-premium">
            <h2 class="checklist-title">Checklist Preview</h2>
            <span class="project-badge">Project #<?php echo htmlspecialchars($project_no); ?></span>
        </div>
        
        <div class="card-body-premium">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">Checklist Number</div>
                    <div class="info-value"><?php echo htmlspecialchars($checklist_data['checklist_id']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Checklist Type</div>
                    <div class="info-value"><?php echo ucwords(str_replace(['-', '_'], ' ', htmlspecialchars($project_data['checklist_type']))); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Customer Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($project_data['customer_name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Inspector</div>
                    <div class="info-value"><?php echo htmlspecialchars($project_data['inspector_name']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Equipment Location</div>
                    <div class="info-value"><?php echo htmlspecialchars($project_data['equipment_location']); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Inspection Date</div>
                    <div class="info-value"><?php echo date('d-m-Y', strtotime($checklist_data['inspection_date'])); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Equipment Serial No</div>
                    <div class="info-value"><?php echo htmlspecialchars($checklist_data['crane_serial_no'] ?: 'N/A'); ?></div>
                </div>
                <div class="info-item">
                    <div class="info-label">Sticker Number</div>
                    <div class="info-value"><?php echo htmlspecialchars($checklist_data['sticker_no'] ?: 'N/A'); ?></div>
                </div>
            </div>

            <div class="action-bar no-print">
                <button onclick="window.close()" class="btn btn-premium btn-back">
                    <i class="fa-solid fa-arrow-left"></i> Back to Dashboard
                </button>
                <a href="download.php?project_no=<?php echo urlencode($project_no); ?>" class="btn btn-premium btn-download">
                    <i class="fa-solid fa-file-pdf"></i> Download PDF Report
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
