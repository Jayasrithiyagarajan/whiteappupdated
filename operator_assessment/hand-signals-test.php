<?php
// session_start();
include_once('../file/config.php');
// include_once('../inc/function.php');
include_once('signals-config.php');

// Get assessment ID from URL
$assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($assessment_id === 0) {
    $_SESSION['error_msg'] = "Invalid assessment ID";
    header("Location: assessment-list.php");
    exit();
}

// Fetch assessment details
$sql = "SELECT 
            oa.*,
            c.customer_name as client_name,
            nu.username as inspector_name
        FROM operator_assessments oa
        LEFT JOIN customers c ON oa.client_id = c.cus_id
        LEFT JOIN new_users nu ON oa.inspector_id = nu.user_id
        WHERE oa.id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $assessment_id);
$stmt->execute();
$result = $stmt->get_result();
$assessment = $result->fetch_assoc();

if (!$assessment) {
    $_SESSION['error_msg'] = "Assessment not found";
    header("Location: assessment-list.php");
    exit();
}

// Check if written exam passed
if ($assessment['exam_status'] !== 'PASSED') {
    $_SESSION['error_msg'] = "Please complete and pass the written exam first.";
    header("Location: written-exam.php?id=" . $assessment_id);
    exit();
}

// Check if signals test already passed
if ($assessment['signals_status'] === 'PASSED') {
    $_SESSION['info_msg'] = "You have already passed the hand signals test.";
    header("Location: signals-result.php?id=" . $assessment_id);
    exit();
}

// Check if max attempts reached
if ($signals_settings['allow_retake'] && $assessment['signals_attempts'] >= $signals_settings['max_attempts']) {
    $_SESSION['error_msg'] = "Maximum hand signals test attempts reached.";
    header("Location: view-assessment.php?id=" . $assessment_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hand Signals Test - Operator Assessment</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            padding: 40px 0;
            color: #f8fafc;
        }
        
        .test-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .test-header {
            background: #1e293b;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .test-header h1 {
            color: #38bdf8;
            font-weight: 700;
            font-size: 2.2rem;
            margin-bottom: 8px;
            background: linear-gradient(to right, #38bdf8, #0ea5e9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .test-header p {
            color: #94a3b8;
            font-size: 1.05rem;
            margin-bottom: 0;
        }
        
        .info-card {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border-radius: 16px;
            padding: 25px;
            margin-bottom: 25px;
            color: #f8fafc;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .info-item {
            margin: 5px 0;
            font-size: 0.95rem;
        }
        
        .info-item strong {
            color: #38bdf8;
        }
        
        .instructions-card {
            background: rgba(245, 158, 11, 0.08);
            border-left: 5px solid #f59e0b;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
            border-top: 1px solid rgba(245, 158, 11, 0.15);
            border-right: 1px solid rgba(245, 158, 11, 0.15);
            border-bottom: 1px solid rgba(245, 158, 11, 0.15);
        }
        
        .instructions-card h5 {
            color: #f59e0b;
            font-weight: 600;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .instructions-card ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .instructions-card li {
            margin: 8px 0;
            color: #cbd5e1;
            font-size: 0.95rem;
        }
        
        .progress-card {
            background: #1e293b;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            position: sticky;
            top: 20px;
            z-index: 1000;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .progress {
            height: 30px;
            border-radius: 15px;
            background-color: #334155;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, #38bdf8 0%, #0ea5e9 100%);
            border-radius: 15px;
            font-weight: 600;
            font-size: 14px;
            line-height: 30px;
            color: white;
            box-shadow: 0 2px 8px rgba(14, 165, 233, 0.3);
        }
        
        .signal-card {
            background: #1e293b;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 25px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.25);
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .signal-number {
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            color: white;
            padding: 10px 25px;
            border-radius: 50px;
            display: inline-block;
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(14, 165, 233, 0.25);
        }
        
        .signal-name {
            font-size: 2rem;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 12px;
        }
        
        .signal-description {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-bottom: 30px;
        }
        
        .signal-image-container {
            background: #0f172a;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            min-height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .signal-image {
            max-width: 100%;
            max-height: 500px;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.35);
        }
        
        .placeholder-image {
            color: #94a3b8;
            font-size: 1.2rem;
            text-align: center;
        }
        
        .action-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }
        
        .btn-pass {
            background: linear-gradient(135deg, #059669 0%, #10b981 100%);
            color: white;
            border: none;
            padding: 18px 50px;
            font-size: 1.4rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(16, 185, 129, 0.25);
        }
        
        .btn-pass:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(16, 185, 129, 0.45);
        }
        
        .btn-fail {
            background: linear-gradient(135deg, #dc2626 0%, #f87171 100%);
            color: white;
            border: none;
            padding: 18px 50px;
            font-size: 1.4rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(220, 38, 38, 0.25);
        }
        
        .btn-fail:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(220, 38, 38, 0.45);
        }
        
        .btn-pass.selected {
            box-shadow: 0 0 0 5px rgba(16, 185, 129, 0.4);
            transform: scale(1.03);
        }
        
        .btn-fail.selected {
            box-shadow: 0 0 0 5px rgba(220, 38, 38, 0.4);
            transform: scale(1.03);
        }
        
        .navigation-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 25px;
        }
        
        .btn-nav {
            background: transparent;
            color: #38bdf8;
            border: 2px solid #38bdf8;
            padding: 12px 35px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .btn-nav:hover {
            background: #38bdf8;
            color: #0f172a;
        }
        
        .btn-nav:disabled {
            opacity: 0.25;
            cursor: not-allowed;
            border-color: #475569;
            color: #475569;
        }
        
        .submit-card {
            background: #1e293b;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.25);
            margin-bottom: 30px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            display: none;
        }
        
        .submit-card.show {
            display: block;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: white;
            border: none;
            padding: 18px 50px;
            font-size: 1.3rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(14, 165, 233, 0.3);
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(14, 165, 233, 0.5);
        }
        
        @media (max-width: 768px) {
            .test-header {
                padding: 20px 15px;
            }
            .test-header h1 {
                font-size: 1.8rem;
            }
            .test-header p {
                font-size: 1rem;
            }
            .info-card {
                padding: 15px;
            }
            .info-row {
                flex-direction: column;
                gap: 10px;
            }
            .signal-card {
                padding: 20px 15px;
            }
            .signal-name {
                font-size: 1.5rem;
            }
            .signal-description {
                font-size: 1rem;
            }
            .signal-image-container {
                min-height: 250px;
                padding: 15px;
                margin: 20px 0;
            }
            .signal-image {
                max-height: 300px;
            }
            .action-buttons {
                flex-direction: column;
                gap: 15px;
            }
            .btn-pass, .btn-fail {
                width: 100%;
                padding: 15px 20px;
                font-size: 1.2rem;
            }
            .navigation-buttons {
                flex-direction: column;
                gap: 15px;
            }
            .btn-nav {
                width: 100%;
                text-align: center;
                margin-top: 5px;
            }
            .submit-card {
                padding: 25px 15px;
            }
            .submit-btn {
                width: 100%;
                font-size: 1.1rem;
                padding: 15px 20px;
                white-space: normal;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
    <div class="test-container">
        <!-- Header -->
        <div class="test-header">
            <h1><i class="fas fa-hand-paper"></i> Hand Signals Practical Test</h1>
            <p>Step 4: Demonstrate Knowledge of Crane Hand Signals</p>
        </div>

        <!-- Assessment Info -->
        <div class="info-card">
            <div class="info-row">
                <div>
                    <div class="info-item"><strong>Assessment No:</strong> <?php echo htmlspecialchars($assessment['assessment_no']); ?></div>
                    <div class="info-item"><strong>Operator:</strong> <?php echo htmlspecialchars($assessment['operator_name']); ?></div>
                </div>
                <div>
                    <div class="info-item"><strong>Total Signals:</strong> 18</div>
                    <div class="info-item"><strong>Passing Score:</strong> 80% (15/18)</div>
                    <?php if ($signals_settings['allow_retake']): ?>
                    <div class="info-item"><strong>Attempt:</strong> <?php echo ($assessment['signals_attempts'] + 1); ?> of <?php echo $signals_settings['max_attempts']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="instructions-card">
            <h5><i class="fas fa-info-circle"></i> Instructions for Inspector</h5>
            <ul>
                <li>Show each hand signal diagram to the operator</li>
                <li>Ask the operator to identify or demonstrate the signal</li>
                <li>Mark PASS if the operator correctly identifies/demonstrates the signal</li>
                <li>Mark FAIL if the operator's response is incorrect</li>
                <li>You must evaluate all 18 signals before submitting</li>
                <li>Operator needs 15 or more correct answers (80%) to pass</li>
            </ul>
        </div>

        <!-- Test Form -->
        <form id="signalsForm" action="submit-signals.php" method="POST">
            <input type="hidden" name="assessment_id" value="<?php echo $assessment_id; ?>">
            
            <!-- Progress Indicator -->
            <div class="progress-card">
                <div class="progress">
                    <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;">
                        <span id="progressText">0/18 Tested</span>
                    </div>
                </div>
            </div>

            <!-- Signal Cards -->
            <?php foreach ($hand_signals as $signal_num => $signal): ?>
            <div class="signal-card" id="signal-<?php echo $signal_num; ?>" style="display: <?php echo $signal_num === 1 ? 'block' : 'none'; ?>;">
                <div class="signal-number">
                    Signal <?php echo $signal_num; ?> of 18
                </div>
                
                <h2 class="signal-name"><?php echo htmlspecialchars($signal['name']); ?></h2>
                <p class="signal-description"><?php echo htmlspecialchars($signal['description']); ?></p>
                
                <div class="signal-image-container">
                    <?php 
                    $image_path = 'hand-signals/' . $signal['image'];
                    if (file_exists($image_path)): 
                    ?>
                        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($signal['name']); ?>" class="signal-image">
                    <?php else: ?>
                        <div class="placeholder-image">
                            <i class="fas fa-image fa-5x mb-3"></i>
                            <p>Image will be added soon</p>
                            <small>Signal: <?php echo htmlspecialchars($signal['name']); ?></small>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="action-buttons">
                    <button type="button" class="btn-pass" onclick="markSignal(<?php echo $signal_num; ?>, 'PASS')">
                        <i class="fas fa-check-circle"></i> PASS
                    </button>
                    <button type="button" class="btn-fail" onclick="markSignal(<?php echo $signal_num; ?>, 'FAIL')">
                        <i class="fas fa-times-circle"></i> FAIL
                    </button>
                </div>
                
                <input type="hidden" name="signal_<?php echo $signal_num; ?>" id="result_<?php echo $signal_num; ?>" value="">
                
                <div class="navigation-buttons">
                    <button type="button" class="btn-nav" onclick="previousSignal()" id="prevBtn" <?php echo $signal_num === 1 ? 'disabled' : ''; ?>>
                        <i class="fas fa-arrow-left"></i> Previous
                    </button>
                    <button type="button" class="btn-nav" onclick="nextSignal()" id="nextBtn">
                        Next <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Submit Card -->
            <div class="submit-card" id="submitCard">
                <h3 style="color: #38bdf8; margin-bottom: 20px;">All Signals Tested!</h3>
                <p style="color: #94a3b8; margin-bottom: 30px;">Review your evaluations and submit the test results</p>
                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> Submit Test Results
                </button>
            </div>
        </form>
    </div>
    </div>

    
    
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let currentSignal = 1;
        const totalSignals = 18;
        let testedSignals = new Set();
        let results = {};

        function markSignal(signalNum, result) {
            // Store result
            results[signalNum] = result;
            document.getElementById('result_' + signalNum).value = result;
            
            // Add to tested set
            testedSignals.add(signalNum);
            
            // Visual feedback
            const passBtn = document.querySelector(`#signal-${signalNum} .btn-pass`);
            const failBtn = document.querySelector(`#signal-${signalNum} .btn-fail`);
            
            passBtn.classList.remove('selected');
            failBtn.classList.remove('selected');
            
            if (result === 'PASS') {
                passBtn.classList.add('selected');
            } else {
                failBtn.classList.add('selected');
            }
            
            // Update progress
            updateProgress();
            
            // Auto-advance to next signal after 1 second
            setTimeout(() => {
                if (currentSignal < totalSignals) {
                    nextSignal();
                } else {
                    showSubmitCard();
                }
            }, 1000);
        }

        function nextSignal() {
            if (currentSignal < totalSignals) {
                document.getElementById('signal-' + currentSignal).style.display = 'none';
                currentSignal++;
                document.getElementById('signal-' + currentSignal).style.display = 'block';
                updateNavigationButtons();
            }
        }

        function previousSignal() {
            if (currentSignal > 1) {
                document.getElementById('signal-' + currentSignal).style.display = 'none';
                currentSignal--;
                document.getElementById('signal-' + currentSignal).style.display = 'block';
                updateNavigationButtons();
            }
        }

        function updateNavigationButtons() {
            document.getElementById('prevBtn').disabled = (currentSignal === 1);
            document.getElementById('nextBtn').disabled = (currentSignal === totalSignals);
        }

        function updateProgress() {
            const tested = testedSignals.size;
            const percentage = (tested / totalSignals) * 100;
            
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = tested + '/' + totalSignals + ' Tested';
            
            if (tested === totalSignals) {
                showSubmitCard();
            }
        }

        function showSubmitCard() {
            document.getElementById('submitCard').classList.add('show');
            document.getElementById('submitCard').scrollIntoView({ behavior: 'smooth' });
        }

        // Form submission validation
        document.getElementById('signalsForm').addEventListener('submit', function(e) {
            if (testedSignals.size < totalSignals) {
                e.preventDefault();
                const untested = totalSignals - testedSignals.size;
                alert(`Please evaluate all signals! You have ${untested} signal(s) not yet tested.`);
                return false;
            }
            
            if (!confirm('Are you sure you want to submit the test results? You cannot change the evaluation after submission.')) {
                e.preventDefault();
                return false;
            }
        });

        // Prevent accidental page leave
        window.addEventListener('beforeunload', function(e) {
            if (testedSignals.size > 0 && testedSignals.size < totalSignals) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>
</html>
