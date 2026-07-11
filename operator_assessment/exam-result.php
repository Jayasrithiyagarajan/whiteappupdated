<?php
session_start();
include_once('../inc/function.php');
include_once('exam-config.php');

// Get assessment ID from URL
$assessment_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($assessment_id === 0) {
    $_SESSION['error_msg'] = "Invalid assessment ID";
    header("Location: assessment-list.php");
    exit();
}

// Fetch assessment with exam results
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

// Fetch exam answers
$answers_sql = "SELECT * FROM operator_exam_answers WHERE assessment_id = ? ORDER BY question_number ASC";
$answers_stmt = $conn->prepare($answers_sql);
$answers_stmt->bind_param("i", $assessment_id);
$answers_stmt->execute();
$answers_result = $answers_stmt->get_result();

$user_answers = [];
while ($ans = $answers_result->fetch_assoc()) {
    $user_answers[$ans['question_number']] = $ans;
}

$is_passed = ($assessment['exam_status'] === 'PASSED');
$score = $assessment['exam_score'];
$percentage = ($score / $exam_settings['total_marks']) * 100;
?>

<!-- Main Content -->
<div class="main-content">
    <div class="container-fluid">
        <!-- Header -->
        <div class="directory-hero mb-4">
            <div class="directory-title">
                <h2>Written Exam Results</h2>
                <p>Detailed performance report and answer analysis for the operator assessment</p>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success exam-alert alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i> <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger exam-alert alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i> <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Result Card -->
        <div class="card result-card mb-4 <?php echo $is_passed ? 'is-passed' : 'is-failed'; ?>">
            <div class="card-body text-center py-5">
                <div class="result-badge-container">
                    <div class="result-icon-circle">
                        <i class="fas <?php echo $is_passed ? 'fa-check' : 'fa-times'; ?>"></i>
                    </div>
                </div>
                <h2 class="result-status-title mb-2"><?php echo $is_passed ? 'PASSED!' : 'FAILED'; ?></h2>
                <div class="result-score-main mb-5">
                    Score: <strong><?php echo $score; ?>/<?php echo $exam_settings['total_marks']; ?></strong> (<?php echo number_format($percentage, 1); ?>%)
                </div>
                
                <div class="row px-md-4">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="stat-card">
                            <div class="stat-icon correct" style="color: #10b981;"><i class="fas fa-check-circle"></i></div>
                            <div class="stat-info">
                                <h4><?php echo count(array_filter($user_answers, function($a) { return $a['is_correct'] == 1; })); ?></h4>
                                <p>Correct Answers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="stat-card">
                            <div class="stat-icon wrong" style="color: #ef4444;"><i class="fas fa-times-circle"></i></div>
                            <div class="stat-info">
                                <h4><?php echo count(array_filter($user_answers, function($a) { return $a['is_correct'] == 0; })); ?></h4>
                                <p>Wrong Answers</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card">
                            <div class="stat-icon attempts" style="color: #f59e0b;"><i class="fas fa-redo-alt"></i></div>
                            <div class="stat-info">
                                <h4><?php echo $assessment['exam_attempts']; ?></h4>
                                <p>Attempt(s)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assessment Info -->
        <div class="card info-card mb-4">
            <div class="info-card-header d-flex align-items-center">
                <h5 class="mb-0"><i class="fas fa-info-circle mr-2"></i> Assessment Information</h5>
            </div>
            <div class="info-card-body">
                <div class="info-grid">
                    <div class="info-cell">
                        <span class="info-label">Assessment No</span>
                        <span class="info-val"><?php echo htmlspecialchars($assessment['assessment_no']); ?></span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Operator Name</span>
                        <span class="info-val"><?php echo htmlspecialchars($assessment['operator_name']); ?></span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Client / Company</span>
                        <span class="info-val"><?php echo htmlspecialchars($assessment['client_name']); ?></span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Inspector</span>
                        <span class="info-val"><?php echo htmlspecialchars($assessment['inspector_name']); ?></span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Exam Taken On</span>
                        <span class="info-val"><?php echo date('d-M-Y H:i', strtotime($assessment['exam_taken_at'])); ?></span>
                    </div>
                    <div class="info-cell">
                        <span class="info-label">Result Status</span>
                        <span class="info-val">
                            <span class="result-pill <?php echo $is_passed ? 'pill-passed' : 'pill-failed'; ?>">
                                <?php echo $assessment['exam_status']; ?>
                            </span>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Answers -->
        <div class="card review-card mb-4">
            <div class="review-card-header d-flex align-items-center">
                <h5 class="mb-0"><i class="fas fa-tasks mr-2"></i> Detailed Answers Review</h5>
            </div>
            <div class="review-card-body">
                <?php foreach ($exam_questions as $q_num => $question): ?>
                    <?php 
                    $user_answer = $user_answers[$q_num] ?? null;
                    $is_correct_answer = $user_answer && $user_answer['is_correct'];
                    $selected = $user_answer ? $user_answer['selected_answer'] : null;
                    $correct = $correct_answers[$q_num];
                    ?>
                    
                    <div class="question-box mb-4 <?php echo $is_correct_answer ? 'is-correct' : 'is-incorrect'; ?>">
                        <div class="question-header">
                            <div class="question-status-badge">
                                <i class="fas <?php echo $is_correct_answer ? 'fa-check' : 'fa-times'; ?>"></i>
                            </div>
                            <div class="question-title-text">
                                <span class="q-label">Question <?php echo $q_num; ?></span>
                                <p class="q-main"><?php echo htmlspecialchars($question['question']); ?></p>
                            </div>
                            <div class="question-score-badge <?php echo $is_correct_answer ? 'score-correct' : 'score-incorrect'; ?>">
                                <?php echo $user_answer ? $user_answer['marks_obtained'] : 0; ?> / 5 Marks
                            </div>
                        </div>
                        
                        <?php if (isset($question['context']) || isset($question['sub_question'])): ?>
                            <div class="question-sub-info">
                                <?php if (isset($question['context'])): ?>
                                    <div class="q-context"><?php echo htmlspecialchars($question['context']); ?></div>
                                <?php endif; ?>
                                <?php if (isset($question['sub_question'])): ?>
                                    <div class="q-sub"><?php echo htmlspecialchars($question['sub_question']); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="answers-compare">
                            <?php if ($selected): ?>
                                <div class="answer-item selected-answer <?php echo $is_correct_answer ? 'correct-style' : 'wrong-style'; ?>">
                                    <span class="ans-label">Your Answer:</span>
                                    <span class="ans-text">
                                        <?php 
                                        $selected_keys = explode(',', $selected);
                                        $selected_text_parts = [];
                                        foreach ($selected_keys as $key) {
                                            if (isset($question['options'][$key])) {
                                                $selected_text_parts[] = '<strong>' . strtoupper($key) . ')</strong> ' . htmlspecialchars($question['options'][$key]);
                                            }
                                        }
                                        echo implode(' + ', $selected_text_parts);
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!$is_correct_answer): ?>
                                <div class="answer-item correct-answer">
                                    <span class="ans-label">Correct Answer:</span>
                                    <span class="ans-text">
                                        <?php 
                                        $correct_keys = explode(',', $correct);
                                        $correct_text_parts = [];
                                        foreach ($correct_keys as $key) {
                                            if (isset($question['options'][$key])) {
                                                $correct_text_parts[] = '<strong>' . strtoupper($key) . ')</strong> ' . htmlspecialchars($question['options'][$key]);
                                            }
                                        }
                                        echo implode(' + ', $correct_text_parts);
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card action-card mb-5">
            <div class="card-body text-center py-4">
                <!-- Always show Proceed to Hand Signals Test if available -->
                <a href="hand-signals-test.php?id=<?php echo $assessment_id; ?>" class="btn btn-warning btn-premium mr-2 mb-2">
                    <i class="fas fa-hand-paper mr-1"></i> Proceed to Hand Signals Test
                </a>

                <?php if ($is_passed): ?>
                    <a href="view-assessment.php?id=<?php echo $assessment_id; ?>" class="btn btn-primary btn-premium mr-2 mb-2">
                        <i class="fas fa-eye mr-1"></i> View Complete Assessment
                    </a>
                    <button onclick="window.print()" class="btn btn-success btn-premium mb-2">
                        <i class="fas fa-print mr-1"></i> Print Certificate
                    </button>
                <?php else: ?>
                    <?php if ($exam_settings['allow_retake'] && $assessment['exam_attempts'] < $exam_settings['max_attempts']): ?>
                        <a href="written-exam.php?id=<?php echo $assessment_id; ?>" class="btn btn-info btn-premium mr-2 mb-2">
                            <i class="fas fa-redo mr-1"></i> Retake Exam
                        </a>
                    <?php endif; ?>
                    <a href="assessment-list.php" class="btn btn-secondary btn-premium mb-2">
                        <i class="fas fa-list mr-1"></i> Back to List
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<style>
/* Premium Exam Results Theme matching dashboard */
.directory-hero {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    color: white;
    padding: 24px 30px;
    border-radius: 12px;
    margin-bottom: 24px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.05);
}
.directory-title h2 {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 6px;
    background: linear-gradient(to right, #38bdf8, #0ea5e9);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.directory-title p {
    color: #94a3b8;
    margin-bottom: 0;
    font-size: 0.95rem;
}

/* Premium alert styling */
.exam-alert {
    border-radius: 10px;
    border: none;
    padding: 16px 20px;
    font-weight: 500;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}

/* Premium Result Card styling */
.result-card {
    border: none !important;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
}
.result-card.is-passed {
    background: linear-gradient(135deg, #059669 0%, #10b981 100%) !important;
}
.result-card.is-failed {
    background: linear-gradient(135deg, #dc2626 0%, #f87171 100%) !important;
}
.result-card .card-body {
    background: transparent !important;
}

.result-badge-container {
    display: flex;
    justify-content: center;
    margin-bottom: 15px;
}
.result-icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    animation: scaleIn 0.5s ease-out;
}
@keyframes scaleIn {
    0% { transform: scale(0.6); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

.result-status-title {
    font-size: 2.5rem;
    font-weight: 800;
    letter-spacing: 1.5px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.15);
}
.result-score-main {
    font-size: 1.35rem;
    font-weight: 500;
    margin-bottom: 25px;
    background: rgba(0,0,0,0.12);
    padding: 10px 25px;
    display: inline-block;
    border-radius: 30px;
    border: 1px solid rgba(255,255,255,0.15);
}

.stat-card {
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(0,0,0,0.05);
    border-radius: 12px;
    padding: 16px 20px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
    text-align: left;
    color: #1e293b;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
}
.stat-icon {
    font-size: 2.2rem;
    opacity: 0.9;
    display: flex;
    align-items: center;
}
.stat-info h4 {
    font-size: 1.8rem;
    font-weight: 800;
    margin: 0;
    line-height: 1.1;
    color: #0f172a;
}
.stat-info p {
    margin: 0;
    font-size: 0.8rem;
    color: #64748b;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* Info card styling */
.info-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    overflow: hidden;
}
.info-card-header {
    background: #1e293b;
    color: white;
    padding: 16px 24px;
}
.info-card-header h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}
.info-card-body {
    padding: 24px;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}
.info-cell {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.info-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    color: #64748b;
    font-weight: 600;
    letter-spacing: 0.5px;
}
.info-val {
    font-size: 1rem;
    font-weight: 700;
    color: #0f172a;
}
.result-pill {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 700;
    display: inline-block;
}
.result-pill.pill-passed {
    background: #d1fae5;
    color: #065f46;
}
.result-pill.pill-failed {
    background: #fee2e2;
    color: #991b1b;
}

/* Review answers styling */
.review-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    overflow: hidden;
}
.review-card-header {
    background: #475569;
    color: white;
    padding: 16px 24px;
}
.review-card-header h5 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
}
.review-card-body {
    padding: 24px;
}

.question-box {
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.02);
    transition: transform 0.3s;
}
.question-box:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.06);
}
.question-box.is-correct {
    border-left: 5px solid #10b981;
    background-color: #f0fdf4;
}
.question-box.is-incorrect {
    border-left: 5px solid #ef4444;
    background-color: #fef2f2;
}

.question-header {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 15px;
}
.question-status-badge {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 0.95rem;
    flex-shrink: 0;
}
.question-box.is-correct .question-status-badge {
    background-color: #10b981;
}
.question-box.is-incorrect .question-status-badge {
    background-color: #ef4444;
}

.question-title-text {
    flex-grow: 1;
}
.question-title-text .q-label {
    font-size: 0.8rem;
    font-weight: 700;
    text-transform: uppercase;
    color: #64748b;
    display: block;
    margin-bottom: 4px;
}
.question-title-text .q-main {
    font-size: 1.05rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
}

.question-score-badge {
    font-size: 0.85rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 30px;
    white-space: nowrap;
}
.question-score-badge.score-correct {
    background-color: #d1fae5;
    color: #065f46;
}
.question-score-badge.score-incorrect {
    background-color: #fee2e2;
    color: #991b1b;
}

.question-sub-info {
    margin-left: 45px;
    margin-bottom: 15px;
    padding: 8px 15px;
    background-color: rgba(0,0,0,0.03);
    border-radius: 6px;
}
.q-context {
    font-size: 0.85rem;
    font-style: italic;
    color: #64748b;
}
.q-sub {
    font-size: 0.95rem;
    color: #334155;
    margin-top: 4px;
}

.answers-compare {
    margin-left: 45px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.answer-item {
    padding: 10px 16px;
    border-radius: 8px;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 10px;
}
.answer-item .ans-label {
    font-weight: 700;
    min-width: 120px;
}
.answer-item.correct-style {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}
.answer-item.wrong-style {
    background-color: #fee2e2;
    color: #991b1b;
    border: 1px solid #fecaca;
}
.answer-item.correct-answer {
    background-color: #d1fae5;
    color: #065f46;
    border: 1px dashed #34d399;
}

/* Action card styling */
.action-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
}
.action-card .card-body {
    padding: 24px;
}
.btn-premium {
    padding: 12px 28px;
    border-radius: 30px;
    font-weight: 700;
    font-size: 1rem;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(0,0,0,0.08);
}
.btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.12);
}

@media print {
    .btn, .review-card, .action-card {
        display: none !important;
    }
}
</style>
