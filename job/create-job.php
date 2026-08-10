<?php
include_once('../inc/function.php');
include_once('../file/config.php');

// Restrict access to admin only
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard/");
    exit();
}

// Fetch the latest project number from the database
$projectQuery = "SELECT MAX(CAST(SUBSTRING(project_no, 5) AS UNSIGNED)) AS last_project_no FROM project_info";
$projectResult = $conn->query($projectQuery);

if ($projectResult && $projectResult->num_rows > 0) {
    $row = $projectResult->fetch_assoc();
    $lastProjectNo = $row['last_project_no'];
    $newProjectNo = intval($lastProjectNo) + 1;
} else {
    $newProjectNo = 1;
}

$formattedProjectNo = "CIMS" . str_pad($newProjectNo, 3, "0", STR_PAD_LEFT);

// Fetch customer names
$customerQuery = "SELECT * FROM customers ORDER BY customer_name ASC";
$customerResult = $conn->query($customerQuery);

// Fetch inspector names
$inspectorQuery = "SELECT * FROM inspectors ORDER BY inspector_name ASC";
$inspectorResult = mysqli_query($conn, $inspectorQuery);
?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
        min-height: 40px;
        padding: 8px 12px;
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

    /* Select2 Custom Styling to match theme-input-style */
    .select2-container .select2-selection--single {
        min-height: 40px;
        padding: 6px 12px;
        border: 1px solid rgba(148, 163, 184, 0.26);
        border-radius: 12px;
        background: rgba(255, 255, 255, 0.72);
        color: #111827;
        font-weight: 700;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.88);
        transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px;
        right: 14px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #111827;
        line-height: normal;
        padding-left: 0;
    }
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: rgba(37, 99, 235, 0.42);
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }
    .select2-dropdown {
        border: 1px solid rgba(37, 99, 235, 0.42);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.1);
        overflow: hidden;
        z-index: 9999;
    }
    .select2-search--dropdown .select2-search__field {
        border-radius: 6px;
        border: 1px solid rgba(148, 163, 184, 0.4);
        padding: 8px 12px;
        outline: none;
    }
    .select2-search--dropdown .select2-search__field:focus {
        border-color: rgba(37, 99, 235, 0.42);
    }
    .select2-results__option {
        padding: 10px 14px;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #2563eb;
    }
</style>

<!-- Main Content -->
<div class="main-content create-job-glass">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card mb-30 create-job-shell">
                    <div class="card-body">
                        <div class="create-job-hero">
                            <div class="create-job-title">
                                <span class="create-job-title-icon"><i class="icofont-briefcase"></i></span>
                                <div>
                                    <h4 class="font-20">Create New Project</h4>
                                    <p>Set up project details, customer information, inspector assignment, and checklist routing.</p>
                                </div>
                            </div>
                            <a href="overall-job-list.php">
                                <button type="button" class="btn btn-outline-primary"><i class="icofont-list"></i> View List</button>
                            </a>
                        </div>

                        <form id="projectForm" class="create-job-form" style="padding: 15px;">
                            <div class="row">
                                <!-- Project Data Section -->
                                <div class="col-lg-12 mb-15">
                                    <div class="create-job-section" style="padding: 15px 24px; min-height: auto;">
                                        <h4 class="font-16 create-job-section-title" style="margin-bottom: 15px; padding-bottom: 10px;"><i class="icofont-paper"></i> Project Data</h4>
                                        
                                        <div class="row">
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Project No <span class="text-danger">*</span></label>
                                                    <input type="text" name="project_no" class="theme-input-style" value="<?php echo htmlspecialchars($formattedProjectNo); ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Equipment Category</label>
                                                    <select name="equipment_type" class="theme-input-style">
                                                        <option value="Lifting Equipment">Lifting Equipment</option>
                                                        <option value="NDT Equipment">NDT Equipment</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Type of Inspection <span class="text-danger">*</span></label>
                                                    <select name="inspection_type" class="theme-input-style">
                                                        <option value="" disabled selected>Select Type of Inspection</option>
                                                        <option value="lifting">Below the Hook Lifting Gears</option>
                                                        <option value="eddycurrent">Eddy Current</option>
                                                        <option value="lmi">LMI</option>
                                                        <option value="withloadtest">Load Test</option>
                                                        <option value="liquidpenetrantinspection">LPI</option>
                                                        <option value="mobile">Mobile Crane with Load Test</option>
                                                        <option value="mpi">MPI</option>
                                                        <option value="healthcheck">Offshore Crane Health Check</option>
                                                        <option value="rocktest">RT</option>
                                                        <option value="loadtestwithload">Thorough Examination </option>
                                                        <option value="ut">UT</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Sticker / Non Sticker</label>
                                                    <select name="sticker_status" class="theme-input-style">
                                                        <option value="Yes">Yes</option>
                                                        <option value="No">No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Equipment Location</label>
                                                    <input type="text" name="equipment_location" class="theme-input-style" placeholder="Location">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Equipment ID <span class="text-danger">*</span></label>
                                                    <input type="text" name="equipment_id" class="theme-input-style" placeholder="Enter Equipment ID">
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Payment Mode</label>
                                                    <select name="payment_mode" class="theme-input-style">
                                                        <option value="Credit">Credit</option>
                                                        <option value="Cash">Cash</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Date of Creation</label>
                                                    <input type="date" name="creation_date" class="theme-input-style" value="<?php echo date('Y-m-d'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Customer & Inspector Section -->
                                <div class="col-lg-12 mb-15">
                                    <div class="create-job-section" style="padding: 15px 24px; min-height: auto;">
                                    <h4 class="font-16 create-job-section-title" style="margin-bottom: 15px; padding-bottom: 10px;"><i class="icofont-users-alt-4"></i> Customer & Inspector Details</h4>

                                        <div class="row">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Select Customer <span class="text-danger">*</span></label>
                                                    <select name="customer_id" id="customer-select" class="theme-input-style">
                                                        <option value="">Select Customer</option>
                                                        <?php
                                                        if ($customerResult && $customerResult->num_rows > 0) {
                                                            while ($row = $customerResult->fetch_assoc()) {
                                                                echo "<option value='" . $row['id'] . "'>" . htmlspecialchars($row['customer_name']) . "</option>";
                                                            }
                                                        } else {
                                                            echo "<option value='' disabled>No customers found</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Select Inspector <span class="text-danger">*</span></label>
                                                    <select name="inspector_name" id="inspector_select" class="theme-input-style">
                                                        <option value="" disabled selected>Select an Inspector</option>
                                                        <?php
                                                        if ($inspectorResult && mysqli_num_rows($inspectorResult) > 0) {
                                                            while ($row = mysqli_fetch_assoc($inspectorResult)) {
                                                                echo '<option value="' . htmlspecialchars($row['inspector_name']) . '">' . htmlspecialchars($row['inspector_name']) . '</option>';
                                                            }
                                                        } else {
                                                            echo '<option value="">No Inspectors Found</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6" id="crane-section">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Handle Crane</label>
                                                    <select id="crane_select" name="checklist_type" class="theme-input-style">
                                                        <option value="" disabled selected>Select a Crane</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Customer Email</label>
                                                    <input type="email" id="customer-email" name="email" class="theme-input-style" placeholder="Customer Email" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-sm-6">
                                                <div class="form-group mb-10">
                                                    <label class="font-14 bold mb-1">Customer Mobile</label>
                                                    <input type="number" id="customer-mobile" name="mobile" class="theme-input-style" placeholder="Customer Mobile" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="col-12 create-job-actions" style="margin-top: 5px; padding-top: 15px;">
                                    <button type="submit" id="submitBtn" class="btn btn-primary long">Save Project</button>
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
include_once('../inc/footer.php');
?>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize Select2 on dropdowns
    $('select').select2({
        width: '100%'
    });

    // Handle customer selection
    $('#customer-select').change(function() {
        var customerId = $(this).val();
        if (customerId) {
            $.ajax({
                url: 'fetch-customer-details.php',
                type: 'GET',
                data: { customer_id: customerId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#customer-email').val(response.email);
                        $('#customer-mobile').val(response.mobile);
                    } else {
                        $('#customer-email').val('');
                        $('#customer-mobile').val('');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        } else {
            $('#customer-email').val('');
            $('#customer-mobile').val('');
        }
    });

    // Handle inspector selection
    $('#inspector_select').change(function() {
        let inspectorName = $(this).val();
        if (inspectorName) {
            $.ajax({
                url: 'fetch_cranes.php',
                type: 'GET',
                data: { inspector_name: inspectorName },
                dataType: 'json',
                success: function(response) {
                    let optionsHtml = '<option value="">Select Crane</option>';
                    response.forEach(function(item) {
                        optionsHtml += `<option value="${item.value}">${item.label}</option>`;
                    });
                    $('#crane_select').html(optionsHtml).trigger('change');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching cranes:", error);
                }
            });
        } else {
            $('#crane_select').html('<option value="">Select Crane</option>').trigger('change');
        }
    });

    // Handle form submission
    $('#submitBtn').click(function(e) {
        e.preventDefault();
        
        // Validate required fields
        if (!$('#customer-select').val() || !$('#inspector_select').val() || !$('input[name="equipment_id"]').val() || !$('select[name="inspection_type"]').val() || !$('input[name="creation_date"]').val()) {
            Swal.fire({
                icon: "error",
                title: "Missing Information",
                text: "Please fill all required fields marked with *",
                confirmButtonColor: '#6045E2'
            });
            return;
        }

        Swal.fire({
            title: "Processing...",
            text: "Creating your project",
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: 'process-create-job.php',
            type: 'POST',
            data: $('#projectForm').serialize() + 
                  '&customer_id=' + $('#customer-select').val() + 
                  '&email=' + $('#customer-email').val() + 
                  '&mobile=' + $('#customer-mobile').val() +
                  '&inspector_name=' + $('#inspector_select').val() + 
                  '&checklist_type=' + $('#crane_select').val(),
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        icon: "success",
                        title: "Success!",
                        text: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        // Reload page to get next project number
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error!",
                        text: response.message,
                        confirmButtonColor: '#6045E2'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    icon: "error",
                    title: "System Error",
                    text: "An error occurred: " + error,
                    confirmButtonColor: '#6045E2'
                });
            }
        });
    });
});
</script>
