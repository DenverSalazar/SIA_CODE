<?php
include('../../php/db_config.php');
session_start();

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    
    $stmt = $con->prepare("SELECT file_name FROM books WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $upload_dir = realpath(__DIR__ . "/../teacher/uploads/");
        $filename = basename($row['file_name']);
        $filepath = $upload_dir . DIRECTORY_SEPARATOR . $filename;
        
        if (file_exists($filepath) && is_readable($filepath)) {
            $mime_type = mime_content_type($filepath);
            header('Content-Type: ' . $mime_type);
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            header('Content-Length: ' . filesize($filepath));
            readfile($filepath);
            exit;
        }
    }
}
echo "File not found";