<?php include '../../php/db_config.php';

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $book = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $book_category = $_POST['bookCategory'];

    $sql = "UPDATE books SET title = ?, book_category = ?, description = ? WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sssi", $title, $book_category, $description, $book_id);
    $stmt->execute();

    header("Location: bookAdmin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        /* Styles omitted for brevity */
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Edit Book</h1>
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>
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
            <button type="submit" class="btn btn-primary">Update Book</button>
            <a href="bookAdmin.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>