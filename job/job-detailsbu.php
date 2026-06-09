<?php
include_once('../file/config.php');
// include '../file/auth.php';
include_once('../inc/function.php');

$userRole = $_SESSION['user_role'] ?? ''; // Default to empty if not set

// Check if the user is a reviewer
$isReviewer = ($userRole === 'reviewer');

// Check if project_no is set in the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $data_id = $_GET['id'];

    // Query to fetch project, checklist, and report details using JOIN
    $query = "
        SELECT 
            p.project_no, p.creation_date, p.project_status, p.equipment_location, p.customer_mobile, p.customer_email, p.checklist_status, p.report_status, p.certificatestatus,
            c.checklist_no, c.client_name, c.crane_serial_no, c.inspected_by, c.created_at, c.checklist_type, c.checklist_id,
            r.report_no, r.sticker_number_issued, r.inspection_status
        FROM project_info p
        LEFT JOIN checklist_information c ON p.project_no = c.project_no
        LEFT JOIN reports r ON p.project_no = r.project_no
        WHERE p.project_no = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $data_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Determine checklist and report creation status
        $checklistCreated = isset($data['checklist_no']);
        $reportCreated = isset($data['report_no']);

        // Extract the certificatestatus from the database
        $certificateStatus = $data['certificatestatus'];
        // Retrieve checklist_status and report_status directly from the joined data
        
        $checklistStatus = $data['checklist_status'];
        $dataStatus = $data['report_status'];

        // Determine if the "Add Certificate" button should be enabled
        $enableAddCertificate = ($checklistStatus === "Created" && $dataStatus === "Generated");
    } else {
        echo "No details found for this project.";
        exit;
    }

    // Query to fetch certificate data
    $query = "
        SELECT 
            'healthcheck' AS certificate_type,
            hc.certificate_no, hc.created_at
        FROM crane_health_check_certificate hc
        WHERE hc.project_no = ?        

        UNION

        SELECT 
            'loadtestwithload' AS certificate_type,
            lw.certificate_no, lw.created_at
        FROM loadtest_certificate lw
        WHERE lw.project_no = ?

        UNION

        SELECT 
            'mobile' AS certificate_type,
            mc.certificate_no, mc.created_at
        FROM mobile_crane_loadtest mc
        WHERE mc.project_no = ?


        UNION

        SELECT 
            'lifting' AS certificate_type,
            lc.certificate_no, lc.created_at
        FROM lifting_gear_certificates lc
        WHERE lc.project_no = ?


        UNION

        SELECT 
            'mpi' AS certificate_type,
            mp.certificate_no, mp.created_at
        FROM mpi_certificates mp
        WHERE mp.project_no = ?
       
    ";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssss", $data_id, $data_id, $data_id, $data_id, $data_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $certificates = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $certificates[] = $row;
        }
    } 
} else {
    echo "Invalid Project ID.";
    exit;
}
?>

         <div class="main-content d-flex flex-column flex-md-row">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-12">
                    <!-- Invoice Header -->
                <div class="invoice-details-header bg-white d-flex flex-column flex-sm-row mb-30 justify-content-between">
                    <div class="d-flex align-items-center mb-3 mb-sm-0">
                        <a href="#" class="mr-2">
                            <img src="<?php echo $url;?>assets/img/svg/angle-left.svg" alt="" class="svg">
                        </a>
                        <h2 class="regular mr-3 font-30">JOB ID</h2>
                        <h4 class="c4">#256987</h4>
                    </div>
                    <div class="invoice-header-right d-flex align-items-center justify-content-end">
                        <div class="delete_mail mr-20">
                            <a href="#"><img src="<?php echo $url;?>assets/img/svg/delete.svg" alt="" class="svg"></a>
                        </div>
                        <div class="edit-invoice-btn pr-1">
                            <a href="invoice-add-new.html" class="btn-circle">
                                <img src="<?php echo $url;?>assets/img/svg/writing.svg" alt="" class="svg">
                            </a>
                        </div>
                    </div>
                </div>
                <!-- End Invoice Header -->

                <!-- Invoice Top -->
                <div class="invoice-pd c2-bg" data-bg-img="../../../assets/img/media/invoice-pattern.png">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <!-- Invoice Left -->
                            <div class="invoice-left">
                                <h3 class="white font-20 mb-3">Customer Details</h3>
                                <ul class="list-invoice">
                                    <li class="location"> 
                                        <?php echo htmlspecialchars($data['equipment_location']); ?>
                                    </li>
                                    <li class="call">
                                        <!-- <a href="tel:+01234567891">+0 (123) 456 7891</a> <br /> -->
                                        <a href="tel:+01234567891"><?php echo htmlspecialchars($data['customer_mobile']); ?></a>
                                    </li>
                                    <li class="mail"><?php echo htmlspecialchars($data['customer_email']); ?></li>
                                </ul>
                            </div>
                            <!-- End Invoice Left -->
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <!-- Invoice Right -->
                            <div class="invoice-right">
                                <h3 class="white font-20 mb-3">Project Status</h3>
                                <ul class="status-list">
                                    <li><span class="key font-14">Serial No:</span>
                                        <span class="white bold font-17">
                                        <?php echo htmlspecialchars($data['crane_serial_no']); ?></span>

                                        
                                    </li>
                                    <li><span class="key font-14">Project No:</span>
                                        <span class="white bold font-17"><?php echo htmlspecialchars($data['project_no']); ?></span>
                                    </li>
                                    <li>
    <span class="key font-14">Start Date:</span>
    <span class="white bold font-17">
        <?php echo htmlspecialchars(date('d/m/Y', strtotime($data['creation_date']))); ?>
    </span>
</li>
                                    <li><span class="key font-14">End Date:</span>
                                        <span class="white bold font-17">07/03/2019</span>
                                    </li>
                                    <li><span class="key font-14">Status:</span>
                                        <span class="white status-btn completed"><?php echo htmlspecialchars($data['project_status']); ?></span>
                                    </li>
                                </ul>
                            </div>
                            <!-- End Invoice Right -->
                        </div>
                        <div class="col-md-4">
                            <!-- Invoice Right -->
                            <div class="invoice-right">
                                <h3 class="white font-20 mb-3">QR Status</h3>
                                <img class="img-fluid" src="../document/code.png" alt="QR Code" width="150px">
                            </div>
                            <!-- End Invoice Right -->
                        </div>
                    </div>
                </div>
                <!-- End Invoice Top -->

                     <!-- Invois Wrapper -->
                     <div class="bg-white invoice-pd position-relative">
                        <!-- Button in the top-right corner -->
    <div class="position-absolute" style="top: 10px; right: 10px;">
    
        <?php if ($isReviewer && (!isset($certificateStatus) || $certificateStatus !== "Certificate Created")): ?>

    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCertificateModal">
        Add Certificate
    </button>
<?php endif; ?>
    </div>
                        <div class="row">
                        <div class="col-lg-4 col-md-6 mt-5">
        <!-- Checklist Details -->
        <div class="invoice payment-details mt-5 mt-xl-0">
            <div class="bold black font-17 mb-3">Checklist Details:</div>
             <?php if ($checklistCreated): ?>
            <ul class="status-list">
                                    <li><span class="key">Checklist No:</span> <span class="black"><?php echo htmlspecialchars($data['checklist_no']); ?></span></li>
                                    <li><span class="key">Inspector:</span> <span class="black"><?php echo htmlspecialchars($data['inspected_by']); ?></span></li>
                                    <li><span class="key">Client Name:</span> <span class="black"><?php echo htmlspecialchars($data['client_name']); ?></span></li>
                                    <li><span class="key">Created At:</span> <span class="black"><?php echo date('F d, Y', strtotime($data['created_at'])); ?></span></li>
                                    <li><span class="key">Review Status:</span> <span class="text-warning">Pending </span></li>
                                    <a href="../document/checklist/type/view/<?php echo htmlspecialchars($data['checklist_type']); ?>.php?checklist_type=<?php echo htmlspecialchars($data['checklist_type']); ?>&checklist_no=<?php echo htmlspecialchars($data['checklist_id']); ?>">
                                        <span class="bg-primary text-white status-btn completed"> View</span></a>
                                  
                                </ul>
                                <?php else: ?>
                    <p class="black">Checklist not created.</p>
                <?php endif; ?>
        </div>
    </div>

    <!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Review Checklist</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reviewForm">
                    <div class="form-group">
                        <label for="projectNo">Project No</label>
                        <input type="text" class="form-control" id="projectNo" name="projectNo" readonly>
                    </div>
                    <div class="form-group">
                        <label for="checklistNo">Checklist No</label>
                        <input type="text" class="form-control" id="checklistNo" name="checklistNo" readonly>
                    </div>
                    <div class="form-group">
                        <label for="checklistType">Checklist Type</label>
                        <input type="text" class="form-control" id="checklistType" name="checklistType" readonly>
                    </div>
                    <div class="form-group">
                        <label for="reportNo">Report No</label>
                        <input type="text" class="form-control" id="reportNo" name="reportNo" readonly>
                    </div>
                    <div class="form-group">
                        <label for="reviewStatus">Review Status</label>
                        <select class="form-control" id="reviewStatus" name="reviewStatus" required>
                        <option value="Completed">Completed</option>
                            <option value="Pending">Pending</option>
                            
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="commentBox">Comment</label>
                        <textarea class="form-control" id="commentBox" name="commentBox" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="submitReview">Submit</button>
            </div>
        </div>
    </div>
</div>
    
    <div class="col-lg-4 col-md-6 mt-5">
        <!-- Report Details -->
        <div class="invoice invoice-form">
            <div class="bold black font-17 mb-3">Report Details:</div>
            <?php if ($reportCreated): ?>
            
            <ul class="status-list">
                                    <li><span class="key">Report No:</span> <span class="black"><?php echo htmlspecialchars($data['report_no']); ?></span></li>
                                    <li><span class="key">Sticker:</span> <span class="black"><?php echo htmlspecialchars($data['sticker_number_issued']); ?></span></li>
                                    <li><span class="key">Inspection Status:</span> <span class="black"><?php echo htmlspecialchars($data['inspection_status']); ?></span></li>
                                    <li><span class="key">Review Status:</span> <span class="text-warning">Pending </span></li>
                                    <a href="../document/report/view.php?project_no=<?php echo $data['project_no']; ?>">
                                        <span class="bg-primary text-white status-btn completed"> View</span></a>
                                        <a href="#" data-toggle="modal" data-target="#reviewModal" data-project-no="<?php echo htmlspecialchars($data['project_no']); ?>" data-checklist-no="<?php echo htmlspecialchars($data['checklist_no']); ?>" data-checklist-type="<?php echo htmlspecialchars($data['checklist_type']); ?>" data-checklist-type="<?php echo htmlspecialchars($data['client_name']); ?>" data-report-no="<?php echo htmlspecialchars($data['report_no']); ?>">
                                        <span class="white status-btn completed"> Review</span>
                                        </a>        
                                    <!-- <li><span class="key">Date of Creation:</span> <span class="black"><?php echo htmlspecialchars($data['date_of_creation']); ?></span></li>
                                    <li><span class="key">Rep. Name:</span> <span class="black"><?php echo htmlspecialchars($data['rep_name']); ?></span></li> -->
                                </ul>

                                <?php else: ?>
                    <p class="black">Report not created.</p>
                <?php endif; ?>
            
                <!-- <p>No report data found.</p> -->
            
        </div>
    </div>
                           <div class="col-lg-4 col-md-6 mt-5">
                              <!-- Invoice To -->
                              <div class="invoice invoice-form mt-5 mt-md-0">
                                 <div class="black bold font-17 mb-3">Certificate Details :</div>
      
                                 <?php if (!empty($certificates)) : ?>
                                    <ul class="status-list">
                            <?php foreach ($certificates as $certificate) : ?>
                                <!-- <li> -->
                                
                                     <!-- <strong>Type:</strong> <?php echo htmlspecialchars($certificate['certificate_type']); ?>, 
                                    <strong>Number:</strong> <?php echo htmlspecialchars($certificate['certificate_no']); ?>;
                                     <strong>Status:</strong> <?php echo htmlspecialchars($certificate['status']); ?>, 
                                    <strong>Created On:</strong> <?php echo htmlspecialchars($certificate['created_at']); ?>,
                                     <strong>Prepared By:</strong> <?php echo htmlspecialchars($certificate['prepared_by']); ?>  -->
                                <!-- </li> -->

                                <li><span class="key">Number:</span> <span class="black"><?php echo htmlspecialchars($certificate['certificate_no']); ?></span></li>
                                <li><span class="key">Created On:</span> <span class="black"><?php echo date('F d, Y', strtotime($certificate['created_at'])); ?></span></li>

                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                        <p>Certificates not created yet.</p>
                    <?php endif; ?>
                              </div>
                              <!-- End Invoice To -->
                           </div>
                          
                        </div>


                        <!-- Add Certificate Modal -->
<div class="modal fade" id="addCertificateModal" tabindex="-1" role="dialog" aria-labelledby="addCertificateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCertificateModalLabel">Add Certificate</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="projectNo">Project ID</label>
                    <input type="text" class="form-control" id="projectNo" value="<?php echo htmlspecialchars($data['project_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="checklistNo">Checklist No</label>
                    <input type="text" class="form-control" id="checklistNo" value="<?php echo htmlspecialchars($data['checklist_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="reportNo">Report No</label>
                    <input type="text" class="form-control" id="reportNo" value="<?php echo htmlspecialchars($data['report_no']); ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="certificateType">Certificate Type</label>
                    <select class="form-control" id="certificateType" required>
                        <option value="" disabled selected>Select Certificate Type</option>
                        <option value="healthcheck">Health Check</option>                        
                        <option value="loadtestwithload">With Load</option>                        
                        <option value="mobile">Mobile</option>
                        <option value="lifting">Lifting</option>
                        <option value="mpi">MPI</option>                       

                        <!-- Add more options as required -->
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="createCertificateBtn">Create</button>
            </div>
        </div>
    </div>
</div>

                     </div>
                     <!-- End Invois Wrapper -->

                     <!-- Invoice Details List Wrapper -->
                      
                     <?php if ($checklistCreated && $reportCreated && !empty($certificates)) : ?>
    <div class="bg-white details-list-wrap">
        <div class="table-responsive">
            <!-- Invoice List Table -->
            <table class="invoice-list-table style-two some-center text-nowrap">
                <thead>
                    <tr>
                        <th>Doc ID</th>
                        <th>Document Type</th>
                        <th>Date of Creation</th>
                        <th>Created By</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <?php if ($checklistCreated): ?>
                        <tr>
                            <td class="bold">#<?php echo htmlspecialchars($data['checklist_no']); ?></td>
                            <td class="bold"><?php echo htmlspecialchars($data['checklist_type']); ?> Checklist</td>
                            
                            <td><?php echo date('F d, Y', strtotime($data['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($data['inspected_by']); ?></td>
                            <td>Completed</td>
                            <td>
                                <!-- <a href="../document/checklist/download.php?checklist_no=<?php echo $data['checklist_no']; ?>" class="download-btn mr-3"><img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg"></a> -->
                                <a href="../document/checklist/type/view/<?php echo htmlspecialchars($data['checklist_type']); ?>.php?checklist_type=<?php echo htmlspecialchars($data['checklist_type']); ?>&checklist_no=<?php echo htmlspecialchars($data['checklist_id']); ?>" class="download-btn mr-3 bg-info">
    <img src="<?php echo $url; ?>assets/img/svg/copy.svg" alt="" class="svg">
</a>

                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php if ($reportCreated): ?>
                        <tr>
                            <td class="bold">#<?php echo htmlspecialchars($data['report_no']); ?></td>
                            <td>Report</td>
                            <td><?php echo date('F d, Y', strtotime($data['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($data['inspected_by']); ?></td>
                            <td>Generated</td>
                            <td>
                                <a href="../document/report/download.php?project_no=<?php echo $data['project_no']; ?>" class="download-btn mr-3"><img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg"></a>
                                <a href="../document/report/view.php?project_no=<?php echo $data['project_no']; ?>" class="download-btn mr-3 bg-info"><img src="<?php echo $url; ?>assets/img/svg/copy.svg" alt="" class="svg"></a>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <?php foreach ($certificates as $certificate) : ?>
                        <tr>
                            <td class="bold">#<?php echo htmlspecialchars($certificate['certificate_no']); ?></td>
                            <td><?php echo ucfirst(htmlspecialchars($certificate['certificate_type'])); ?> Certificate</td>
                            <td><?php echo date('F d, Y', strtotime($certificate['created_at'])); ?></td>
                            <td><?php echo htmlspecialchars($certificate['created_by'] ?? 'N/A'); ?></td>
                            <td>Created</td>
                            <td>
                                
                            <?php
$certificate_types = [
    'healthcheck' => 'health-check', // Map `health-check` to `healthcheck`
];

$certificate_type = isset($certificate_types[$certificate['certificate_type']]) 
    ? $certificate_types[$certificate['certificate_type']] 
    : $certificate['certificate_type'];
?>
<a href="../document/<?php echo htmlspecialchars($certificate_type); ?>/download.php?project_no=<?php echo $data['project_no']; ?>" class="download-btn mr-3">
    <img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg">
</a>

<a href="../document/<?php echo htmlspecialchars($certificate_type); ?>/view.php?project_no=<?php echo $data['project_no']; ?>" class="download-btn mr-3 bg-info"><img src="<?php echo $url; ?>assets/img/svg/copy.svg" alt="" class="svg"></a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <!-- End Invoice List Table -->
        </div>
    </div>
<?php else: ?>
    <div style="display: block; text-align: center; padding: 20px">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reviewModal">
        QC Controller Review
    </button>
    </div>
    <!-- <p class="bg-white details-list-wrap">No documents created for this project yet.</p> -->
<?php endif; ?>


                     <!-- End Invoice Details List Wrapper -->
                  </div>
               </div>
            </div>
         </div>
         <!-- End Main Content -->
      </div>
      <!-- End Main Wrapper -->

      <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        const addCertificateButton = document.querySelector("button[data-target='#addCertificateModal']");
                        if (<?php echo json_encode($enableAddCertificate); ?>) {
                            addCertificateButton.removeAttribute("disabled");
                        } else {
                            addCertificateButton.setAttribute("disabled", "true");
                        }
                    });
                </script>

      <script>
    document.getElementById('createCertificateBtn').addEventListener('click', function () {
        // Get form values
        const projectNo = document.getElementById('projectNo').value;
        const checklistNo = document.getElementById('checklistNo').value;
        const reportNo = document.getElementById('reportNo').value;
        const certificateType = document.getElementById('certificateType').value;

        if (!checklistNo || !reportNo || !certificateType) {
            alert('Please fill in all the required fields.');
            return;
        }

        // Define redirection URLs for each certificate type
        const certificateLinks = {
            healthcheck: '../document/health-check/create.php',
            lifting: '../document/lifting/create.php',
            loadtestwithload: '../document/loadtest/with_load.php',            
            mobile: '../document/mobile/create.php',
            mpi: '../document/mpi/create.php',
            eddycurrent: '../document/eddycurrent/create.php',
            liquidpenetrantinspection: "../document/liquid-penetrant-inspection-certificate/create.php",
            rocktest: "../document/rocktest/create.php",
            lmi: "../document/lmi/create.php",
            // Add more mappings as needed
        };

        // Check if the selected certificate type has a link
        if (certificateLinks[certificateType]) {
            // Redirect to the appropriate link
            const redirectUrl = `${certificateLinks[certificateType]}?project_no=${projectNo}&checklist_no=${checklistNo}&report_no=${reportNo}`;
            window.location.href = redirectUrl;
        } else {
            alert('Invalid certificate type selected.');
        }
    });
</script>

<script>

document.addEventListener("DOMContentLoaded", function () {
    $('#reviewModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var projectNo = button.data('project-no'); // Extract info from data-* attributes
        var checklistNo = button.data('checklist-no');
        var checklistType = button.data('checklist-type');
        var reportNo = button.data('report-no');

        // Update the modal's content.
        var modal = $(this);
        modal.find('#projectNo').val(projectNo);
        modal.find('#checklistNo').val(checklistNo);
        modal.find('#checklistType').val(checklistType);
        modal.find('#reportNo').val(reportNo);
    });
});
</script>

<script>
    document.getElementById('submitReview').addEventListener('click', function () {
        const formData = new FormData(document.getElementById('reviewForm'));

        fetch('submit_review.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Review submitted successfully!');
                $('#reviewModal').modal('hide');

                // Disable the review button
                const reviewButton = document.querySelector('a[data-target="#reviewModal"]');
                if (reviewButton) {
                    reviewButton.style.pointerEvents = 'none'; // Disable click events
                    reviewButton.style.opacity = '0.5'; // Make it look disabled
                }
            } else {
                alert('Error submitting review: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
</script>


<?php
include_once('../inc/footer.php');
?>