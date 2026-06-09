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
        <div class="card bg-transparent pb-3">
            <div class="card-body bg-white">
                <div class="row">
                    <div class="col-12 text-center">
                        <h3 class="pt-3 pb-2 font-weight-bold">EXAM RESULTS</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <?php if (isset($_SESSION['success_msg'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> <?php echo $_SESSION['success_msg']; unset($_SESSION['success_msg']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_msg'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> <?php echo $_SESSION['error_msg']; unset($_SESSION['error_msg']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <!-- Result Card -->
        <div class="card mb-4" style="border: 3px solid <?php echo $is_passed ? '#28a745' : '#dc3545'; ?>;">
            <div class="card-body text-center" style="background: <?php echo $is_passed ? 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)' : 'linear-gradient(135deg, #eb3349 0%, #f45c43 100%)'; ?>; color: white;">
                <h1 class="display-1 mb-3">
                    <?php echo $is_passed ? '✓' : '✗'; ?>
                </h1>
                <h2 class="mb-3"><?php echo $is_passed ? 'PASSED!' : 'FAILED'; ?></h2>
                <h3 class="mb-4">Score: <?php echo $score; ?>/<?php echo $exam_settings['total_marks']; ?> (<?php echo number_format($percentage, 1); ?>%)</h3>
                
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="stat-box">
                            <h4><?php echo count(array_filter($user_answers, function($a) { return $a['is_correct'] == 1; })); ?></h4>
                            <p>Correct Answers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box">
                            <h4><?php echo count(array_filter($user_answers, function($a) { return $a['is_correct'] == 0; })); ?></h4>
                            <p>Wrong Answers</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-box">
                            <h4><?php echo $assessment['exam_attempts']; ?></h4>
                            <p>Attempt(s)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assessment Info -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Assessment Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Assessment No:</strong> <?php echo htmlspecialchars($assessment['assessment_no']); ?></p>
                        <p><strong>Operator Name:</strong> <?php echo htmlspecialchars($assessment['operator_name']); ?></p>
                        <p><strong>Client:</strong> <?php echo htmlspecialchars($assessment['client_name']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Inspector:</strong> <?php echo htmlspecialchars($assessment['inspector_name']); ?></p>
                        <p><strong>Exam Taken:</strong> <?php echo date('d-M-Y H:i', strtotime($assessment['exam_taken_at'])); ?></p>
                        <p><strong>Status:</strong> 
                            <span class="badge badge-<?php echo $is_passed ? 'success' : 'danger'; ?>">
                                <?php echo $assessment['exam_status']; ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Answers -->
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0"><i class="fas fa-list-alt"></i> Detailed Answers Review</h5>
            </div>
            <div class="card-body">
                <?php foreach ($exam_questions as $q_num => $question): ?>
                    <?php 
                    $user_answer = $user_answers[$q_num] ?? null;
                    $is_correct_answer = $user_answer && $user_answer['is_correct'];
                    $selected = $user_answer ? $user_answer['selected_answer'] : null;
                    $correct = $correct_answers[$q_num];
                    ?>
                    
                    <div class="question-review mb-4 p-3" style="border-left: 4px solid <?php echo $is_correct_answer ? '#28a745' : '#dc3545'; ?>; background-color: <?php echo $is_correct_answer ? '#d4edda' : '#f8d7da'; ?>;">
                        <div class="row">
                            <div class="col-md-10">
                                <h6 class="font-weight-bold">
                                    <?php echo $is_correct_answer ? '✓' : '✗'; ?> 
                                    Question <?php echo $q_num; ?>: <?php echo htmlspecialchars($question['question']); ?>
                                </h6>
                                
                                <?php if (isset($question['context'])): ?>
                                <p class="text-muted mb-1"><em><?php echo htmlspecialchars($question['context']); ?></em></p>
                                <?php endif; ?>
                                
                                <?php if (isset($question['sub_question'])): ?>
                                <p class="mb-2"><?php echo htmlspecialchars($question['sub_question']); ?></p>
                                <?php endif; ?>
                                
                                <div class="mt-2">
                                    <?php if ($selected): ?>
                                        <p class="mb-1">
                                            <strong>Your Answer:</strong> 
                                            <span class="badge badge-<?php echo $is_correct_answer ? 'success' : 'danger'; ?>">
                                                <?php echo strtoupper($selected); ?>) <?php echo htmlspecialchars($question['options'][$selected]); ?>
                                            </span>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <?php if (!$is_correct_answer): ?>
                                        <p class="mb-0">
                                            <strong>Correct Answer:</strong> 
                                            <span class="badge badge-success">
                                                <?php echo strtoupper($correct); ?>) <?php echo htmlspecialchars($question['options'][$correct]); ?>
                                            </span>
                                        </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-2 text-right">
                                <h5 class="mb-0">
                                    <span class="badge badge-<?php echo $is_correct_answer ? 'success' : 'secondary'; ?>">
                                        <?php echo $user_answer ? $user_answer['marks_obtained'] : 0; ?>/5
                                    </span>
                                </h5>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="card mb-5">
            <div class="card-body text-center">
                <!-- Always show Proceed to Hand Signals Test if available -->
                <a href="hand-signals-test.php?id=<?php echo $assessment_id; ?>" class="btn btn-warning btn-lg mb-3">
                    <i class="fas fa-hand-paper"></i> Proceed to Hand Signals Test
                </a>
                <br>

                <?php if ($is_passed): ?>
                    <a href="view-assessment.php?id=<?php echo $assessment_id; ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-eye"></i> View Complete Assessment
                    </a>
                    <button onclick="window.print()" class="btn btn-success btn-lg">
                        <i class="fas fa-print"></i> Print Certificate
                    </button>
                <?php else: ?>
                    <?php if ($exam_settings['allow_retake'] && $assessment['exam_attempts'] < $exam_settings['max_attempts']): ?>
                        <a href="written-exam.php?id=<?php echo $assessment_id; ?>" class="btn btn-info btn-lg">
                            <i class="fas fa-redo"></i> Retake Exam
                        </a>
                    <?php endif; ?>
                    <a href="assessment-list.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-list"></i> Back to List
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include_once('../inc/footer.php'); ?>

<style>
.stat-box {
    background: rgba(255,255,255,0.2);
    padding: 15px;
    border-radius: 10px;
    margin: 10px 0;
}

.stat-box h4 {
    font-size: 2.5rem;
    margin-bottom: 5px;
}

.stat-box p {
    margin: 0;
    font-size: 0.9rem;
}

.question-review {
    border-radius: 5px;
    transition: all 0.3s;
}

.question-review:hover {
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

@media print {
    .btn, .card-header, .question-review {
        display: none !important;
    }
}
</style>
