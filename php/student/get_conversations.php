<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['id'])){
    exit("No session found");
}

$student_id = $_SESSION['id'];

$conversations_query = "
    SELECT DISTINCT t.id, t.email, t.fName, t.lName,
    (SELECT message FROM messages 
     WHERE (student_id = ? AND admin_id = t.id) 
     OR (student_id = t.id AND admin_id = ?) 
     ORDER BY created_at DESC LIMIT 1) as last_message,
    (SELECT created_at FROM messages 
     WHERE (student_id = ? AND admin_id = t.id) 
     OR (student_id = t.id AND admin_id = ?) 
     ORDER BY created_at DESC LIMIT 1) as last_message_time,
    (SELECT COUNT(*) FROM messages 
     WHERE student_id = ? AND admin_id = t.id 
     AND sender = 'admin' AND is_read = 0) as unread_count
    FROM teacher t
    INNER JOIN messages m ON (t.id = m.admin_id)
    WHERE m.student_id = ?
    GROUP BY t.id
    ORDER BY last_message_time DESC
";

$stmt = $con->prepare($conversations_query);
$stmt->bind_param("iiiiii", 
    $student_id, $student_id, 
    $student_id, $student_id,
    $student_id, $student_id
);
$stmt->execute();
$conversations_result = $stmt->get_result();
$conversations = $conversations_result->fetch_all(MYSQLI_ASSOC);

foreach ($conversations as $conversation): ?>
    <div class="conversation-item <?php echo $conversation['unread_count'] > 0 ? 'unread' : ''; ?>" 
        data-teacher-id="<?php echo $conversation['id']; ?>">
        <strong><?php echo htmlspecialchars($conversation['fName'] . ' ' . $conversation['lName']); ?></strong>
        <?php if ($conversation['unread_count'] > 0): ?>
            <span class="unread-badge"><?php echo $conversation['unread_count']; ?></span>
        <?php endif; ?>
        <br>
        <small><?php echo htmlspecialchars(substr($conversation['last_message'], 0, 30)) . '...'; ?></small>
    </div>
<?php endforeach;