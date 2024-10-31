<?php
include('../../php/db_config.php');
session_start();

// Function to stream file content
function streamFile($filepath, $mime_type) {
    if (file_exists($filepath) && is_readable($filepath)) {
        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
    die("Error: File not found or not readable.");
}

// Validate and sanitize the ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No file ID provided. Please select a valid book.");
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    die("Error: Invalid book ID format.");
}

// Fetch book details
$sql = "SELECT file_name FROM books WHERE id = ?";
$stmt = $con->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $con->error);
}

$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    die("Error: Book not found in database.");
}

// Construct file path
$upload_dir = realpath(__DIR__ . "/../teacher/uploads/");
$sanitized_filename = basename($book['file_name']);
$final_path = $upload_dir . DIRECTORY_SEPARATOR . $sanitized_filename;

// Security check
if (strpos(realpath($final_path), $upload_dir) !== 0) {
    die("Error: Invalid file path detected.");
}

$file_extension = strtolower(pathinfo($sanitized_filename, PATHINFO_EXTENSION));
$mime_type = mime_content_type($final_path);

// Stream the file
streamFile($final_path, $mime_type);
?>
