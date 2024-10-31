<?php
include('../../php/db_config.php');

if (isset($_POST['student_id']) && isset($_POST['admin_id'])) {
    $student_id = $_POST['student_id'];
    $admin_id = $_POST['admin_id'];

    $query = "UPDATE messages 
              SET is_read = 1 
              WHERE student_id = ? AND admin_id = ? AND is_read = 0";

    $stmt = $con->prepare($query);
    $stmt->bind_param("ii", $student_id, $admin_id);
    
    if ($stmt->execute()) {
        echo "Messages marked as read";
    } else {
        echo "Error updating messages: " . $stmt->error;
    }
    
    $stmt->close();
} else {
    echo "Error: Required parameters not provided.";
}
?>