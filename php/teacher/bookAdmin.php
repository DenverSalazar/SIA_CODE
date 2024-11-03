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
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>

</head>
<style>
    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: white;
    }

    main {
        margin-left: 250px; /* Width of the sidebar */
        padding: 20px;
        transition: margin-left 0.3s ease;
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
        min-height: 350px;
    }

    .book img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .book-title {
        font-size: 16px;
        margin-bottom: 5px;
        text-align: center;
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
        margin-bottom: 10px;
    }

    .d-flex {
        justify-content: space-between;
        margin-top: auto;
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

    /* Adjust the container padding */
    .container {
        padding: 20px;
        max-width: 100%;
    }

    /* Search and filter form styling */
    .search-filter-form {
        margin-bottom: 30px;
    }

    /* Pagination styling */
    .pagination {
        margin-top: 30px;
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
                    'homeAdmin.php' => ['icon' => 'fas fa-chart-bar', 'text' => 'Dashboard'],
                    'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                    'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                    'bookAdmin.php' => ['icon' => 'fas fa-book', 'text' => 'Modules'],
                    'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                    'admin_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                    'admin_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
                ];

                foreach ($nav_items as $page => $item) {
                    $active_class = ($current_page === $page) ? 'active' : '';
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
