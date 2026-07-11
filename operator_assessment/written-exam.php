<?php
// session_start();
include_once('../file/config.php');
//include_once('../inc/function.php');

include_once('exam-config.php');

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

// Check if exam already passed
if ($assessment['exam_status'] === 'PASSED') {
    $_SESSION['info_msg'] = "You have already passed this exam.";
    header("Location: exam-result.php?id=" . $assessment_id);
    exit();
}

// Check if max attempts reached
if ($exam_settings['allow_retake'] && $assessment['exam_attempts'] >= $exam_settings['max_attempts']) {
    $_SESSION['error_msg'] = "Maximum exam attempts reached.";
    header("Location: view-assessment.php?id=" . $assessment_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Written Examination - Operator Assessment</title>
    
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 90px 0 20px 0;
        }
        
        .exam-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        .exam-header {
            background: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            text-align: center;
        }
        
        .exam-header h1 {
            color: #667eea;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        
        .exam-header p {
            color: #6c757d;
            font-size: 1.1rem;
        }
        
        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        
        .info-item {
            margin: 5px 0;
        }
        
        .info-item strong {
            font-weight: 600;
        }
        
        .instructions-card {
            background: #e7f3ff;
            border-left: 5px solid #2196F3;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        
        .instructions-card h5 {
            color: #2196F3;
            font-weight: 600;
            margin-bottom: 15px;
        }
        
        .instructions-card ul {
            margin: 0;
            padding-left: 20px;
        }
        
        .instructions-card li {
            margin: 8px 0;
            color: #333;
        }
        
        .progress-sticky {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: white;
            border-radius: 0;
            padding: 12px 30px;
            margin-bottom: 0;
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .progress {
            height: 30px;
            border-radius: 15px;
            background-color: #e9ecef;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
            font-weight: 600;
            font-size: 14px;
            line-height: 30px;
        }
        
        .timer {
            font-size: 1.5rem;
            font-weight: 700;
            color: #667eea;
        }
        
        .question-card {
            background: white;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .question-card:hover {
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            transform: translateY(-2px);
        }
        
        .question-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .question-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.2rem;
        }
        
        .marks-badge {
            background: rgba(255,255,255,0.3);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: 600;
        }
        
        .question-body {
            padding: 30px;
        }
        
        .question-text {
            font-size: 1.15rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
        }
        
        .question-context {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px 15px;
            margin: 15px 0;
            border-radius: 5px;
            font-style: italic;
            color: #856404;
        }
        
        .option-item {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px 20px;
            margin: 12px 0;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
        }
        
        .option-item:hover {
            background: #e7f3ff;
            border-color: #667eea;
        }
        
        .option-item input[type="radio"] {
            width: 20px;
            height: 20px;
            margin-right: 15px;
            cursor: pointer;
        }
        
        .option-item label {
            margin: 0;
            cursor: pointer;
            flex: 1;
            font-size: 1.05rem;
        }
        
        .option-item.selected {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            border-color: #28a745;
            border-width: 3px;
        }
        
        .option-item.selected label {
            font-weight: 600;
            color: #155724;
        }
        
        .navigation-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }
        
        .navigation-card h5 {
            color: #667eea;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .nav-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
            gap: 12px;
        }
        
        .nav-btn {
            padding: 12px;
            border: 2px solid #667eea;
            background: white;
            color: #667eea;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .nav-btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
        
        .nav-btn.answered {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }
        
        .nav-btn.answered:hover {
            background: #218838;
            border-color: #218838;
        }
        
        .submit-card {
            background: white;
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        .submit-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 18px 50px;
            font-size: 1.3rem;
            font-weight: 600;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.3);
        }
        
        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4);
        }
        
        .submit-note {
            color: #6c757d;
            margin-top: 15px;
            font-size: 0.95rem;
        }
        
        @media (max-width: 768px) {
            .exam-header h1 {
                font-size: 1.8rem;
            }
            
            .info-row {
                flex-direction: column;
            }
            
            .nav-grid {
                grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            }
            
            .question-body {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="exam-container">
        <!-- Header -->
        <div class="exam-header">
            <h1><i class="fas fa-clipboard-list"></i> Operator Written Examination</h1>
            <p>Step 3: Written Exam - 20 Questions</p>
        </div>

        <!-- Assessment Info -->
        <div class="info-card">
            <div class="info-row">
                <div>
                    <div class="info-item"><strong>Assessment No:</strong> <?php echo htmlspecialchars($assessment['assessment_no']); ?></div>
                    <div class="info-item"><strong>Operator:</strong> <?php echo htmlspecialchars($assessment['operator_name']); ?></div>
                </div>
                <div>
                    <div class="info-item"><strong>Total Questions:</strong> 20</div>
                    <div class="info-item"><strong>Marks per Question:</strong> 5</div>
                    <div class="info-item"><strong>Passing Marks:</strong> 80/100</div>
                    <?php if ($exam_settings['time_limit_minutes'] > 0): ?>
                    <div class="info-item"><strong>Time Limit:</strong> <?php echo $exam_settings['time_limit_minutes']; ?> minutes</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Instructions -->
        <div class="instructions-card">
            <h5><i class="fas fa-info-circle"></i> Instructions</h5>
            <ul>
                <li>Read each question carefully before selecting your answer</li>
                <li>Each question carries 5 marks</li>
                <li>You need to score 80 marks or above to pass</li>
                <li>Select only ONE answer for each question</li>
                <li>Make sure to answer ALL questions before submitting</li>
                <?php if ($exam_settings['allow_retake']): ?>
                <li>Current Attempt: <?php echo ($assessment['exam_attempts'] + 1); ?> of <?php echo $exam_settings['max_attempts']; ?></li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Exam Form -->
        <form id="examForm" action="submit-exam.php" method="POST">
            <input type="hidden" name="assessment_id" value="<?php echo $assessment_id; ?>">
            
            <!-- Progress Indicator -->
            <div class="progress-sticky">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <div class="progress">
                            <div id="progressBar" class="progress-bar" role="progressbar" style="width: 0%;">
                                <span id="progressText">0/20 Answered</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 text-right">
                        <?php if ($exam_settings['time_limit_minutes'] > 0): ?>
                        <div class="timer">
                            <i class="fas fa-clock"></i> <span id="timer">--:--</span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Questions -->
            <?php foreach ($exam_questions as $q_num => $question): ?>
            <div class="question-card" id="question-<?php echo $q_num; ?>">
                <div class="question-header">
                    <h5><i class="fas fa-question-circle"></i> Question <?php echo $q_num; ?> of 20</h5>
                    <span class="marks-badge">5 Marks</span>
                </div>
                <div class="question-body">
                    <div class="question-text"><?php echo htmlspecialchars($question['question']); ?></div>
                    
                    <?php if (isset($question['context'])): ?>
                    <div class="question-context">
                        <i class="fas fa-info-circle"></i> <?php echo htmlspecialchars($question['context']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (isset($question['sub_question'])): ?>
                    <div class="question-text" style="font-size: 1.05rem; margin-top: 10px;">
                        <?php echo htmlspecialchars($question['sub_question']); ?>
                    </div>
                    <?php endif; ?>
                    
                    <div class="options-container">
                        <?php 
                        $is_multiselect = ($q_num == 7 || $q_num == 8);
                        $input_type = $is_multiselect ? 'checkbox' : 'radio';
                        $input_name = $is_multiselect ? "answer_{$q_num}[]" : "answer_{$q_num}";
                        $req_attr = $is_multiselect ? '' : 'required';
                        
                        foreach ($question['options'] as $opt_key => $opt_value): 
                        ?>
                        <div class="option-item" onclick="selectOption(event, <?php echo $q_num; ?>, '<?php echo $opt_key; ?>')">
                            <input type="<?= $input_type; ?>" 
                                   id="q<?php echo $q_num; ?>_<?php echo $opt_key; ?>" 
                                   name="<?= $input_name; ?>" 
                                   value="<?php echo $opt_key; ?>" 
                                   data-question="<?php echo $q_num; ?>"
                                   <?= $req_attr; ?>>
                            <label for="q<?php echo $q_num; ?>_<?php echo $opt_key; ?>">
                                <strong><?php echo strtoupper($opt_key); ?>)</strong> <?php echo htmlspecialchars($opt_value); ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>

            <!-- Question Navigation -->
            <div class="navigation-card">
                <h5><i class="fas fa-th"></i> Quick Navigation</h5>
                <div class="nav-grid">
                    <?php for ($i = 1; $i <= 20; $i++): ?>
                    <button type="button" class="nav-btn" data-question="<?php echo $i; ?>" id="nav-btn-<?php echo $i; ?>">
                        Q<?php echo $i; ?>
                    </button>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="submit-card">
                <button type="submit" class="submit-btn" id="submitBtn">
                    <i class="fas fa-paper-plane"></i> Submit Exam
                </button>
                <p class="submit-note">Make sure you have answered all questions before submitting</p>
            </div>
        </form>
    </div>



    <!-- jQuery and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        let answeredQuestions = new Set();
        const totalQuestions = 20;

        // Timer functionality
        <?php if ($exam_settings['time_limit_minutes'] > 0): ?>
        let timeLimit = <?php echo $exam_settings['time_limit_minutes'] * 60; ?>;
        let timeRemaining = timeLimit;

        function updateTimer() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            document.getElementById('timer').textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (timeRemaining <= 0) {
                alert('Time is up! The exam will be submitted automatically.');
                document.getElementById('examForm').submit();
            }
            timeRemaining--;
        }

        setInterval(updateTimer, 1000);
        updateTimer();
        <?php endif; ?>

        // Select option function
        function selectOption(e, questionNum, optKey) {
            const input = document.getElementById(`q${questionNum}_${optKey}`);
            
            // If the user clicked directly on the input or label, the browser already handles the toggle/select.
            // We only need to manually toggle/select if they clicked the container div itself.
            if (e.target.tagName !== 'INPUT' && e.target.tagName !== 'LABEL') {
                input.checked = !input.checked;
                // Trigger change event to run the event listener logic
                const event = new Event('change', { bubbles: true });
                input.dispatchEvent(event);
            }
        }

        // Track input changes (radio and checkbox)
        document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
            input.addEventListener('change', function(e) {
                const questionNum = parseInt(this.dataset.question);
                
                if (this.type === 'checkbox') {
                    if (this.checked) {
                        this.closest('.option-item').classList.add('selected');
                    } else {
                        this.closest('.option-item').classList.remove('selected');
                    }
                    const anyChecked = document.querySelectorAll(`input[name="answer_${questionNum}[]"]:checked`).length > 0;
                    if (anyChecked) {
                        answeredQuestions.add(questionNum);
                        document.getElementById('nav-btn-' + questionNum).classList.add('answered');
                    } else {
                        answeredQuestions.delete(questionNum);
                        document.getElementById('nav-btn-' + questionNum).classList.remove('answered');
                    }
                } else {
                    // Radio button
                    document.querySelectorAll(`input[name="answer_${questionNum}"]`).forEach(r => {
                        r.closest('.option-item').classList.remove('selected');
                    });
                    this.closest('.option-item').classList.add('selected');
                    
                    answeredQuestions.add(questionNum);
                    document.getElementById('nav-btn-' + questionNum).classList.add('answered');
                }
                updateProgress();
            });
        });

        function updateProgress() {
            const answered = answeredQuestions.size;
            const percentage = (answered / totalQuestions) * 100;
            
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressText').textContent = answered + '/' + totalQuestions + ' Answered';
        }

        // Question navigation
        document.querySelectorAll('.nav-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const questionNum = this.dataset.question;
                const questionCard = document.getElementById('question-' + questionNum);
                questionCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // Form submission validation
        document.getElementById('examForm').addEventListener('submit', function(e) {
            if (answeredQuestions.size < totalQuestions) {
                e.preventDefault();
                const unanswered = totalQuestions - answeredQuestions.size;
                alert(`Please answer all questions! You have ${unanswered} unanswered question(s).`);
                return false;
            }
            
            if (!confirm('Are you sure you want to submit your exam? You cannot change your answers after submission.')) {
                e.preventDefault();
                return false;
            }
        });

        // Prevent accidental page leave
        window.addEventListener('beforeunload', function(e) {
            if (answeredQuestions.size > 0 && answeredQuestions.size < totalQuestions) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
</body>
</html>
