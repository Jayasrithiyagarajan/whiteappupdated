<?php
include_once('../file/config.php');
include '../file/auth.php';
include_once('../inc/function.php');

// Check if the user is logged in and has the role of QC Checker
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'qcchecker') {
    header('Location: ../index.php');
    exit();
}

// Get the logged-in QC Checker's name or ID from the session
$qc_checker_name = $_SESSION['username']; // Assuming you store the QC Checker's name in the session
$qc_checker_id = $_SESSION['user_id']; // Assuming you store the QC Checker's ID in the session

// Query to get total projects count for the logged-in QC Checker
$stmt_projects = mysqli_prepare($conn, "SELECT COUNT(*) AS total_projects FROM project_info WHERE qc_checker_name = ?");
mysqli_stmt_bind_param($stmt_projects, "s", $qc_checker_name);
mysqli_stmt_execute($stmt_projects);
$result_projects = mysqli_stmt_get_result($stmt_projects);
$total_projects = mysqli_fetch_assoc($result_projects)['total_projects'];

// Query to get total pending QC checks for the logged-in QC Checker
$stmt_pending_qc = mysqli_prepare($conn, "SELECT COUNT(*) AS total_pending_qc FROM project_info WHERE qc_checker_name = ? AND qc_status = 'Pending'");
mysqli_stmt_bind_param($stmt_pending_qc, "s", $qc_checker_name);
mysqli_stmt_execute($stmt_pending_qc);
$result_pending_qc = mysqli_stmt_get_result($stmt_pending_qc);
$total_pending_qc = mysqli_fetch_assoc($result_pending_qc)['total_pending_qc'];

// Query to get total completed QC checks for the logged-in QC Checker
$stmt_completed_qc = mysqli_prepare($conn, "SELECT COUNT(*) AS total_completed_qc FROM project_info WHERE qc_checker_name = ? AND qc_status = 'Completed'");
mysqli_stmt_bind_param($stmt_completed_qc, "s", $qc_checker_name);
mysqli_stmt_execute($stmt_completed_qc);
$result_completed_qc = mysqli_stmt_get_result($stmt_completed_qc);
$total_completed_qc = mysqli_fetch_assoc($result_completed_qc)['total_completed_qc'];

// Query to get recent projects for the logged-in QC Checker
$query_recent_projects = "SELECT project_no, customer_name, project_status, creation_date 
                          FROM project_info 
                          WHERE qc_checker_name = '$qc_checker_name'
                          ORDER BY creation_date DESC 
                          LIMIT 5"; // Adjust the limit as needed
$result_recent_projects = mysqli_query($conn, $query_recent_projects);

?>

<!-- Main Content -->
<div class="main-content">
   <div class="container-fluid">
      <div class="row">
         <div class="col-xl-3 col-sm-6">
            <!-- Card -->
            <div class="card mb-30">
               <div class="state">
                  <div class="d-flex align-items-center flex-wrap">
                     <div class="state-icon d-flex justify-content-center">
                        <i class="fa-solid fa-folder-open fa-3x text-primary"></i> <!-- Folder Open Icon -->
                     </div>
                     <div class="state-content">
                        <p class="font-14 mb-2">Total Projects</p>
                        <h2><?php echo $total_projects; ?></h2>
                     </div>
                  </div>
               </div>
            </div>
            <!-- End Card -->
         </div>

         <div class="col-xl-3 col-sm-6">
            <!-- Card -->
            <div class="card mb-30">
               <div class="state">
                  <div class="d-flex align-items-center flex-wrap">
                     <div class="state-icon d-flex justify-content-center">
                        <i class="fa-solid fa-hourglass-half fa-3x text-warning"></i> <!-- Pending Icon -->
                     </div>
                     <div class="state-content">
                        <p class="font-14 mb-2">Pending QC Checks</p>
                        <h2><?php echo $total_pending_qc; ?></h2>
                     </div>
                  </div>
               </div>
            </div>
            <!-- End Card -->
         </div>

         <div class="col-xl-3 col-sm-6">
            <!-- Card -->
            <div class="card mb-30">
               <div class="state">
                  <div class="d-flex align-items-center flex-wrap">
                     <div class="state-icon d-flex justify-content-center">
                        <i class="fa-solid fa-check-circle fa-3x text-success"></i> <!-- Completed Icon -->
                     </div>
                     <div class="state-content">
                        <p class="font-14 mb-2">Completed QC Checks</p>
                        <h2><?php echo $total_completed_qc; ?></h2>
                     </div>
                  </div>
               </div>
            </div>
            <!-- End Card -->
         </div>

         <div class="col-xl-3 col-sm-6">
            <!-- Card -->
            <div class="card mb-30">
               <div class="state">
                  <div class="d-flex align-items-center flex-wrap">
                     <div class="state-icon d-flex justify-content-center">
                        <i class="fa-solid fa-exclamation-triangle fa-3x text-danger"></i> <!-- Expiring Icon -->
                     </div>
                     <div class="state-content">
                        <p class="font-14 mb-2">Expiring Stickers</p>
                        <h2>46</h2>
                     </div>
                  </div>
               </div>
            </div>
            <!-- End Card -->
         </div>

         <div class="col-xl-6 col-lg-6">
            <!-- Card -->
            <div class="card pb-2 mb-30">
               <div class="p-4">
                  <div class="row">
                     <div class="col-xl-12 mb-40">
                        <h4 class="mb-3">Recent Projects Timeline</h4>
                        <p>Overview of the most recent projects and their status in a timeline format.</p>
                     </div>

                     <div class="col-xl-12 p-4">
                        <!-- Timeline Wrap -->
                        <div id="timeline-wrap">
                           <ul class="timeline">
                              <?php while ($row = mysqli_fetch_assoc($result_recent_projects)): ?>
                                 <li class="event" data-date="<?php echo htmlspecialchars($row['creation_date']); ?>">
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
                  </div>
               </div>
            </div>
            <!-- End Card -->
         </div>

         <div class="col-xl-6 col-lg-6">
            <!-- Card -->
            <div class="card mb-30">
               <div class="card-body">
                  <div class="d-flex align-items-start align-items-sm-end justify-content-between mb-3">
                     <div class="">
                        <h4 class="mb-1">Ongoing QC Checks</h4>
                        <p class="font-14">List of projects pending QC checks.</p>
                     </div>
                  </div>

                  <!-- Product List -->
                  <div class="product-list">
                     <?php
                     // Query to get ongoing QC checks for the logged-in QC Checker
                     $query_ongoing_qc = "SELECT project_no, customer_name, project_status 
                                          FROM project_info 
                                          WHERE qc_checker_name = '$qc_checker_name' AND qc_status = 'Pending' 
                                          ORDER BY creation_date DESC 
                                          LIMIT 4";
                     $result_ongoing_qc = mysqli_query($conn, $query_ongoing_qc);

                     while ($row = mysqli_fetch_assoc($result_ongoing_qc)): ?>
                        <div class="product-list-item mb-20 d-flex justify-content-between align-items-center">
                           <div class="d-flex align-items-center">
                              <div class="icon mr-3">
                                 <i class="fa-solid fa-file-alt fa-2x text-primary"></i> <!-- Document Icon -->
                              </div>
                              <div class="content">
                                 <p class="black mb-1"><?= htmlspecialchars($row['project_no']) ?></p>
                                 <span class="c3 bold font-14">Client: <?= htmlspecialchars($row['customer_name']) ?></span>
                              </div>
                           </div>
                           <p class="font-14"><?= htmlspecialchars($row['project_status']) ?></p>
                        </div>
                     <?php endwhile; ?>
                  </div>
                  <!-- End Product List -->
               </div>
            </div>
            <!-- End Card -->
         </div>

         <div class="col-xl-12">
            <!-- Card -->
            <div class="card">
               <div class="card-body pb-0">
                  <div class="d-flex justify-content-between">
                     <div class="title-content mb-4">
                        <h4 class="mb-2">Recent Projects</h4>
                        <p class="font-14">List of recent projects assigned to you for QC checks.</p>
                     </div>
                  </div>
               </div>

               <div class="table-responsive">
                  <table id="job-table" class="order-list-table style--three table-centered text-nowrap">
                     <thead>
                        <tr>
                           <th>Project ID</th>
                           <th>Start Date</th>
                           <th>Customer</th>
                           <th>Status</th>
                           <th>QC Status</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        // Query to get recent projects for the logged-in QC Checker
                        $query_recent = "SELECT * FROM project_info WHERE qc_checker_name = '$qc_checker_name' ORDER BY creation_date DESC LIMIT 10";
                        $result_recent = mysqli_query($conn, $query_recent);

                        if ($result_recent->num_rows > 0) {
                           while ($row = $result_recent->fetch_assoc()) {
                              ?>
                              <tr>
                                 <td class="bold"><?php echo "#" . str_pad($row["project_no"], 5, "0", STR_PAD_LEFT); ?></td>
                                 <td><?php echo date("d M Y", strtotime($row["creation_date"])); ?></td>
                                 <td><?php echo htmlspecialchars($row["customer_name"]); ?></td>
                                 <td class="status-btn">
                                    <a href="#" class="btn s_alert <?php echo ($row["project_status"] === "Completed") ? 'bg-success-light text-success' : 'bg-danger-light text-danger'; ?> mb-10">
                                       <?php echo ($row["project_status"] === "Completed") ? 'Completed' : 'Pending'; ?>
                                    </a>
                                 </td>
                                 <td class="status-btn">
                                    <a href="#" class="btn s_alert <?php echo ($row["qc_status"] === "Completed") ? 'bg-success-light text-success' : 'bg-danger-light text-danger'; ?> mb-10">
                                       <?php echo ($row["qc_status"] === "Completed") ? 'Completed' : 'Pending'; ?>
                                    </a>
                                 </td>
                                 <td>
                                    <a href="../job/job-details.php?id=<?php echo $row['project_no']; ?>">
                                       <button type="button" class="details-btn">
                                          Details <i class="icofont-arrow-right"></i>
                                       </button>
                                    </a>
                                 </td>
                              </tr>
                              <?php
                           }
                        } else {
                           echo "<tr><td colspan='6' class='text-center'>No records found.</td></tr>";
                        }
                        ?>
                     </tbody>
                  </table>
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