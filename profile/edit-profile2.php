  
     <?php 
include_once('../inc/function.php');

?>
  <!-- Main Content -->
   <div class="main-content d-flex flex-column flex-md-row">
                <div class="mb-4 mb-md-0">
                    <!-- Tasks Aside -->
                    <div class="aside">
                        <!-- Aside Body -->
                        <nav class="aside-body">
                            <h4 class="mb-3">Account Settings</h4>

                            <ul class="nav flex-column">
                                <li><a class="active" data-toggle="tab" href="#general">General</a></li>
                                <li><a data-toggle="tab" href="#c_pass">Change Password</a></li>
                                <li><a data-toggle="tab" href="#info">Info</a></li>
                                <li><a data-toggle="tab" href="#social">Social links</a></li>
                                <li><a data-toggle="tab" href="#notifications">Notifications</a></li>
                            </ul>
                        </nav>
                        <!-- End Aside Body -->
                    </div>
                    <!-- End Tasks Aside -->
                 </div>
                 <div class="container-fluid">
                    <div class="row">
     
                       <div class="col-xl-10 mb-30 mb-xl-0">
                          <!-- Card -->
                          <div class="card h-100">
                             <div class="card-body p-30">
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="general">
                                        <h4 class="mb-4">Account Settings</h4>

                                        <form action="#">
                                            <div class="row">
                                                <div class="col-xl-4 col-lg-6">
                                                    <!-- Form Group -->
                                                    <div class="form-group mb-20">
                                                        <label for="userName" class="mb-2 font-14 bold black">User Name</label>
                                                        <input type="text" id="userName" class="theme-input-style" placeholder="Type Here">
                                                    </div>
                                                    <!-- End Form Group -->

                                                    <!-- Form Group -->
                                                    <div class="form-group mb-20">
                                                        <label for="email" class="mb-2 font-14 bold black">Email</label>
                                                        <input type="email" id="email" class="theme-input-style" placeholder="Type Here">
                                                    </div>
                                                    <!-- End Form Group -->
                                                </div>
                                                <div class="col-xl-4 col-lg-6">
                                                    <!-- Form Group -->
                                                    <div class="form-group mb-20">
                                                        <label for="name" class="mb-2 font-14 bold black">Name</label>
                                                        <input type="text" id="name" class="theme-input-style" placeholder="Type Here">
                                                    </div>
                                                    <!-- End Form Group -->

                                                    <!-- Form Group -->
                                                    <div class="form-group mb-20">
                                                        <label for="company" class="mb-2 font-14 bold black">Company</label>
                                                        <input type="text" id="company" class="theme-input-style" placeholder="Type Here">
                                                    </div>
                                                    <!-- End Form Group -->
                                                </div>
                                                <div class="col-xl-4">
                                                    <div class="upload-avatar d-xl-flex align-items-center flex-column">

                                                        <div>
                                                            <div class="attach-file style--two rounded-0 align-items-end mb-3">
                                                                <img src="../../assets/img/img-placeholder.png" class="profile-avatar" alt="">
                                                                <div class="upload-button mb-20">
                                                                   <img src="../../assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                                                   <span>Upload Photo</span>
                                                                   <input class="file-input" type="file" id="fileUpload" accept="image/*">
                                                                </div>
                                                             </div>
             
                                                             <div class="content">
                                                                <h4 class="mb-2">Upload a Photo</h4>
                                                                <p class="font-12 c4">Allowed JPG, GIF or PNG. Max size <br /> of 800kB</p>   
                                                             </div>
                                                        </div>
                                                     </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="button-group mt-30 mt-xl-n5">
                                                        <button type="submit" class="btn">Save Changes</button>
                                                        <button type="button" class="link-btn bg-transparent ml-3 soft-pink">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="c_pass">
                                        <h4 class="mb-4">Change Password</h4>

                                        <form action="#">
                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="oldPassword" class="mb-2 font-14 bold black">Old Password</label>
                                                <input type="password" id="oldPassword" class="theme-input-style" placeholder="Type Here">
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="newPassword" class="mb-2 font-14 bold black">New Password</label>
                                                <input type="password" id="newPassword" class="theme-input-style" placeholder="Type Here">
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="retypePassword" class="mb-2 font-14 bold black">Retype Password</label>
                                                <input type="password" id="retypePassword" class="theme-input-style" placeholder="Type Here">
                                            </div>
                                            <!-- End Form Group -->

                                            <div class="button-group mt-30">
                                                <button type="submit" class="btn">Save Changes</button>
                                                <button type="button" class="link-btn bg-transparent ml-3 soft-pink">Cancel</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="info">
                                        <h4 class="mb-4">Informations</h4>

                                        <form action="#">
                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="bio" class="mb-2 font-14 bold black">Bio</label>
                                                <textarea id="bio" class="theme-input-style style--two" placeholder="Type Your Bio"></textarea>
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="default-date" class="mb-2 font-14 bold black">Date Of Birth</label>
                                                <input type="text" id="default-date" class="theme-input-style" placeholder="05 September 1998">
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label class="mb-2 font-14 bold black">Country</label>
                                                <select class="form-control">
                                                    <option value="bangladesh">Bangladesh</option>
                                                    <option value="india">India</option>
                                                    <option value="nepal">Nepal</option>
                                                    <option value="pakistan">Pakistan</option>
                                                </select>
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label class="mb-2 font-14 bold black">Lenguage</label>
                                                 
                                                <select class="language-select" name="states[]" multiple="multiple">
                                                    <option value="english">English</option>
                                                    <option value="bangla">Bangla</option>
                                                    <option value="arabic">Arabic</option>
                                                    <option value="french">French</option>
                                                </select>
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="phone" class="mb-2 font-14 bold black">Phone</label>
                                                <input type="text" id="phone" class="theme-input-style" placeholder="(+656) 254 2568">
                                            </div>
                                            <!-- End Form Group -->

                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="webSite" class="mb-2 font-14 bold black">Website</label>
                                                <input type="url" id="webSite" class="theme-input-style" placeholder="Type Here">
                                            </div>
                                            <!-- End Form Group -->

                                            <div class="button-group mt-30">
                                                <button type="submit" class="btn">Save Changes</button>
                                                <button type="button" class="link-btn bg-transparent ml-3 soft-pink">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="social">
                                        <h4 class="mb-4">Social Links</h4>

                                        <form action="#">
                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="facebook" class="mb-2 font-14 bold black">Facebook</label>
                                                <input type="url" id="facebook" class="theme-input-style" placeholder="Add Links">
                                            </div>
                                            <!-- End Form Group -->
                                            
                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="twitter" class="mb-2 font-14 bold black">Twitter</label>
                                                <input type="url" id="twitter" class="theme-input-style" placeholder="Add Links">
                                            </div>
                                            <!-- End Form Group -->
                                            
                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="linkedin" class="mb-2 font-14 bold black">Linkedin</label>
                                                <input type="url" id="linkedin" class="theme-input-style" placeholder="Add Links">
                                            </div>
                                            <!-- End Form Group -->
                                            
                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="pinterest" class="mb-2 font-14 bold black">Pinterest</label>
                                                <input type="url" id="pinterest" class="theme-input-style" placeholder="Add Links">
                                            </div>
                                            <!-- End Form Group -->
                                            
                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="quora" class="mb-2 font-14 bold black">Quora</label>
                                                <input type="url" id="quora" class="theme-input-style" placeholder="Add Links">
                                            </div>
                                            <!-- End Form Group -->
                                            
                                            <!-- Form Group -->
                                            <div class="form-group mb-20">
                                                <label for="instagram" class="mb-2 font-14 bold black">Instagram</label>
                                                <input type="url" id="instagram" class="theme-input-style" placeholder="Add Links">
                                            </div>
                                            <!-- End Form Group -->

                                            <div class="button-group mt-30">
                                                <button type="submit" class="btn">Save Changes</button>
                                                <button type="button" class="link-btn bg-transparent ml-3 soft-pink">Cancel</button>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="tab-pane fade" id="notifications">
                                        <h4 class="mb-5">Notification Settings</h4>

                                        <form action="#">
                                            <h5 class="text_color mb-4">Account Settings</h5>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="switch-wrap">
                                                    <!-- Switch -->
                                                    <label class="switch">
                                                        <input type="checkbox" checked="checked">
                                                        <span class="control"></span>
                                                    </label>
                                                    <!-- End Switch -->
                                                </div>

                                                <span>Email me when someone comments onmy article</span>

                                            </div>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="switch-wrap">
                                                    <!-- Switch -->
                                                    <label class="switch">
                                                        <input type="checkbox" checked="checked">
                                                        <span class="control"></span>
                                                    </label>
                                                    <!-- End Switch -->
                                                </div>

                                                <span>Email me when someone answers on my form</span>

                                            </div>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="switch-wrap">
                                                    <!-- Switch -->
                                                    <label class="switch">
                                                        <input type="checkbox">
                                                        <span class="control"></span>
                                                    </label>
                                                    <!-- End Switch -->
                                                </div>

                                                <span>Invites me to co-own a moodboard</span>

                                            </div>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="switch-wrap">
                                                    <!-- Switch -->
                                                    <label class="switch">
                                                        <input type="checkbox" checked="checked">
                                                        <span class="control"></span>
                                                    </label>
                                                    <!-- End Switch -->
                                                </div>

                                                <span>Receive an email summary of notifications instead of individual emails</span>

                                            </div>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="switch-wrap">
                                                    <!-- Switch -->
                                                    <label class="switch">
                                                        <input type="checkbox">
                                                        <span class="control"></span>
                                                    </label>
                                                    <!-- End Switch -->
                                                </div>

                                                <span>Notifications about upcoming live events</span>
                                            </div>

                                            
                                            <h5 class="text_color pt-3 mb-4">Activity</h5>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="switch-wrap">
                                                    <!-- Switch -->
                                                    <label class="switch">
                                                        <input type="checkbox" checked="checked">
                                                        <span class="control"></span>
                                                    </label>
                                                    <!-- End Switch -->
                                                </div>

                                                <span>Blocked users will no longer be allowed to: follow you, see your work in their feed.</span>
                                            </div>

                                            <div class="d-flex align-items-center mb-4">
                                                <div class="switch-wrap">
                                                    <!-- Switch -->
                                                    <label class="switch">
                                                        <input type="checkbox" checked="checked">
                                                        <span class="control"></span>
                                                    </label>
                                                    <!-- End Switch -->
                                                </div>

                                                <span>Receive an email summary of notifications instead of individual emails</span>

                                            </div>

                                            <div class="d-flex align-items-center">
                                                <div class="switch-wrap">
                                                    <!-- Switch -->
                                                    <label class="switch">
                                                        <input type="checkbox">
                                                        <span class="control"></span>
                                                    </label>
                                                    <!-- End Switch -->
                                                </div>

                                                <span>Error saving: please try again later</span>
                                            </div>


                                            <div class="button-group mt-30">
                                                <button type="submit" class="btn">Save Changes</button>
                                                <button type="button" class="link-btn bg-transparent ml-3 soft-pink">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
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
        