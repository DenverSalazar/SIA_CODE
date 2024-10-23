<?php
include '../../php/db_config.php'; // Include database configuration

// Fetch books from the database
$sql = "SELECT * FROM books";
$result = mysqli_query($con, $sql);
$books = mysqli_fetch_all($result, MYSQLI_ASSOC);

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
                    <a class="nav-link active" aria-current="page" href="homeAdmin.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../php/profile.php">Admin Profile</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Feedback Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item-x">
                    <a class="nav-link logout-link" href="../../php/logout.php">Logout</a>
                </li>            
            </ul>
        </div>
    </div>
</header>

<main>
<div class="container" style="margin-top: 80px;">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="bookshelf-title">My Book Shelf</div>
        <a href="upload.php"><button class="upload-btn"><i class="fas fa-upload"></i>Upload New Book</button></a>
    </div>

    <div class="books">
        <?php foreach ($books as $book): ?>
            <div class="book">
                <img alt="<?= htmlspecialchars($book['title']) ?> book cover" src="uploads/<?= htmlspecialchars($book['cover_image']) ?>" />
                <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                <div class="book-status">Published</div>
                <div class="book-meta">
                    Author: <?= htmlspecialchars($book['author']) ?><br/>
                    Publication Year: <?= htmlspecialchars($book['publication_year']) ?><br/>
                    Description: <p><?= substr(htmlspecialchars($book['description']), 0, 90) ?>...</p>
                </div>
                <div class="d-flex justify-content-between mt-2">
                <a href="editBook.php?id=<?= htmlspecialchars($book['id']) ?>" class="btn btn-success btn-sm">Edit</a>
                <a href="deleteBook.php?id=<?= htmlspecialchars($book['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?');">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>    
    </div>
</div>
</main>

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

<script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
