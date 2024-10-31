<?php
include('../../php/db_config.php');

if (isset($_POST['message']) && isset($_POST['student_id']) && isset($_POST['admin_id'])) {
    $message = $_POST['message'];
    $student_id = $_POST['student_id'];
    $admin_id = $_POST['admin_id'];
    $sender = $_POST['sender'];

    $is_read = 0; // Messages start as unread for the recipient

    $stmt = $con->prepare("INSERT INTO messages (student_id, admin_id, message, sender, is_read, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("iissi", $student_id, $admin_id, $message, $sender, $is_read);
    
    if ($stmt->execute()) {
        echo "Message sent successfully";
    } else {
        echo "Error sending message: " . $stmt->error;
    }
    $stmt->close();
} else {
    echo "Error: Missing required parameters";
}
?>