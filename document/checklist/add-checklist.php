<?php
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Check if 'project_no' is passed
if (isset($_GET['project_no'])) {
    $project_no = $_GET['project_no'];

    // Query to fetch data from project_info table
    $stmt = $conn->prepare("SELECT project_no, equipment_type, checklist_type, inspector_name, customer_name, equipment_location, equipment_id FROM project_info WHERE project_no = ?");
    $stmt->bind_param("s", $project_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $report_no = $row['project_no'];
        $equipment_type = $row['equipment_type'];
        $checklist_type = $row['checklist_type'];
        $inspected_by = $row['inspector_name'];
        $client_name = $row['customer_name'];
        $location = $row['equipment_location'];
        $equipment_no = $row['equipment_id'];        
    } else {
        echo "Invalid Project ID!";
        exit;
    }

    $stmt->close();
} else {
    echo "No project ID provided!";
    exit;
}

// Fetch available stickers for the inspector (if project_no is not assigned)
$stickers = [];
if (!empty($inspected_by)) {
    $sticker_stmt = $conn->prepare("SELECT sticker_start_no FROM stickers WHERE assign_inspector = ? AND (project_no IS NULL OR project_no = '' OR project_no = '0') ORDER BY sticker_start_no ASC");
    $sticker_stmt->bind_param("s", $inspected_by);
    $sticker_stmt->execute();
    $sticker_result = $sticker_stmt->get_result();
    while ($sticker_row = $sticker_result->fetch_assoc()) {
        $stickers[] = $sticker_row['sticker_start_no'];
    }
    $sticker_stmt->close();
}

// Fetch the latest checklist_no from the database
// Replace the checklist_no generation code with this:
$checklistQuery = "SELECT MAX(CAST(checklist_no AS UNSIGNED)) AS last_checklist_no FROM checklist_information";
$checklistResult = $conn->query($checklistQuery);

if ($checklistResult && $checklistResult->num_rows > 0) {
    $row = $checklistResult->fetch_assoc();
    $lastChecklistNo = $row['last_checklist_no'];
    $newChecklistNo = intval($lastChecklistNo) + 1;
} else {
    $newChecklistNo = 1;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checklist Form</title>
    <link rel="stylesheet" href="../../assets/css/custom-style.css">
<style>
    .create-job-glass {
        position: relative;
        min-height: calc(100vh - 110px);
        padding: 6px 10px 46px;
        background:
            radial-gradient(circle at 12% 6%, rgba(20, 184, 166, 0.16), transparent 28%),
            radial-gradient(circle at 92% 8%, rgba(37, 99, 235, 0.13), transparent 26%),
            linear-gradient(135deg, #f7fbff 0%, #eef4fb 48%, #f8fafc 100%);
        overflow: hidden;
    }

    .create-job-glass:before {
        content: "";
        position: fixed;
        right: 6%;
        top: 140px;
        width: 340px;
        height: 340px;
        border-radius: 999px;
        background: rgba(20, 184, 166, 0.1);
        filter: blur(4px);
        pointer-events: none;
        z-index: -1;
    }

    .create-job-glass .container-fluid {
        max-width: 1500px;
    }

    .create-job-shell {
        border: 1px solid rgba(255, 255, 255, 0.62);
        border-radius: 24px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.78), rgba(255, 255, 255, 0.48));
        box-shadow: 0 28px 70px rgba(15, 23, 42, 0.14);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        overflow: hidden;
    }

    .create-job-shell .card-body {
        padding: 0;
    }

    .create-job-hero {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 18px;
        padding: 28px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.18);
        background:
            radial-gradient(circle at top right, rgba(37, 99, 235, 0.1), transparent 36%),
            linear-gradient(135deg, rgba(255, 255, 255, 0.72), rgba(255, 255, 255, 0.36));
    }

    .create-job-title {
        display: flex;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .create-job-title-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 18px;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.16), rgba(20, 184, 166, 0.14));
        color: #2563eb;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.9), 0 16px 32px rgba(15, 23, 42, 0.1);
        font-size: 24px;
        flex: 0 0 auto;
    }

    .create-job-title h4 {
        margin-bottom: 7px;
        color: #111827;
        font-size: clamp(24px, 2vw, 34px);
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
    }

    .create-job-title p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.45;
    }

    .create-job-glass .btn-outline-primary,
    .create-job-glass .btn-primary {
        min-height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        border-radius: 12px;
        font-weight: 800;
        box-shadow: 0 16px 32px rgba(37, 99, 235, 0.14);
        transition: transform .2s ease, box-shadow .2s ease;
    }

    .create-job-glass .btn-outline-primary {
        padding: 11px 18px;
        border: 1px solid rgba(37, 99, 235, 0.24);
        background: rgba(255, 255, 255, 0.62);
        color: #1d4ed8;
    }

    .create-job-glass .btn-primary {
        min-width: 190px;
        padding: 13px 24px;
        border: 0;
        background: linear-gradient(135deg, #2563eb 0%, #16a3d8 52%, #14b8a6 100%);
        color: #fff;
        box-shadow: 0 18px 34px rgba(37, 99, 235, 0.26);
    }

    .create-job-glass .btn-outline-primary:hover,
    .create-job-glass .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 22px 42px rgba(20, 184, 166, 0.2);
    }

    .create-job-form {
        padding: 28px;
    }

    .create-job-section {
        min-height: 100%;
        padding: 24px;
        border: 1px solid rgba(255, 255, 255, 0.62);
        border-radius: 20px;
        background: rgba(255, 255, 255, 0.48);
        box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
        backdrop-filter: blur(14px);
        -webkit-backdrop-filter: blur(14px);
    }

    .create-job-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 22px;
        padding-bottom: 14px;
        border-bottom: 1px solid rgba(148, 163, 184, 0.2);
        color: #111827;
        font-size: 17px;
        font-weight: 800;
        letter-spacing: 0;
        text-transform: none;
    }

    .create-job-section-title i {
        width: 34px;
        height: 34px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 11px;
        background: rgba(20, 184, 166, 0.14);
        color: #0f766e;
    }

    .create-job-glass .form-group {
        margin-bottom: 18px;
    }

    .create-job-glass label {
        display: block;
        margin-bottom: 8px;
        color: #334155;
        font-size: 13px;
        font-weight: 800;
    }

    .create-job-glass .theme-input-style {
        width: 100%;
        min-height: 48px;
        padding: 12px 14px;
        border: 1px solid rgba(148, 163, 184, 0.26);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.72);
        color: #111827;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
        font-weight: 700;
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .create-job-glass .theme-input-style:focus {
        border-color: rgba(37, 99, 235, 0.42);
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .create-job-glass .theme-input-style[readonly] {
        background: rgba(241, 245, 249, 0.78);
        color: #475569;
    }

    .create-job-actions {
        margin-top: 30px;
        padding-top: 24px;
        border-top: 1px solid rgba(148, 163, 184, 0.18);
        text-align: center;
    }

    @media (max-width: 991px) {
        .create-job-form {
            padding: 22px;
        }

        .create-job-section {
            padding: 20px;
        }
    }

    @media (max-width: 767px) {
        .create-job-glass {
            padding: 0 0 32px;
        }

        .create-job-shell {
            border-radius: 18px;
        }

        .create-job-hero {
            flex-direction: column;
            align-items: stretch;
            padding: 22px;
        }

        .create-job-title {
            align-items: flex-start;
        }

        .create-job-title-icon {
            width: 50px;
            height: 50px;
            border-radius: 15px;
        }

        .create-job-hero a,
        .create-job-hero button,
        .create-job-glass .btn-primary {
            width: 100%;
        }

        .create-job-form {
            padding: 18px;
        }
    }
</style>
</head>
<body>
    <div class="main-content create-job-glass">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="card mb-30 create-job-shell">
                        <div class="card-body">
                            <div class="create-job-hero">
                                <div class="create-job-title">
                                    <span class="create-job-title-icon"><i class="icofont-check-alt"></i></span>
                                    <div>
                                        <h4 class="font-20">Add Checklist</h4>
                                        <p>Enter checklist information and equipment details.</p>
                                    </div>
                                </div>
                                <a href="index.php">
                                    <button type="button" class="btn btn-outline-primary"><i class="icofont-list"></i> View Checklists</button>
                                </a>
                            </div>

                            <form action="save_checklist.php" method="POST" class="create-job-form">
                            <input type="hidden" name="project_no" value="<?php echo $project_no; ?>">
                            <input type="hidden" name="checklist_no" value="<?= $newChecklistNo ?>">

                            <div class="row">
                                <div class="col-lg-6 mb-30">
                                    <div class="create-job-section">
                                        <h4 class="font-16 create-job-section-title"><i class="icofont-paper"></i> Information</h4>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Checklist NO</label>
                                        <input type="text" class="theme-input-style" value="<?php echo htmlspecialchars($newChecklistNo); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">REPORT NO</label>
                                        <input type="text" name="report_no" class="theme-input-style" value="<?php echo htmlspecialchars($report_no); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">CLIENT'S NAME</label>
                                        <input type="text" name="client_name" class="theme-input-style" value="<?php echo htmlspecialchars($client_name); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">LOCATION</label>
                                        <input type="text" name="location" class="theme-input-style" value="<?php echo htmlspecialchars($location); ?>" readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">EQUIPMENT NO</label>
                                        <input type="text" name="equipment_no" class="theme-input-style" value="<?php echo htmlspecialchars($equipment_no); ?>">
                                    </div>

                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">EQUIPMENT CATEGORY</label>
                                        <input type="text" name="equipment_type" class="theme-input-style" value="<?php echo htmlspecialchars($equipment_type); ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">MANUFACTURER</label>
                                        <input type="text" name="manufacturer" class="theme-input-style">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">VESSEL NAME</label>
                                        <input type="text" name="vessel_name" class="theme-input-style">
                                    </div>

                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">EQUIPMENT TYPE</label>
                                        <input type="text" name="equipmenttype" class="theme-input-style">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">MODEL NO.</label>
                                        <input type="text" name="model_no" class="theme-input-style" required>
                                    </div>
                                </div>
                                    </div>

                                <div class="col-lg-6 mb-30">
                                    <div class="create-job-section">
                                        <h4 class="font-16 create-job-section-title"><i class="icofont-settings"></i> Details</h4>
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">Checklist Type</label>
                                        <input type="text" name="checklist_type" class="theme-input-style" value="<?php echo htmlspecialchars($checklist_type); ?>" readonly>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">INSPECTION DATE</label>
                                        <input type="date" name="inspection_date" class="theme-input-style" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">INSPECTED BY</label>
                                        <input type="text" name="inspected_by" class="theme-input-style" value="<?php echo htmlspecialchars($inspected_by); ?>" readonly>
                                    </div>
                                    
                                    <!--<div class="form-group">-->
                                    <!--    <label class="font-14 bold mb-2">STICKER NO</label>-->
                                    <!--    <input type="text" name="sticker_no" class="theme-input-style">-->
                                    <!--</div>-->
                                    
                                    <div class="form-group" id="stickerNoField">
    <label class="font-14 bold mb-2">STICKER NO</label>
    <?php if ($equipment_type === "NDT Equipment") { ?>
        <input type="text" name="sticker_no" class="theme-input-style" value="NA" readonly>
    <?php } else { ?>
        <select name="sticker_no" class="theme-input-style">
            <option value="">Select Sticker No</option>
            <?php foreach ($stickers as $st_no) { ?>
                <option value="<?php echo htmlspecialchars($st_no); ?>"><?php echo htmlspecialchars($st_no); ?></option>
            <?php } ?>
        </select>
    <?php } ?>
</div>

                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">CRANE SERIAL NO.</label>
                                        <input type="text" name="crane_serial_no" class="theme-input-style" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">CAPACITY (SWL)</label>
                                        <input type="text" name="capacity_swl" class="theme-input-style" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="font-14 bold mb-2">YEAR MODEL</label>
                                        <input type="text" name="year_model" class="theme-input-style">
                                    </div>
                                </div>
                                    </div>
                            </div>

                            <div class="form-row">
                                <div class="col-12 create-job-actions">
                                    <button type="submit" class="btn btn-primary long">Submit</button>
                                </div>
                            </div>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php 
    include_once('../../inc/footer.php');
    ?>
    
    <script>
document.addEventListener("DOMContentLoaded", function () {
    var equipmentType = <?php echo json_encode($equipment_type); ?>;
    var stickerInput = document.querySelector("[name='sticker_no']");

    if (equipmentType === "NDT Equipment") {
        if (stickerInput) {
            stickerInput.value = "NA";
            if (stickerInput.tagName === "INPUT") {
                stickerInput.readOnly = true;
            }
        }
    }
});
</script>

</body>
</html>