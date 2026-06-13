<?php
// session_start();
include_once('../file/config.php');
include_once('../inc/function.php');
include_once('signals-config.php');

// Get assessment ID from URL
$assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($assessment_id === 0) {
    $_SESSION['error_msg'] = "Invalid assessment ID";
    header("Location: assessment-list.php");
    exit();
}

// Fetch assessment details with signal results
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

// Check if signals test has been taken
if ($assessment['signals_status'] === 'NOT_STARTED') {
    $_SESSION['error_msg'] = "Hand signals test has not been taken yet.";
    header("Location: hand-signals-test.php?id=" . $assessment_id);
    exit();
}

// Fetch individual signal results
$signals_sql = "SELECT * FROM operator_hand_signals WHERE assessment_id = ? ORDER BY signal_number ASC";
$signals_stmt = $conn->prepare($signals_sql);
$signals_stmt->bind_param("i", $assessment_id);
$signals_stmt->execute();
$signals_result = $signals_stmt->get_result();

$is_passed = ($assessment['signals_status'] === 'PASSED');
$bg_gradient = $is_passed 
    ? 'linear-gradient(135deg, #062f22 0%, #0b1329 100%)' 
    : 'linear-gradient(135deg, #441014 0%, #0b1329 100%)';
$theme_border = $is_passed ? 'rgba(16, 185, 129, 0.25)' : 'rgba(239, 68, 68, 0.25)';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hand Signals Test Results - Operator Assessment</title>
    
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
            background: <?php echo $bg_gradient; ?>;
            min-height: 100vh;
            padding: 40px 0;
            color: #f8fafc;
        }
        
        .results-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .result-header {
            background: #111a2e;
            border-radius: 20px;
            padding: 50px;
            margin-bottom: 30px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.3);
            text-align: center;
            border: 1px solid <?php echo $theme_border; ?>;
        }
        
        .status-icon {
            font-size: 6rem;
            margin-bottom: 20px;
            animation: scaleIn 0.5s ease-out;
        }
        @keyframes scaleIn {
            0% { transform: scale(0.6); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }
        
        .status-icon.passed {
            color: #10b981;
        }
        
        .status-icon.failed {
            color: #ef4444;
        }
        
        .status-text {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        
        .status-text.passed {
            color: #10b981;
        }
        
        .status-text.failed {
            color: #ef4444;
        }
        
        .score-display {
            font-size: 4rem;
            font-weight: 800;
            margin: 20px 0;
            display: inline-block;
            padding: 5px 30px;
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .score-display.passed {
            color: #10b981;
            background-color: rgba(16, 185, 129, 0.1);
        }
        
        .score-display.failed {
            color: #ef4444;
            background-color: rgba(239, 68, 68, 0.1);
        }
        
        .stats-row {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 30px;
            flex-wrap: wrap;
        }
        
        .stat-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            padding: 15px 30px;
            border-radius: 12px;
            min-width: 140px;
        }
        
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
        }
        
        .stat-label {
            color: #94a3b8;
            font-size: 1rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }
        
        .signals-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .signal-result-card {
            background: #1e293b;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.06);
            text-align: center;
        }
        
        .signal-result-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.2);
        }
        
        .signal-result-card.passed {
            border-left: 5px solid #10b981;
        }
        
        .signal-result-card.failed {
            border-left: 5px solid #ef4444;
        }
        
        .signal-mini-image {
            width: 100%;
            height: 150px;
            object-fit: contain;
            background: #0f172a;
            border-radius: 10px;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .signal-result-name {
            font-weight: 600;
            font-size: 1.05rem;
            margin-bottom: 12px;
            color: #f8fafc;
        }
        
        .result-badge {
            display: inline-block;
            padding: 6px 18px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 0.85rem;
        }
        
        .result-badge.passed {
            background: #d1fae5;
            color: #065f46;
        }
        
        .result-badge.failed {
            background: #fee2e2;
            color: #991b1b;
        }
        
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 35px;
            border-top: 1px solid rgba(255, 255, 255, 0.08);
            padding-top: 30px;
        }
        
        .btn-action {
            padding: 12px 35px;
            font-size: 1.05rem;
            font-weight: 700;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0,0,0,0.25);
            text-decoration: none;
            color: white;
        }
        
        .btn-retake {
            background: linear-gradient(135deg, #dc2626 0%, #f87171 100%);
            color: white;
        }
        
        .btn-view {
            background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
            color: white;
        }
        
        .section-card {
            background: #1e293b;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        .section-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: #f8fafc;
            margin-bottom: 30px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .result-header {
                padding: 30px 20px;
            }
            
            .status-icon {
                font-size: 4rem;
            }
            
            .status-text {
                font-size: 2rem;
            }
            
            .score-display {
                font-size: 2.5rem;
            }
            
            .signals-grid {
                grid-template-columns: 1fr;
            }
            .section-card {
                padding: 20px;
            }
        }
        
        @media print {
            body {
                background: white;
                color: black;
            }
            .result-header, .section-card {
                background: white !important;
                border: none !important;
                box-shadow: none !important;
                color: black !important;
            }
            .btn-action, .action-buttons {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="main-content">
    <div class="results-container">
        <!-- Result Header -->
        <div class="result-header">
            <div class="status-icon <?php echo strtolower($assessment['signals_status']); ?>">
                <?php if ($assessment['signals_status'] === 'PASSED'): ?>
                    <i class="fas fa-check-circle"></i>
                <?php else: ?>
                    <i class="fas fa-times-circle"></i>
                <?php endif; ?>
            </div>
            
            <h1 class="status-text <?php echo strtolower($assessment['signals_status']); ?>">
                <?php echo $assessment['signals_status']; ?>
            </h1>
            
            <p style="color: #94a3b8; font-size: 1.2rem;">Hand Signals Practical Test</p>
            
            <div class="score-display <?php echo strtolower($assessment['signals_status']); ?>">
                <?php echo round($assessment['signals_score'], 2); ?>%
            </div>
            
            <div class="stats-row">
                <div class="stat-item">
                    <div class="stat-value" style="color: #10b981;"><?php echo $assessment['signals_passed']; ?></div>
                    <div class="stat-label">Passed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="color: #ef4444;"><?php echo $assessment['signals_failed']; ?></div>
                    <div class="stat-label">Failed</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" style="color: #38bdf8;"><?php echo $assessment['signals_attempts']; ?></div>
                    <div class="stat-label">Attempts</div>
                </div>
            </div>
            
            <div style="margin-top: 30px; color: #cbd5e1; font-size: 0.95rem;">
                <p><strong>Assessment No:</strong> <?php echo htmlspecialchars($assessment['assessment_no']); ?></p>
                <p><strong>Operator:</strong> <?php echo htmlspecialchars($assessment['operator_name']); ?></p>
                <p><strong>Test Date:</strong> <?php echo date('d-M-Y H:i', strtotime($assessment['signals_tested_at'])); ?></p>
            </div>
            
            <div class="action-buttons">
                <?php if ($assessment['signals_status'] === 'FAILED' && $assessment['signals_attempts'] < $signals_settings['max_attempts']): ?>
                    <a href="hand-signals-test.php?id=<?php echo $assessment_id; ?>" class="btn-action btn-retake">
                        <i class="fas fa-redo"></i> Retake Test
                    </a>
                <?php endif; ?>
                <a href="view-assessment.php?id=<?php echo $assessment_id; ?>" class="btn-action btn-view">
                    <i class="fas fa-file-alt"></i> View Full Assessment
                </a>
                <button onclick="window.print()" class="btn-action" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%); color: white;">
                    <i class="fas fa-print"></i> Print Results
                </button>
            </div>
        </div>

        <!-- Individual Signal Results -->
        <div class="section-card">
            <h2 class="section-title">Individual Signal Results</h2>
            <div class="signals-grid">
                <?php while ($signal = $signals_result->fetch_assoc()): ?>
                <div class="signal-result-card <?php echo strtolower($signal['result']); ?>">
                    <?php 
                    $signal_num = $signal['signal_number'];
                    $image_path = 'hand-signals/signal-' . str_pad($signal_num, 2, '0', STR_PAD_LEFT) . '.jpg';
                    if (file_exists($image_path)): 
                    ?>
                        <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($signal['signal_name']); ?>" class="signal-mini-image">
                    <?php else: ?>
                        <div class="signal-mini-image" style="display: flex; align-items: center; justify-content: center; color: #6c757d;">
                            <i class="fas fa-image fa-3x"></i>
                        </div>
                    <?php endif; ?>
                    
                    <div class="signal-result-name">
                        <?php echo $signal_num; ?>. <?php echo htmlspecialchars($signal['signal_name']); ?>
                    </div>
                    
                    <span class="result-badge <?php echo strtolower($signal['result']); ?>">
                        <?php if ($signal['result'] === 'PASS'): ?>
                            <i class="fas fa-check"></i> PASS
                        <?php else: ?>
                            <i class="fas fa-times"></i> FAIL
                        <?php endif; ?>
                    </span>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    </div>

    <?php include_once('../inc/footer.php'); ?>
    
    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
