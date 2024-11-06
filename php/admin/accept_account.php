<?php
include('../../php/db_config.php');
session_start();

if(isset($_GET['id']) && isset($_GET['type'])) {
    $id = $_GET['id'];
    $type = $_GET['type'];
    
    if($type == 'student') {
        $query = "UPDATE students SET is_accepted = 1 WHERE id = $id";
    } elseif($type == 'teacher') {
        $query = "UPDATE teacher SET is_accepted = 1 WHERE id = $id";
    } else {
        header("Location: accounts.php?error=invalid_type");
        exit();
    }
    
    if(mysqli_query($con, $query)) {
        header("Location: accounts.php?success=1");
    } else {
        header("Location: accounts.php?error=1");
    }
} else {
    header("Location: accounts.php?error=missing_params");
}
?>