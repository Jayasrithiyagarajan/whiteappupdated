<?php 
include_once('../inc/function.php');
include('../file/config.php');
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
                                <h3>User List</h3>
                            </div>
                            <!-- End Add New Contact Btn -->

                            <!-- Search Form -->
                            <form action="#" class="search-form flex-grow">
                                <div class="theme-input-group style--two">
                                    <input type="text" class="theme-input-style" placeholder="Search Here">
                                    <button type="submit"><img src="<?php echo $url; ?>assets/img/svg/search-icon.svg" alt="" class="svg"></button>
                                </div>
                            </form>
                            <!-- End Search Form -->
                        </div>

                        <div class="contact-header-right d-flex align-items-center justify-content-end mt-3 mt-sm-0">
                            <div class="add-new-contact mr-20">
                                <a href="./create-user.php" class="btn">Create New</a>
                            </div>
                        </div>
                    </div>
                    <!-- End Contact Header -->

                    <div class="table-responsive">
                        <!-- User List Table -->
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
                                    <th class="text-center">User ID</th>
                                    <th class="text-center">User Name</th>
                                    <!--<th>Emp.ID</th>-->
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                // Fetch data from the new_users table
                                $sql = "SELECT user_id, username, emp_id, email, mobile, role FROM new_users";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        // Server-side image verification
                                        $imagePath = 'uploads/profile_photos/' . $row['username'] . '.jpg';
                                        $fullPath = $_SERVER['DOCUMENT_ROOT'] . '/' . $imagePath;
                                        $defaultImage = $url . 'assets/img/default-user.png';
                                        
                                        // Check if image exists on server
                                        if (file_exists($fullPath)) {
                                            $imageUrl = $url . $imagePath;
                                        } else {
                                            $imageUrl = $defaultImage;
                                        }
                                        
                                        // Status - You can add your logic here
                                        $status = "Active";

                                        echo "<tr>
                                            <td>
                                                <label class='custom-checkbox'>
                                                    <input type='checkbox' class='select-checkbox'>
                                                    <span class='checkmark'></span>
                                                </label>
                                            </td>
                                            <td>{$row['user_id']}</td>
                                            <td>
                                                <div class='d-flex align-items-center'>
                                                <!--    <div class='mr-2'>
                                                        <img src='{$imageUrl}' alt='{$row['username']}' 
                                                             onerror=\"this.src='{$defaultImage}'; this.onerror=null;\" 
                                                             style='width: 30px; height: 30px; border-radius: 50%; object-fit: cover;'>
                                                    </div>-->
                                                    <div>{$row['username']}</div>
                                                </div>
                                            </td>
                                            <!--<td>{$row['emp_id']}</td>-->
                                            <td>{$row['email']}</td>
                                            <td>{$row['mobile']}</td>
                                            <td>{$row['role']}</td>
                                            <td>{$status}</td>
                                        </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='9' class='text-center'>No users found.</td></tr>";
                                }
                                
                                // Close connection
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                        <!-- End User List Table -->
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>
</div>
<!-- End Main Content -->
<?php 
include_once('../inc/footer.php');
?>