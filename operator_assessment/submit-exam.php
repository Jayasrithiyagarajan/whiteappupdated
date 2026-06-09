<?php
session_start();
include '../file/config.php';
include 'exam-config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assessment_id'])) {
    $assessment_id = (int)$_POST['assessment_id'];
    
    // Fetch current assessment
    $check_sql = "SELECT exam_status, exam_attempts FROM operator_assessments WHERE id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("i", $assessment_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $current_assessment = $check_result->fetch_assoc();
    
    if (!$current_assessment) {
        $_SESSION['error_msg'] = "Assessment not found";
        header("Location: assessment-list.php");
        exit();
    }
    
    // Check if already passed
    if ($current_assessment['exam_status'] === 'PASSED') {
        $_SESSION['info_msg'] = "Exam already passed";
        header("Location: exam-result.php?id=" . $assessment_id);
        exit();
    }
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        $total_score = 0;
        $correct_count = 0;
        
        // Delete previous exam answers for this assessment (if retaking)
        $delete_sql = "DELETE FROM operator_exam_answers WHERE assessment_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $assessment_id);
        $delete_stmt->execute();
        $delete_stmt->close();
        
        // Process each answer
        $insert_sql = "INSERT INTO operator_exam_answers 
                      (assessment_id, question_number, selected_answer, is_correct, marks_obtained) 
                      VALUES (?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        
        for ($q_num = 1; $q_num <= $exam_settings['total_questions']; $q_num++) {
            $answer_key = 'answer_' . $q_num;
            
            if (!isset($_POST[$answer_key])) {
                throw new Exception("Missing answer for question " . $q_num);
            }
            
            $selected_answer = $_POST[$answer_key];
            $correct_answer = $correct_answers[$q_num];
            $is_correct = ($selected_answer === $correct_answer) ? 1 : 0;
            $marks = $is_correct ? $exam_settings['marks_per_question'] : 0;
            
            if ($is_correct) {
                $correct_count++;
                $total_score += $marks;
            }
            
            $insert_stmt->bind_param("iisii", 
                $assessment_id, 
                $q_num, 
                $selected_answer, 
                $is_correct, 
                $marks
            );
            $insert_stmt->execute();
        }
        
        $insert_stmt->close();
        
        // Determine pass/fail
        $exam_status = ($total_score >= $exam_settings['passing_marks']) ? 'PASSED' : 'FAILED';
        $new_attempts = $current_assessment['exam_attempts'] + 1;
        
        // Update assessment with exam results
        $update_sql = "UPDATE operator_assessments 
                      SET exam_status = ?, 
                          exam_score = ?, 
                          exam_taken_at = NOW(), 
                          exam_attempts = ?,
                          status = CASE 
                              WHEN ? = 'PASSED' THEN 'COMPLETED' 
                              ELSE status 
                          END,
                          updated_at = NOW()
                      WHERE id = ?";
        
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("siisi", 
            $exam_status, 
            $total_score, 
            $new_attempts,
            $exam_status,
            $assessment_id
        );
        $update_stmt->execute();
        $update_stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        // Set success message
        if ($exam_status === 'PASSED') {
            $_SESSION['success_msg'] = "Congratulations! You have passed the exam with a score of {$total_score}/100";
        } else {
            $_SESSION['error_msg'] = "You scored {$total_score}/100. You need 80 marks to pass. ";
            if ($exam_settings['allow_retake'] && $new_attempts < $exam_settings['max_attempts']) {
                $_SESSION['error_msg'] .= "You have " . ($exam_settings['max_attempts'] - $new_attempts) . " attempt(s) remaining.";
            } else {
                $_SESSION['error_msg'] .= "No more attempts available.";
            }
        }
        
        header("Location: exam-result.php?id=" . $assessment_id);
        exit();
        
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_msg'] = "Error submitting exam: " . $e->getMessage();
        header("Location: written-exam.php?id=" . $assessment_id);
        exit();
    }
    
    $conn->close();
} else {
    header("Location: assessment-list.php");
    exit();
}
?>
