<?php
session_start();
include_once('../file/config.php');
include_once('signals-config.php');

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: assessment-list.php");
    exit();
}

$assessment_id = isset($_POST['assessment_id']) ? (int)$_POST['assessment_id'] : 0;

if ($assessment_id === 0) {
    $_SESSION['error_msg'] = "Invalid assessment ID";
    header("Location: assessment-list.php");
    exit();
}

// Collect all signal results
$signal_results = [];
$passed_count = 0;
$failed_count = 0;

for ($i = 1; $i <= $signals_settings['total_signals']; $i++) {
    $result = isset($_POST['signal_' . $i]) ? $_POST['signal_' . $i] : '';
    
    if (empty($result) || !in_array($result, ['PASS', 'FAIL'])) {
        $_SESSION['error_msg'] = "Invalid or missing result for signal " . $i;
        header("Location: hand-signals-test.php?id=" . $assessment_id);
        exit();
    }
    
    $signal_results[$i] = $result;
    
    if ($result === 'PASS') {
        $passed_count++;
    } else {
        $failed_count++;
    }
}

// Calculate score
$score_percentage = ($passed_count / $signals_settings['total_signals']) * 100;
$test_status = ($score_percentage >= $signals_settings['passing_percentage']) ? 'PASSED' : 'FAILED';

// Start transaction
$conn->begin_transaction();

try {
    // Delete previous signal results if retaking
    $delete_sql = "DELETE FROM operator_hand_signals WHERE assessment_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $assessment_id);
    $delete_stmt->execute();
    $delete_stmt->close();
    
    // Insert new signal results
    $insert_sql = "INSERT INTO operator_hand_signals (assessment_id, signal_number, signal_name, result) 
                   VALUES (?, ?, ?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    
    foreach ($signal_results as $signal_num => $result) {
        $signal_name = $hand_signals[$signal_num]['name'];
        $insert_stmt->bind_param("iiss", $assessment_id, $signal_num, $signal_name, $result);
        $insert_stmt->execute();
    }
    $insert_stmt->close();
    
    // Update assessment with test results
    $update_sql = "UPDATE operator_assessments 
                   SET signals_status = ?,
                       signals_score = ?,
                       signals_passed = ?,
                       signals_failed = ?,
                       signals_tested_at = NOW(),
                       signals_attempts = signals_attempts + 1,
                       status = CASE 
                           WHEN ? = 'PASSED' THEN 'COMPLETED'
                           ELSE status
                       END
                   WHERE id = ?";
    
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("sdiisi", 
        $test_status, 
        $score_percentage, 
        $passed_count, 
        $failed_count,
        $test_status,
        $assessment_id
    );
    $update_stmt->execute();
    $update_stmt->close();
    
    // Commit transaction
    $conn->commit();
    
    // Set success message
    if ($test_status === 'PASSED') {
        $_SESSION['success_msg'] = "Congratulations! Hand signals test passed with " . round($score_percentage, 2) . "% score.";
    } else {
        $_SESSION['error_msg'] = "Hand signals test failed. Score: " . round($score_percentage, 2) . "%. You need " . $signals_settings['passing_percentage'] . "% to pass.";
    }
    
    // Redirect to results page
    header("Location: signals-result.php?id=" . $assessment_id);
    exit();
    
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    $_SESSION['error_msg'] = "Error saving test results: " . $e->getMessage();
    header("Location: hand-signals-test.php?id=" . $assessment_id);
    exit();
}

$conn->close();
?>
