<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['type'])) {
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $type = mysqli_real_escape_string($con, $_GET['type']);

    if($type == 'student') {
        $query = "UPDATE students SET is_accepted = 1 WHERE id = ?";
        $stmt = mysqli_prepare($con, $query);
        mysqli_stmt_bind_param($stmt, "i", $id);
        
        if(mysqli_stmt_execute($stmt)) {
            header("Location: accounts.php?success=1");
        } else {
            header("Location: accounts.php?error=1");
        }
        mysqli_stmt_close($stmt);
    }
} else {
    header("Location: accounts.php");
}
exit();
?>