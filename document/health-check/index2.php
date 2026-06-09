<?php 
include_once('../../inc/function.php');
include_once('../../file/config.php'); // include your database connection

// SQL query to fetch data from the 'lifting_gears_certificate' table
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

$sql = "SELECT chc.certificate_no,
chc.project_no,
chc.vessel_name_location,
chc.serial_number,
chc.customer_name,
chc.asset_number,
chc.inspector,
chc.created_at, pi.project_status FROM crane_health_check_certificate chc LEFT JOIN project_info pi ON chc.project_no = pi.project_no";


// Add condition for inspector role
if ($role === 'inspector') {
    $sql .= " WHERE chc.inspector = '" . $conn->real_escape_string($username) . "'";
}

$result = $conn->query($sql);


// $sql = "SELECT chc.*, pi.project_status 
//         FROM crane_health_check_certificate chc
//         LEFT JOIN project_info pi 
//         ON chc.project_no = pi.project_no";

// $result = $conn->query($sql);


// $sql = "SELECT * FROM crane_health_check_certificate ORDER BY created_at DESC";
// $result = $conn->query($sql);

// SQL query to fetch data from the 'crane_health_check_certificate' table and join with the 'inspector' table
// $sql = "SELECT 
//             chc.*, 
//             i.profile_photo AS inspector_image 
//         FROM crane_health_check_certificate chc
//         LEFT JOIN inspector i 
//         ON chc.inspector = i.name";

// $result = $conn->query($sql);
?>
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
                                 <a href="#" class="btn">Health Check Certificate</a>
                              </div>
                            </div>
                                 <!-- Search Form -->
                                 <form action="#" class="search-form flex-grow">
                                    <div class="theme-input-group style--two">
                                    <input type="text" class="theme-input-style" placeholder="Search Here" id="search-input">
                                    <button type="submit"><img src="<?php echo $url; ?>assets/img/svg/search-icon.svg" alt=""
                                          class="svg"></button>
                                    </div>
                                 </form>
                                 <!-- End Search Form -->
                           </div>

                           <div class="contact-header-right d-flex align-items-center justify-content-end mt-3 mt-sm-0">
                              <!-- Grid -->

                                    <!-- Add New Contact Btn -->
                                    <!-- <div class="add-new-contact mr-20">
                                       <a href="#" class="btn-circle" data-toggle="modal" data-target="#contactAddModal">
                                          <img src="<?php echo $url; ?>assets/img/svg/plus_white.svg" alt="" class="svg">
                                       </a>
                                 </div> -->


                                 <div class="add-new-contact mr-20">
   <a href="create.php" class="btn-circle">
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

                              <!-- Pagination -->
                              <!-- <div class="pagination style--two d-flex flex-column align-items-center ml-n2">
                                 <ul class="list-inline d-inline-flex align-items-center">
                                 <li><a href="#">
                                       <img src="<?php echo $url; ?>assets/img/svg/left-angle.svg" alt="" class="svg">
                                 </a></li>
                                 <li><a href="#" class="current">
                                       <img src="<?php echo $url; ?>assets/img/svg/right-angle.svg" alt="" class="svg">
                                 </a></li>
                                 </ul>
                              </div> -->
                              <!-- End Pagination -->
                           </div>
                        </div>
                        <!-- End Contact Header -->


                        <div class="table-responsive">
                            <!-- Invoice List Table -->
                            <table id="inspection-table" class="contact-list-table text-nowrap bg-white">
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
            <th>Project ID</th>
            <th>Certificate No</th>
            <th>Inspected Items</th>
            <th>Serial No. / Equipment Id</th>
            <th class="text-center">Inspector Name</th>
            <th>Client Name</th>
            <th>Location</th>
            <th>Date of Creation</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) : ?>
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
                        <a href="./view.php?project_no=<?php echo $row['project_no']; ?>" target="_blank" rel="noopener noreferrer">
                            <div class="icon text-primary">
                                <i class="et-clipboard"></i>
                            </div>
                        </a>

                        <a href="./download.php?project_no=<?php echo $row['project_no']; ?>"><img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" class="svg"></a>
                    </div>
                    <!-- End Star -->

                    
                </td>
                <td><?php echo $row['project_no']; ?></td> 
                <td><?php echo $row['certificate_no']; ?></td>
                <td><?php echo $row['vessel_name_location']; ?></td>
                <td><?php echo $row['serial_number']; ?></td>
                <td>
    <div class="d-flex align-items-center">
        <div class="img mr-20">
            <?php
            // Sanitize the inspector name for use in file paths
            $inspectorName = $row['inspector'];
            // $encodedName = urlencode(strtolower($inspectorName));
            $encodedName = urlencode(str_replace(' ', '_', strtolower($inspectorName)));
            
            // Construct the image path
            $inspectorImagePath = "{$url}/inspector/uploads/{$encodedName}/images/profile_image.jpg";
            
            // Check if the image exists and is readable
            $imageExists = file_exists($inspectorImagePath) && is_readable($inspectorImagePath);
            
            // Determine which image to display
            $imageSrc = $imageExists ? $inspectorImagePath : "{$url}inspector/uploads/{$encodedName}/images/profile_image.jpg";
            $imageAlt = $imageExists ? "Inspector {$inspectorName}'s Image" : "Default Image";
            
            // Output the image tag
            echo "<img src='{$imageSrc}' class='img-40' alt='{$imageAlt}'>";
            ?>
        </div>
        <div class="name bold">
            <?php echo htmlspecialchars($inspectorName, ENT_QUOTES, 'UTF-8'); ?>
        </div>
    </div>
</td>
<td><?php echo $row['customer_name']; ?></td>
<td><?php echo $row['asset_number']; ?></td>
<td><?php echo $row['created_at']; ?></td> 
                
                
                 <!-- Assuming customer_name is the company name -->
                
                <td class="actions">
                                            <?php if ($_SESSION['role'] === 'document controller' && $row['project_status'] !== 'Completed') : ?>
                                                
                                                <a href="edit.php?project_no=<?php echo $row['project_no']; ?>" target="_blank" rel="noopener noreferrer" class="contact-edit">
                                                    <img src="<?php echo $url; ?>assets/img/svg/c-edit.svg" alt="" class="svg">
                                                </a>
                                            <?php else : ?>
                                                <a class="contact-edit disabled" style="pointer-events: none; opacity: 0.5;">
                                                    <img src="<?php echo $url; ?>assets/img/svg/c-edit.svg" alt="" class="svg">
                                                </a>
                                            <?php endif; ?>

                                            <span class="contact-close" onclick="deleteRow('<?php echo $row['project_no']; ?>', this)">
                                                <img src="<?php echo $url; ?>assets/img/svg/c-close.svg" alt="" class="svg">
                                            </span>
                                        </td>

            </tr>
        <?php endwhile; ?>
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

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.1.0/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

        
//         <script>
// document.getElementById('search-input').addEventListener('keyup', function () {
//     const filter = this.value.toLowerCase();
//     const rows = document.querySelectorAll('#inspection-table tbody tr');

//     rows.forEach(row => {
//         const rowText = row.textContent.toLowerCase();
//         row.style.display = rowText.includes(filter) ? '' : 'none';
//     });
// });
// </script>


<script>
$(document).ready(function() {
    $('#inspection-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export',
                title: 'Crane Health Check Report',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude action column
                }
            }
        ],
        order: [[8, "desc"]], // Sort by Created Date
        responsive: true,
        pageLength: 10
    });
});
</script>


        <script>
    function deleteRow(project_no, element) {
        if (confirm("Are you sure you want to delete this row?")) {
            // Remove the row from the frontend
            var row = element.closest('tr');
            row.parentNode.removeChild(row);

            // Send an AJAX request to delete the row from the database
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "delete.php", true);
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