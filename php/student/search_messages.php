<?php
include('../../php/db_config.php');
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../../login.php");
    exit();
}

if (isset($_POST['student_id'], $_POST['admin_id'], $_POST['query'])) {
    $student_id = $_POST['student_id'];
    $admin_id = $_POST['admin_id'];
    $query = '%' . mysqli_real_escape_string($con, $_POST['query']) . '%';

    $sql = "SELECT * FROM messages 
            WHERE ((student_id = ? AND admin_id = ?) OR (student_id = ? AND admin_id = ?)) 
            AND message LIKE ? 
            ORDER BY created_at DESC";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("iiiis", $student_id, $admin_id, $admin_id, $student_id, $query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='message-item'>";
            echo "<strong>" . htmlspecialchars($row['sender']) . ":</strong> ";
            echo "<span>" . htmlspecialchars($row['message']) . "</span><br>";
            echo "<small class='text-muted'>" . htmlspecialchars($row['created_at']) . "</small>";
            echo "</div>";
        }
    } else {
        echo "<p class='text-muted'>No matching messages found.</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid request parameters.</p>";
}
?>
