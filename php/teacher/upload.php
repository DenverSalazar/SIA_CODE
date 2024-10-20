<?php
include '../../php/db_config.php'; // Include database configuration

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publication_year = $_POST['publication_year'];
    $description = $_POST['description'];

    // Handle the uploaded file
    $cover_image = $_FILES['cover_image']['name'];
    $target_dir = "uploads/"; // Specify your upload directory
    $target_file = $target_dir . basename($cover_image);
    move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file);

    // Insert book details into the database
    $sql = "INSERT INTO books (title, author, publication_year, description, cover_image, created_at) VALUES ('$title', '$author', '$publication_year', '$description', '$cover_image', NOW())";
    
    if (mysqli_query($con, $sql)) {
        // Redirect to bookAdmin.php with a success message
        header("Location: bookAdmin.php?success=1");
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload New Book</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<body>
<div class="container" style="margin-top: 80px;">
    <h2>Upload New Book</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="title" class="form-label">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" required>
        </div>
        <div class="mb-3">
            <label for="publication_year" class="form-label">Publication Year</label>
            <input type="number" class="form-control" id="publication_year" name="publication_year" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Book</button>
        <a href="bookAdmin.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
