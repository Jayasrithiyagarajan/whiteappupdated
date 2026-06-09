<?php 
include_once('../inc/function.php');
include_once('../file/config.php');

// Check if the user is logged in
$logged_in_user = $_SESSION['username'] ?? null; // Replace with the appropriate session key
$user_role = $_SESSION['role'] ?? null; // Assuming you have a role stored in the session

if (!$logged_in_user || $user_role !== 'inspector') {
    // Redirect to login page if not logged in or not an inspector
    header("Location: ../index.php");
    exit;
}

// Queries to get counts from each table for the logged-in inspector
$query_projects = "SELECT COUNT(*) AS total_projects FROM project_info WHERE inspector_name = '$logged_in_user'";
$query_checklists = "SELECT COUNT(*) AS total_checklists FROM checklist_information WHERE inspected_by = '$logged_in_user'";
$query_reports = "SELECT COUNT(*) AS total_reports FROM reports WHERE issued_by = '$logged_in_user'";

// Execute queries
$result_projects = mysqli_query($conn, $query_projects);
$result_checklists = mysqli_query($conn, $query_checklists);
$result_reports = mysqli_query($conn, $query_reports);

// Fetch results
$total_projects = mysqli_fetch_assoc($result_projects)['total_projects'];
$total_checklists = mysqli_fetch_assoc($result_checklists)['total_checklists'];
$total_reports = mysqli_fetch_assoc($result_reports)['total_reports'];

// Query to get total projects for the logged-in inspector
$query_total_projects = "SELECT COUNT(*) AS total_projects FROM project_info WHERE inspector_name = '$logged_in_user'";
$result_total_projects = mysqli_query($conn, $query_total_projects);
$total_projects = mysqli_fetch_assoc($result_total_projects)['total_projects'];

// Query to get completed projects for the logged-in inspector
$query_completed_projects = "SELECT COUNT(*) AS completed_projects FROM project_info WHERE inspector_name = '$logged_in_user' AND project_status = 'Completed'";
$result_completed_projects = mysqli_query($conn, $query_completed_projects);
$completed_projects = mysqli_fetch_assoc($result_completed_projects)['completed_projects'];

// Calculate the percentage (avoid division by zero)
$project_percentage = ($total_projects > 0) ? round(($completed_projects / $total_projects) * 100, 2) : 0;

// Fetch projects created today for the logged-in inspector
$query_todays_projects = "SELECT project_no, creation_date FROM project_info WHERE inspector_name = '$logged_in_user' AND DATE(creation_date) = CURDATE()";
$result_todays_projects = mysqli_query($conn, $query_todays_projects);

// Fetch total projects for the logged-in inspector
$query_total = "SELECT COUNT(*) AS total FROM project_info WHERE inspector_name = '$logged_in_user'";
$result_total = mysqli_query($conn, $query_total);
$total_projects = mysqli_fetch_assoc($result_total)['total'];

// Fetch completed projects for the logged-in inspector
$query_completed = "SELECT COUNT(*) AS completed FROM project_info WHERE inspector_name = '$logged_in_user' AND project_status = 'Completed'";
$result_completed = mysqli_query($conn, $query_completed);
$completed_projects = mysqli_fetch_assoc($result_completed)['completed'];

// Fetch pending projects for the logged-in inspector
$query_pending = "SELECT COUNT(*) AS pending FROM project_info WHERE inspector_name = '$logged_in_user' AND project_status = 'Pending'";
$result_pending = mysqli_query($conn, $query_pending);
$pending_projects = mysqli_fetch_assoc($result_pending)['pending'];

// Calculate percentages (avoid division by zero)
$completed_percentage = ($total_projects > 0) ? round(($completed_projects / $total_projects) * 100, 2) : 0;
$pending_percentage = ($total_projects > 0) ? round(($pending_projects / $total_projects) * 100, 2) : 0;

// Fetch recent projects with their status for the logged-in inspector
$query_recent_projects = "SELECT project_no, customer_name, project_status, creation_date 
                          FROM project_info 
                          WHERE inspector_name = '$logged_in_user'
                          ORDER BY creation_date DESC 
                          LIMIT 5"; // Adjust the limit as needed

$result_recent_projects = mysqli_query($conn, $query_recent_projects);

// Query to fetch projects assigned to the inspector
$query_projects = "SELECT project_no, customer_name, project_status, creation_date 
                   FROM project_info 
                   WHERE inspector_name = '$logged_in_user' 
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

                <!-- <div class="mx-2 mx-lg-4 mx-xl-5"> -->
                <div>
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
                                    <h3><?php echo $logged_in_user; ?></h3>
                                    <p class="font-14">Inspector</p>
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

                <div class="col-xl-6 col-lg-10">
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
                    
                <div class="card mb-30">
    <div class="card-body">
        <h4 class="mb-20 text-center">Latest News</h4>

        <?php
        include '../file/config.php';
        $query = "SELECT id, news_text, created_at FROM news ORDER BY created_at DESC LIMIT 5";
        $result = mysqli_query($conn, $query);

        // Define an array of Bootstrap color classes for variety
        $colors = ['primary', 'success', 'warning', 'info', 'danger'];
        $i = 0;

        while ($row = mysqli_fetch_assoc($result)) {
            $color = $colors[$i % count($colors)]; // Rotate colors
            echo '<div class="card bg-' . $color . ' text-white mb-3">
                    <div class="card-body">
                        <p class="mb-1 font-weight-bold">' . htmlspecialchars($row['news_text']) . '</p>
                        <small class="text-white-50">' . date("F j, Y, g:i A", strtotime($row['created_at'])) . '</small>
                    </div>
                  </div>';
            $i++;
        }
        ?>
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
                    <!-- Notifications -->
<!-- Notifications -->
<div class="card notifications mb-30 mb-xl-0">
    <div class="card-body">
        <h5 class="mb-4">Notifications</h5>

        <?php
        // Pagination logic
        $limit = 5; // Number of notifications per page
        $page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Current page number
        $offset = ($page - 1) * $limit; // Offset for SQL query

        // Query to fetch total number of assigned projects
        $query_total_assigned = "SELECT COUNT(*) AS total FROM project_info WHERE inspector_name = '$logged_in_user'";
        $result_total_assigned = mysqli_query($conn, $query_total_assigned);
        $total_assigned = mysqli_fetch_assoc($result_total_assigned)['total'];

        // Query to fetch assigned projects with pagination
        $query_assigned_projects = "SELECT project_no, customer_name, creation_date 
                                    FROM project_info 
                                    WHERE inspector_name = '$logged_in_user' 
                                    ORDER BY creation_date DESC 
                                    LIMIT $limit OFFSET $offset";

        $result_assigned_projects = mysqli_query($conn, $query_assigned_projects);

        if (mysqli_num_rows($result_assigned_projects) > 0) {
            while ($row = mysqli_fetch_assoc($result_assigned_projects)) {
                $project_no = htmlspecialchars($row['project_no']);
                $customer_name = htmlspecialchars($row['customer_name']);
                $creation_date = htmlspecialchars($row['creation_date']);
                ?>
                <!-- Notification Item -->
                <a href="#" class="item-single border-bottom d-flex align-items-center">
                    <div class="content">
                        <span class="time"><?php echo date("h:i A", strtotime($creation_date)); ?></span>
                        <p><strong>Project #<?php echo $project_no; ?></strong> assigned to you.</p>
                        <p>Customer: <?php echo $customer_name; ?></p>
                    </div>
                </a>
                <!-- End Notification Item -->
                <?php
            }
        } else {
            echo '<p class="text-center p-3">No projects assigned.</p>';
        }
        ?>

        <!-- Pagination -->
        <?php if ($total_assigned > $limit): ?>
            <div class="pagination mt-3">
                <?php
                $total_pages = ceil($total_assigned / $limit); // Calculate total pages
                $prev_page = ($page > 1) ? $page - 1 : 1;
                $next_page = ($page < $total_pages) ? $page + 1 : $total_pages;
                ?>
                <a href="?page=<?php echo $prev_page; ?>" class="btn btn-sm btn-outline-primary <?php echo ($page == 1) ? 'disabled' : ''; ?>">Previous</a>
                <span class="mx-2">Page <?php echo $page; ?> of <?php echo $total_pages; ?></span>
                <a href="?page=<?php echo $next_page; ?>" class="btn btn-sm btn-outline-primary <?php echo ($page == $total_pages) ? 'disabled' : ''; ?>">Next</a>
            </div>
        <?php endif; ?>
        <!-- End Pagination -->
    </div>
</div>
<!-- End Notifications -->





<!-- End Notifications -->
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
        <h4 class="font-20 pb-3">Completed Projects</h4>
        <p><?php echo date("l, d F Y"); ?></p>
    </div>

    <?php
    // Query to fetch completed projects assigned to the inspector
    $query_completed_projects = "SELECT project_no, customer_name, project_status, creation_date 
                                 FROM project_info 
                                 WHERE inspector_name = '$logged_in_user' 
                                 AND project_status = 'Completed' 
                                 ORDER BY creation_date DESC 
                                 LIMIT 5"; // Adjust the limit as needed

    $result_completed_projects = mysqli_query($conn, $query_completed_projects);

    if (mysqli_num_rows($result_completed_projects) > 0) {
        while ($row = mysqli_fetch_assoc($result_completed_projects)) {
            $project_no = $row['project_no'];
            $customer_name = $row['customer_name'];
            $project_status = $row['project_status'];
            $creation_date = $row['creation_date'];

            // Determine the class based on project status
            $status_class = 'level-not_important'; // Completed projects are styled differently

            // Determine if the project is completed
            $is_completed = 'line-through'; // Completed projects have a line-through style
            $is_checked = 'checked'; // Completed projects have the checkbox checked
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
        echo '<p class="text-center p-3">No completed projects found.</p>';
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
            <h4 class="mb-3">Recent Projects Timeline</h4>
            <p class="black">Overview of the most recent projects and their status in a timeline format.</p>
        </div>
    </div>
    <ul class="timeline style--two">
        <?php while ($row = mysqli_fetch_assoc($result_recent_projects)): ?>
            <li class="event" data-date="<?php echo htmlspecialchars($row['creation_date']); ?>">
                <span><?php echo date("h:i A", strtotime($row['creation_date'])); ?></span>
                <h4><?php echo htmlspecialchars($row['project_no']); ?></h4>
                <p><strong>Customer:</strong> <?php echo htmlspecialchars($row['customer_name']); ?></p>
                <p><strong>Status:</strong> 
                    <?php 
                        $status = htmlspecialchars($row['project_status']);
                        $badgeClass = ($status == 'Pending') ? 'badge-danger' : (($status == 'Completed') ? 'badge-success' : 'badge-secondary'); 
                    ?>
                    <span class="badge <?php echo $badgeClass; ?>"><?php echo $status; ?></span>
                </p>
            </li>
        <?php endwhile; ?>
    </ul>
</div>
                            <!-- End Timeline Wrap -->
                        </div>
                        <!-- Pending Checklists Section -->
                        <div class="col-xl-12 col-md-7">
<div class="card todo-list mb-30">
    <div class="card-body">
        <h4 class="font-20 pb-3">Pending Checklists</h4>
        <p><?php echo date("l, d F Y"); ?></p>
    </div>

    <?php
    // Query to fetch pending checklists assigned to the inspector
    $query_pending_checklists = "SELECT project_no, checklist_type, creation_date 
                                 FROM project_info 
                                 WHERE inspector_name = '$logged_in_user' 
                                 AND checklist_status = 'Pending' 
                                 ORDER BY creation_date DESC 
                                 LIMIT 5"; // Adjust the limit as needed

    $result_pending_checklists = mysqli_query($conn, $query_pending_checklists);

    if (mysqli_num_rows($result_pending_checklists) > 0) {
        while ($row = mysqli_fetch_assoc($result_pending_checklists)) {
            $project_no = $row['project_no'];
            $checklist_type = $row['checklist_type'];
            $creation_date = $row['creation_date'];
            ?>
            <!-- Checklist Single -->
            <div class="single-row level-urgent border-bottom pt-3 pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex position-relative">
                        <!-- Custom Checkbox -->
                        <label class="custom-checkbox">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                        </label>
                        <!-- End Custom Checkbox -->

                        <!-- Checklist Details -->
                        <!-- <a href="project-details.php?project_no=<?php echo $project_no; ?>" class="todo-text"> -->
                        <a href="../job/job-details.php?id=<?php echo $project_no; ?>" class="todo-text">
                            <p class="card-text mb-1">Checklist: <?php echo $checklist_type; ?></p>
                            <p class="label-text font-12 mb-0">Project #<?php echo $project_no; ?></p>
                            <p class="label-text font-12 mb-0">Created: <?php echo $creation_date; ?></p>
                        </a>
                        <!-- End Checklist Details -->
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
                        <!-- <div class="dropdown-button">
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
                        </div> -->
                        <!-- End Dropdown Button -->
                    </div>
                </div>
            </div>
            <!-- End Checklist Single -->
            <?php
        }
    } else {
        echo '<p class="text-center p-3">No pending checklists found.</p>';
    }
    ?>
</div>
</div>
<!-- End Pending Checklists Section -->

<!-- Completed Checklists with Pending Reports Section -->
<div class="col-xl-12 col-md-7">
<div class="card todo-list mb-30">
    <div class="card-body">
        <h4 class="font-20 pb-3">Completed Checklists (Pending Reports)</h4>
        <p><?php echo date("l, d F Y"); ?></p>
    </div>

    <?php
    // Query to fetch completed checklists with pending reports
    $query_completed_checklists_pending_reports = "SELECT project_no, checklist_type, creation_date 
                                                   FROM project_info 
                                                   WHERE inspector_name = '$logged_in_user' 
                                                   AND checklist_status = 'Created' 
                                                   AND report_status = 'Pending' 
                                                   ORDER BY creation_date DESC 
                                                   LIMIT 5"; // Adjust the limit as needed

    $result_completed_checklists_pending_reports = mysqli_query($conn, $query_completed_checklists_pending_reports);

    if (mysqli_num_rows($result_completed_checklists_pending_reports) > 0) {
        while ($row = mysqli_fetch_assoc($result_completed_checklists_pending_reports)) {
            $project_no = $row['project_no'];
            $checklist_type = $row['checklist_type'];
            $creation_date = $row['creation_date'];
            ?>
            <!-- Checklist Single -->
            <div class="single-row level-important border-bottom pt-3 pb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex position-relative">
                        <!-- Custom Checkbox -->
                        <label class="custom-checkbox">
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                        </label>
                        <!-- End Custom Checkbox -->

                        <!-- Checklist Details -->
                        <a href="project-details.php?project_no=<?php echo $project_no; ?>" class="todo-text line-through">
                            <p class="card-text mb-1">Checklist: <?php echo $checklist_type; ?></p>
                            <p class="label-text font-12 mb-0">Project #<?php echo $project_no; ?></p>
                            <p class="label-text font-12 mb-0">Created: <?php echo $creation_date; ?></p>
                        </a>
                        <!-- End Checklist Details -->
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
            <!-- End Checklist Single -->
            <?php
        }
    } else {
        echo '<p class="text-center p-3">No completed checklists with pending reports found.</p>';
    }
    ?>
</div>
</div>
<!-- End Completed Checklists with Pending Reports Section -->






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