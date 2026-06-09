<?php 
include_once('../inc/function.php');

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
                                 <!-- Add New Contact Btn -->
                                 <div class="add-new-contact mr-20">
                                     <h3>  Inspector List  </h3>
                                      <!-- <img src="<?php echo $url; ?>assets/img/svg/plus_white.svg" alt="" class="svg"> -->
                                      
                                 </div>
                                 <!-- End Add New Contact Btn -->

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
                           <div class="add-new-contact mr-20">
                                       <a href="./create-inspector.php" class="btn" >
                                         Create New
                                       </a>
                            </div>
                              <!-- End Pagination -->
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
                                        </th>
                                        <th class="text-center">Inspector ID </th>
                                        <th class="text-center">Inspector Name </th>
                                        <th>Emp.ID</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Address</th>
                                        <!-- <th>Inspect crane</th> -->
                                        <th>Status</th>
                                         <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php 
include ('../file/config.php');

$sql = "SELECT * FROM inspectors";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {

      // Deserialize handle_crane if it's not null
      $handle_crane_list = $row['handle_crane'] ? unserialize($row['handle_crane']) : [];

      // Convert handle_crane array into a comma-separated string
      $handle_crane_display = !empty($handle_crane_list) ? implode(", ", $handle_crane_list) : "N/A";

      // Status - You can add your logic here (e.g., fetched from the DB or a default value)
      $status = "Active"; // Change this as per your requirement

      // Construct the image path
      $inspector_folder = strtolower(str_replace(' ', '_', $row['inspector_name']));
      $profile_photo_path = "./uploads/{$inspector_folder}/images/profile_image.jpg";
if (file_exists($profile_photo_path)) {
    $profile_photo_path .= '?v=' . filemtime($profile_photo_path);
}


        echo "<tr>
            <td>
                <label class='custom-checkbox'>
                    <input type='checkbox' class='select-checkbox'>
                    <span class='checkmark'></span>
                </label>
            </td>
            <td>{$row['inspector_id']}</td>
          <td>
                <img src='{$profile_photo_path}' alt='Profile Photo' class='img-thumbnail' style='width: 50px; height: 50px; object-fit: cover; margin-right: 10px;'>
                {$row['inspector_name']}
            </td>
            <td>{$row['emp_id']}</td>
            <td>{$row['email']}</td>
            <td>{$row['mobile']}</td>                        
            <td>{$row['address']}</td>
            
                    <td>{$status}</td>
            
            <td>
    <a href='index.php?id={$row['id']}' class='btn btn-sm btn-primary'>View</a>

    <a href='edit-inspector.php?id={$row['id']}' class='btn btn-sm'>Edit</a>
    <a href='delete-inspector.php?id={$row['id']}' class='btn btn-sm text-danger'>Delete</a>
</td>

        </tr>";
    }
} else {
    echo "<tr><td colspan='9' class='text-center'>No inspectors found.</td></tr>";
}
?>
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
        include_once('../inc/footer.php');
        ?>
        