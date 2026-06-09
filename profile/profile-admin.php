<?php
include_once('../inc/function.php');
include ('../file/config.php');
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
   <!-- Page Title -->
   <title>Dashmin - Multipurpose Bootstrap Dashboard Template</title>

   <!-- Meta Data -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta http-equiv="content-type" content="text/html; charset=utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="keywords" content="">

   <!-- Favicon -->
   <link rel="shortcut icon" href="../../../assets/img/favicon.png">

   <!-- Web Fonts -->
   <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet">
   
   <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
   <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="../../../assets/fonts/icofont/icofont.min.css">
   <link rel="stylesheet" href="../../../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.css">
   <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

   <!-- ======= MAIN STYLES ======= -->
   <link rel="stylesheet" href="../../../assets/css/style.css">
   <!-- ======= END MAIN STYLES ======= -->

</head>

<body>

   <!-- Offcanval Overlay -->
   <!-- <div class="offcanvas-overlay"></div> -->
   <!-- Offcanval Overlay -->

   <!-- Wrapper -->
   <!-- <div class="wrapper"> -->
      

      <!-- Main Wrapper -->
      <div class="main-wrapper">       
<!-- Main Content -->
         <div class="main-content">             
            <div class="container-fluid">
               <div class="row">
                   <div class="col-12">
                       <div class="card">
                           <!-- User Profile Image -->
                           <div class="user-profile-img">
                               <div class="cover-img">
                                    <img src="../../../assets/img/media/cover.jpg" class="w-100" alt="">

                                    <!-- Upload Photo -->
                                    <div class="upload-button">
                                        <img src="../../../assets/img/svg/gallery.svg" alt="" class="svg mr-2">
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
                                        <div class="profile-pic mr-3">
                                            <img src="../../../assets/img/media/profile-pic.jpg" alt="">
    
                                            <!-- Upload Photo -->
                                            <div class="upload-button">
                                                <img src="../../../assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                                <span>Upload Photo</span>
                                                <input class="file-input" type="file" id="fileUpload2" accept="image/*">
                                            </div>
                                            <!-- End Upload Photo -->
                                        </div>

                                        <div>
                                            <h3>Abrilay Khatun</h3>
                                            <p class="font-14">Head Of Business Development</p>
                                        </div>
                                    </div>
                                    <!-- End Profile Info -->

                                    <div class="d-flex align-items-center mt-3 mt-xl-0">
                                        <ul class="nav profile-nav-tabs">
                                            <li>
                                                <a class="active pr-0 pl-2 pl-xl-30">
                                                    <span class="chat">
                                                        <img src="../../../assets/img/svg/chat-icon.svg" alt="" class="svg">
                                                    </span>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="conncetion" href="connection.html">
                                                    <div class="btn-circle mr-20">
                                                        <img src="../../../assets/img/svg/user-check.svg" alt="" class="svg">
                                                    </div>
                                                    <div class="font-14">
                                                        <h4>154</h4>
                                                        <p class="font-14 text_color">Connections</p>
                                                    </div>
                                                </a>
                                            </li>
                                            <li>
                                                <a class="p_nav-link has-before active" href="about.html">About</a>
                                            </li>
                                            <!-- <li>
                                                <a class="p_nav-link" href="gallery.html">Gallery</a>
                                            </li>
                                            <li>
                                                <a class="p_nav-link" href="news-feed.html">News Feed</a>
                                            </li> -->
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

                            <div class="mt-30">
                                <!-- Profile Completion -->
                                <div class="profile-completion d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <!-- Progress -->
                                        <div class="progress_22 mr-3">
                                            <div class="ProgressBar-wrap2 position-relative">
                                                <div class="ProgressBar ProgressBar_22" data-progress="86">
                                                   <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                                                      <!-- on rotation circle -->
                                                      <circle transform="rotate(-90, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="85" />
                                                      <circle transform="rotate(-90, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                                                   </svg>
                                                    
                                                   <span class="ProgressBar-percentage ProgressBar-percentage--count"></span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End Progress -->

                                        <div class="">
                                            <h4 class="c2 mb-1">Profile Completion</h4>
                                            <p class="font-14">Praesent tempor dictum tellus ut molestie. Sed sed ullamcorper lorem, id faucibus odio.</p>
                                        </div>
                                    </div>

                                    <!-- Edit Profile Button -->
                                    <div class="edit-profile-btn pr-1">
                                        <a href="edit-profile.html" class="btn-circle">
                                        <img src="../../../assets/img/svg/writing.svg" alt="" class="svg">
                                        </a>
                                    </div>
                                    <!-- End Edit Profile Button -->
                                </div>
                                <!-- End Profile Completion -->

                                <!-- Card -->
                                <div class="card">
                                    <div class="p-30">
                                        <div class="about-myself mt-2 pb-2">
                                            <h4 class="mb-3">About Myself</h4>
                                            <p>Fusce at nisi eget dolor rhoncus facilisis. Mauris ante nisl, consectetur et luctus et, porta ut dolor. Curabitur ultricies ultrices nulla. Morbi blandit nec est vitae dictum. Etiam vel consectetur diam. Maecenas vitae egestas dolor. Fusce tempor magna at tortor aliquet finibus. Sed eu nunc sit amet elit euismod faucibus. Class aptent taciti sociosqu ad litora torquent per conubia nostra.</p>
                                        </div>


                                        <div class="row mt-5">
                                           <div class="col-md-3">
                                             <nav>
                                                <div class="nav flex-md-column about-nav-tab">

                                                    <!-- <a class="active" id="nav-overview-tab" data-toggle="tab" href="#nav-overview">Overview</a> -->

                                                     <!-- <a id="nav-work-tab" data-toggle="tab" href="#nav-work">Work</a> -->

                                                    <!-- <a id="nav-education-tab" data-toggle="tab" href="#nav-education">Education</a> -->

                                                    <a id="nav-basic-tab" data-toggle="tab" href="#nav-basic">Contact And Basic Info</a>

                                                    <a id="nav-skill-tab" data-toggle="tab" href="#nav-skill">Skills</a> 
                                                </div>
                                            </nav>
                                          </div>

                                          <div class="col-md-9">
                                             <div class="tab-content about-tab-content pl-md-5 mt-4 mt-md-0">
                                                   
                                                   
                                                   <div class="tab-pane fade  show active" id="nav-basic" role="tabpanel">
                                                      <!-- Personal Info -->
                                                      <div class="personal-info">
                                                         <h4 class="mb-3">Personal Information</h4>
      
                                                         <ul class="info-list list-unstyled">
                                                               <li><span>First Name</span> Abrilay</li>
                                                               <li><span>Last Name</span> Khatun</li>
                                                               <li><span>age</span> 26</li>
                                                               <li><span>Position</span> Front End Developer</li>
                                                               <li><span>Address</span> 228 Park Ave Str. New York, USA</li>
                                                               <li><span>Phone</span> <a href="tel:0021364545">00 2136 4545</a></li>
                                                               <li><span>Phone</span> <a href="mailto:abrilakh@gmail.com">abrilakh@gmail.com</a></li>
                                                         </ul>
                                                      </div>
                                                      <!-- End Personal Info -->
                                                   </div>
                                                   <div class="tab-pane fade" id="nav-skill" role="tabpanel">
                                                      <!-- Skill -->
                                                      <div class="skill">
                                                         <h4 class="mb-3">Skill</h4>
      
                                                         <ul class="skill-list list-unstyled">
                                                               <li>
                                                                  <span>UI Design</span>
                                                                  
                                                                  <div class="process-bar-wrapper style--five">
                                                                     <span class="process-bar" data-process-width="68"></span>
                                                                  </div>
                                                               </li>
                                                               
                                                               <li>
                                                                  <span>UX Design</span>
                                                                  
                                                                  <div class="process-bar-wrapper style--five pink">
                                                                     <span class="process-bar" data-process-width="90"></span>
                                                                  </div>
                                                               </li>
                                                               
                                                               <li>
                                                                  <span>HTML</span>
                                                                  
                                                                  <div class="process-bar-wrapper style--five green">
                                                                     <span class="process-bar" data-process-width="30"></span>
                                                                  </div>
                                                               </li>
                                                               
                                                               <li>
                                                                  <span>CSS</span>
                                                                  
                                                                  <div class="process-bar-wrapper style--five blue">
                                                                     <span class="process-bar" data-process-width="50"></span>
                                                                  </div>
                                                               </li>
                                                               
                                                               <li>
                                                                  <span>Wordpress</span>
                                                                  
                                                                  <div class="process-bar-wrapper style--five c2">
                                                                     <span class="process-bar" data-process-width="52"></span>
                                                                  </div>
                                                               </li>
                                                         </ul>
                                                      </div>
                                                      <!-- End Skill -->
                                                   </div>
                                             </div>
                                          </div>
                                       </div>
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>
                        </div>
                        
                        
                        <!-- Modal -->
                        <!-- <div class="modal fade" id="deleteConfirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                           aria-hidden="true">
                           <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">Delete Work Experience</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                    </button>
                                 </div>
                                 <div class="modal-body">
                                    Are you sure you want to delete Work Experience?
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn long" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn long">Delete</button>
                                 </div>
                              </div>
                           </div>
                        </div> -->

                        <!-- Modal -->
                        <!-- <div class="modal fade" id="deleteConfirmEducationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
                           aria-hidden="true">
                           <div class="modal-dialog modal-dialog-centered" role="document">
                              <div class="modal-content">
                                 <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel2">Delete School Experience</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                       <span aria-hidden="true">&times;</span>
                                    </button>
                                 </div>
                                 <div class="modal-body">
                                    Are you sure you want to delete School Experience?
                                 </div>
                                 <div class="modal-footer">
                                    <button type="button" class="btn long" data-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn long">Delete</button>
                                 </div>
                              </div>
                           </div>
                        </div> -->

                    </div> 
                </div>
            </div>            
                    
         </div>
         <!-- End Main Content -->
      </div>
      <!-- End Main Wrapper -->


      <!-- Footer -->
      <!-- <footer class="footer">
         Dashmin © 2020 created by <a href="https://www.themelooks.com/"> ThemeLooks</a>
      </footer> -->
      <!-- End Footer -->
   <!-- </div> -->
   <!-- End wrapper -->

   <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
   <!-- <script src="../../../assets/js/jquery.min.js"></script>
   <script src="../../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
   <script src="../../../assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
   <script src="../../../assets/js/script.js"></script> -->
   <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
</body>

</html>

<?php include_once('../inc/footer.php'); ?>