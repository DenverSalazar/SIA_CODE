<?php
include '../../php/db_config.php'; // Ensure this path is correct and the file contains $conn
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $book_category = $_POST['bookCategory'];
    $description = $_POST['description'];

    // Handle the uploaded cover image
    $cover_image = $_FILES['cover_image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($cover_image);
    
    // Move uploaded cover image
    if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
        echo "Error uploading cover image.";
        exit();
    }

    // Check if the module file is uploaded
    if (isset($_FILES['file_name']) && $_FILES['file_name']['error'] == UPLOAD_ERR_OK) {
        $file_name = $_FILES['file_name']['name'];
        $module_target_file = $target_dir . basename($file_name);

        // Move uploaded module file
        if (!move_uploaded_file($_FILES['file_name']['tmp_name'], $module_target_file)) {
            echo "Error uploading module file.";
            exit();
        }
    } else {
        echo "No module file uploaded or there was an error.";
        exit();
    }

    // Prepare an SQL statement to insert the book details
    $stmt = $con->prepare("INSERT INTO books (title, description, cover_image, book_category, file_name, file_path) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo "Error preparing statement: " . $con->error;
        exit();
    }

    // Bind parameters to the statement
    $stmt->bind_param("ssssss", $title, $description, $cover_image, $book_category, $file_name, $module_target_file);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: bookAdmin.php?success=1");
        exit();
    } else {
        echo "Error inserting book: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload New Module</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/upload.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/> 
</head>
<body>
    <div class="sidebar">
        <h5 class="sidebar-title mb-5">
            <img src="../../img/logo.png" alt="Logo" width="190" height="20">
        </h5>
        <ul class="nav flex-column">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            $nav_items = [
                'homeAdmin.php' => ['icon' => 'fas fa-chart-bar', 'text' => 'Dashboard'],
                'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                'bookAdmin.php' => ['icon' => 'fas fa-book', 'text' => 'Modules'],
                'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                'admin_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                'admin_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
            ];

            foreach ($nav_items as $page => $item) {
                $active_class = ($current_page === $page || ($current_page === 'editBook.php' && $page === 'bookAdmin.php') || ($current_page === 'upload.php' && $page === 'bookAdmin.php')) ? 'active' : '';
                echo "<li class='nav-item'>
                        <a class='nav-link {$active_class}' href='{$page}'>
                            <i class='{$item['icon']}'></i> {$item['text']}
                        </a>
                    </li>";
            }
            ?>
            <li class="nav-item mt-auto">
                <a class="nav-link text-danger" href="../../php/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <main>
        <div class="container">
            <div class="upload-container">
                <h1 class="upload-title">Upload New Module</h1>
                <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title:</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="bookCategory" class="form-label">Category</label>
                        <select class="form-control" id="bookCategory" name="bookCategory" required>
                            <option value="">Select a Category</option>
                            <option value="HTML">HTML</option>
                            <option value="CSS">CSS</option>
                            <option value="Bootstrap">Bootstrap</option>
                            <option value="Python">Python</option>
                            <option value="Java">Java</option>
                            <option value="JavaScript">JavaScript</option>
                            <option value="C#">C#</option>
                            <option value="C++">C++</option>
                            <option value="SQL">SQL</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description:</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="cover_image" class="form-label">Cover Image:</label>
                        <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" required>
                    </div>
                    <div class="mb-3">
                        <label for="file_name" class="form-label">Module File:</label>
                        <input type="file" class="form-control" id="file_name" name="file_name" accept=".pdf,.doc,.docx,.txt,.pptx,.xlsx" required>
                    </div>
                    <a href="bookAdmin.php" class="btn btn-cancel">Cancel</a>
                    <button type="submit" name="submit" class="btn btn-upload">Upload Module</button>
                </form>
            </div>
        </div>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>