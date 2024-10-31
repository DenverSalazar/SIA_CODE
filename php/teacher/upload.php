<?php
include '../../php/db_config.php'; // Ensure this path is correct and the file contains $conn

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
        <!-- <div class="mb-3">
            <label for="author" class="form-label">Author</label>
            <input type="text" class="form-control" id="author" name="author" required>
        </div> -->
        <div class="mb-3">
        <label for="bookCategory" class="form-label">Category</label>
        <select class="form-control" id="bookCategory" name="bookCategory" required>
            <option value="">Select a Category</option>
            <option value="HTML">HTML</option>
            <option value="CSS">CSS</option>
            <option value="Bootstrap">Bootstrap</option>
            <option value="python">Python</option>
            <option value="java">Java</option>
            <option value="javascript">JavaScript</option>
            <option value="c#">C#</option>
            <option value="c++">C++</option>
            <option value="SQL">SQL</option>
        </select>
        </div>
        <!-- <div class="mb-3">
        <label for="publication_year" class="form-label">Publication Year</label>
            <select class="form-control" id="publication_year" name="publication_year" required>
                <option value="" disabled selected>Select a year</option>
                <script>
                    const currentYear = new Date().getFullYear();
                    for (let year = currentYear; year >= 1900; year--) {
                        document.write(`<option value="${year}">${year}</option>`);
                    }
                </script>
            </select>
        </div> -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <div class="mb-3">
            <label for="cover_image" class="form-label">Cover Image</label>
            <input type="file" class="form-control" id="cover_image" name="cover_image" accept="image/*" required>
        </div>
        <div class="mb-3">
            <label for="file_name" class="form-label">Upload Module</label>
            <input type="file" class="form-control" id="file_name" name="file_name" accept=".pdf,.doc,.docx,.txt,.pptx,.xlsx" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload Book</button>
        <a href="bookAdmin.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
