<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

// Delete all feedback records
$delete_query = "DELETE FROM feedback";
if(mysqli_query($con, $delete_query)) {
    header("Location: admin_feedback.php?msg=all_deleted");
} else {
    header("Location: admin_feedback.php?error=delete_failed");
}
?>