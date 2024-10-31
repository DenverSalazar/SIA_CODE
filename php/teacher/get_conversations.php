<?php
include('../../php/db_config.php');
session_start();

if (isset($_SESSION['id'])) {
    $admin_id = $_SESSION['id'];

    $conversations_query = "
        SELECT DISTINCT s.id, s.email, s.fName, s.lName,
        (SELECT message FROM messages 
         WHERE (student_id = s.id AND admin_id = ?) 
         OR (student_id = ? AND admin_id = s.id) 
         ORDER BY created_at DESC LIMIT 1) as last_message,
        (SELECT created_at FROM messages 
         WHERE (student_id = s.id AND admin_id = ?) 
         OR (student_id = ? AND admin_id = s.id) 
         ORDER BY created_at DESC LIMIT 1) as last_message_time,
        (SELECT COUNT(*) FROM messages 
         WHERE student_id = s.id AND admin_id = ? AND is_read = 0) as unread_count
        FROM students s
        INNER JOIN messages m ON (s.id = m.student_id OR s.id = m.admin_id)
        WHERE m.admin_id = ? OR m.student_id = ?
        GROUP BY s.id
        ORDER BY last_message_time DESC
    ";

    $stmt = $con->prepare($conversations_query);
    $stmt->bind_param("iiiiiii", $admin_id, $admin_id, $admin_id, $admin_id, $admin_id, $admin_id, $admin_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $output = '';
    while ($conversation = $result->fetch_assoc()) {
        $unread_class = $conversation['unread_count'] > 0 ? 'unread' : '';
        $output .= '
            <div class="conversation-item ' . $unread_class . '" data-student-id="' . $conversation['id'] . '">
                <strong>' . htmlspecialchars($conversation['fName'] . ' ' . $conversation['lName']) . '</strong>';
        
        if ($conversation['unread_count'] > 0) {
            $output .= '<span class="unread-badge">' . $conversation['unread_count'] . '</span>';
        }
        
        $output .= '<br>
                <small>' . htmlspecialchars(substr($conversation['last_message'], 0, 30)) . '...</small>
            </div>';
    }

    echo $output;
    $stmt->close();
} else {
    echo "Error: User not logged in";
}
?>