<?php
    include '../../php/db_config.php'; // Include database configuration

    // Pagination settings
    $books_per_page = 10; // Set how many books to display per page
    $offset = isset($_GET['page']) ? ($_GET['page'] - 1) * $books_per_page : 0;

    // Initialize search and category variables
    $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
    $selected_category = isset($_GET['category']) ? $_GET['category'] : 'All';

    // Build the WHERE clause based on search and category
    $where_clause = '';
    if ($search) {
        $where_clause .= "WHERE (title LIKE '%$search%')";
    }
    if ($selected_category !== 'All') {
        $where_clause .= ($where_clause ? " AND " : "WHERE ") . "book_category = '$selected_category'";
    }

    // Count total books for pagination
    $count_sql = "SELECT COUNT(*) as total FROM books $where_clause";
    $count_result = mysqli_query($con, $count_sql);
    $total_books = mysqli_fetch_assoc($count_result)['total'];
    $total_pages = ceil($total_books / $books_per_page);

    // Fetch books
    $sql = "SELECT * FROM books $where_clause LIMIT $offset, $books_per_page";
    $result = mysqli_query($con, $sql);
    $books = mysqli_fetch_all($result, MYSQLI_ASSOC);

    // Fetch categories from the database
    $category_result = mysqli_query($con, "SELECT DISTINCT book_category FROM books"); // Use DISTINCT to avoid duplicates
    $categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

    mysqli_close($con);


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Book Shelf</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<style>

    .offcanvas {
        width: 300px !important;
    }

    .navbar {
        background-color: white;
    }

    .offcanvas-header {
        background-color: #343a40;
        border-bottom: 1px solid #ddd;
    }

    .offcanvas-body {
        padding: 1rem;
        background-color: #343a40;
    }

    .btn-outline-success {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .btn-outline-success:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .offcanvas .nav-item {
        margin-bottom: 10px;
    }

    .offcanvas .nav-item a {
        color: white;
        font-size: 16px;
    }

    .logout-link {
        color: red; 
    }

    .logout-link:hover {
        color: darkred; 
    }

    .offcanvas .nav-item a:hover {
        color: #0056b3;
        text-decoration: none;
    }

    .navbar-brand img {
        width: 150px;
    }
          
    
    .offcanvas-header h5 {
        margin-bottom: 0;
        filter: brightness(0) invert(1);
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: white;
    }

    .bookshelf-title {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .books {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
    }

    .book {
        background-color: #fff;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        min-height: 350px; /* Set a minimum height */
    }

    .book img {
        width: 100%;
        height: 250px; /* Fixed height for book covers */
        object-fit: cover; /* Ensures cover image fits the given size */
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .book-title {
        font-size: 16px;
        margin-bottom: 5px;
        text-align: center; /* Center-align the title */
    }

    .book-status {
        color: #888;
        font-size: 14px;
        margin-bottom: 10px;
        text-align: center;
    }

    .book-meta {
        font-size: 12px;
        color: #888;
        text-align: center;
        margin-bottom: 10px; /* Add spacing below meta information */
    }

    .d-flex {
        justify-content: space-between;
        margin-top: auto; /* Push buttons to the bottom */
    }

    .upload-btn {
        background-color: #007bff;
        color: #fff;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        align-self: center;
    }

    .upload-btn i {
        margin-right: 5px;
    }
    
</style>
<body>
<!-- HEADER -->
<header>
    <nav class="navbar navbar-light fixed-top">
        <div class="container">
          <a class="navbar-brand"><img src="../../img/logo.png" alt="Readiculous" width=""></a>
          <form class="d-flex">
           
          </form>
          <button type="button" class="btn btn-secondary" onclick="location.href='homeAdmin.php'">Back</button>
          </div>
      </nav>
  </header>

<main>
<div class="container" style="margin-top: 80px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="bookshelf-title">My Book Shelf</div>
        <a href="upload.php"><button class="upload-btn"><i class="fas fa-upload"></i>Upload New Book</button></a>
    </div>

    <div class="container mt-5">
        <!-- <h1 class="mb-4">Browse Books</h1> -->

        <form class="d-flex align-items-center justify-content-center" action="" method="get">
            <input class="form-control w-25 me-3" type="search" name="search" placeholder="Search books..." aria-label="Search" value="<?= htmlspecialchars($search) ?>">
            
            <select name="category" class="form-control w-25 me-3">
                <option value="All" <?= $selected_category === 'All' ? 'selected' : '' ?>>All Categories</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?= htmlspecialchars($category['book_category']) ?>" <?= $selected_category === $category['book_category'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['book_category']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            
            <button class="btn btn-outline-primary me-2" type="submit" name="submit">Filter</button>
        </form>

       <!-- No books found message -->
        <?php if (isset($books) && count($books) == 0): ?>
            <div class="container">
                <div class="row mt-5">
                    <div class="col-12 text-center" style="color:red; font-weight:bolder">
                        <p>No Modules found matching your search or category selection.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="container mt-5">
            <div class="books">
                <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book">
                        <img alt="<?= htmlspecialchars($book['title']) ?> book cover" src="uploads/<?= htmlspecialchars($book['cover_image']) ?>" />
                        <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                        <div class="book-status">Uploaded</div>
                        <div class="book-meta">
                            Category: <?= htmlspecialchars($book['book_category']) ?><br>
                            Description: <p><?= substr(htmlspecialchars($book['description']), 0, 90) ?>...</p>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                        <a href="editBook.php?id=<?= htmlspecialchars($book['id']) ?>" class="btn btn-success btn-sm">Edit</a>
                        <a href="deleteBook.php?id=<?= htmlspecialchars($book['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>    
                <?php endif; ?>
                </div>
                <nav aria-label="Book pagination" class="mt-5">
                <ul class="pagination justify-content-center">
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                </ul>
            </nav>

</div>
</main>
<script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
