<?php 
session_start();
$url= 'http://localhost/whiteappupdated/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CIMS - Modern Inspection Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/img/favicon.png">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Global Styles -->
    <link rel="stylesheet" href="<?php echo $url; ?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/icofont/icofont.min.css">
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">

    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #224abe;
            --accent-color: #1cc88a;
            --bg-gradient: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            --glass-bg: rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            overflow: hidden;
        }

        .login-container {
            display: flex;
            width: 1000px;
            max-width: 95%;
            height: 600px;
            background: var(--glass-bg);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.3);
            overflow: hidden;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-side-info {
            flex: 1;
            background: #f8f9fc;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            border-right: 1px solid #e3e6f0;
        }

        .login-form-side {
            width: 450px;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-area {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-area img {
            max-width: 180px;
            margin-bottom: 10px;
        }

        .welcome-msg {
            text-align: center;
            margin-bottom: 40px;
        }

        .welcome-msg h2 {
            font-weight: 700;
            font-size: 24px;
            color: #1e3c72;
            margin-bottom: 5px;
        }

        .welcome-msg p {
            color: #858796;
            font-size: 14px;
        }

        .theme-input-style {
            width: 100%;
            height: 50px;
            background: #fff;
            border: 2px solid #eaecf4;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 14px;
            transition: all 0.3s;
            margin-bottom: 20px;
        }

        .theme-input-style:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 10px rgba(78, 115, 223, 0.1);
            outline: none;
        }

        .login-btn {
            width: 100%;
            height: 50px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }

        .login-btn:hover {
            background: var(--secondary-color);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(34, 74, 190, 0.3);
        }

        .cert-section {
            margin-top: auto;
        }

        .cert-title {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 700;
            color: #b7b9cc;
            letter-spacing: 1px;
            margin-bottom: 15px;
            display: block;
        }

        .cert-badges {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .badge-item {
            background: white;
            padding: 8px 15px;
            border-radius: 8px;
            border: 1px solid #eaecf4;
            font-size: 12px;
            font-weight: 600;
            color: #4e73df;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        .customer-req {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #eaecf4;
            padding-top: 20px;
        }

        .customer-req a {
            color: var(--accent-color);
            font-weight: 600;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .customer-req a:hover {
            color: #17a673;
            text-decoration: underline;
        }

        .footer-text {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: rgba(255,255,255,0.7);
            font-size: 12px;
        }

        .footer-text a {
            color: #fff;
            text-decoration: none;
            font-weight: 600;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .login-side-info {
                display: none;
            }
            .login-container {
                width: 450px;
                height: auto;
            }
            .login-form-side {
                width: 100%;
                padding: 40px 30px;
            }
        }
    </style>
</head>

<body>
    <div class="login-container">
        <!-- Side Info -->
        <div class="login-side-info">
            <div>
                <img src="assets/img/logo.png" alt="CIMS Logo" style="max-width: 150px; margin-bottom: 20px;">
                <h1 style="font-size: 32px; font-weight: 800; color: #1e3c72; line-height: 1.2;">Smart Inspection <br><span style="color: var(--accent-color);">Management System</span></h1>
                <p style="margin-top: 20px; color: #5a5c69; line-height: 1.6;">Streamline your inspection workflow with CIMS. Powerful, reliable, and easy to use 3rd party application.</p>
            </div>

            <div class="cert-section">
                <span class="cert-title">Accreditations & Certifications</span>
                <div class="cert-badges">
                    <div class="badge-item"><i class="icofont-check-circled mr-2"></i> ISO 9001</div>
                    <div class="badge-item"><i class="icofont-check-circled mr-2"></i> LEEA Member</div>
                    <div class="badge-item"><i class="icofont-check-circled mr-2"></i> ISO 17020</div>
                    <div class="badge-item"><i class="icofont-check-circled mr-2"></i> OHSAS 18001</div>
                </div>
            </div>
        </div>

        <!-- Login Form -->
        <div class="login-form-side">
            <div class="welcome-msg">
                <h2>Welcome Back!</h2>
                <p>Please log in to your account</p>
            </div>

            <form method="post" action="./file/authentication.php">
                <div class="form-group">
                    <label class="font-14 bold black mb-2">Username</label>
                    <input type="text" name="username" class="theme-input-style" placeholder="Enter your username" required>
                </div>
                
                <div class="form-group">
                    <div class="d-flex justify-content-between mb-2">
                        <label class="font-14 bold black">Password</label>
                        <a href="authentication/forget-pass.html" style="font-size: 12px; color: #858796;">Forgot?</a>
                    </div>
                    <input type="password" name="password" class="theme-input-style" placeholder="********" required>
                </div>

                <div class="d-flex align-items-center mb-4">
                    <input type="checkbox" id="remember" style="margin-right: 10px;">
                    <label for="remember" style="font-size: 13px; color: #858796; margin-bottom: 0;">Remember Me</label>
                </div>

                <button type="submit" class="login-btn">Log In</button>
            </form>

            <div class="customer-req">
                <a href="#"><i class="icofont-user-alt-3 mr-2"></i> Customer Login Request</a>
            </div>
        </div>
    </div>

    <div class="footer-text">
        CIMS © 2026 | Developed by <a href="https://www.burion.in/" target="_blank">Burion Technologies</a>
    </div>

    <!-- Scripts -->
    <script src="<?php echo $url; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $url; ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
