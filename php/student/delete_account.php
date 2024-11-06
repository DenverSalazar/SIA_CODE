<?php
session_start();
include("../db_config.php");

if (isset($_POST['submit']) && isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $role = $_SESSION['role'];

    // Sanitize the id to prevent SQL injection
    $id = mysqli_real_escape_string($con, $id);

    // DELETE ACCOUNT PERMANENTLY
    if ($role == 'student') {
        $delete_query = mysqli_query($con, "DELETE FROM students WHERE id = '$id'") or die("error occurred");
    } else if ($role == 'teacher') {
        $delete_query = mysqli_query($con, "DELETE FROM teacher WHERE id = '$id'") or die("error occurred");
    }

    if ($delete_query) {
        session_destroy(); // Destroy session after deletion
        echo "<script>alert('Account Deleted Successfully!');</script>";
        echo "<script>window.location.href='../login.php';</script>"; // Redirect to login page
    } else {
        echo "<script>alert('Error deleting account!');</script>";
        echo "<script>window.location.href='student_profile.php';</script>"; // Redirect back to profile on error
    }
} else {
    // If someone tries to access this page directly without proper POST data
    echo "<script>alert('Invalid request!');</script>";
    echo "<script>window.location.href='student_profile.php';</script>";
}
?>