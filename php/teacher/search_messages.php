<?php
include('../../php/db_config.php');
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../../login.php");
    exit();
}

if (isset($_POST['student_id']) && isset($_POST['query'])) {
    $student_id = $_POST['student_id'];
    $query = '%' . mysqli_real_escape_string($con, $_POST['query']) . '%';

    $sql = "SELECT * FROM messages 
            WHERE (student_id = ? AND admin_id = ?) 
            AND message LIKE ? 
            ORDER BY created_at DESC";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("iis", $student_id, $_SESSION['id'], $query);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if any rows are returned
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='message-item'>";
            echo "<strong>" . htmlspecialchars($row['sender']) . ":</strong> ";
            echo "<span>" . htmlspecialchars($row['message']) . "<br>" . "</span>";
            echo "<small class='text-muted'>" . htmlspecialchars($row['created_at']) . "</small>";
            echo "</div>";
        }
    } else {
        // No messages found
        echo "<p class='text-muted'>No matching messages found.</p>";
    }
} else {
    echo "<p class='text-danger'>Invalid request parameters.</p>";
}
