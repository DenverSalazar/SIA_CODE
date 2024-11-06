<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
    exit();
}

if(isset($_GET['id']) && isset($_GET['type'])){
    $id = mysqli_real_escape_string($con, $_GET['id']);
    $type = mysqli_real_escape_string($con, $_GET['type']);

    if($type == 'student'){
        $delete_query = mysqli_query($con, "DELETE FROM students WHERE id = '$id'");
    } elseif($type == 'teacher'){
        $delete_query = mysqli_query($con, "DELETE FROM teacher WHERE id = '$id'");
    } else {
        echo "<script>alert('Invalid account type!');</script>";
        echo "<script>window.location.href='accounts.php';</script>";
        exit();
    }

    if($delete_query){
        echo "<script>alert('Account deleted successfully!');</script>";
    } else {
        echo "<script>alert('Error deleting account: " . mysqli_error($con) . "');</script>";
    }

    echo "<script>window.location.href='accounts.php';</script>";
} else {
    echo "<script>alert('Invalid request!');</script>";
    echo "<script>window.location.href='accounts.php';</script>";
}
?>