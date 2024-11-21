<?php
include('../../php/db_config.php');
session_start();

// Check if user is logged in
if (!isset($_SESSION['valid'])) {
    header("Location: ../../login.php");
    exit();
}

// Get the module ID from the URL
if (isset($_GET['id'])) {
    $module_id = mysqli_real_escape_string($con, $_GET['id']);

    // Fetch module details
    $query = "SELECT * FROM books WHERE id = '$module_id'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $module = mysqli_fetch_assoc($result);
        $file_path = '../../php/teacher/uploads/' . $module['file_name'];

        // Check if the file exists
        if (file_exists($file_path)) {
            // Log the download action
            $download_action = 'download_module';
            $download_details = "Downloaded module: " . htmlspecialchars($module['title']);
            
            // Insert download activity log
            $log_query = "INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())";
            $log_stmt = $con->prepare($log_query);
            $log_stmt->bind_param("iss", $_SESSION['id'], $download_action, $download_details);
            $log_stmt->execute();
            $log_stmt->close();

            // Force download
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf'); // Change this if your file type is different
            header('Content-Disposition: attachment; filename="' . basename($file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file_path));
            readfile($file_path);
            exit;
        } else {
            echo '<span class="text-danger">File not found.</span>';
        }
    } else {
        echo '<span class="text-danger">Module not found.</span>';
    }
} else {
    echo '<span class="text-danger">Invalid request.</span>';
}
?>