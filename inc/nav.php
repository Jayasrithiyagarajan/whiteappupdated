<?php
//session_start(); // Start the session

// Check if the role is set in the session
$role = isset($_SESSION['role']) ? $_SESSION['role'] : ''; // Default to empty if not set

// If the role is not set or is 'guest', redirect to login page or another appropriate page
if ($role == '' || $role == 'guest') {
    header("Location: ../index.php"); // Redirect to login if the user is not logged in or is a guest
    exit();
}
?>


<!-- Main Wrapper -->
<div class="main-wrapper">
   <!-- Sidebar -->
   <nav class="sidebar" data-trigger="scrollbar">
      <!-- Sidebar Header -->
      <div class="sidebar-header d-none d-lg-block">
         <!-- Sidebar Toggle Pin Button -->
         <div class="sidebar-toogle-pin">
            <i class="icofont-tack-pin"></i>
         </div>
         <!-- End Sidebar Toggle Pin Button -->
      </div>
      <!-- End Sidebar Header -->

      <!-- Sidebar Body -->
      <div class="sidebar-body">
         <!-- Nav -->
         <ul class="nav">
            <li class="nav-category">Main</li>
            <!-- Dashboard Link Based on Role -->
            <!-- <li class="active">
   <a href="<?php echo $url; ?>dashboard/<?php echo $role; ?>.php">
      <i class="icofont-pie-chart"></i>
      <span class="link-title">Dashboard</span>
   </a>
</li> -->



<li class="active">
   <a href="<?php 
      // Conditional check for role to link to the correct dashboard
      if ($role === 'admin') {
         echo $url . 'dashboard/index.php'; // Admin dashboard
      } elseif ($role === 'customer') {
         echo $url . 'dashboard/customer_new.php'; // Inspector dashboard
      } elseif ($role === 'inspector') {
         echo $url . 'dashboard/inspector.php'; // Inspector dashboard
      } elseif ($role === 'reviewer') {
         echo $url . 'dashboard/reviewer.php'; // Reviewer dashboard      
      } elseif ($role === 'document controller') {
      echo $url . 'dashboard/document controller.php'; // Reviewer dashboard
      } elseif ($role === 'quality controller') {
         echo $url . 'dashboard/quality controller.php'; // Reviewer dashboard
      }
      else {
         echo $url . 'dashboard/'; // Default fallback
      }
   ?>">
      <i class="icofont-pie-chart"></i>
      <span class="link-title">Dashboard</span>
   </a>
</li>



            <!-- Sticker Portal (Visible to Admin Only) -->
           

<?php if ($role === 'admin'): ?>
    <li>
        <a href="#">
            <i class="icofont-ui-tag"></i>
            <span class="link-title">Sticker Portal</span>
        </a>
        <ul class="nav sub-menu">
            <li><a href="<?php echo $url; ?>sticker/sticker-status.php">Sticker Status</a></li>
            <li><a href="<?php echo $url; ?>sticker/add-sticker.php">Add Sticker</a></li>
            <li><a href="<?php echo $url; ?>sticker/sticker-list.php">Sticker List</a></li>
        </ul>
    </li>
<?php elseif (in_array($role, ['reviewer', 'document controller', 'quality controller'])): ?>
    <li>
        <a href="#">
            <i class="icofont-ui-tag"></i>
            <span class="link-title">Sticker Portal</span>
        </a>
        <ul class="nav sub-menu">
            <li><a href="<?php echo $url; ?>sticker/sticker-status.php">Sticker Status</a></li>
            <li><a href="<?php echo $url; ?>sticker/sticker-list.php">Sticker List</a></li>
        </ul>
    </li>
<?php elseif ($role === 'inspector'): ?>
    <!-- <li>
        <a href="<?php echo $url; ?>inspector/schedule-manager.php">
            <i class="icofont-calendar"></i>
            <span class="link-title">Schedule Manager</span>
        </a>
    </li> -->
    <li>
        <a href="#">
            <i class="icofont-shopping-cart"></i>
            <span class="link-title">Sticker Portal</span>
        </a>
        <ul class="nav sub-menu">
            <li><a href="<?php echo $url; ?>sticker/sticker-status.php">Sticker Status</a></li>
            <li><a href="<?php echo $url; ?>sticker/sticker-list.php">Sticker List</a></li>
        </ul>
    </li>
<?php endif; ?>



            <!-- Job Portal (Visible to All Users) -->
            <li>
               <a href="#">
                  <!--<i class="icofont-navigation-menu"></i>-->
                              <i class="icofont-briefcase"></i>

                  <span class="link-title">Job Portal</span>
               </a>
               <!-- Sub Menu -->
               <ul class="nav sub-menu">
                  <?php if ($_SESSION['role'] === 'admin'): ?>
                     <li><a href="<?php echo $url; ?>job/create-job.php">Create New Project</a></li>
                  <?php endif; ?>
                  <li><a href="<?php echo $url; ?>job/overall-job-list.php">Over all Projects</a></li>
                  <li><a href="<?php echo $url; ?>job/pending_projects.php">Pending Projects</a></li>
               </ul>
               <!-- End Sub Menu -->
            </li>

            <!-- Certificate Portal (Visible to Admin Only) -->
           
               <li>
                  <a href="#">
                     <!--<i class="icofont-navigation-menu"></i>-->
                                 <i class="icofont-certificate-alt-2"></i>

                     <span class="link-title">Certificate Portal</span>
                  </a>
                  <!-- Sub Menu -->
                  <ul class="nav sub-menu">
                     <li><a href="<?php echo $url; ?>document/health-check/index.php">Offshore Crane Health Check</a></li>
                     <li><a href="<?php echo $url; ?>document/lifting/index.php">Below the Hook Lifting Gears</a></li>
                     <li><a href="<?php echo $url; ?>document/loadtest/index.php">Thorough Examination</a></li>
                     <li><a href="<?php echo $url; ?>document/mobile/index.php">Crane With Load Test</a></li>
                     <li><a href="<?php echo $url; ?>document/withloadtest/index.php">Load Test</a></li>
                     <li><a href="<?php echo $url; ?>document/mpi/index.php">MPI</a></li>
                     <li><a href="<?php echo $url; ?>document/eddycurrent/index.php">Eddy Current</a></li>
                     <li><a href="<?php echo $url; ?>document/liquid-penetrant-inspection-certificate/index.php">LPI</a></li>
                     <li><a href="<?php echo $url; ?>document/rocktest/index.php">RT</a></li>
                     <li><a href="<?php echo $url; ?>document/lmi/index.php">LMI</a></li>
                     <?php if ($_SESSION['role'] === 'admin'): ?>
                     <li><a href="<?php echo $url; ?>document/kpi/index.php">Certificate KPI</a></li>
                     <?php endif; ?>
                     <!-- <li><a href="<?php echo $url; ?>operator_card/index.php">Operator Card</a></li> -->
                  </ul>
                  <!-- End Sub Menu -->
               </li>
            

            <!-- Checklist Portal (Visible to All Users) -->
            <li>
               <a href="#">
                  <!--<i class="icofont-contacts"></i>-->
                              <i class="icofont-check-alt"></i>

                  <span class="link-title">Checklist Portal</span>
               </a>
               <!-- Sub Menu -->
               <ul class="nav sub-menu">
                  <li><a href="<?php echo $url; ?>document/checklist/index.php">All Checklist</a></li>
                  <li><a href="<?php echo $url; ?>document/checklist/kpi_dashboard.php">Checklist KPI Dashboard</a></li>
               </ul>
               <!-- End Sub Menu -->
            </li>


 <li>
               <a href="#">
                  <!--<i class="icofont-contacts"></i>-->
                              <i class="icofont-star"></i>



                  <span class="link-title">NDT Portal</span>
               </a>
               <!-- Sub Menu -->
               <ul class="nav sub-menu">
                  <li><a href="<?php echo $url; ?>job/ndt-list.php">NDT List</a></li>
               </ul>
               <!-- End Sub Menu -->
            </li>

            <!-- Report Portal (Visible to All Users) -->
            <li>
               <a href="#">
                  <!--<i class="icofont-contacts"></i>-->
                 <i class="icofont-file-document"></i>
                  <span class="link-title">Report Portal</span>
               </a>
               <!-- Sub Menu -->
               <ul class="nav sub-menu">
                  <li><a href="<?php echo $url; ?>document/report/index.php">All Report List</a></li>
               </ul>
               <!-- End Sub Menu -->
            </li>

            <!-- KPI Result (Visible to All Users) -->
            <li>
               <a href="#">
               
                   <i class="icofont-chart-bar-graph"></i>
                  <span class="link-title">KPI Result</span>
               </a>
               <!-- Sub Menu -->
               <ul class="nav sub-menu">
                   <?php if ($_SESSION['role'] === 'admin'): ?>
                   <li><a href="<?php echo $url; ?>kpi/admin_kpi_dashboard.php">Admin KPI Dashboard</a></li>
                   <li><a href="<?php echo $url; ?>kpi/admin_customer_kpi.php">Customer KPI Dashboard</a></li>
                    <li><a href="<?php echo $url; ?>kpi/admin_inspector_kpi.php">Inspector KPI Dashboard</a></li>
                   <li><a href="<?php echo $url; ?>job/survey_kpi_dashboard.php">Survey KPI Dashboard</a></li>
                   <li><a href="<?php echo $url; ?>job/survey-list.php">Survey Report</a></li>
                   <li><a href="">Job Report</a></li>
                   <?php endif; ?>
                   <?php if ($_SESSION['role'] === 'inspector'): ?>
                   <li><a href="<?php echo $url; ?>kpi/inspector_kpi_dashboard.php">Inspector KPI Dashboard</a></li>
                   <?php endif; ?>
                   <?php if (in_array($_SESSION['role'], ['admin', 'inspector'])): ?>
                   <li><a href="<?php echo $url; ?>operator_assessment/operator_kpi_dashboard.php">Operator Assessment KPI</a></li>
                   <?php endif; ?>
                   
               </ul>
               <!-- End Sub Menu -->
            </li>

            <!-- Customer Portal (Visible to All Users)-->
            <?php if ($_SESSION['role'] === 'admin'): ?>
            <li>
               <a href="#">
                  <i class="icofont-contacts"></i>
                  <span class="link-title">Customer Portal</span>
               </a>
               <!-- Sub Menu -->
               <ul class="nav sub-menu">
                  <li><a href="<?php echo $url; ?>customer/customer-list.php">Customer List</a></li>
                  <!-- <li><a href="<?php echo $url; ?>customer/view-customer.php">Customer Status</a></li> -->
               </ul>
               <!-- End Sub Menu -->
            </li>
            <?php endif; ?>


            <!-- General Setup (Visible to Admin Only) -->
            <?php if (in_array($_SESSION['role'], ['admin', 'inspector', 'document controller'])): ?>
               <li>
                  <a href="#">
                     <!--<i class="icofont-binary"></i>-->
                                    <i class="icofont-gear"></i>

                     <span class="link-title">Operator Setup</span>
                  </a>
                  <!-- Sub Menu -->
                  <ul class="nav sub-menu">
                     <li><a href="<?php echo $url; ?>operator_assessment/assessment-list.php">Operator Assessment List</a></li>
                     <?php if ($_SESSION['role'] === 'admin'): ?>
                     <li><a href="<?php echo $url; ?>operator_assessment/add-assessment.php">Assign Operator</a></li>
                     <?php endif; ?>
                     <!-- <li><a href="<?php echo $url; ?>user/all-user.php">User List</a></li>
                     <li><a href="<?php echo $url; ?>contact/contact.php">Contact</a></li>
                     <li><a href="<?php echo $url; ?>setup/timeline.php">Timeline</a></li>
                     <li><a href="">Account Settings</a></li> -->
                  </ul>
                  <!-- End Sub Menu -->
               </li>
            <?php endif; ?>

            <!-- General Setup (Visible to Admin Only) -->
            <?php if ($_SESSION['role'] === 'admin'): ?>
               <li>
                  <a href="#">
                     <!--<i class="icofont-binary"></i>-->
                                    <!-- <i class="icofont-gear"></i> -->
                                    <i class="icofont-tools-alt-2"></i>

                     <span class="link-title">General Setup</span>
                  </a>
                  <!-- Sub Menu -->
                  <ul class="nav sub-menu">
                     <li><a href="<?php echo $url; ?>inspector/all-inspector.php">Inspector List</a></li>
                     <li><a href="<?php echo $url; ?>inspector/schedule-manager.php">Schedule Manager</a></li>
                     <li><a href="<?php echo $url; ?>user/all-user.php">User List</a></li>
                     <li><a href="<?php echo $url; ?>contact/contact.php">Contact</a></li>
                     <!-- <li><a href="<?php echo $url; ?>setup/timeline.php">Timeline</a></li> -->
                     <li><a href="">Account Settings</a></li>
                  </ul>
                  <!-- End Sub Menu -->
               </li>
            <?php endif; ?>


            <!-- Mobile User Profile & Logout -->
            <li class="nav-category d-lg-none">User Actions</li>
            <li class="d-lg-none">
                <a href="<?php echo $url; ?>profile/user-profile.php?id=<?php echo $_SESSION['user_id']; ?>">
                    <i class="icofont-user"></i>
                    <span class="link-title">My Profile</span>
                </a>
            </li>
            <li class="d-lg-none">
                <a href="<?php echo $url; ?>file/logout.php">
                    <i class="icofont-logout"></i>
                    <span class="link-title">Log Out</span>
                </a>
            </li>

            <li class="nav-category">Support</li>
         </ul>
         <!-- End Nav -->
      </div>
      <!-- End Sidebar Body -->
   </nav>
   <!-- End Sidebar -->
