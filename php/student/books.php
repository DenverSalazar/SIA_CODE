<?php
include('../../php/db_config.php');
session_start();

// Pagination setup
$books_per_page = 9;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $books_per_page;

// Search functionality
$search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
$where_clause = $search ? "WHERE title LIKE '%$search%' OR author LIKE '%$search%'" : "";

// Count total books for pagination
$count_sql = "SELECT COUNT(*) as total FROM books $where_clause";
$count_result = mysqli_query($con, $count_sql);
$total_books = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_books / $books_per_page);

// Fetch books
$sql = "SELECT * FROM books $where_clause LIMIT $offset, $books_per_page";
$result = mysqli_query($con, $sql);
$books = mysqli_fetch_all($result, MYSQLI_ASSOC);

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Books</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <style>
        .book-card img {
            height: 300px;
            object-fit: cover;
        }
        body {
            background-color: #f8f9fa;
            color: #333;
        }
        .navbar {
            background-color: #ffffff;
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
        }
        .book-card {
            transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .book-cover {
            height: 300px;
            object-fit: cover;
        }
        .search-bar {
            max-width: 500px;
            margin: 0 auto;
        }
        .offcanvas {
        width: 300px !important;
    }

    .navbar {
        background-color: #f8f9fa;
    }

    .offcanvas-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }

    .offcanvas-body {
        padding: 1rem;
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
        color: #333;
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
    }

    body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        background-color: #f5f5f5;
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
    }

    .book img {
        width: 100%;
        height: auto;
        border-bottom: 1px solid #ddd;
        margin-bottom: 10px;
    }

    .book-title {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .book-status {
        color: #888;
        font-size: 14px;
        margin-bottom: 10px;
    }

    .book-meta {
        font-size: 12px;
        color: #888;
    }

    .upload-btn {
        background-color: #007bff;
        color: #fff;
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .upload-btn i {
        margin-right: 5px;
    }

    .footer-section {
        background-color: #2c3e50;
        color: #ecf0f1;
    }

    .footer-logo {
        filter: brightness(0) invert(1);
    }

    .footer-description {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .footer-heading {
        font-family: 'Merriweather', serif;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #3498db;
    }

    .footer-links a {
        color: #ecf0f1;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: #3498db;
    }

    .footer-contact {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .footer-bottom {
        background-color: #333;
        padding: 1rem 0;
    }

    .footer-divider {
        border: none;
        border-top: 1px solid #444;
        margin: 1rem 0;
    }

    .footer-copyright {
        color: white;
    }

    .about-section {
        padding: 40px 0; 
    }

    .about-text {
        overflow: hidden; 
        max-height: 400px; 
        overflow-y: auto; 
    }

    @media (max-width: 768px) {
        .about-text {
            max-height: none; 
        }
    }
    </style>
</head>
<body>
<!-- HEADER -->
<header>
    <nav class="navbar navbar-light fixed-top">
        <div class="container">
          <a class="navbar-brand"><img src="../../img/logo.png" alt="Readiculous" width=""></a> 
          <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
            <span class="navbar-toggler-icon"></span>
          </button>
        </div>
      </nav>

      <!-- Offcanvas Menu -->
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><img src="../../img/logo.png" alt="Readiculous" width="150"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../php/profile.php">User Profile</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="../../php/student/feedback.php">Feedback</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.php">About us</a>
            </li>
            <li class="nav-item-x">
              <a class="nav-link logout-link" href="../../php/logout.php">Logout</a>
            </li>            
          </ul>
        </div>
      </div>
  </header>



    <div class="container mt-5">
        <h1 class="mb-4">Browse Books</h1>

        <form class="d-flex mb-5" method="GET" action="">
    <input class="form-control me-2" type="search" name="search" placeholder="Search books..." aria-label="Search" value="<?= htmlspecialchars($search) ?>">
    <button class="btn btn-outline-primary me-2" type="submit">Search</button>

    <!-- Filter Button -->
    <div class="dropdown">
        <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            Filter
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="filterDropdown">
            <li><a class="dropdown-item" href="?filter=latest">Latest</a></li>
            <li><a class="dropdown-item" href="?filter=popular">Most Popular</a></li>
            <li><a class="dropdown-item" href="?filter=author">By Author</a></li>
        </ul>
    </div>
</form>

        <div class="row">
            <?php foreach ($books as $book): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm book-card">
                        <img src="../teacher/uploads/<?= htmlspecialchars($book['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>">
                        <div class="card-body">
                            <h 5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text">By <?= htmlspecialchars($book['author']) ?></p>
                          <p class="card-text"><?= substr(htmlspecialchars($book['description']), 0, 100) ?>...</p>
                            <a href="#" class="btn btn-outline-primary">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
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

    
    <hr class="featurette-divider">

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
                    <li><a href="#home">Home</a></li>
                    <li><a href="#about">About Us</a></li>
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

    <script src="../../js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>