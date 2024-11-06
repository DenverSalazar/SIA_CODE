<?php
    include('../../php/db_config.php');
    session_start();

    function getProfilePicturePath($profile_picture) {
        if (isset($profile_picture) && !empty($profile_picture)) {
            return "../../../uploads/profiles/" . htmlspecialchars($profile_picture);
        } else {
            return "../../../img/default-profile.png";
        }
    }
  
    // Fetch user data including profile picture
        $id = $_SESSION['id'];
        $query = mysqli_query($con, "SELECT * FROM students WHERE id = '$id'");
        $result = mysqli_fetch_assoc($query);
        $res_profile_picture = $result['profile_picture'];
        $res_fName = $result['fName'];
        $res_lName = $result['lName'];

    // Pagination settings
    $books_per_page = 9; // Set how many books to display per page
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
    <title>Browse Books</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/books.css">
    <style>
        .navbar {
        background-color: #052659;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }
    .navbar-brand img {
        filter: brightness(0) invert(1);
    }
    .navbar-nav .nav-link {
        color: rgba(255,255,255,0.8) !important;
        transition: color 0.3s ease;
    }
    .navbar-nav .nav-link:hover {
        color: #ffffff !important;
    }
    .nav-item.dropdown .user-profile {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        color: #ffffff;
        background-color: rgba(255,255,255,0.1);
        border-radius: 50px;
        transition: background-color 0.3s ease;
    }
    .nav-item.dropdown .user-profile:hover {
        background-color: rgba(255,255,255,0.2);
    }
    .nav-item.dropdown img {
        width: 32px;
        height: 32px;
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid #ffffff;
    }
    .dropdown-menu {
        background-color: #ffffff;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        border-radius: 0.5rem;
    }
    .dropdown-item {
        color: #052659;
        padding: 0.5rem 1.5rem;
        transition: background-color 0.3s ease;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #052659;
    }
    .dropdown-item i {
        margin-right: 10px;
        color: #052659;
    }
</style>
<body>
  <!-- HEADER -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="../../img/logo.png" alt="Readiculous"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./student_messages.php">Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./feedback.php">Feedback</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./about.php">About</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-profile" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo getProfilePicturePath($res_profile_picture); ?>" alt="Profile" class="rounded-circle">
                        <span><?php echo $res_fName; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="./student_profile.php"><i class="fas fa-user-circle"></i> View Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../../php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Browse Books</h1>

    <form class="d-flex align-items-center justify-content-center" action="" method="get">
        <input class="form-control w-25 me-3" type ```php
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

    <div class="row mt-5">
        <?php if (count($books) > 0): ?>
            <?php foreach ($books as $book): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm book-card">
                        <img src="../teacher/uploads/<?= htmlspecialchars($book['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text">Category: <b><?= htmlspecialchars($book['book_category']) ?></b></p>
                            <p class="card-text"><?= substr(htmlspecialchars($book['description']), 0, 100) ?>...</p>
                            <div class="d-flex justify-content-center align-items-center">
                                <a href="read_more.php?id=<?= $book['id'] ?>" class="btn btn-outline-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <p>No books found matching your search or category selection.</p>
            </div>
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

<footer class="footer-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="/SIA/img/logo.png" alt="Readiculous" class="footer-logo mb-3" style="max-width: 200px;">
                <p class="footer-description">Readiculous: Your gateway to a world of knowledge and imagination. Explore, learn, and grow with our comprehensive library management system.</p>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="footer-heading">Quick Links</h5>
                <ul class="footer-links list-unstyled">
                    <li><a href="home.php">Home</a></li>
                    <li><a href="about.php">About Us</a></li>
                </ul>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="footer-heading">Services</h5>
                <ul class="footer-links list-unstyled">
                    <li>Book Search</li>
                    <li>Online Reading</li>
                    <li>Give Feedback</li>
                    <li>Digital Resources</li>
                </ul>
            </div>
            <div class="col-lg-4 col-md-4">
                <h5 class="footer-heading">Contact Us</h5>
                <address class="footer-contact">
                    <p><i class="fas fa-map-marker-alt me-2"></i>123 Library Street, Booktown, BK 12345</p>
                    <p><i class="fas fa-phone me-2"></i>(123) 456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i>info@readiculous.com</p>
                </address>
            </div>
        </div>
    </div>
    <div class="footer-bottom text-center mt-4" style="background-color: transparent;">
        <div class="container">
            <hr class="footer-divider">
            <p class="footer-copyright">&copy; 2024 Readiculous Library Management System. All rights reserved.</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var navLinks = document.querySelectorAll('.navbar-nav .nav-link');
        var bsCollapse = new bootstrap.Collapse(document.getElementById('navbarNav'), {toggle: false});
        
        navLinks.forEach(function(navLink) {
            navLink.addEventListener('click', function() {
                if (window.innerWidth < 992) { // Only close for mobile view
                    bsCollapse.hide();
                }
            });
        });
    });
</script>
</body>
</html>