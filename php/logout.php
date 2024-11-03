<?php
include('db_config.php'); // Adjust this path according to your db_config.php location
session_start();

// Check if the user is logged in and is a student
if(isset($_SESSION['valid']) && $_SESSION['role'] == 'student') {
    $student_id = $_SESSION['id'];
    
    // Log the logout action
    $log_query = "INSERT INTO activity_logs (student_id, action, details, timestamp) 
                  VALUES ('$student_id', 'logout', 'Student logged out', NOW())";
    mysqli_query($con, $log_query);
}

// Destroy the session
session_destroy();

// Redirect to login page
header("Location: login.php"); // Adjust this path to your login.php location
exit();
?>