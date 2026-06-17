<?php 
include_once('../../inc/function.php');
include_once('../../file/config.php');

// Check if 'project_no' is passed
if (isset($_GET['project_no'])) {
    $project_no = $_GET['project_no'];

    // Query to fetch checklist_no based on project_no
    // $query = "SELECT checklist_no FROM checklist_information WHERE project_no = '$project_no' LIMIT 1";

    $query = "
    SELECT c.checklist_no, c.client_name, c.location, c.equipment_type, c.inspection_date, c.report_no, c.manufacturer, c.capacity_swl, c.sticker_no, c.crane_serial_no, c.model_no, c.equipmenttype,
           p.project_no, p.creation_date, p.sticker_status, p.customer_name, p.equipment_location,
           p.inspector_name, p.checklist_type, p.equipment_id
    FROM checklist_information c
    JOIN project_info p ON c.project_no = p.project_no
    WHERE c.project_no = '$project_no' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $checklist_no = $row['checklist_no'];
        $client_name = $row['client_name'];
        $location = $row['location'];
        $equipment_type = $row['equipment_type'];
        $inspection_date = $row['inspection_date'];
        $report_no = $row['report_no'];
        $manufacturer = $row['manufacturer'];    
        $capacity_swl = $row['capacity_swl'];
        $sticker_no = $row['sticker_no'];
        $crane_serial_no = $row['crane_serial_no'];
        $project_no = $row['project_no'];
        $creation_date = $row['creation_date'];
        $sticker_status = $row['sticker_status'];
        $customer_name = $row['customer_name'];
        $equipment_location = $row['equipment_location'];
        $inspector_name = $row['inspector_name'];
        $checklist_type = $row['checklist_type'];
        $equipment_id = $row['equipment_id'];
        $model_no = $row['model_no'];
        $equipmenttype = $row['equipmenttype'];
    } else {
        echo "No checklist found for the provided Project ID!";
        exit;
    }
} else {
    echo "No project ID provided!";
    exit;
}
?>


            <!-- Main Content -->
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
<div class="main-content create-job-glass">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mb-30 create-job-shell">
                    <div class="card-body">
                        <div class="create-job-hero">
                            <div class="create-job-title">
                                <span class="create-job-title-icon"><i class="icofont-paper"></i></span>
                                <div>
                                    <h4 class="font-20">Create Report</h4>
                                    <p>Enter general information and deficiencies for the report.</p>
                                </div>
                            </div>
                            <a href="index.php" target="_blank">
                                <button type="button" class="btn btn-outline-primary"><i class="icofont-list"></i> View List</button>
                            </a>
                        </div>
                        <form action="save_report.php" method="POST" class="create-job-form">
                            <div class="row">
                                <div class="col-lg-6 mb-30">
                                    <div class="create-job-section">
                                        <h4 class="font-16 create-job-section-title"><i class="icofont-info-circle"></i> General Information</h4>                    

                <div class="form-group">
                    <label class="font-14 bold mb-2">Client Company / Name</label>
                    <!-- <input type="text" class="theme-input-style" placeholder="Enter client company name" name="client_company_name" required> -->
                    <input type="text" class="theme-input-style" value="<?php echo htmlspecialchars($client_name); ?>" name="client_company_name" required>
                </div>

                <!--<div class="form-group">
                    <label class="font-14 bold mb-2">Client Company Address</label>
                    <textarea class="theme-input-style" placeholder="Enter company address" name="client_company_address" required></textarea>
                </div>-->

                <div class="form-group">
                    <label class="font-14 bold mb-2">Manufacturer</label>
                    <input type="text" class="theme-input-style" placeholder="Enter manufacturer" value="<?php echo htmlspecialchars($manufacturer); ?>" name="manufacturer" required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Model</label>
                    <input type="text" class="theme-input-style" placeholder="Enter model" value="<?php echo htmlspecialchars($model_no); ?>" name="model" required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Type</label>
                    <input type="text" class="theme-input-style" placeholder="Enter type" value="<?php echo htmlspecialchars($equipmenttype); ?>" name="type" required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Previous Sticker S.No.</label>
                    <input type="text" class="theme-input-style" placeholder="Enter previous sticker serial number" name="prev_sticker_no" required>
                </div>
                
                 <div class="form-group">
                    <label class="font-14 bold mb-2">Issued Company</label>
                    <input type="text" class="theme-input-style" placeholder="Enter Issued Company" name="issued_company">
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Inspector</label>
                    <input type="text" class="theme-input-style" placeholder="Inspector" value="<?php echo htmlspecialchars($inspector_name); ?>" name="issued_by" required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Capacity</label>
                    <input type="text" class="theme-input-style" placeholder="Enter capacity" value="<?php echo htmlspecialchars($capacity_swl); ?>" name="capacity" required>                    
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Report No</label>
                    <input type="text" class="theme-input-style" value="<?php echo htmlspecialchars($report_no); ?>" name="report_no" readonly required>
                </div>
                
               

                                                </div>
                                </div>
                                <div class="col-lg-6 mb-30">
                                    <div class="create-job-section">
                                        <h4 class="font-16 create-job-section-title"><i class="icofont-settings"></i> Additional Information</h4>                        
                <div class="form-group">
                    <label class="font-14 bold mb-2">Equipment Identification Number</label>
                    <input type="text" class="theme-input-style" placeholder="Enter identification number" value="<?php echo htmlspecialchars($equipment_id); ?>" name="equipment_id_no" required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Equipment Serial Number</label>
                    <input type="text" class="theme-input-style" placeholder="Enter serial number" name="equipment_serial_no" value="<?php echo htmlspecialchars($crane_serial_no); ?>" required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Location</label>
                    <!-- <input type="text" class="theme-input-style" placeholder="Enter location" name="location" required> -->
                    <input type="text" class="theme-input-style" value="<?php echo htmlspecialchars($location); ?>" name="location" required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Date of Inspection</label>
                    <!-- <input type="date" class="theme-input-style" name="date_of_inspection" required> -->                    
                    <input type="date" class="theme-input-style" value="<?php echo htmlspecialchars($inspection_date); ?>" name="date_of_inspection" id="date_of_inspection" required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Expiry Selection</label>
                    <select class="theme-input-style" id="expiry_selection" name="expiry_selection">
                        <option value="">Select Expiry Period</option>
                        <option value="6_months">6 Months</option>
                        <option value="1_year">1 Year</option>
                        <option value="5_years">5 Years</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Next Inspection Due Date</label>
                    <input type="date" class="theme-input-style" name="next_inspection_due_date" id="next_inspection_due_date" required>
                </div>

                <!--<div class="form-group">-->
                <!--    <label class="font-14 bold mb-2">Inspection Status</label>-->
                <!--    <div>-->
                <!--        <label><input type="radio" name="inspection_status" value="Passed"> Passed</label>-->
                <!--        <label><input type="radio" name="inspection_status" value="Failed"> Failed</label>-->
                <!--    </div>-->
                <!--</div>-->
                
                


<div class="form-group">
    <label class="font-14 bold mb-2">Inspection Status</label>
    <div>
        <label><input type="radio" name="inspection_status" value="Passed" required> Passed</label>
        <label><input type="radio" name="inspection_status" value="Failed" required> Failed</label>
    </div>
</div>



                <!--<div class="form-group">-->
                <!--    <label class="font-14 bold mb-2">Sticker Number Issued</label>-->
                <!--    <input type="text" class="theme-input-style" placeholder="Enter sticker number issued" value="<?php echo htmlspecialchars($sticker_no); ?>" name="sticker_number_issued" readonly required>                    -->
                <!--</div>-->
                
                
    <!--<div class="form-group">-->
    <!--    <label class="font-14 bold mb-2">Sticker Number Issued</label>-->
    <!--    <input type="text" class="theme-input-style" placeholder="Enter sticker number issued" value="<?php echo htmlspecialchars($sticker_no); ?>" name="sticker_number_issued" readonly required>                    -->
    <!--</div>-->
    
    
    <?php if (strtolower(trim($equipment_type)) === 'ndt equipment'): ?>
    <div class="form-group">
        <label class="font-14 bold mb-2">Sticker Number Issued</label>
        <input type="text" class="theme-input-style" value="NA" name="sticker_number_issued" required>
    </div>
<?php else: ?>
    <div class="form-group">
        <label class="font-14 bold mb-2">Sticker Number Issued</label>
        <input type="text" class="theme-input-style" placeholder="Enter sticker number issued" value="<?php echo htmlspecialchars($sticker_no); ?>" name="sticker_number_issued" required>                    
    </div>
<?php endif; ?>




                <div class="form-group">
                    <label class="font-14 bold mb-2">Project ID</label>
                    <input type="text" class="theme-input-style" value="<?php echo htmlspecialchars($project_no); ?>" name="project_no" readonly required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">Checklist No</label>
                    <input type="text" class="theme-input-style" value="<?php echo htmlspecialchars($checklist_no); ?>" name="checklist_no" readonly required>
                </div>

                <div class="form-group">
                    <label class="font-14 bold mb-2">No. of Equipments Inspected</label>
                    <input 
                        type="number" 
                        class="theme-input-style" 
                        name="no_of_equipments_inspected" 
                        placeholder="Enter number of equipments inspected"
                        min="1"
                        required
                    >
                </div>

              


                <!--<div class="form-group">-->
                <!--    <label class="font-14 bold mb-2">JRN</label>-->
                <!--    <input type="text" class="theme-input-style" placeholder="Enter JRN" name="jrn">-->
                <!--</div>-->

                                                </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mb-30">
                                    <div class="create-job-section">
                                        <h4 class="font-16 create-job-section-title"><i class="icofont-warning"></i> Deficiencies</h4>
                                        <div class="row">
                                            <div class="col-lg-12">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Deficiencies</th>
                    <th>Corrective Action Taken</th>
                </tr>
            </thead>
            <tbody>
                <tr>
    <td>1</td>
    <td><textarea class="theme-input-style" name="deficiency" placeholder="Enter deficiency" rows="5"></textarea></td>
    <td><textarea class="theme-input-style" name="corrective_action" placeholder="Enter corrective action" rows="5"></textarea></td>
</tr>

                <!--<tr>-->
                <!--    <td>2</td>-->
                <!--    <td><input type="text" class="theme-input-style" name="deficiency_2" placeholder="Enter deficiency"></td>-->
                <!--    <td><input type="text" class="theme-input-style" name="corrective_action_2" placeholder="Enter corrective action"></td>-->
                <!--</tr>-->
                <!--<tr>-->
                <!--    <td>3</td>-->
                <!--    <td><input type="text" class="theme-input-style" name="deficiency_3" placeholder="Enter deficiency"></td>-->
                <!--    <td><input type="text" class="theme-input-style" name="corrective_action_3" placeholder="Enter corrective action"></td>-->
                <!--</tr>-->
            </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

<div class="form-row">
    <div class="col-12 create-job-actions">
        <button type="submit" class="btn btn-primary long" value="Save Certificate">Save All</button>
    </div>
</div>

 <!-- JavaScript for Adding Rows -->
 <!-- <script>
            function addRow() {
                const table = document.getElementById('deficiencyTable');
                const rowCount = table.rows.length + 1;
                const newRow = `
                    <tr>
                        <td>${rowCount}</td>
                        <td><textarea class="theme-input-style" name="deficiency[]" placeholder="Enter deficiency"></textarea></td>
                        <td><textarea class="theme-input-style" name="corrective_action[]" placeholder="Enter corrective action"></textarea></td>
                    </tr>
                `;
                table.insertAdjacentHTML('beforeend', newRow);
            }
        </script> -->
        
</form>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const inspectionDateInput = document.getElementById('date_of_inspection');
    const expirySelectionInput = document.getElementById('expiry_selection');
    const nextDueDateInput = document.getElementById('next_inspection_due_date');

    function calculateExpiry() {
        const inspectionDateVal = inspectionDateInput.value;
        const expirySelectionVal = expirySelectionInput.value;

        if (inspectionDateVal && expirySelectionVal) {
            const date = new Date(inspectionDateVal);
            if (!isNaN(date.getTime())) {
                if (expirySelectionVal === '6_months') {
                    date.setMonth(date.getMonth() + 6);
                } else if (expirySelectionVal === '1_year') {
                    date.setFullYear(date.getFullYear() + 1);
                } else if (expirySelectionVal === '5_years') {
                    date.setFullYear(date.getFullYear() + 5);
                }
                
                // Subtract 1 day
                date.setDate(date.getDate() - 1);
                
                const yyyy = date.getFullYear();
                let mm = date.getMonth() + 1;
                let dd = date.getDate();

                if (dd < 10) dd = '0' + dd;
                if (mm < 10) mm = '0' + mm;

                nextDueDateInput.value = yyyy + '-' + mm + '-' + dd;
            }
        }
    }

    if (inspectionDateInput && expirySelectionInput && nextDueDateInput) {
        inspectionDateInput.addEventListener('change', calculateExpiry);
        expirySelectionInput.addEventListener('change', calculateExpiry);
    }
});
</script>
</div></div></div></div></div></div>
<!-- End Main Content -->

<?php 
include_once('../../inc/footer.php');
?>