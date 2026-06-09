<?php 
include_once('../inc/customer-option.php');
?>
    <!-- Main Content -->
     <div class="main-content2">
                
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <!-- User Profile Image -->
                                <div class="user-profile-img">
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
                                            <div class="profile-pic mr-3">
                                                <img src="<?php echo $url; ?>assets/img/media/profile-pic.jpg" alt="">
        
                                                <!-- Upload Photo -->
                                                <div class="upload-button">
                                                    <img src="<?php echo $url; ?>assets/img/svg/gallery.svg" alt="" class="svg mr-2">
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
                                            <p class="font-14 mb-2">Member Profit</p>
                                            <h3>&#36;25k</h3>
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
                                            <p class="font-14 mb-2">Orders</p>
                                            <h3>568</h3>
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
                                            <p class="font-14 mb-2">Issue Reports</p>
                                            <h3>165</h3>
                                        </div>
                                        <div class="state-icon">
                                            <img src="<?php echo $url; ?>assets/img/png-icon/report.png" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>

                            <div class="col-xl-2 col-lg-4 col-sm-6">
                                <!-- Card -->
                                <div class="card mb-30">
                                    <div class="statistics-bounce-rate support d-flex align-items-center justify-content-between">
                                        <div class="state-content">
                                            <p class="font-14 mb-2">Customer Support</p>
                                            <h3>354</h3>
                                        </div>
                                        <div class="state-icon">
                                            <img src="<?php echo $url; ?>assets/img/png-icon/what.png" alt="">
                                        </div>
                                    </div>
                                </div>
                                <!-- End Card -->
                            </div>

                            <div class="col-xl-4 col-lg-8">
                                <div class="project-deadline d-flex align-items-center c2-bg mb-30">
                                    <!-- Progress -->
                                    <div class="progress_23 mr-3">
                                        <div class="ProgressBar-wrap2 position-relative">
                                            <div class="ProgressBar ProgressBar_23" data-progress="75">
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
                                        <h4 class="white font-20 mb-1">Project Deadline</h4>
                                        <p>Vestibulum blandit viverra convallis. Pellentesque ligula urnafermentum ut semper intincidunt nec.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-4 col-md-6">
                                <!-- News -->
                                <div class="card mb-30">
                                    <div class="card-body latest-update">

                                        <h4 class="mb-20">Latest Updates</h4>

                                        <!-- Item Single -->
                                        <a href="#" class="item-single pt-0 border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="text-danger font-14 bold">Issue</span>
                                                <p>Duis mauris augue, efficitur eu arcu sit amet, posuere dignissim neque. Aenean enim sem, pharetra et magna sit ame.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="text-success font-14 bold">Done</span>
                                                <p class="mb-1">Duis mauris augue, efficitur eu arcu sit amet, posuere dignissim neque. Aenean enim sem, pharetra et magna sit ame.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="text-info font-14 bold">Fixed</span>
                                                <p class="mb-1">Duis mauris augue, efficitur eu arcu sit amet, posuere dignissim neque. Aenean enim sem, pharetra et magna sit ame.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single border-bottom d-flex align-items-center">
                                            <div class="content">
                                                <span class="text-pink font-14 bold">UPdated</span>
                                                <p class="mb-1">Duis mauris augue, efficitur eu arcu sit amet, posuere dignissim neque. Aenean enim sem, pharetra et magna sit ame.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->

                                        <!-- Item Single -->
                                        <a href="#" class="item-single pb-0 d-flex align-items-center">
                                            <div class="content">
                                                <span class="text-warning font-14 bold">Bug</span>
                                                <p class="mb-1">Duis mauris augue, efficitur eu arcu sit amet, posuere dignissim neque. Aenean enim sem, pharetra et magna sit ame.</p>
                                            </div>
                                        </a>
                                        <!-- End Item Single -->
                                    </div>
                                    <!-- End Notifications -->
                                </div>
                                <!-- End News -->

                                <!-- Card -->
                                <div class="card mb-30">
                                    <div class="card-body">
                                    <div class="title-content mb-4 mb-sm-0">
                                        <h4>Top Followers</h4>
                                    </div>
                                    </div>

                                    <!-- Table Responsive -->
                                    <div class="table-responsive">
                                        <table class="style--five text-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>Profile</th>
                                                    <th>Country</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="img">
                                                            <img src="<?php echo $url; ?>assets/img/avatar/f1.png" alt="">
                                                        </div>
                                                        <div class="name">Kary Threlkeld</div>
                                                    </div>
                                                    </td>
                                                    <td>USA</td>
                                                    <td><a href="#" class="follow-btn">Follow <i class="icofont-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="img">
                                                            <img src="<?php echo $url; ?>assets/img/avatar/f2.png" alt="">
                                                        </div>
                                                        <div class="name">Kizzy Savoy</div>
                                                    </div>
                                                    </td>
                                                    <td>China</td>
                                                    <td><a href="#" class="follow-btn">Follow <i class="icofont-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="img">
                                                            <img src="<?php echo $url; ?>assets/img/avatar/f3.png" alt="">
                                                        </div>
                                                        <div class="name">Tonette Baumgarten</div>
                                                    </div>
                                                    </td>
                                                    <td>Russia</td>
                                                    <td><a href="#" class="follow-btn">Follow <i class="icofont-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="img">
                                                            <img src="<?php echo $url; ?>assets/img/avatar/f4.png" alt="">
                                                        </div>
                                                        <div class="name">Luella Brumit</div>
                                                    </div>
                                                    </td>
                                                    <td>Spain</td>
                                                    <td><a href="#" class="follow-btn">Follow <i class="icofont-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="img">
                                                            <img src="<?php echo $url; ?>assets/img/avatar/f5.png" alt="">
                                                        </div>
                                                        <div class="name">Dionne Rosser</div>
                                                    </div>
                                                    </td>
                                                    <td>Brazil</td>
                                                    <td><a href="#" class="follow-btn">Follow <i class="icofont-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="img">
                                                            <img src="<?php echo $url; ?>assets/img/avatar/f2.png" alt="">
                                                        </div>
                                                        <div class="name">Kizzy Savoy</div>
                                                    </div>
                                                    </td>
                                                    <td>China</td>
                                                    <td><a href="#" class="follow-btn">Follow <i class="icofont-arrow-right"></i></a></td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="img">
                                                            <img src="<?php echo $url; ?>assets/img/avatar/f6.png" alt="">
                                                        </div>
                                                        <div class="name">Earl Penton</div>
                                                    </div>
                                                    </td>
                                                    <td>France</td>
                                                    <td><a href="#" class="follow-btn">Follow <i class="icofont-arrow-right"></i></a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- End Table Responsive -->
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
                                    <!-- Start Todo Single -->
                                    <div class="card-body">
                                        <h4 class="font-20 pb-3">Tasks</h4>

                                        <p>Saturday, 12 October 2019</p>
                                    </div>
                                    <!-- End Todo Single -->
                                    
                                    <!-- Start Todo Single -->
                                    <div class="single-row level-urgent border-bottom pt-3 pb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <div class="d-flex position-relative">
                                            <!-- Custom Checkbox -->
                                            <label class="custom-checkbox">
                                                <input type="checkbox">
                                                <span class="checkmark"></span>
                                            </label>
                                            <!-- End Custom Checkbox -->
        
                                            <!-- Todo Text -->
                                            <a href="../../apps/todolist/task-details.html" class="todo-text">
                                                <p class="card-text mb-1">For detracty charmed add talking age. Shy resolution instrument unreserved man few.</p>
                                                <p class="label-text font-12 mb-0">Urgent</p>
                                            </a>
                                            <!-- End Todo Text -->
                                        </div>
        
                                        <div class="d-flex">
                                            <!-- Assign To -->
                                            <div class="assign_to">
                                                <img src="<?php echo $url; ?>assets/img/svg/Info.svg" alt="" class="svg mb-2">
                                                <div class="assign-content">
                                                    <div class="font-12 text-warning">Back-End</div>
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
                                                    <a href="#">Edit</a>
                                                    <a href="#">Sort By</a>
                                                    <a href="#">Settings</a>
                                                    <a href="#">Delete</a>
                                                </div>
                                            </div>
                                            <!-- End Dropdown Button -->
                                        </div>
                                        </div>
                                    </div>
                                    <!-- End Todo Single -->
        
                                    <!-- Start Todo Single -->
                                    <div class="single-row level-important border-bottom pt-3 pb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex position-relative">
                                                <!-- Custom Checkbox -->
                                                <label class="custom-checkbox">
                                                    <input type="checkbox">
                                                    <span class="checkmark"></span>
                                                </label>
                                                <!-- End Custom Checkbox -->
            
                                                <!-- Todo Text -->
                                                <a href="../../apps/todolist/task-details.html" class="todo-text pr-2 pr-md-4">
                                                    <p class="card-text mb-1">Way sentiments two indulgence uncommonly own.</p>
                                                    <p class="label-text font-12 mb-0">Important</p>
                                                </a>
                                                <!-- End Todo Text -->
                                            </div>
            
                                            <div class="d-flex">
                                                <!-- Assign To -->
                                                <div class="assign_to">
                                                    <img src="<?php echo $url; ?>assets/img/svg/Info.svg" alt="" class="svg mb-2">
                                                    <div class="assign-content">
                                                        <div class="font-12 text-warning">Back-End</div>
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
                                                        <a href="#">Edit</a>
                                                        <a href="#">Sort By</a>
                                                        <a href="#">Settings</a>
                                                        <a href="#">Delete</a>
                                                    </div>
                                                </div>
                                                <!-- End Dropdown Button -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Todo Single -->
        
                                    <!-- Start Todo Single -->
                                    <div class="single-row level-important border-bottom pt-3 pb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex position-relative">
                                            <!-- Custom Checkbox -->
                                            <label class="custom-checkbox">
                                                <input type="checkbox" checked>
                                                <span class="checkmark"></span>
                                            </label>
                                            <!-- End Custom Checkbox -->
            
                                            <!-- Todo Text -->
                                            <a href="../../apps/todolist/task-details.html" class="todo-text line-through pr-2 pr-md-4">
                                                <p class="card-text mb-1">Whose her enjoy chief new young. Felicity if ye required likewise so doubtful. On so attention necessary at by provision otherwise existence </p>
                                                <p class="label-text font-12 mb-0">Important</p>
                                            </a>
                                            <!-- End Todo Text -->
                                            </div>
            
                                            <div class="d-flex">
                                            <!-- Assign To -->
                                            <div class="assign_to">
                                                <img src="<?php echo $url; ?>assets/img/svg/Info.svg" alt="" class="svg mb-2">
                                                <div class="assign-content">
                                                    <div class="font-12 text-warning">Back-End</div>
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
                                                    <a href="#">Edit</a>
                                                    <a href="#">Sort By</a>
                                                    <a href="#">Settings</a>
                                                    <a href="#">Delete</a>
                                                </div>
                                            </div>
                                            <!-- End Dropdown Button -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Todo Single -->
                                    
                                    <!-- Start Todo Single -->
                                    <div class="single-row level-not_important pt-3 pb-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex position-relative">
                                                <!-- Custom Checkbox -->
                                                <label class="custom-checkbox">
                                                    <input type="checkbox" checked>
                                                    <span class="checkmark"></span>
                                                </label>
                                                <!-- End Custom Checkbox -->
            
                                                <!-- Start Todo Text -->
                                                <a href="../../apps/todolist/task-details.html" class="todo-text line-through pr-2 pr-md-4">
                                                    <p class="card-text mb-1">Unpleasing up announcing unpleasant themselves oh do on. Way advantage age led listening</p>
                                                    <p class="label-text font-12 mb-0">Not Important</p>
                                                </a>
                                                <!-- End Todo Text -->
                                            </div>
            
                                            <div class="d-flex">
                                                <!-- Assign To -->
                                                <div class="assign_to">
                                                    <img src="<?php echo $url; ?>assets/img/svg/Info.svg" alt="" class="svg mb-2">
                                                    <div class="assign-content">
                                                        <div class="font-12 text-warning">Back-End</div>
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
                                                        <a href="#">Edit</a>
                                                        <a href="#">Sort By</a>
                                                        <a href="#">Settings</a>
                                                        <a href="#">Delete</a>
                                                    </div>
                                                </div>
                                                <!-- End Dropdown Button -->
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Todo Single -->
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
                                             <h4 class="mb-1">Performances</h4>
                                             <p class="font-14">Tell use paid law ever yet new. Meant to learn of vexed
                                                he there increased.</p>
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
                                          <div class="ProgressBar ProgressBar_20" data-progress="70">
                                             <svg class="ProgressBar-contentCircle" viewBox="0 0 200 200">
                                                <!-- on défini le l'angle et le centre de rotation du cercle -->
                                                <circle transform="rotate(135, 100, 100)" class="ProgressBar-background" cx="100" cy="100" r="8" />
                                                <circle transform="rotate(135, 100, 100)" class="ProgressBar-circle" cx="100" cy="100" r="85" />
                                             </svg>
                                             <span class="ProgressBar-percentage--text">Increase</span>
                                             <span class="ProgressBar-percentage ProgressBar-percentage--count"></span>
                                          </div>
                                       </div>
                                       
                                       <div class="country-performance px-2 pt-3 pb-2">
                                          <div class="process-bar-wrapper">
                                             <span class="process-name">Project Success</span>
                                             <span class="process-width">35%</span>
                                             <span class="process-bar" data-process-width="35"></span>
                                          </div>
                                          
                                          <div class="process-bar-wrapper style--two">
                                             <span class="process-name">Targeted Order</span>
                                             <span class="process-width">68%</span>
                                             <span class="process-bar" data-process-width="68"></span>
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
                                            Today's Events
                                        </h4>
                                    </div>
                                    <div class="card">
                                        <ul class="list-unstyled">
                                            <li class="border-bottom">
                                                <h5>Ligula rhoncus euismod pretium</h5>
                                                <div class="event-meta font-14 d-flex align-items-center">
                                                    <img src="<?php echo $url; ?>assets/img/svg/watch2.svg" alt="" class="svg">
                                                    <span class="time d-inline-block ml-2">7.20pm</span>
                                                    <span class="date d-inline-block ml-2">12 October 2019</span>
                                                </div>
                                                <p class="mt-2">Duis porta, ligula rhoncus euismod pretium, nisi tellus eleifend odio, luctus viverra sem dolor id sem. Maecenas a venenatis enim, quis porttitor magna.</p>
                                            </li>
                                            <li class="border-bottom">
                                                <h5>Phasellus finibus enim nulla, quis ornare odio facilisiseu</h5>
                                                <div class="event-meta font-14 d-flex align-items-center">
                                                    <img src="<?php echo $url; ?>assets/img/svg/watch2.svg" alt="" class="svg">
                                                    <span class="time d-inline-block ml-2">7.20pm</span>
                                                    <span class="date d-inline-block ml-2">12 October 2019</span>
                                                </div>
                                            </li>
                                            <li class="border-bottom">
                                                <h5>Aenean sed nibh a magna posuere tempor.</h5>
                                                <div class="event-meta font-14 d-flex align-items-center">
                                                    <img src="<?php echo $url; ?>assets/img/svg/watch2.svg" alt="" class="svg">
                                                    <span class="time d-inline-block ml-2">7.20pm</span>
                                                    <span class="date d-inline-block ml-2">12 October 2019</span>
                                                </div>
                                            </li>
                                            <li>
                                                <h5>Aenean sed lorem ested quis neque ut nibh suscipit imperdiet</h5>
                                                <div class="event-meta font-14 d-flex align-items-center">
                                                    <img src="<?php echo $url; ?>assets/img/svg/watch2.svg" alt="" class="svg">
                                                    <span class="time d-inline-block ml-2">7.20pm</span>
                                                    <span class="date d-inline-block ml-2">12 October 2019</span>
                                                </div>
                                            </li>
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
        <!-- End Main Wrapper -->


        
        <?php 
        include_once('../inc/footer.php');
        ?>
        