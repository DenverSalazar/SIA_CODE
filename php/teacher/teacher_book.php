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
    <title>Teacher Book Shelf</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/bookAdmin.css">
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
                    'teacher_home.php' => ['icon' => 'fas fa-chart-bar', 'text' => 'Dashboard'],
                    'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                    'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                    'teacher_book.php' => ['icon' => 'fas fa-book', 'text' => 'Modules'],
                    'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                    'teacher_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                    'teacher_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
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
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="bookshelf-title">Collection of Modules</h1>
                <a href="upload.php"><button class="upload-btn"><i class="fas fa-upload me-2"></i>Upload New Module</button></a>
            </div>

            <div class="search-filter-form">
                <form class="row g-3 align-items-center" action="" method="get">
                    <div class="col-md-5">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input class="form-control" type="search" name="search" placeholder="Search modules..." aria-label="Search" value="<?= htmlspecialchars($search) ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-filter"></i></span>
                            <select name="category" class="form-select">
                                <option value="All" <?= $selected_category === 'All' ? 'selected' : '' ?>>All Categories</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= htmlspecialchars($category['book_category']) ?>" <?= $selected_category === $category['book_category'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['book_category']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-primary w-100" type="submit" name="submit">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>
                </form>
            </div>

            <?php if (isset($books) && count($books) == 0): ?>
                <div class="alert alert-info text-center" role="alert">
                    No Modules found matching your search or category selection.
                </div>
            <?php endif; ?>

            <div class="books">
                <?php if (count($books) > 0): ?>
                    <?php foreach ($books as $book): ?>
                        <div class="book">
                            <img alt="<?= htmlspecialchars($book['title']) ?> book cover" src="uploads/<?= htmlspecialchars($book['cover_image']) ?>" />
                            <div class="book-content">
                                <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                                <div class="book-status"><?= isset($book['status']) ? htmlspecialchars($book['status']) : 'Uploaded' ?></div>
                                <div class="book-meta">
                                    Category: <?= htmlspecialchars($book['book_category']) ?><br>
                                    Description: <p><?= substr(htmlspecialchars($book['description']), 0, 90) ?>...</p>
                                </div>
                                <div class="book-actions">
                                    <a href="editBook.php?id=<?= htmlspecialchars($book['id']) ?>" class="btn btn-book btn-edit">Edit</a>
                                    <a href="deleteBook.php?id=<?= htmlspecialchars($book['id']) ?>" class="btn btn-book btn-delete" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>    
                <?php endif; ?>
            </div>

            <?php if (isset($pagination) && count($pagination) > 1): ?>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <?php foreach ($pagination as $page => $url): ?>
                            <li class="page-item <?= $page === $current_page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= htmlspecialchars($url) ?>"><?= htmlspecialchars($page) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </main>

<script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
