<?php
// session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: ../index.php');
    exit();
}

echo "Welcome, Admin!";

include_once('../file/config.php');
include '../file/auth.php';
// include '';

include_once('../inc/function.php');


// Query to get total projects count
$result_projects = mysqli_query($conn, "SELECT COUNT(*) AS total_projects FROM project_info");
$total_projects = mysqli_fetch_assoc($result_projects)['total_projects'];

// Query to get total customers count
$result_customers = mysqli_query($conn, "SELECT COUNT(*) AS total_customers FROM customers");
$total_customers = mysqli_fetch_assoc($result_customers)['total_customers'];
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
                        <img src="../assets/img/png-icon/tax.png" alt="">
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
                        <img src="../assets/img/png-icon/revenue.png" alt="">
                     </div>
                     <div class="state-content">
                        <p class="font-14 mb-2">Pending Projects</p>
                        <h2>25</h2>
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
                        <img src="../assets/img/png-icon/comission.png" alt="">
                     </div>
                     <div class="state-content">
                        <p class="font-14 mb-2">Total Customer</p>
                        <h2><?php echo $total_customers; ?></h2>

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
                        <img src="../assets/img/png-icon/user.png" alt="">
                     </div>
                     <div class="state-content">
                        <p class="font-14 mb-2">Expiring Sticker</p>
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
                                    <h4 class="mb-3">Timeline</h4>
                                    <p>Vestibulum blandit viverra convallis.</p>
                                </div>

                                <div class="col-xl-12 p-4">
                                    <!-- Timeline Wrap -->
                                    <div id="timeline-wrap">
                                        <ul class="timeline">
                                            <li class="event" data-date="12 October 2019">
                                                <span>1:08 AM</span>
                                                <h4>Vitae eius reiciendis</h4>
                                                <p>Recusandae quia explicabo.</p>
                                            </li>
                                            <li class="event">
                                                <span>8:00 PM</span>
                                                <h4>Est accusamus rerum molestias.</h4>
                                                <p> Ut illo ut aut. Est exercitationem voluptas optio molestiae modi.</p>
                                            </li>
                                            <li class="event" data-date="13 October 2019">
                                                <span>2:50 PM</span>
                                                <h4>Quam aut exercitationem adipisci</h4>
                                                <p> Alias vitae voluptatum et. Aut odit facere iure dolore. Ut consequatur omnis.</p>
                                            </li>
                                            <li class="event" data-date="14 October 2019">
                                                <span>1:06 PM</span>
                                                <h4>Mollitia assumenda aut sit vel</h4>
                                                <p>Eum dolores nemo quasi repudiandae sunt nesciunt aut possimus.</p>
                                            </li>
                                            
                                        </ul>
                                        
                                    </div>
                                    <a href="../setup/timeline.php" class="d-flex justify-content-center pt-4"><button type="button" class="details-btn">View More <i class="icofont-arrow-right"></i></button></a>
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
                        <h4 class="mb-1">Ongoing Projects</h4>
                        <p class="font-14"> Tell use paid law ever yet new. Meant to learn of vexed he there.</p>
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

                  <!-- Product List -->
                  <div class="product-list">
                     <!-- Product List Item -->
                     <div class="product-list-item mb-20 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                           <div class="img mr-3">
                              <img src="../assets/img/product/product1.png" alt="">
                           </div>
                           <div class="content">
                              <p class="black mb-1">Fastrack Watches</p>
                              <span class="c3 bold font-14">$245.65</span>
                           </div>
                        </div>
                        <p class="font-14">26584 Selles</p>
                     </div>
                     <!-- End Product List Item -->

                     <!-- Product List Item -->
                     <div class="product-list-item mb-20 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                           <div class="img mr-3">
                              <img src="../assets/img/product/product2.png" alt="">
                           </div>
                           <div class="content">
                              <p class="black mb-1">Fastrack Watches 2654</p>
                              <span class="c3 bold font-14">$245.65</span>
                           </div>
                        </div>
                        <p class="font-14">26584 Selles</p>
                     </div>
                     <!-- End Product List Item -->

                     <!-- Product List Item -->
                     <div class="product-list-item mb-20 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                           <div class="img mr-3">
                              <img src="../assets/img/product/product3.png" alt="">
                           </div>
                           <div class="content">
                              <p class="black mb-1">Fastrack Watches 2654</p>
                              <span class="c3 bold font-14">$245.65</span>
                           </div>
                        </div>
                        <p class="font-14">26584 Selles</p>
                     </div>
                     <!-- End Product List Item -->

                     <!-- Product List Item -->
                     <div class="product-list-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                           <div class="img mr-3">
                              <img src="../assets/img/product/product4.png" alt="">
                           </div>
                           <div class="content">
                              <p class="black mb-1">Fastrack Watches 2654</p>
                              <span class="c3 bold font-14">$245.65</span>
                           </div>
                        </div>
                        <p class="font-14">26584 Selles</p>
                     </div>
                     <!-- End Product List Item -->
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
                        <p class="font-14">Tell use paid law ever yet new. Meant to learn of vexed if style allow he there.</p>
                     </div>

                     <!-- Dropdown Button -->
                     <div class="dropdown-button">
                        <a href="#" class="d-flex align-items-center" data-toggle="dropdown">
                           <div class="menu-icon style--two mr-0 d-flex justify-content-center">
                              <span></span>
                              <span></span>
                              <span></span>
                           </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                           <a href="#">Report</a>
                           <a href="#">FAQ</a>
                           <a href="#">Charts</a>
                           <a href="#">Chat</a>
                           <a href="#">Settings</a>
                        </div>
                     </div>
                     <!-- End Dropdown Button -->
                  </div>
               </div>

               <div class="table-responsive">
                  <table class="style--three table-centered text-nowrap">
                     <thead>
                        <tr>
                           <th>Project ID <img src="../assets/img/svg/table-down-arrow.svg" alt="" class="svg"></th>
                           <th>Date <img src="../assets/img/svg/table-down-arrow.svg" alt="" class="svg"></th>
                           <th>Document</th>
                           <th>Customer Name <img src="../assets/img/svg/table-up-arrow.svg" alt="" class="svg"></th>
                           <th>Status <img src="../assets/img/svg/table-down-arrow.svg" alt="" class="svg"></th>
                           <th>Price</th>
                           <th>Shipping Cost</th>
                           <th>Total Cost</th>
                           <th>Action</th>
                        </tr>
                     </thead>
                     <tbody>
                        <tr>
                           <td class="bold">#01254</td>
                           <td>12 Oct 2019</td>
                           <td>
                              <div class="product-img">
                                 <img src="../assets/img/product/product1.png" alt="">
                                 <img src="../assets/img/product/product5.png" alt="">
                                 <img src="../assets/img/product/product6.png" alt="">
                              </div>
                           </td>
                           <td>Kyle Lee</td>
                           <td class="text-danger">Processing</td>
                           <td class="bold">$2456.4</td>
                           <td class="bold">$24.6</td>
                           <td class="bold">2687</td>
                           <td><button type="button" class="details-btn">Details <i class="icofont-arrow-right"></i></button></td>
                        </tr>

                        <tr>
                           <td class="bold">#01365</td>
                           <td>12 Oct 2019</td>
                           <td>
                              <div class="product-img">
                                 <img src="../assets/img/product/product2.png" alt="">
                                 <img src="../assets/img/product/product7.png" alt="">
                                 <img src="../assets/img/product/product3.png" alt="">
                              </div>
                           </td>
                           <td>Lindo De Sire</td>
                           <td class="text-warning">Shipped</td>
                           <td class="bold">$2456.4</td>
                           <td class="bold">$24.6</td>
                           <td class="bold">2687</td>
                           <td><button type="button" class="details-btn">Details <i class="icofont-arrow-right"></i></button></td>
                        </tr>

                        <tr>
                           <td class="bold">#03654</td>
                           <td>11 Oct 2019</td>
                           <td>
                              <div class="product-img">
                                 <img src="../assets/img/product/product8.png" alt="">
                                 <img src="../assets/img/product/product9.png" alt="">
                                 <img src="../assets/img/product/product10.png" alt="">
                              </div>
                           </td>
                           <td>Laturi Yasn</td>
                           <td class="text-success">Delivered</td>
                           <td class="bold">$2456.4</td>
                           <td class="bold">$24.6</td>
                           <td class="bold">2687</td>
                           <td><button type="button" class="details-btn">Details <i class="icofont-arrow-right"></i></button></td>
                        </tr>
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
</div>
<!-- End Main Wrapper -->


<?php
include_once('../inc/footer.php');
?>