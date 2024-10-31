<?php
include('../../php/db_config.php');

if (isset($_POST['message']) && isset($_POST['student_id']) && isset($_POST['admin_id'])) {
    $message = $_POST['message'];
    $student_id = $_POST['student_id'];
    $admin_id = $_POST['admin_id'];
    $sender = 'student'; // Since this is the student's send_message.php

    // Messages sent by students should be marked as read (is_read = 1)
    $is_read = 1; // Change this from 0 to 1

    $stmt = $con->prepare("INSERT INTO messages (student_id, admin_id, message, sender, is_read, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iissi", $student_id, $admin_id, $message, $sender, $is_read);
    
    if ($stmt->execute()) {
        // Also mark any previous messages in this conversation as read
        $update_stmt = $con->prepare("UPDATE messages SET is_read = 1 WHERE student_id = ? AND admin_id = ? AND sender = 'student'");
        $update_stmt->bind_param("ii", $student_id, $admin_id);
        $update_stmt->execute();
        $update_stmt->close();
        
        echo "Message sent successfully";
    } else {
        echo "Error sending message: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: Missing required parameters";
}
?>