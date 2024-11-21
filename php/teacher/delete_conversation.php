<?php
include('../../php/db_config.php');
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../../login.php");
    exit();
}

// Check if admin is logged in and the request is valid
if (isset($_POST['student_id']) && isset($_SESSION['id'])) {
    $student_id = intval($_POST['student_id']); // Sanitize the student ID
    $admin_id = intval($_SESSION['id']); // Admin ID from session

    // Delete all messages between the admin and the student
    $delete_query = "DELETE FROM messages 
                     WHERE (student_id = ? AND admin_id = ?) 
                     OR (student_id = ? AND admin_id = ?)";
    
    $stmt = $con->prepare($delete_query);
    $stmt->bind_param("iiii", $student_id, $admin_id, $admin_id, $student_id);

    if ($stmt->execute()) {
        echo json_encode([
            'status' => 'success',
            'message' => 'Conversation successfully deleted.'
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Failed to delete the conversation. Please try again.'
        ]);
    }

    $stmt->close();
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request. Missing parameters.'
    ]);
}
?>
