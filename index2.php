<?php 
session_start();
// $url= 'https://appcims.com/whiteapp/';
$url= 'http://localhost/whiteappupdated/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <!-- Page Title -->
   <title>CIMS - 3rd Party App</title>

   <!-- Meta Data -->
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta http-equiv="content-type" content="text/html; charset=utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="description" content="">
   <meta name="keywords" content="">

   <!-- Favicon -->
   <link rel="shortcut icon" href="assets/img/favicon.png">

   <!-- Web Fonts -->
   <link href="https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700,700i&display=swap" rel="stylesheet">
   
   <!-- ======= BEGIN GLOBAL MANDATORY STYLES ======= -->
   <link rel="stylesheet" href="<?php echo $url; ?>assets/bootstrap/css/bootstrap.min.css">
   <link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/icofont/icofont.min.css">
   <link rel="stylesheet" href="<?php echo $url; ?>assets/plugins/perfect-scrollbar/perfect-scrollbar.min.css">
  
  
   <!-- ======= END BEGIN GLOBAL MANDATORY STYLES ======= -->

   
   <!-- ======= BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
   <link rel="stylesheet" href="<?php echo $url; ?>assets/plugins/datepicker/datepicker.min.css">
   <!-- ======= END BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
   <!-- ### datatable -->
   <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">


   
   <!-- ======= BEGIN PAGE LEVEL PLUGINS STYLES ======= -->
   <link rel="stylesheet" href="<?php echo $url; ?>assets/plugins/apex/apexcharts.css">
   <!-- ======= END BEGIN PAGE LEVEL PLUGINS STYLES ======= -->

   <!-- ======= MAIN STYLES ======= -->
   <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">
   <!-- ======= END MAIN STYLES ======= -->
  <!--  icon -------------------------------->
   <link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/et-lineicon/et-lineicons.css">
   <link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/themify-icons/themify-icons.css">


<style>
  .dataTables_filter{
   margin: 20px;
  }
  .dt-buttons button:hover{
      color: #000000;
    background: #12006E;
    border: none;
   }
   .dt-buttons .buttons-excel{
      color: #ffffff;
    background: #6045e2;
    border: none;
   }
   .dt-buttons{
      margin:20px
   }
</style>

</head>

<body>

   <!-- Offcanval Overlay -->
   <div class="offcanvas-overlay"></div>
   <!-- Offcanval Overlay -->

   <!-- Wrapper -->
   <div class="wrapper">
<body>

    <div class="mn-vh-100 d-flex align-items-center">
        <div class="container">
            <!-- Card -->
            <div class="card justify-content-center auth-card">
                <div class="row justify-content-center">
                    <div class="col-xl-7 col-lg-9">
                        <h4 class="mb-2 font-20 text-center">Welcome To CIMS</h4>
                        <p class="mb-5 font-16 text-center">Cims Inspection Application </p>
                        

                        <form method="post" action="./file/authentication.php">
                            <!-- Form Group -->
                            <div class="form-group mb-20">
                                <label for="email" class="mb-2 font-14 bold black">Username</label>
                                <input type="text" name="username" id="email" class="theme-input-style" value="" placeholder="Enter your Username">
                            </div>
                            <!-- End Form Group -->
                            
                            <!-- Form Group -->
                            <div class="form-group mb-20">
                                <label for="password" class="mb-2 font-14 bold black">Password</label>
                                <input type="password" name="password" id="password" class="theme-input-style" value="" placeholder="********">
                            </div>
                            <!-- End Form Group -->

                            <div class="d-flex justify-content-between mb-20">
                                <div class="d-flex align-items-center">
                                    <!-- Custom Checkbox -->
                                    <label class="custom-checkbox position-relative mr-2">
                                        <input type="checkbox" id="checkbox">
                                        <span class="checkmark"></span>
                                    </label>
                                    <!-- End Custom Checkbox -->
                                    
                                    <label for="checkbox" class="font-14">Remember Me</label>
                                </div>

                                <a href="authentication/forget-pass.html" class="font-12 text_color">Forgot Password?</a>
                            </div>
<!-- 
                            <div class="mb-30">
                                <a href="#" class="light-btn mr-3 mb-20">Log In With Facebook</a>
                                <a href="#" class="light-btn style--two mb-20">Log In With Gmail</a>
                            </div> -->

                            <div class="d-flex align-items-center">
                                <button type="submit" class="btn long mr-20">Log In</button>
                                <!-- <span class="font-12 d-block"><a href="register.html" class="bold">Sign Up</a>,If you have no account.</span> -->
                            </div>
                        </form>
                    </div>                                    
                </div>
            </div>
            <!-- End Card -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer style--two">
       CIMS © 2025 Developed by <a href="https://www.burion.in/" target="_blank"> Burion Technologies</a>
    </footer>
    <!-- End Footer -->

    <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
    <script src="<?php echo $url; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $url; ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $url; ?>assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="<?php echo $url; ?>assets/js/script.js"></script>
    <!-- ======= BEGIN GLOBAL MANDATORY SCRIPTS ======= -->
</body>

</html>