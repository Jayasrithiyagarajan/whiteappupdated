<?php 
include_once('../../inc/function.php');
include_once('../../file/config.php'); // include your database connection
// SQL query to fetch data from the 'lifting_gears_certificate' table
$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
// SQL query to fetch data from the 'mobile_crane_certificate' table
// $sql = "SELECT * FROM loadtest_certificate";
// $result = $conn->query($sql);
$sql = "SELECT lc.*, pi.project_status 
        FROM loadtest_certificate lc
        LEFT JOIN project_info pi ON lc.project_no = pi.project_no";

// Add condition for inspector role
if ($role === 'inspector') {
    $sql .= " WHERE lc.inspector_name = '" . $conn->real_escape_string($username) . "'";
}
$result = $conn->query($sql);
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
                                 <a href="#" class="btn">Certificate of Thorough Examination</a>
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
            <th>Date of Thorough Examination</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Loop through the fetched data and populate the table rows
        while ($row = mysqli_fetch_assoc($result)) {
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
                        <a href="./view.php?project_no=<?php echo $row['project_no']; ?>" target="_blank" rel="noopener noreferrer">
                            <div class="icon text-primary">
                                <i class="et-clipboard"></i>
                            </div>
                        </a>
                    
                    <a href="./download.php?project_no=<?php echo $row['project_no']; ?>">
                                    <img src="<?php echo $url; ?>assets/img/svg/download.svg" alt="" style="margin-left: 10px; margin-top: -10px;">
                                </a>
                              </div>
                    <!-- End Star -->
                </td>                
                <td><?php echo $row['project_no']; ?></td>
                <td><?php echo $row['certificate_no']; ?></td>
                <td><?php echo $row['equipment_description']; ?></td>
                <td><?php echo $row['equipment_id']; ?></td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="img mr-20">
    <img src="../../inspector/uploads/<?php echo str_replace(' ', '_', strtolower($row['inspector_name'])); ?>/images/profile_image.jpg" class="img-40" alt="">
</div>
                        <div class="name bold">
                            <?php echo $row['inspector_name']; ?>
                        </div>
                    </div>
                </td>
                <td><?php echo $row['employer_address']; ?></td>
                <td><?php echo $row['premises_address']; ?></td>
                <td><?php echo date('F d, Y', strtotime($row['examination_date'])); ?></td>
                <td class="actions">
    <?php 
    // Debugging output (remove after testing)
    //echo "Role: ".$_SESSION['role']." | Status: ".$row['project_status'];
    
    $isDocumentController = ($_SESSION['role'] === 'document controller');
    $isProjectCompleted = ($row['project_status'] === 'Completed');
    $canEdit = ($isDocumentController && !$isProjectCompleted);
    ?>
    
    <!-- Edit action -->
    <?php if ($canEdit) : ?>
        <a href="edit_loadtest.php?project_no=<?php echo $row['project_no']; ?>" target="_blank" rel="noopener noreferrer" class="contact-edit">
            <img src="<?php echo $url; ?>assets/img/svg/c-edit.svg" alt="" class="svg">
        </a>
    <?php else : ?>
        <span class="contact-edit" style="opacity: 0.5; cursor: not-allowed;" 
              title="<?php echo $isProjectCompleted ? 'Project completed - cannot edit' : 'Edit not allowed for your role' ?>">
            <img src="<?php echo $url; ?>assets/img/svg/c-edit.svg" alt="" class="svg">
        </span>
    <?php endif; ?>

    <!-- Delete action -->
    <span class="contact-close" onclick="deleteRow('<?php echo $row['project_no']; ?>', this)">
        <img src="<?php echo $url; ?>assets/img/svg/c-close.svg" alt="" class="svg">
    </span>
</td>
</tr>
<?php } ?>
    </tbody>
</table>
                            <!-- End Invoice List Table -->
                        </div>
                    </div>
                    <!-- End Card -->

                    <!-- Contact Add New PopUp -->
                    
                     
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
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        

<!--<script>-->
<!--document.getElementById('search-input').addEventListener('keyup', function () {-->
<!--    const filter = this.value.toLowerCase();-->
<!--    const rows = document.querySelectorAll('#inspection-table tbody tr');-->

<!--    rows.forEach(row => {-->
<!--        const rowText = row.textContent.toLowerCase();-->
<!--        row.style.display = rowText.includes(filter) ? '' : 'none';-->
<!--    });-->
<!--});-->
<!--</script>-->

<script>
$(document).ready(function () {
    $('#inspection-table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excelHtml5',
                text: 'Export to Excel',
                title: 'Mobile Crane Load Test Certificates',
                exportOptions: {
                    columns: ':not(:last-child)' // Exclude Actions column
                }
            }
        ],
        order: [[8, 'desc']], // Sort by date descending
        responsive: true,
        pageLength: 10
    });
});
</script>




<script>
function deleteRow(project_no, element) {
    if (confirm("Are you sure you want to delete this row?")) {
        // Send AJAX request to delete the row from the database
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete_project.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // On success, remove the row from the table
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                var response = JSON.parse(xhr.responseText);
                if (response.success) {
                    // Remove the row from the table
                    var row = element.closest('tr');
                    row.parentNode.removeChild(row);
                } else {
                    alert("Error deleting row: " + response.error);
                }
            }
        };

        // Send project_no as data
        xhr.send("project_no=" + project_no);
    }
}
</script>