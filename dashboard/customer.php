<?php
include_once('../file/config.php');
include_once('../inc/function.php');

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
   header("Location: ../index.php");
   exit();
}

// Check if the user has the 'inspector' role
if ($_SESSION['role'] !== 'customer') {
   // Redirect to a default page or show an error
   header("Location: ../index.php");
   exit();
}

// Get the logged-in inspector's name or ID from the session
// $inspector_name = $_SESSION['username']; // Assuming you store the inspector's name in the session
// $inspector_id = $_SESSION['user_id']; // Assuming you store the inspector's ID in the session



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
                        <!-- <h2><?php echo $total_projects; ?></h2> -->
                        <h2> 20 

                        </h2>
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
                        <!-- <h2>                           
                           <?php echo $pending_projects; ?>
                           </h2> -->
                           <h2>20</h2>
                     </div>
                  </div>
               </div>
            </div>
            <!-- End Card -->
         </div>
<!-- 
         <div class="col-xl-3 col-sm-6">
            
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
            
         </div> -->

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

        

         
         
         


         <!-- <div class="col-xl-12">
            
            <div class="card">
               <div class="card-body pb-0">
                  <div class="d-flex justify-content-between">
                     <div class="title-content mb-4">
                        <h4 class="mb-2">Recent Projects</h4>
                        <p class="font-14">Tell use paid law ever yet new. Meant to learn of vexed if style allow he there.</p>
                     </div>

                     
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
            
         </div> -->
      </div>
   </div>
</div>
<!-- End Main Content -->
</div>
<!-- End Main Wrapper -->


<?php
include_once('../inc/footer.php');
?>