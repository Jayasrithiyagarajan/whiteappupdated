<?php
include_once('../../file/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $project_no = isset($_POST['project_no']) ? trim($_POST['project_no']) : '';

    if (!empty($project_no)) {
        $status_check = $conn->prepare("SELECT checklist_status FROM project_info WHERE project_no = ?");
        $status_check->bind_param("s", $project_no);
        $status_check->execute();
        $status_result = $status_check->get_result();

        if ($status_result->num_rows > 0) {
            $row = $status_result->fetch_assoc();

            if ($row['checklist_status'] === 'Pending') {
                // Retrieve form data
                $checklist_no = $_POST['checklist_no'];
                $report_no = $_POST['report_no'];
                $client_name = $_POST['client_name'];
                $location = $_POST['location'];
                $equipment_type = $_POST['equipment_type'];
                $checklist_type = $_POST['checklist_type'];
                $inspection_date = $_POST['inspection_date'];
                $inspected_by = $_POST['inspected_by'];
                $sticker_no = $_POST['sticker_no'];
                $crane_serial_no = $_POST['crane_serial_no'];
                $capacity_swl = $_POST['capacity_swl'];
                $manufacturer = $_POST['manufacturer'];
                $year_model = $_POST['year_model'];
                $equipment_no = $_POST['equipment_no'];
                $vessel_name = $_POST['vessel_name'];
                $model_no = $_POST['model_no'];
                $equipmenttype = $_POST['equipmenttype'];

                // Check if checklist_no already exists
                $check_stmt = $conn->prepare("SELECT checklist_no FROM checklist_information WHERE checklist_no = ?");
                $check_stmt->bind_param("s", $checklist_no);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result->num_rows > 0) {
                    // Generate a new checklist_no
                    $max_stmt = $conn->query("SELECT MAX(CAST(checklist_no AS UNSIGNED)) AS max_no FROM checklist_information");
                    $max_row = $max_stmt->fetch_assoc();
                    $checklist_no = $max_row['max_no'] + 1;
                    $max_stmt->close();
                }
                $check_stmt->close();

                // Insert main checklist data
                $sql = "INSERT INTO checklist_information 
                        (checklist_no, report_no, client_name, location, equipment_type, checklist_type, 
                        inspection_date, inspected_by, sticker_no, crane_serial_no, capacity_swl, 
                        manufacturer, year_model, equipment_no, model_no, equipmenttype, vessel_name, project_no) 
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('ssssssssssssssssss', 
                    $checklist_no, $report_no, $client_name, $location, $equipment_type, 
                    $checklist_type, $inspection_date, $inspected_by, $sticker_no, $crane_serial_no, 
                    $capacity_swl, $manufacturer, $year_model, $equipment_no, $model_no, $equipmenttype, $vessel_name, $project_no);

                if ($stmt->execute()) {
                    $checklist_id = $conn->insert_id;
                    
                    // Update checklist_status to 'Created' in project_info
                    $update_status = $conn->prepare("UPDATE project_info SET checklist_status = 'Created' WHERE project_no = ?");
                    $update_status->bind_param("s", $project_no);
                    $update_status->execute();
                    $update_status->close();

                    // Construct the edit URL
                    $edit_url = "./type/{$checklist_type}.php?checklist_type={$checklist_type}&&checklist_no={$checklist_id}";

                    echo "<script>alert('Checklist created successfully!'); window.location.href='{$edit_url}';</script>";
                } else {
                    echo "<script>alert('Error saving checklist information: " . $stmt->error . "'); window.history.back();</script>";
                }

                $stmt->close();
            } else {
                echo "<script>alert('Checklist has already been created for this project.'); window.location.href='index.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid Project ID.'); window.history.back();</script>";
        }
        $status_check->close();
    } else {
        echo "<script>alert('Invalid Project ID received.'); window.history.back();</script>";
    }
} else {
    echo "Form submission error.";
}

$conn->close();
?>