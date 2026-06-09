<?php
session_start();
include '../file/config.php';

if (isset($_GET['id'])) {
    $assessment_id = (int)$_GET['id'];
    
    // Start transaction
    $conn->begin_transaction();
    
    try {
        // Delete related documents first (due to foreign key)
        $delete_docs_sql = "DELETE FROM operator_documents WHERE assessment_id = ?";
        $docs_stmt = $conn->prepare($delete_docs_sql);
        $docs_stmt->bind_param("i", $assessment_id);
        $docs_stmt->execute();
        $docs_stmt->close();
        
        // Delete related equipment
        $delete_equipment_sql = "DELETE FROM operator_equipment WHERE assessment_id = ?";
        $equipment_stmt = $conn->prepare($delete_equipment_sql);
        $equipment_stmt->bind_param("i", $assessment_id);
        $equipment_stmt->execute();
        $equipment_stmt->close();
        
        // Delete assessment
        $delete_assessment_sql = "DELETE FROM operator_assessments WHERE id = ?";
        $assessment_stmt = $conn->prepare($delete_assessment_sql);
        $assessment_stmt->bind_param("i", $assessment_id);
        $assessment_stmt->execute();
        $assessment_stmt->close();
        
        // Commit transaction
        $conn->commit();
        
        $_SESSION['success_msg'] = "Assessment deleted successfully!";
    } catch (Exception $e) {
        $conn->rollback();
        $_SESSION['error_msg'] = "Error deleting assessment: " . $e->getMessage();
    }
    
    $conn->close();
} else {
    $_SESSION['error_msg'] = "Invalid assessment ID";
}

header("Location: assessment-list.php");
exit();
?>
