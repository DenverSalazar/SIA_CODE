<?php
include '../../php/db_config.php';

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    
    // Delete book from database
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();

    header("Location: bookAdmin.php");
    exit;
}
?>
