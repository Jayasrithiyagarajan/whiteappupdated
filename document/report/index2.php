<?php 
include_once('../../inc/function.php');
include_once('../../file/config.php'); // Include your database connection

// Get logged-in user details
$user_id = $_SESSION['user_id']; // Assuming you store user ID in session
$user_role = $_SESSION['role']; // Assuming you store user role in session
$username = $_SESSION['username']; // Assuming you store user username in session

// Define SQL query based on role
if (in_array($user_role, ['admin', 'document controller', 'quality controller', 'reviewer', 'customer'])) {
   // These roles see all reports
   $sql = "SELECT r.*, p.project_status 
           FROM reports r
           JOIN project_info p ON r.project_no = p.project_no
           ORDER BY r.date_of_inspection DESC";
   $stmt = $conn->prepare($sql);
} else if ($user_role == 'inspector') {
   // Inspector sees only their reports
   $sql = "SELECT r.*, p.project_status 
           FROM reports r
           JOIN project_info p ON r.project_no = p.project_no
           WHERE r.issued_by = ? 
           ORDER BY r.date_of_inspection DESC";
   $stmt = $conn->prepare($sql);
   $stmt->bind_param("s", $username);
}

// Execute query
$stmt->execute();
$result = $stmt->get_result();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports</title>
    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
    <!-- Custom CSS -->
    <style>
        .disabled {
            pointer-events: none; /* Disable click events */
            cursor: not-allowed; /* Show not-allowed cursor */
            opacity: 0.5; /* Make it look faded */
        }
    </style>
</head>
<body>

        <!-- Main Content -->
        <div class="main-content d-flex flex-column flex-md-row">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-12">
                    <!-- Card -->
                    <div class="card bg-transparent">
                        <!-- Contact Header -->
                        <div class="contact-header d-flex align-items-sm-center media flex-column flex-sm-row bg-white mb-30">
                           <div class="contact-header-left media-body d-flex align-items-center mr-4">
                           
                           <div class="card-body bg-white">
                           <div class="main-header-btn ">
                                 <a href="#" class="btn">Report</a>
                              </div>
                            </div>
                                 <!-- Search Form -->
                                 <form action="#" class="search-form flex-grow">
                                    <div class="theme-input-group style--two">
                                    <input type="text" class="theme-input-style" placeholder="Search Here">

                                    <button type="submit"><img src="<?php echo $url; ?>assets/img/svg/search-icon.svg" alt=""
                                          class="svg"></button>
                                    </div>
                                 </form>
                                 <!-- End Search Form -->
                           </div>

                           <div class="contact-header-right d-flex align-items-center justify-content-end mt-3 mt-sm-0">
                              <!-- Grid -->

                                    <!-- Add New Contact Btn -->
                                    <div class="add-new-contact mr-20">
                                       <a href="#" class="btn-circle" data-toggle="modal" data-target="#contactAddModal">
                                          <img src="<?php echo $url; ?>assets/img/svg/plus_white.svg" alt="" class="svg">
                                       </a>
                                 </div>
                                 <!-- End Add New Contact Btn -->
                              <!-- <div class="grid">
                                 <a href="contact-grid.html">
                                    <img src="<?php echo $url; ?>assets/img/svg/grid-icon.svg" alt="" class="svg">
                                </a>
                              </div> -->
                              <!-- End Grid -->

                              <!-- Starred -->
                              <div class="star">
                                 <a href="#">
                                    <img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg">
                                </a>
                              </div>
                              <!-- End Starred -->

                              <!-- Delete Mail -->
                              <div class="delete_mail">
                                 <a href="#">
                                    <img src="<?php echo $url; ?>assets/img/svg/delete.svg" alt="" class="svg">
                                </a>
                              </div>
                              <!-- End Delete Mail -->

                              
                           </div>
                        </div>
                        <!-- End Contact Header -->


                        <div class="table-responsive">
                            <!-- Invoice List Table -->
                            <table class="contact-list-table text-nowrap bg-white">
                                <thead>
                                    <tr>
                                        <th>
                                            <!-- Custom Checkbox -->
                                            <label class="custom-checkbox">
                                                <input type="checkbox">
                                                <span class="checkmark"></span>
                                            </label>
                                            <!-- End Custom Checkbox -->

                                            <!-- Star -->
                                            <div class="star">
                                                <a href="#"><img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg"></a>
                                            </div>
                                            <!-- End Star -->
                                        </th>
                                        <th>Report No</th>
                                        <th>Project ID </th>
                                        <th>Checklist No </th>                                        
                                        <th>Date of Inspection</th>                           
                                        <th>Company Name</th>
                                        <th>Equipment No </th>
                                        <th>Serial No</th>  
                                        <th>Sticker No</th>  
                                        <th>Location</th>  
                                        <th class="text-center">Inspector Name </th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
        
         <!-- Loop through the fetched data and populate the table rows -->
       <?php
while ($row = mysqli_fetch_assoc($result)) {
    $inspector_name = $row['issued_by']; 
    $project_status = $row['project_status']; // Fetch project status

    // Lowercase version for checking
    // $inspector_name_lower = strtolower($inspector_name);
    $inspector_name_lower = strtolower(str_replace(' ', '_', $inspector_name));
    
    // Define base URL path
    $lowercase_path = "{$url}inspector/uploads/{$inspector_name_lower}/images/profile_image.jpg";
    $original_path  = "{$url}inspector/uploads/{$inspector_name}/images/profile_image.jpg";

    // Check file existence (use server file path here, not URL)
    $server_base_path = $_SERVER['DOCUMENT_ROOT']; // Adjust if needed

    if (file_exists("$server_base_path/inspector/uploads/{$inspector_name_lower}/images/profile_image.jpg")) {
        $inspector_image = $lowercase_path;
    } elseif (file_exists("$server_base_path/inspector/uploads/{$inspector_name}/images/profile_image.jpg")) {
        $inspector_image = $original_path;
    } else {
        // Final fallback image (default placeholder)
        $inspector_image = "{$url}inspector/uploads/{$inspector_name_lower}/images/profile_image.jpg";
    }
?>

            <tr>
                <td>
                    <!-- Custom Checkbox -->
                    <label class="custom-checkbox">
                        <input type="checkbox">
                        <span class="checkmark"></span>
                    </label>
                    <!-- End Custom Checkbox -->
        
                    <!-- Star -->
                    <div class="star">
    <a href="./view.php?project_no=<?php echo $row['project_no']; ?>&report_no=<?php echo $row['report_no']; ?>">
        <div class="icon text-primary">
            <i class="et-clipboard"></i>
        </div>
    </a>                    
    <a href="./download.php?project_no=<?php echo $row['project_no']; ?>&report_no=<?php echo $row['report_no']; ?>">
        <img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" style="margin-left: 10px; margin-top: -10px;">
    </a>
</div>

                    <!-- End Star -->
                </td>
                
                <td><?php echo $row['report_no']; ?></td>
                <td><?php echo $row['project_no']; ?></td>
                <td>
                    <!-- <a href='../checklist/type/view/{$checklist_type_raw}.php?checklist_type={$checklist_type_raw}&&checklist_no={$checklist_no}' class="text-primary"> -->
                        <?php echo htmlspecialchars($row['checklist_no']); ?>
                    <!-- </a> -->
                </td>
                <td><?php echo date('F d, Y', strtotime($row['date_of_inspection'])); ?></td>
                <td><?php echo $row['client_company_name']; ?></td>
                <td><?php echo $row['equipment_id_no']; ?></td>
                <td><?php echo $row['equipment_serial_no']; ?></td>
                <td><?php echo $row['sticker_number_issued']; ?></td>
                <td><?php echo $row['location']; ?></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="img mr-20">
                            <!-- Output the inspector image -->
                            <img src="<?php echo $inspector_image; ?>" class="img-40" alt="Inspector Image">
                        </div>
                        <div class="name bold">
                            <?php echo $row['issued_by']; ?>
                        </div>
                    </div>
                </td>
                <td class="actions">
                    <!-- Edit action (only for the assigned inspector and if project is not completed) -->
                    <?php if ($username === $row['issued_by'] && $project_status !== 'Completed') { ?>
    <a href="edit.php?project_no=<?php echo $row['project_no']; ?>&report_no=<?php echo $row['report_no']; ?>" class="contact-edit">
        <img src="<?php echo $url; ?>assets/img/svg/c-edit.svg" alt="" class="svg">
    </a>
<?php } else { ?>
    <!-- Disabled Edit Button -->
    <span class="contact-edit disabled" title="Edit disabled (Not assigned inspector or Project Completed)">
        <img src="<?php echo $url; ?>assets/img/svg/c-edit.svg" alt="" class="svg" style="opacity: 0.5;">
    </span>
<?php } ?>

                </td>
            </tr>
        <?php } ?>
    </tbody>
                            </table>
                            <!-- End Invoice List Table -->
                        </div>
                    </div>
                    <!-- End Card -->                 

                     
                  </div>
               </div>
            </div>
         </div>
         <!-- End Main Content -->
      </div>
      <!-- End Main Wrapper -->
      <?php 
        include_once('../../inc/footer.php');
        ?>


<!-- DataTables scripts -->
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">
<script type="text/javascript" charset="utf8" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

<script>
    function deleteRow(project_no, element) {
        if (confirm("Are you sure you want to delete this row?")) {
            // Remove the row from the frontend
            var row = element.closest('tr');
            row.parentNode.removeChild(row);

            // Send an AJAX request to delete the row from the database
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete_report.php", true);
            xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert("Row deleted successfully");
                } else if (xhr.status != 200) {
                    alert("Failed to delete row. Please try again.");
                }
            };
            xhr.send("project_no=" + project_no);
        }
    }
</script>


<script>
    $(document).ready(function() {
    $('.contact-list-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export', // Change button text
                title: 'Report List',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude the last column (Action column)
                }
            }
        ],
        "searching": true
    });
       
    });
</script>