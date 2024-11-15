<?php
include '../../php/db_config.php'; // Ensure this path is correct and the file contains $con
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
    exit();
}

// Fetch book details based on the ID passed in the URL
if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $stmt = $con->prepare("SELECT * FROM books WHERE id = ?");
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Book ID not specified.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $book_category = $_POST['bookCategory'];
    $description = $_POST['description'];
    
    // Handle the uploaded cover image if a new one is provided
    if ($_FILES['cover_image']['name']) {
        $cover_image = $_FILES['cover_image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($cover_image);
        
        // Move uploaded cover image
        if (!move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)) {
            echo "Error uploading cover image.";
            exit();
        }
    } else {
        $cover_image = $book['cover_image']; // Keep the old cover image if not updated
    }

    // Prepare an SQL statement to update the book details
    $stmt = $con->prepare("UPDATE books SET title = ?, description = ?, cover_image = ?, book_category = ? WHERE id = ?");
    if ($stmt === false) {
        echo "Error preparing statement: " . $con->error;
        exit();
    }

    // Bind parameters to the statement
    $stmt->bind_param("ssssi", $title, $description, $cover_image, $book_category, $book_id);

    // Execute the statement
    if ($stmt->execute()) {
        header("Location: teacher_home.php?success=1");
        exit();
    } else {
        echo "Error updating book: " . $stmt->error;
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
    <title>Edit Module</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/editBook.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<style>
    .sidebar{
        background-color: #052659;
    }
</style>
<body>
<div class="sidebar">
            <h5 class="sidebar-title mb-5">
                <img src="../../img/logo.png" alt="Logo" width="190" height="20">
            </h5>
            <ul class="nav flex-column">
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $nav_items = [
                    'teacher_home.php' => ['icon' => 'fas fa-home', 'text' => 'Home'],
                    'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                    'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                    'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                    'teacher_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                    'teacher_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
                ];

                foreach ($nav_items as $page => $item) {
                    $active_class = ($current_page === $page || ($current_page === 'editBook.php' && $page === 'teacher_home.php') || ($current_page === 'upload.php' && $page === 'teacher_home.php')) ? 'active' : '';
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
        <div class="edit-container">
            <h1 class="edit-title">Edit Module</h1>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Title:</label>
                    <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="bookCategory" class="form-label">Category</label>
                    <select class="form-control" id="bookCategory" name="bookCategory" required>
                        <option value="">Select a Category</option>
                        <option value="HTML" <?= $book['book_category'] == 'HTML' ? 'selected' : '' ?>>HTML</option>
                        <option value="CSS" <?= $book['book_category'] == 'CSS' ? 'selected' : '' ?>>CSS</option>
                        <option value="Bootstrap" <?= $book['book_category'] == 'Bootstrap' ? 'selected' : '' ?>>Bootstrap</option>
                        <option value="python" <?= $book['book_category'] == 'python' ? 'selected' : '' ?>>Python</option>
                        <option value="java" <?= $book['book_category'] == 'java' ? 'selected' : '' ?>>Java</option>
                        <option value="javascript" <?= $book['book_category'] == 'javascript' ? 'selected' : '' ?>>JavaScript</option>
                        <option value="c#" <?= $book['book_category'] == 'c#' ? 'selected' : '' ?>>C#</option>
                        <option value="c++" <?= $book['book_category'] == 'c++' ? 'selected' : '' ?>>C++</option>
                        <option value="SQL" <?= $book['book_category'] == 'SQL' ? 'selected' : '' ?>>SQL</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description:</label>
                    <textarea class="form-control" name="description" rows="4" required><?= htmlspecialchars($book['description']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="cover_image" class="form-label">Cover Image</label>
                    <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*">
                </div>
                <div class="mb-3">
                    <label for="upload_module" class="form-label">Upload Module</label>
                    <input type="file" class="form-control" id="upload_module" name="upload_module" accept=".pdf,.doc,.docx,.txt,.pptx,.xlsx">
                </div>
                <a href="teacher_home.php" class="btn btn-cancel">Cancel</a>
                <button type="submit" class="btn btn-submit">Update Module</button>
            </form>
        </div>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>