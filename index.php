<?php 
session_start();
$url= 'http://localhost/whiteappupdated/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>CIMS • modern inspection login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="assets/img/favicon.png">
    
    <!-- Google Fonts (Inter + Poppins fallback) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Global Styles -->
    <link rel="stylesheet" href="<?php echo $url; ?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo $url; ?>assets/fonts/icofont/icofont.min.css">
    <link rel="stylesheet" href="<?php echo $url; ?>assets/css/style.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --depth-primary: #0f2b4b;
            --depth-accent: #1b4a6b;
            --glow-blue: #2a7faa;
            --soft-teal: #3ab5b0;
            --gold-light: #e8c284;
            --glass-edge: rgba(255, 255, 255, 0.08);
            --card-bg: rgba(22, 34, 55, 0.7);
            --input-bg: rgba(255, 255, 255, 0.06);
            --border-glow: rgba(90, 190, 230, 0.3);
            --font-main: 'Inter', 'Poppins', sans-serif;
        }

        body {
            font-family: var(--font-main);
            background: #07111f;  /* deep base */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            position: relative;
            overflow: hidden;
            color: #eef4ff;
        }

        /* sophisticated animated gradient overlay */
        body::before {
            content: "";
            position: absolute;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle at 20% 30%, rgba(43, 108, 176, 0.4) 0%, transparent 30%),
                        radial-gradient(circle at 80% 70%, rgba(58, 181, 176, 0.3) 0%, transparent 35%),
                        repeating-linear-gradient(45deg, rgba(255,255,255,0.01) 0px, rgba(255,255,255,0.01) 2px, transparent 2px, transparent 8px);
            animation: slowDrift 32s infinite alternate ease-in-out;
            z-index: 0;
        }

        @keyframes slowDrift {
            0% { transform: translate(-5%, -5%) rotate(0deg); }
            100% { transform: translate(5%, 5%) rotate(2deg); }
        }

        .login-container {
            width: 1060px;
            max-width: 96%;
            background: rgba(18, 30, 46, 0.65);
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            border-radius: 42px;
            border: 1px solid var(--glass-edge);
            box-shadow: 0 40px 70px -10px rgba(0, 0, 0, 0.7), 0 0 0 1px rgba(90, 180, 230, 0.15) inset;
            display: flex;
            overflow: hidden;
            position: relative;
            z-index: 10;
            transition: box-shadow 0.5s;
            animation: floatIn 0.9s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .login-container:hover {
            box-shadow: 0 50px 90px -8px #030b14, 0 0 0 1px rgba(58, 181, 176, 0.3) inset;
        }

        @keyframes floatIn {
            0% { opacity: 0; transform: scale(0.96) translateY(20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Left panel – premium info with glass */
        .login-side-info {
            flex: 1.1;
            padding: 48px 38px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background: linear-gradient(145deg, rgba(12, 24, 42, 0.7), rgba(7, 15, 28, 0.9));
            backdrop-filter: blur(12px);
            border-right: 1px solid rgba(78, 152, 200, 0.25);
            position: relative;
        }

        .login-side-info::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 30% 50%, rgba(58, 181, 176, 0.1) 0%, transparent 60%);
            pointer-events: none;
        }

        .logo-tag {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 40px;
            position: relative;
        }

        .logo-tag img {
            max-width: 140px;
            filter: drop-shadow(0 4px 8px #00000040);
            transition: transform 0.3s ease;
        }

        .logo-tag img:hover {
            transform: scale(1.02);
        }

        .side-headline {
            margin-top: 20px;
        }

        .side-headline h1 {
            font-size: 40px;
            font-weight: 700;
            line-height: 1.15;
            background: linear-gradient(to right, #ffffff, #c6e2ff);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.02em;
            text-shadow: 0 2px 5px rgba(0,20,40,0.5);
        }

        .side-headline .accent-gradient {
            background: linear-gradient(135deg, #78d9d0, #b1e0f9);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            font-weight: 600;
            display: inline-block;
        }

        .side-description {
            margin-top: 28px;
            font-size: 16px;
            line-height: 1.7;
            color: rgba(210, 230, 255, 0.85);
            font-weight: 400;
            max-width: 380px;
            border-left: 3px solid #2f8fbb;
            padding-left: 24px;
            background: linear-gradient(90deg, rgba(47,143,187,0.1), transparent);
        }

        .cert-section {
            margin-top: auto;
            padding-top: 40px;
        }

        .cert-title {
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2.5px;
            font-weight: 600;
            color: #9bb5d4;
            margin-bottom: 24px;
            display: block;
            opacity: 0.8;
        }

        .cert-badges {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
        }

        .badge-item {
            background: rgba(18, 34, 58, 0.6);
            backdrop-filter: blur(6px);
            padding: 8px 18px;
            border-radius: 60px;
            border: 1px solid rgba(120, 200, 240, 0.25);
            font-size: 13px;
            font-weight: 500;
            color: #d0ebff;
            display: inline-flex;
            align-items: center;
            box-shadow: 0 8px 16px -10px #000;
            transition: all 0.2s;
        }

        .badge-item i {
            color: #3ab5b0;
            margin-right: 8px;
            font-size: 16px;
        }

        .badge-item:hover {
            border-color: #3ab5b0;
            background: rgba(32, 62, 98, 0.7);
            transform: translateY(-2px);
        }

        /* RIGHT panel – form premium minimal */
        .login-form-side {
            width: 460px;
            padding: 60px 52px;
            background: rgba(12, 22, 36, 0.5);
            backdrop-filter: blur(30px);
            display: flex;
            flex-direction: column;
            justify-content: center;
            border-left: 1px solid rgba(255,255,255,0.02);
        }

        .welcome-msg h2 {
            font-weight: 600;
            font-size: 32px;
            background: linear-gradient(to bottom, #f2f9ff, #c1d9f0);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            letter-spacing: -0.5px;
            margin-bottom: 8px;
        }

        .welcome-msg p {
            color: #9bb0ca;
            font-size: 15px;
            font-weight: 400;
            border-bottom: 1px solid rgba(90, 160, 220, 0.3);
            padding-bottom: 26px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 28px;
        }

        .input-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .input-label label {
            font-weight: 500;
            font-size: 14px;
            color: #c9def5;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .input-label a {
            font-size: 13px;
            color: #86b8dd;
            text-decoration: none;
            transition: 0.2s;
            border-bottom: 1px dashed transparent;
        }

        .input-label a:hover {
            color: #b3e2f2;
            border-bottom-color: #3ab5b0;
        }

        .theme-input-style {
            width: 100%;
            height: 60px;
            background: var(--input-bg);
            border: 1.5px solid rgba(70, 130, 200, 0.3);
            border-radius: 18px;
            padding: 0 24px;
            font-size: 16px;
            font-weight: 400;
            color: #f0f7ff;
            transition: all 0.25s ease;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }

        .theme-input-style:focus {
            border-color: #3ab5b0;
            background: rgba(30, 50, 76, 0.6);
            box-shadow: 0 0 0 4px rgba(58, 181, 176, 0.2), 0 6px 14px rgba(0, 10, 20, 0.5);
            outline: none;
        }

        .theme-input-style::placeholder {
            color: #7e98b5;
            font-weight: 300;
            font-size: 15px;
        }

        .remember-row {
            display: flex;
            align-items: center;
            margin: 24px 0 28px;
        }

        .remember-row input[type="checkbox"] {
            width: 20px;
            height: 20px;
            margin-right: 12px;
            accent-color: #3ab5b0;
            border-radius: 6px;
            border: 1px solid #5688b7;
            background: transparent;
            cursor: pointer;
        }

        .remember-row label {
            font-size: 15px;
            color: #b7d0e9;
            font-weight: 400;
            cursor: pointer;
        }

        .login-btn {
            width: 100%;
            height: 64px;
            background: linear-gradient(145deg, #1f5f7a, #103a56);
            border: none;
            border-radius: 36px;
            font-weight: 600;
            font-size: 18px;
            letter-spacing: 0.5px;
            color: white;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 10px 22px -8px #020b14, 0 0 0 1px rgba(90, 200, 220, 0.3) inset;
            margin-top: 14px;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255,255,255,0.25), transparent 70%);
            opacity: 0;
            transition: opacity 0.4s;
        }

        .login-btn:hover {
            background: linear-gradient(145deg, #267a9c, #164b6e);
            box-shadow: 0 18px 32px -8px #000, 0 0 0 1px #3ab5b0 inset;
            transform: scale(1.02);
        }

        .login-btn:hover::before {
            opacity: 0.4;
        }

        .customer-req {
            margin-top: 32px;
            text-align: center;
        }

        .customer-req a {
            color: #b3daf5;
            font-weight: 500;
            text-decoration: none;
            font-size: 15px;
            border-bottom: 1px dotted #5887b5;
            transition: 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .customer-req a i {
            font-size: 18px;
            color: #3ab5b0;
        }

        .customer-req a:hover {
            color: white;
            border-bottom-color: #3ab5b0;
            gap: 12px;
        }

        .footer-text {
            position: absolute;
            bottom: 24px;
            left: 0;
            width: 100%;
            text-align: center;
            color: rgba(170, 200, 240, 0.5);
            font-size: 14px;
            font-weight: 400;
            z-index: 20;
            backdrop-filter: blur(4px);
            padding: 6px 0;
        }

        .footer-text a {
            color: #aad0f0;
            text-decoration: none;
            font-weight: 600;
            border-bottom: 1px solid rgba(90, 180, 200, 0.4);
        }

        .footer-text a:hover {
            color: white;
            border-bottom-color: #7ae2d9;
        }

        /* responsive finesse */
        @media (max-width: 820px) {
            .login-side-info {
                display: none;
            }
            .login-form-side {
                width: 100%;
                padding: 50px 36px;
            }
            .login-container {
                width: 480px;
                border-radius: 48px;
            }
        }
        @media (max-width: 480px) {
            .login-form-side { padding: 40px 24px; }
            .login-btn { height: 56px; font-size: 16px; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left side: premium brand & credibility (more visual) -->
        <div class="login-side-info">
            <div>
                <div class="logo-tag">
                    <!-- keep original logo path, added subtle filter to match dark bg -->
                    <img src="assets/img/logo.png" alt="CIMS" onerror="this.style.display='none';">
                    <span style="font-size: 22px; font-weight: 300; color: #90b8e0;">|</span>
                    <span style="font-size: 20px; font-weight: 300; letter-spacing: 2px; background: linear-gradient(145deg,#d1ecfd,#a5cef0); -webkit-background-clip:text; background-clip:text; color:transparent;">inspectionOS</span>
                </div>
                <div class="side-headline">
                    <h1>Smart<br><span class="accent-gradient">inspection</span> workflow</h1>
                </div>
                <div class="side-description">
                    <i class="icofont-shield" style="color: #3ab5b0; margin-right: 12px;"></i> 
                    Trusted by 500+ certified bodies. Lifting equipment, cranes, and industrial inspection.
                </div>
            </div>

            <div class="cert-section">
                <span class="cert-title">⛓️ accreditations & memberships</span>
                <div class="cert-badges">
                    <div class="badge-item"><i class="icofont-check-circled"></i> ISO 9001:2024</div>
                    <div class="badge-item"><i class="icofont-check-circled"></i> LEEA Corporate</div>
                    <div class="badge-item"><i class="icofont-check-circled"></i> ISO 17020</div>
                    <div class="badge-item"><i class="icofont-check-circled"></i> OHSAS 45001</div>
                    <div class="badge-item"><i class="icofont-check-circled"></i> NADCAP</div>
                </div>
            </div>
        </div>

        <!-- Right side: login form (exact same functionality, enhanced UI) -->
        <div class="login-form-side">
            <div class="welcome-msg">
                <h2>secure login</h2>
                <p>enter your credentials — CIMS v3.2</p>
            </div>

            <form method="post" action="./file/authentication.php">
                <div class="form-group">
                    <div class="input-label">
                        <label>Username</label>
                    </div>
                    <input type="text" name="username" class="theme-input-style" placeholder="e.g., j.smith@inspect" required>
                </div>
                
                <div class="form-group">
                    <div class="input-label">
                        <label>Password</label>
                        <a href="authentication/forget-pass.html">forgot?</a>
                    </div>
                    <input type="password" name="password" class="theme-input-style" placeholder="··········" required>
                </div>

                <div class="remember-row">
                    <input type="checkbox" id="remember">
                    <label for="remember">Keep me signed in until I log out</label>
                </div>

                <button type="submit" class="login-btn">sign in →</button>
            </form>

            <div class="customer-req">
                <a href="#"><i class="icofont-user-alt-3"></i> request customer access</a>
            </div>
        </div>
    </div>

    <!-- <div class="footer-text">
        CIMS © 2026 •  <a href="https://www.burion.in/" target="_blank">Burion Technologies</a>  •  secure inspection suite
    </div> -->

    <div class="footer-text">
    CIMS © <?php echo date("Y"); ?> •  
    <a href="https://www.burion.in/" target="_blank">Burion Technologies</a>  
    • secure inspection suite
</div>
    <!-- Scripts (unchanged) -->
    <script src="<?php echo $url; ?>assets/js/jquery.min.js"></script>
    <script src="<?php echo $url; ?>assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- no extra functionality — only UI elevation -->
</body>
</html>