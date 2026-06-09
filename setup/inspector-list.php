
<?php 
include_once('../inc/function.php');

?>
         <!-- Main Content -->
         <div class="main-content d-flex flex-column flex-md-row">
            <div class="container-fluid">
               <div class="row">
                    <div class="col-12">
                        <!-- Contact Header -->
                        <div class="contact-header d-flex align-items-sm-center media flex-column flex-sm-row bg-white mb-30">
                           <div class="contact-header-left media-body d-flex align-items-center w-100 mr-sm-4">
                                 <!-- Add New Contact Btn -->
                                 <div class="add-new-contact mr-20">
                                       <a href="#" class="btn-circle" data-toggle="modal" data-target="#contactAddModal">
                                          <img src="<?php echo $url; ?>assets/img/svg/plus_white.svg" alt="" class="svg">
                                       </a>
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
                                <!-- Grid -->
                                <div class="grid">
                                    <a href="contact-list.html"><img src="<?php echo $url; ?>assets/img/svg/list-icon.svg" alt="" class="svg"></a>
                                </div>
                                <!-- End Grid -->

                                <!-- Starred -->
                                <div class="star">
                                    <a href="#"><img src="<?php echo $url; ?>assets/img/svg/star.svg" alt="" class="svg"></a>
                                </div>
                                <!-- End Starred -->

                                <!-- Delete Mail -->
                                <div class="delete_mail">
                                    <a href="#"><img src="<?php echo $url; ?>assets/img/svg/delete.svg" alt="" class="svg"></a>
                                </div>
                                <!-- End Delete Mail -->

                                <!-- Pagination -->
                                <div class="pagination style--two d-flex flex-column align-items-center ml-n2">
                                    <ul class="list-inline d-inline-flex align-items-center">
                                    <li><a href="#">
                                        <img src="<?php echo $url; ?>assets/img/svg/left-angle.svg" alt="" class="svg">
                                    </a></li>
                                    <li><a href="#" class="current">
                                        <img src="<?php echo $url; ?>assets/img/svg/right-angle.svg" alt="" class="svg">
                                    </a></li>
                                    </ul>
                                </div>
                                <!-- End Pagination -->
                            </div>
                        </div>
                        <!-- End Contact Header -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m12.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Jodi Alvarez</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m18.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Marvin Butler</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m11.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Evan Mcbride</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m20.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Grant Gibbs</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m25.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Phil Bush</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m9.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Ana Quinn</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m26.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Vera Cox</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m2.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Delia Holmes</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m21.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Terrance Matews</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m3.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Frances Owen</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m13.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Martha Lopez</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m15.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Jill Holmes</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m19.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Krystal Nelson</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m24.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Ismael Drake</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m10.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Minnie Cook</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m27.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Shirley Colon</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m30.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Christy Weber</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m31.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Robert Henderson</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m32.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Erica Howell</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m33.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Luke Curtis</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m34.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Kelvin Lamb</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m35.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Franklin Adkins</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m36.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Rie Mcdonald</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m16.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Camille Ford</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m37.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Amy Shaw</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m38.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Kirk Mendoza</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m39.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Kate Perkins</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m11.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Evan Mcbride</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m20.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Grant Gibbs</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>

                    <div class="col-xl-2 col-md-4 col-sm-6">
                        <!-- Contact -->
                        <div class="contact-box mb-30">
                            <div class="contact-head">
                                <div class="d-flex align-items-center">
                                    <div class="avatar img-50 mr-2">
                                        <img src="<?php echo $url; ?>assets/img/avatar/m25.png" alt="">
                                    </div>
                                    <div class="content pl-1">
                                        <h4 class="c2 mb-1">Phil Bush</h4>
                                        <p>UX Researcher</p>
                                    </div>
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
                                       <a href="#" data-toggle="modal" data-target="#contactEditModal">Edit</a>
                                       <a href="#" class="contact-close">Delete</a>
                                    </div>
                                 </div>
                            </div>
                            <div class="contact-body">
                                <a href="mailto:yourname@email.com">yourname@email.com</a>
                                <a href="tel:+026544464698">+02 654 446 4698</a>
                                <p>712 Clarkson Ave Brooklyn, NY
                                    11203, USA</p>
                            </div>
                        </div>
                        <!-- End Contact -->
                    </div>



                    <!-- Contact Add New PopUp -->
                    <div id="contactAddModal" class="modal fade">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <form action="#">
                                        <div class="media flex-column flex-sm-row">
                                            <div class="modal-upload-avatar mr-0 mr-sm-3 mr-md-5 mb-5 mb-sm-0">

                                                    <div class="attach-file style--two mb-3">
                                                    <img src="<?php echo $url; ?>assets/img/img-placeholder.png" class="profile-avatar" alt="">
                                                    <div class="upload-button">
                                                        <img src="<?php echo $url; ?>assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                                        <span>Upload Photo</span>
                                                        <input class="file-input" type="file" id="fileUpload" accept="image/*">
                                                    </div>
                                                    </div>

                                                    <div class="content">
                                                    <h4 class="mb-2">Upload a Photo</h4>
                                                    <p class="font-12 c4">Allowed JPG, GIF or PNG. Max size <br /> of 800kB</p>
                                                    </div>
                                            </div>
                
                
                                            <div class="contact-account-setting media-body">

                                                    <h4 class="mb-4">Account Settings</h4>

                                                    <div class="mb-4">
                                                    <label class="bold black mb-2" for="as_name">Name</label>
                                                    <input type="text" id="as_name" class="theme-input-style" placeholder="Type Here" required>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                    <label class="bold black mb-2" for="as_email">Email</label>
                                                    <input type="email" id="as_email" class="theme-input-style" placeholder="Type Here" required>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                    <label class="bold black mb-2"  for="as_phone">Phone</label>
                                                    <input type="number" id="as_phone" class="theme-input-style" placeholder="Type Here" required>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                    <label class="bold black mb-2" for="as_age">Age</label>
                                                    <input type="text" id="as_age" class="theme-input-style" placeholder="Type Here" required>
                                                    </div>
                                                    
                                                    <div class="mb-4">
                                                    <label class="bold black mb-2" for="as_post">Post</label>
                                                    <input type="text" id="as_post" class="theme-input-style" placeholder="Type Here" required>
                                                    </div>
                                                    
                                                    <div class="mb-30">
                                                    <label class="bold black mb-2">Joining Date</label>
                                                    
                                                    <div class="date datepicker dashboard-date style--two" id="datePickerExample">
                                                        <span class="input-group-addon mr-0"><img src="<?php echo $url; ?>assets/img/svg/calender.svg" alt="" class="svg"></span>
                                                        <input type="text" class="pl-2" required>
                                                    </div>
                                                    </div>

                                                    <div class="">
                                                    <a href="#" class="btn mr-4">Save Changes</a>
                                                    <a href="#" class="cancel font-14 bold" data-dismiss="modal">Cancel</a>
                                                    </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- End Modal Body -->
                            </div>
                        </div>
                    </div>
                    <!-- End Contact Add New PopUp -->

                    <!-- Contact Edit PopUp -->
                    <div id="contactEditModal" class="modal fade">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <form action="#">
                                        <div class="media flex-column flex-sm-row">
                                        <div class="modal-upload-avatar mr-0 mr-sm-3 mr-md-5 mb-5 mb-sm-0">

                                                <div class="attach-file style--two mb-3">
                                                    <img src="<?php echo $url; ?>assets/img/avatar/user.png" class="profile-avatar" alt="">
                                                    <div class="upload-button">
                                                    <img src="<?php echo $url; ?>assets/img/svg/gallery.svg" alt="" class="svg mr-2">
                                                    <span>Upload Photo</span>
                                                    <input class="file-input" type="file" id="fileUpload2" accept="image/*">
                                                    </div>
                                                </div>

                                                <div class="content">
                                                    <h4 class="mb-2">Upload a Photo</h4>
                                                    <p class="font-12 c4">Allowed JPG, GIF or PNG. Max size <br /> of 800kB</p>
                                                </div>
                                        </div>
                
                
                                        <div class="contact-account-setting media-body">

                                            <h4 class="mb-4">Account Settings</h4>

                                            <div class="mb-4">
                                                <label class="bold black mb-2" for="as_name2">Name</label>
                                                <input type="text" id="as_name2" class="theme-input-style" value="Marvin Butler" required>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="bold black mb-2" for="as_email2">Email</label>
                                                <input type="email" id="as_email2" class="theme-input-style" value="yourname@email.com" required>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="bold black mb-2"  for="as_phone2">Phone</label>
                                                <input type="text" id="as_phone2" class="theme-input-style" value="+02 654 446 4698" required>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="bold black mb-2" for="as_age2">Age</label>
                                                <input type="text" id="as_age2" class="theme-input-style" value="28" required>
                                            </div>
                                            
                                            <div class="mb-4">
                                                <label class="bold black mb-2" for="as_post2">Post</label>
                                                <input type="text" id="as_post2" class="theme-input-style" value="UX Researcher" required>
                                            </div>
                                            
                                            <div class="mb-30">
                                                <label class="bold black mb-2">Joining Date</label>
                                                
                                                <div class="date datepicker dashboard-date style--two" id="datePickerExample2">
                                                    <span class="input-group-addon mr-0"><img src="<?php echo $url; ?>assets/img/svg/calender.svg" alt="" class="svg"></span>
                                                    <input type="text" class="pl-2" required>
                                                </div>
                                            </div>

                                            <div class="">
                                                <a href="#" class="btn mr-4">Save Changes</a>
                                                <a href="#" class="cancel font-14 bold" data-dismiss="modal">Cancel</a>
                                            </div>
                                        </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- End Modal Body -->
                            </div>
                        </div>
                    </div>
                    <!-- End Contact Edit PopUp -->
                </div>
            </div>
         </div>
         <!-- End Main Content -->
      </div>
      <!-- End Main Wrapper -->
      <?php 
        include_once('../inc/footer.php');
        ?>
        