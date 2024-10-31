<?php
include('../../php/db_config.php');
session_start();

if(isset($_POST['student_id']) && isset($_POST['admin_id'])) {
    $student_id = $_POST['student_id'];
    $admin_id = $_POST['admin_id'];

    $query = "SELECT * FROM messages 
              WHERE (student_id = ? AND admin_id = ?) 
              OR (student_id = ? AND admin_id = ?) 
              ORDER BY created_at ASC";

    $stmt = $con->prepare($query);
    $stmt->bind_param("iiii", $student_id, $admin_id, $admin_id, $student_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $isAdmin = ($row['sender'] == 'admin');
        $time = date('h:i A', strtotime($row['created_at']));
        
        if($isAdmin) {
            // Admin message (right side)
            echo '<div class="message-container right">
                    <div class="message admin">
                        ' . htmlspecialchars($row['message']) . '
                        <div class="message-time">' . $time . '</div>
                    </div>
                  </div>';
        } else {
            // Student message (left side)
            echo '<div class="message-container left">
                    <div class="message student">
                        ' . htmlspecialchars($row['message']) . '
                        <div class="message-time">' . $time . '</div>
                    </div>
                  </div>';
        }
    }
}
?>