<?php 
include_once('../inc/function.php');
include_once('../file/config.php');
// include 'db_connection.php'; // Include your database connection

// Assuming you have the inspector's ID stored in a session or variable
$username = $_SESSION['username'];

// Replace with your actual session variable or logic

// Queries to get counts from each table
$query_projects = "SELECT COUNT(*) AS total_projects FROM project_info";
$query_checklists = "SELECT COUNT(*) AS total_checklists FROM checklist_information";
$query_reports = "SELECT COUNT(*) AS total_reports FROM reports";
// $query_certificates = "SELECT COUNT(*) AS total_certificates FROM certificates";

// Execute queries
$result_projects = mysqli_query($conn, $query_projects);
$result_checklists = mysqli_query($conn, $query_checklists);
$result_reports = mysqli_query($conn, $query_reports);
// $result_certificates = mysqli_query($conn, $query_certificates);

// Fetch results
$total_projects = mysqli_fetch_assoc($result_projects)['total_projects'];
$total_checklists = mysqli_fetch_assoc($result_checklists)['total_checklists'];
$total_reports = mysqli_fetch_assoc($result_reports)['total_reports'];
// $total_certificates = mysqli_fetch_assoc($result_certificates)['total_certificates'];


// Query to get total projects
$query_total_projects = "SELECT COUNT(*) AS total_projects FROM project_info";
$result_total_projects = mysqli_query($conn, $query_total_projects);
$total_projects = mysqli_fetch_assoc($result_total_projects)['total_projects'];

// Query to get completed projects
$query_completed_projects = "SELECT COUNT(*) AS completed_projects FROM project_info WHERE project_status = 'Completed'";
$result_completed_projects = mysqli_query($conn, $query_completed_projects);
$completed_projects = mysqli_fetch_assoc($result_completed_projects)['completed_projects'];

// Calculate the percentage (avoid division by zero)
$project_percentage = ($total_projects > 0) ? round(($completed_projects / $total_projects) * 100, 2) : 0;


// Fetch projects created today
$query_todays_projects = "SELECT project_no, creation_date FROM project_info WHERE DATE(creation_date) = CURDATE()";
$result_todays_projects = mysqli_query($conn, $query_todays_projects);



// Fetch total projects
$query_total = "SELECT COUNT(*) AS total FROM project_info";
$result_total = mysqli_query($conn, $query_total);
$total_projects = mysqli_fetch_assoc($result_total)['total'];

// Fetch completed projects
$query_completed = "SELECT COUNT(*) AS completed FROM project_info WHERE project_status = 'Completed'";
$result_completed = mysqli_query($conn, $query_completed);
$completed_projects = mysqli_fetch_assoc($result_completed)['completed'];

// Fetch pending projects
$query_pending = "SELECT COUNT(*) AS pending FROM project_info WHERE project_status = 'Pending'";
$result_pending = mysqli_query($conn, $query_pending);
$pending_projects = mysqli_fetch_assoc($result_pending)['pending'];

// Calculate percentages (avoid division by zero)
$completed_percentage = ($total_projects > 0) ? round(($completed_projects / $total_projects) * 100, 2) : 0;
$pending_percentage = ($total_projects > 0) ? round(($pending_projects / $total_projects) * 100, 2) : 0;



// Fetch recent projects with their status
$query_recent_projects = "SELECT project_no, customer_name, project_status, creation_date 
                          FROM project_info 
                          ORDER BY creation_date DESC 
                          LIMIT 5"; // Adjust the limit as needed

$result_recent_projects = mysqli_query($conn, $query_recent_projects);



// Query to fetch projects assigned to the inspector
$query_projects = "SELECT project_no, customer_name, project_status, creation_date 
                   FROM project_info 
                   WHERE assigned_inspector = '$inspector_id' 
                   ORDER BY creation_date DESC 
                   LIMIT 5"; // Adjust the limit as needed

$result_projects = mysqli_query($conn, $query_projects);
?>
        
        <!-- Main Content -->
            <div class="main-content">
                
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!-- User Profile Image -->
                                <div class="user-profile-img d-none ">
                                    <div class="cover-img">
                                            <img src="<?php echo $url; ?>assets/img/media/cover.jpg" class="w-100" alt="">

                                            <!-- Upload Photo -->
                                            <div class="upload-button">
                                                <img src="<?php echo $url; ?>assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                                <span>Upload Photo</span>
                                                <input class="file-input" type="file" id="fileUpload3" accept="image/*">
                                            </div>
                                            <!-- End Upload Photo -->
                                    </div>
                                </div>
                                <!-- End User Profile Image -->
                            </div>

                            <div class="mx-2 mx-lg-4 mx-xl-5">
                                <div class="card mt-1">
                                    <!-- User Profile Nav -->
                                    <div class="user-profile-nav d-flex justify-content-xl-between align-items-xl-center flex-column flex-xl-row">
                                        <!-- Profile Info -->
                                        <div class="profile-info d-flex align-items-center">
                                            <div class="profile-pic mr-3 d-none">
                                                <img src="<?php echo $url; ?>assets/img/media/profile-pic.jpg" alt="">
        
                                                <!-- Upload Photo -->
                                                <div class="upload-button">
                                                    <img src="<?php echo $url; ?>assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                                    <span>Upload Photo</span>
                                                    <input class="file-input" type="file" id="fileUpload2" accept="image/*">
                                                </div>
                                                <!-- End Upload Photo -->
                                            </div>

                                            <div class="mr-3 p-4">
                                                <h3>Abrilay Khatun</h3>
                                                <p class="font-14">Head Of Business Development</p>
                                            </div>
                                        </div>
                                        <!-- End Profile Info -->

                                        <div class="d-none align-items-center mt-3 mt-xl-0">
                                            <ul class="nav profile-nav-tabs">
                                                <li>
                                                    <a href="profile-chat.html" class="pr-0 pl-2 pl-xl-30">
                                                        <span class="chat">
                                                            <img src="<?php echo $url; ?>assets/img/svg/chat-icon.svg" alt="" class="svg">
                                                        </span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="conncetion" href="connection.html">
                                                        <div class="btn-circle mr-20">
                                                            <img src="<?php echo $url; ?>assets/img/svg/user-check.svg" alt="" class="svg">
                                                        </div>
                                                        <div class="font-14">
                                                            <h4>154</h4>
                                                            <p class="font-14 text_color">Connections</p>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="p_nav-link has-before" href="about.html">About</a>
                                                </li>
                                                <li>
                                                    <a class="p_nav-link" href="gallery.html">Gallery</a>
                                                </li>
                                                <li>
                                                    <a class="p_nav-link" href="news-feed.html">News Feed</a>
                                                </li>
                                            </ul>

                                            <div class="px-3">
                                                <!-- Dropdown Button -->
                                                <div class="dropdown-button">
                                                    <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                                    <div class="menu-icon style--two w-auto justify-content-center mr-0">
                                                        <span></span>
                                                        <span></span>
                                                        <span></span>
                                                    </div>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-right">
                                                        <a href="edit-profile.html">Edit Profile</a>
                                                        <a href="user-dashboard.html">User Dashboard</a>
                                                    </div>
                                                </div>
                                                <!-- End Dropdown Button -->
                                            </div>
                                        </div>

                                    </div>
                                    <!-- End User Profile Nav -->
                                </div>
                            </div>
                        </div>
                    </div> 

                    <div class="mt-30">
                        <div class="row">
                            <div class="col-xl-2 col-lg-4 col-sm-6">
                                <!-- Card -->
                                <div class="card mb-30">
                                    <div class="statistics-bounce-rate d-flex align-items-center justify-content-between">
                                        <div class="state-content">
                                            <p class="font-14 mb-2">Projects</p>
                                            <h3><?php echo $total_projects; ?></h3>
                                        </div>
                                        <div class="state-icon">
                                            <img src="<?php echo $url; ?>assets/img/png-icon/bar.png" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>

                            <div class="col-xl-2 col-lg-4 col-sm-6">
                                <!-- Card -->
                                <div class="card mb-30">
                                    <div class="statistics-bounce-rate order style--two d-flex align-items-center justify-content-between">
                                        <div class="state-content">
                                            <p class="font-14 mb-2">Checklists</p>
                                            <h3><?php echo $total_checklists; ?></h3>
                                        </div>
                                        <div class="state-icon">
                                            <img src="<?php echo $url; ?>assets/img/png-icon/badge.png" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>

                            <div class="col-xl-2 col-lg-4 col-sm-6">
                                <!-- Card -->
                                <div class="card mb-30">
                                    <div class="statistics-bounce-rate report d-flex align-items-center justify-content-between">
                                        <div class="state-content">
                                            <p class="font-14 mb-2">Reports</p>
                                            <h3><?php echo $total_reports; ?></h3>
                                        </div>
                                        <div class="state-icon">
                                            <img src="<?php echo $url; ?>assets/img/png-icon/report.png" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>

                            <!-- <div class="col-xl-2 col-lg-4 col-sm-6">
                                
                                <div class="card mb-30">
                                    <div class="statistics-bounce-rate support d-flex align-items-center justify-content-between">
                                        <div class="state-content">
                                            <p class="font-14 mb-2">Certificates</p>
                                            <h3>354</h3>
                                        </div>
                                        <div class="state-icon">
                                            <img src="<?php echo $url; ?>assets/img/png-icon/what.png" alt="">
                                        </div>
                                    </div>
                                </div>
                                
                            </div> -->

                            <div class="col-xl-4 col-lg-8">
    <div class="project-deadline d-flex align-items-center c2-bg mb-30">
        <div class="progress_23 mr-3">
            <div class="ProgressBar-wrap2 position-relative">
                <div class="ProgressBar ProgressBar_23" data-progress="<?php echo $project_percentage; ?>">
                    <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                        <circle transform="rotate(-90, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="85" />
                        <circle transform="rotate(-90, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                    </svg>
                    <span class="ProgressBar-percentage ProgressBar-percentage--count">
                        <?php echo $project_percentage; ?>%
                    </span>
                </div>
            </div>
        </div>

        <div>
            <h4 class="white font-20 mb-1">Project Completion</h4>
            <p><?php echo $completed_projects . " out of " . $total_projects . " projects completed."; ?></p>
        </div>
    </div>
</div>

                            <div class="col-xl-4 col-md-6">
                                <!-- News -->
                                <div class="card mb-30">
    <div class="card-body latest-update">
        <h4 class="mb-20">Latest Updates</h4>

        <!-- Existing Updates (Dynamically Load from Database) -->
        <?php
        include '../file/config.php';
        $query = "SELECT id, status, description FROM updates ORDER BY created_at DESC";
        $result = mysqli_query($conn, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<div class="item-single border-bottom d-flex align-items-center">
                    <div class="content">
                        <span class="font-14 bold text-' . getStatusColor($row['status']) . '">' . ucfirst($row['status']) . '</span>
                        <p class="mb-1">' . htmlspecialchars($row['description']) . '</p>
                    </div>
                  </div>';
        }

        function getStatusColor($status) {
            $colors = [
                'issue' => 'danger',
                'done' => 'success',
                'fixed' => 'info',
                'updated' => 'pink',
                'bug' => 'warning'
            ];
            return $colors[strtolower($status)] ?? 'secondary';
        }
        ?>

        <!-- Add New Update -->
        <div id="new-update-form" class="d-none">
            <div class="item-single border-bottom d-flex align-items-center">
                <div class="content">
                    <select id="status" class="form-control mb-2">
                        <option value="issue">Issue</option>
                        <option value="done">Done</option>
                        <option value="fixed">Fixed</option>
                        <option value="updated">Updated</option>
                        <option value="bug">Bug</option>
                    </select>
                    <textarea id="description" class="form-control mb-2" rows="2" placeholder="Enter update details..."></textarea>
                    <button class="btn btn-success btn-sm" onclick="saveUpdate()">Save</button>
                    <button class="btn btn-danger btn-sm" onclick="cancelUpdate()">Cancel</button>
                </div>
            </div>
        </div>

        <!-- Add Button -->
        <button class="btn btn-primary mt-3" onclick="showUpdateForm()">Add Update</button>
    </div>
</div>

                                <!-- End News -->

                                <!-- Card -->
                                <div class="card mb-30">
    <div class="card-body">
        <div class="title-content mb-4 mb-sm-0">
            <h4>Top Customers</h4>
        </div>
    </div>

    <!-- Table Responsive -->
    <div class="table-responsive">
        <table class="style--five text-nowrap">
            <thead>
                <tr>
                    <th>Profile</th>
                    <th>City</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../file/config.php'; // Include your database connection

                // Fetch the latest 10 customers
                $query = "SELECT cus_id, customer_name, city, profile_photo FROM customers ORDER BY created_at DESC LIMIT 10";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="img">
                                    <img src="' . $row['profile_photo'] . '" alt="">
                                </div>
                                <div class="name">' . htmlspecialchars($row['customer_name']) . '</div>
                            </div>
                        </td>
                        <td>' . htmlspecialchars($row['city']) . '</td>
                        <td><a href="customer-profile.php?id=' . $row['cus_id'] . '" class="follow-btn">Follow <i class="icofont-arrow-right"></i></a></td>
                    </tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
    <!-- End Table Responsive -->

    <!-- View All Button -->
    <div class="text-center mt-3 mb-4">
        <a href="../customer/customer-list.php" class="btn btn-primary">View All</a>
    </div>
</div>

                                <!-- End Card -->

                                <!-- Notifications -->
                                <div class="card notifications mb-30 mb-xl-0">
                                    <div class="card-body">
                                        <h5 class="mb-4">notifications</h5>

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="time">2 min ago</span>
                                                <p>Donec dapibus mauris id odio ornare tempus. Duis sit amet accumsan justo.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="time">2 min ago</span>
                                                <p>Donec dapibus mauris id odio ornare tempus. Duis sit amet accumsan justo.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="time">2 min ago</span>
                                                <p>Donec dapibus mauris id odio ornare tempus. Duis sit amet accumsan justo.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="time">2 min ago</span>
                                                <p>Donec dapibus mauris id odio ornare tempus. Duis sit amet accumsan justo.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="time">2 min ago</span>
                                                <p>Donec dapibus mauris id odio ornare tempus. Duis sit amet accumsan justo.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="time">2 min ago</span>
                                                <p>Donec dapibus mauris id odio ornare tempus. Duis sit amet accumsan justo.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single d-flex align-items-center">
                                            <div class="content">
                                                <span class="time">2 min ago</span>
                                                <p>Donec dapibus mauris id odio ornare tempus. Duis sit amet accumsan justo.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->
                                    </div>
                                </div>
                                <!-- End Notifications -->
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <!-- Card -->
                                <div class="card mb-30">
                                    <div class="card-body">
                                        <div class="row align-items-end">
                                            <div class="col-5 col-sm-6 col-lg-5">
                                                <div id="apex_column2-chart"></div>
                                            </div>
                                            <div class="col-7 col-sm-6 col-lg-7">
                                                <div class="">
                                                    <h4 class="mb-2">Registration</h4>
                                                    <p>Pellentesque tincidunt tristique neque, eget venenatis enim gravida.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card -->

                                <!-- Card -->
                                <div class="card todo-list mb-30">
    <div class="card-body">
        <h4 class="font-20 pb-3">Assigned Projects</h4>
        <p><?php echo date("l, d F Y"); ?></p>
    </div>

    <?php
    if (mysqli_num_rows($result_projects) > 0) {
        while ($row = mysqli_fetch_assoc($result_projects)) {
            $project_no = $row['project_no'];
            $customer_name = $row['customer_name'];
            $project_status = $row['project_status'];
            $creation_date = $row['creation_date'];

            // Determine the class based on project status
            $status_class = '';
            if ($project_status == 'Completed') {
                $status_class = 'level-not_important'; // Completed projects can be styled differently
            } elseif ($project_status == 'Pending') {
                $status_class = 'level-urgent'; // Pending projects can be styled as urgent
            } else {
                $status_class = 'level-important'; // Other statuses
            }

            // Determine if the project is completed
            $is_completed = ($project_status == 'Completed') ? 'line-through' : '';
            $is_checked = ($project_status == 'Completed') ? 'checked' : '';
            ?>
            <!-- Start Project Single -->
            <div class="single-row <?php echo $status_class; ?> border-bottom pt-3 pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex position-relative">
                        <!-- Custom Checkbox -->
                        <label class="custom-checkbox">
                            <input type="checkbox" <?php echo $is_checked; ?>>
                            <span class="checkmark"></span>
                        </label>
                        <!-- End Custom Checkbox -->

                        <!-- Project Details -->
                        <a href="project-details.php?project_no=<?php echo $project_no; ?>" class="todo-text <?php echo $is_completed; ?>">
                            <p class="card-text mb-1">Project #<?php echo $project_no; ?> - <?php echo $customer_name; ?></p>
                            <p class="label-text font-12 mb-0">Status: <?php echo $project_status; ?></p>
                            <p class="label-text font-12 mb-0">Created: <?php echo $creation_date; ?></p>
                        </a>
                        <!-- End Project Details -->
                    </div>

                    <div class="d-flex">
                        <!-- Assign To (Inspector) -->
                        <div class="assign_to">
                            <img src="<?php echo $url; ?>assets/img/svg/Info.svg" alt="" class="svg mb-2">
                            <div class="assign-content">
                                <div class="font-12 text-warning">Inspector</div>
                                <img src="<?php echo $url; ?>assets/img/avatar/info-avatar.png" alt="" class="assign-avatar">
                            </div>
                        </div>
                        <!-- End Assign To -->

                        <!-- Dropdown Button -->
                        <div class="dropdown-button">
                            <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                <div class="menu-icon style--two w-14 mr-0">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="edit-project.php?project_no=<?php echo $project_no; ?>">Edit</a>
                                <a href="#">Sort By</a>
                                <a href="#">Settings</a>
                                <a href="delete-project.php?project_no=<?php echo $project_no; ?>">Delete</a>
                            </div>
                        </div>
                        <!-- End Dropdown Button -->
                    </div>
                </div>
            </div>
            <!-- End Project Single -->
            <?php
        }
    } else {
        echo '<p>No projects assigned.</p>';
    }
    ?>
</div>
                                <!-- End Card -->

                                <!-- Card -->
                                <div class="card mb-30">
                                    <div class="card-body">
                                    <div class="row align-items-end">
                                        <div class="col-6">
                                            <div class="d-flex justify-content-start">
                                                <div class="ProgressBar-wrap2 position-relative">
                                                <div class="ProgressBar ProgressBar_4" data-progress="67">
                                                    <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                                                        <!-- on défini le l'angle et le centre de rotation du cercle -->
                                                        <circle transform="rotate(-90, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="85" />
                                                        <circle transform="rotate(-90, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                                                    </svg>
                                                    <span class="ProgressBar-percentage ProgressBar-percentage--count"></span>
                                                    <span class="ProgressBar-percentage--text">Bounce Rate</span>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="d-flex justify-content-start progress_5">
                                                <div class="ProgressBar-wrap2 position-relative">
                                                <div class="ProgressBar ProgressBar_5" data-progress="48">
                                                    <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                                                        <!-- on défini le l'angle et le centre de rotation du cercle -->
                                                        <circle transform="rotate(-90, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="85" />
                                                        <circle transform="rotate(-90, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                                                    </svg>
                                                    <span class="ProgressBar-percentage ProgressBar-percentage--count style--two"></span>
                                                    <span class="ProgressBar-percentage--text">Conversion</span>
                                                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <!-- End Card -->

                                <!-- Card -->
                                <div class="card mb-30 progress_20">
    <div class="card-body">
        <div class="d-flex justify-content-between pb-2 mb-4">
            <div class="progress-title">
                <h4 class="mb-1">Project Performance</h4>
                <p class="font-14">Overview of project completion and pending status.</p>
            </div>

            <div class="dropdown-button">
                <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                    <div class="menu-icon style--two mr-0">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#">Daily</a>
                    <a href="#">Sort By</a>
                    <a href="#">Monthly</a>
                </div>
            </div>
        </div>

        <div class="ProgressBar-wrap position-relative mb-3">
            <div class="ProgressBar ProgressBar_20" data-progress="<?php echo $completed_percentage; ?>">
                <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                    <circle transform="rotate(135, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="85" />
                    <circle transform="rotate(135, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                </svg>
                <span class="ProgressBar-percentage--text">
                    <?php echo ($completed_percentage >= 50) ? 'Increase' : 'Needs Improvement'; ?>
                </span>
                <span class="ProgressBar-percentage ProgressBar-percentage--count">
                    <?php echo $completed_percentage; ?>%
                </span>
            </div>
        </div>

        <div class="country-performance px-2 pt-3 pb-2">
            <div class="process-bar-wrapper">
                <span class="process-name">Projects Completed</span>
                <span class="process-width"><?php echo $completed_percentage; ?>%</span>
                <span class="process-bar" data-process-width="<?php echo $completed_percentage; ?>"></span>
            </div>

            <div class="process-bar-wrapper style--two">
                <span class="process-name">Pending Projects</span>
                <span class="process-width"><?php echo $pending_percentage; ?>%</span>
                <span class="process-bar" data-process-width="<?php echo $pending_percentage; ?>"></span>
            </div>
        </div>
    </div>
</div>
                                <!-- End Card -->

                                <!-- Today's Event -->
                                <div class="todays-evnet mb-30 mb-xl-0">
    <div class="bg-c2-light profile-widget-header">
        <h4 class="d-flex align-items-center">
            <img src="<?php echo $url; ?>assets/img/svg/calender-color.svg" alt="" class="svg mr-3">
            Today's Projects
        </h4>
    </div>
    <div class="card">
        <ul class="list-unstyled">
            <?php if (mysqli_num_rows($result_todays_projects) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result_todays_projects)): ?>
                    <li class="border-bottom">
                        <h5><?php echo htmlspecialchars($row['project_no']); ?></h5>
                        <div class="event-meta font-14 d-flex align-items-center">
                            <img src="<?php echo $url; ?>assets/img/svg/watch2.svg" alt="" class="svg">
                            <span class="time d-inline-block ml-2"><?php echo date("h:i A", strtotime($row['creation_date'])); ?></span>
                            <span class="date d-inline-block ml-2"><?php echo date("d F Y", strtotime($row['creation_date'])); ?></span>
                        </div>
                    </li>
                <?php endwhile; ?>
            <?php else: ?>
                <li class="text-center p-3">No projects created today.</li>
            <?php endif; ?>
        </ul>
    </div>
</div>
                                <!-- End Today's Event -->
                            </div>

                            <div class="col-xl-4 col-md-12">
                                <div class="row">
                                    <div class="col-xl-12 col-md-7">
                                        <!-- Timeline Wrap -->
                                        <div class="timeline-wrap mb-30">
                                            <div class="card pb-2">
                                                <div class="card-body">
                                                    <h4 class="mb-3">Timeline</h4>
                                                    <p class="black">Vestibulum blandit viverra convallis. Pellentesque ligula fermentum ut semper in, tincidunt nec dui.</p>
                                                </div>
                                            </div>
                                            <ul class="timeline style--two">
                                                <li class="event" data-date="12 October 2019">
                                                    <span>1:08 AM</span>
                                                    <h4>Vitae eius reiciendis voluptatum non non ut temporibus voluptatum enim.</h4>
                                                    <p>Aenean sed lorem est. Sed quis neque ut nibh suscipit imperdiet ac non augue. Aenean ornare sit amet lectus non tristique. Nunc ut volutpat lectus. Nulla velit augue, pulvinar sed nisi sit amet, eleifend fermentum est.</p>
                                                </li>
                                                <li class="event">
                                                    <span>8:00 PM</span>
                                                    <h4>Est accusamus rerum molestias.</h4>
                                                    <p>Aenean sed lorem est. Sed quis neque ut nibh suscipit imperdiet ac non augue. Aenean ornare sit amet lectus non tristique. Nunc ut volutpat lectus. Nulla velit augue, pulvinar sed nisi sit amet, eleifend fermentum est.</p>
                                                </li>
                                                <li class="event" data-date="13 October 2019">
                                                    <span>2:50 PM</span>
                                                    <h4>Quam aut exercitationem adipisci eaque quibusdam autem dolores nam.</h4>
                                                    <p>Aenean sed lorem est. Sed quis neque ut nibh suscipit imperdiet ac non augue. Aenean ornare sit amet lectus non tristique. Nunc ut volutpat lectus. Nulla velit augue, pulvinar sed nisi sit amet, eleifend fermentum est.</p>
                                                </li>
                                                <li class="event" data-date="14 October 2019">
                                                    <span>1:06 PM</span>
                                                    <h4>Mollitia assumenda aut sit vel consectetur labore eos debitis.</h4>
                                                    <p>Aenean sed lorem est. Sed quis neque ut nibh suscipit imperdiet ac non augue. Aenean ornare sit amet lectus non tristique. Nunc ut volutpat lectus. Nulla velit augue, pulvinar sed nisi sit amet, eleifend fermentum est.</p>
                                                </li>
                                                <li class="event" data-date="">
                                                    <span>11:21 PM</span>
                                                    <h4>Voluptas voluptas aut magnam maiores fuga veritatis est nam.</h4>
                                                    <p>Aenean sed lorem est. Sed quis neque ut nibh suscipit imperdiet ac non augue. Aenean ornare sit amet lectus non tristique. Nunc ut volutpat lectus. Nulla velit augue, pulvinar sed nisi sit amet, eleifend fermentum est.</p>
                                                </li>
                                                <li class="event" data-date="15 October 2019">
                                                    <span>7:10 PM</span>
                                                    <h4>Provident omnis nobis distinctio deserunt dolor excepturi enim.</h4>
                                                    <p>Aenean sed lorem est. Sed quis neque ut nibh suscipit imperdiet ac non augue. Aenean ornare sit amet lectus non tristique. Nunc ut volutpat lectus. Nulla velit augue, pulvinar sed nisi sit amet, eleifend fermentum est.</p>
                                                </li>
                                            </ul>
                                        </div>
                                        <!-- End Timeline Wrap -->
                                    </div>
                                    <div class="col-xl-12 col-md-5">
                                        <!-- Card -->
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-20">
                                                    <div class="">
                                                        <h4 class="mb-1">Transaction History</h4>
                                                    </div>
                        
                                                    <div class="dropdown-button">
                                                        <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                                                            <div class="menu-icon style--two mr-0">
                                                            <span></span>
                                                            <span></span>
                                                            <span></span>
                                                            </div>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-right">
                                                            <a href="#">Daily</a>
                                                            <a href="#">Sort By</a>
                                                            <a href="#">Monthly</a>
                                                        </div>
                                                    </div>
                                                </div>
                        
                                                <!-- Transaction History -->
                                                <div class="trans-history">
                                                    <!-- Transaction History Item -->
                                                    <div class="border-bottom mb-20 pb-20 d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="img mr-3">
                                                            <img src="<?php echo $url; ?>assets/img/png-icon/th1.png" class="img-40" alt="">
                                                            </div>
                                                            <div class="content">
                                                                <h4>Clothings</h4>
                                                                <span class="font-12">Food Category</span>
                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <h4>$2654</h4>
                                                            <span class="font-12">USD</span>
                                                        </div>
                                                    </div>
                                                    <!-- End Transaction History Item -->

                                                    
                                                    <!-- Transaction History Item -->
                                                    <div class="border-bottom mb-20 pb-20 d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="img mr-3">
                                                            <img src="<?php echo $url; ?>assets/img/png-icon/th2.png" class="img-40" alt="">
                                                            </div>
                                                            <div class="content">
                                                                <h4>Company</h4>
                                                                <span class="font-12">Food Category</span>
                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <h4>$2654</h4>
                                                            <span class="font-12">USD</span>
                                                        </div>
                                                    </div>
                                                    <!-- End Transaction History Item -->

                                                    <!-- Transaction History Item -->
                                                    <div class="border-bottom mb-20 pb-20 d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="img mr-3">
                                                            <img src="<?php echo $url; ?>assets/img/png-icon/th3.png" class="img-40" alt="">
                                                            </div>
                                                            <div class="content">
                                                                <h4>Super Shop</h4>
                                                                <span class="font-12">Food Category</span>
                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <h4>$2654</h4>
                                                            <span class="font-12">USD</span>
                                                        </div>
                                                    </div>
                                                    <!-- End Transaction History Item -->

                                                    <!-- Transaction History Item -->
                                                    <div class="border-bottom mb-20 pb-20 d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="img mr-3">
                                                            <img src="<?php echo $url; ?>assets/img/png-icon/th5.png" class="img-40" alt="">
                                                            </div>
                                                            <div class="content">
                                                                <h4>KD co.</h4>
                                                                <span class="font-12">Food Category</span>
                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <h4>$2654</h4>
                                                            <span class="font-12">USD</span>
                                                        </div>
                                                    </div>
                                                    <!-- End Transaction History Item -->

                                                    <!-- Transaction History Item -->
                                                    <div class="border-bottom mb-20 pb-20 d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="img mr-3">
                                                            <img src="<?php echo $url; ?>assets/img/png-icon/th6.png" class="img-40" alt="">
                                                            </div>
                                                            <div class="content">
                                                                <h4>Kids Shop</h4>
                                                                <span class="font-12">Food Category</span>
                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <h4>$2654</h4>
                                                            <span class="font-12">USD</span>
                                                        </div>
                                                    </div>
                                                    <!-- End Transaction History Item -->

                                                    <!-- Transaction History Item -->
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex align-items-center">
                                                            <div class="img mr-3">
                                                            <img src="<?php echo $url; ?>assets/img/png-icon/th4.png" class="img-40" alt="">
                                                            </div>
                                                            <div class="content">
                                                                <h4>Food</h4>
                                                                <span class="font-12">Food Category</span>
                                                            </div>
                                                        </div>

                                                        <div class="">
                                                            <h4>$2654</h4>
                                                            <span class="font-12">USD</span>
                                                        </div>
                                                    </div>
                                                    <!-- End Transaction History Item -->
                                                </div>
                                                <!-- End Transaction History -->
                                            </div>
                                        </div>
                                        <!-- End Card -->
                                    </div>
                                </div>
                            </div>
                        </div>
                            
                    </div>
                </div>            
                        
            </div>
            <!-- End Main Content -->
        </div>


<script>
            function showUpdateForm() {
    document.getElementById('new-update-form').classList.remove('d-none');
}

function cancelUpdate() {
    document.getElementById('new-update-form').classList.add('d-none');
}

function saveUpdate() {
    var status = document.getElementById('status').value;
    var description = document.getElementById('description').value;

    if (description.trim() === '') {
        alert('Description cannot be empty.');
        return;
    }

    // AJAX request to save data
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "save-update.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            location.reload(); // Reload the page to show the new update
        }
    };
    xhr.send("status=" + encodeURIComponent(status) + "&description=" + encodeURIComponent(description));
}

</script>
        <!-- End Main Wrapper -->
<?php 
include_once('../inc/footer.php');
?>        