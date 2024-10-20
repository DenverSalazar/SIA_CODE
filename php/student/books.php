<?php
session_start();
include('../../php/db_config.php');

if(!isset($_SESSION['valid'])){
    header("Location: ../../index.html");
}

// Fetch books from database (you'll need to implement this)
// $books = fetchBooksFromDatabase();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Books - Readiculous Library</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
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
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="../../img/logo.png" alt="Readiculous" width="150">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="books.php">Books</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../php/profile.php">Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../../php/logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container" style="margin-top: 100px;">
        <h1 class="text-center mb-5">Explore Our Book Collection</h1>
        
        <div class="search-bar mb-5">
            <form class="d-flex">
                <input class="form-control me-2" type="search" placeholder="Search books..." aria-label="Search">
                <button class="btn btn-outline-primary" type="submit">Search</button>
            </form>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <select class="form-select" aria-label="Filter by genre">
                <option selected>Filter by genre</option>
                    <option value="1">Fiction</option>
                    <option value="2">Non-fiction</option>
                    <option value="3">Mystery</option>
                    <option value="4">Science Fiction</option>
                    <option value="5">Romance</option>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" aria-label="Sort by">
                    <option selected>Sort by</option>
                    <option value="1">Title (A-Z)</option>
                    <option value="2">Title (Z-A)</option>
                    <option value="3">Author (A-Z)</option>
                    <option value="4">Publication Date (Newest)</option>
                    <option value="5">Publication Date (Oldest)</option>
                </select>
            </div>
            <div class="col-md-4">
                <select class="form-select" aria-label="Availability">
                    <option selected>Availability</option>
                    <option value="1">All Books</option>
                    <option value="2">Available Now</option>
                    <option value="3">Coming Soon</option>
                </select>
            </div>
        </div>

        <div class="row">
            <?php
            // Placeholder data - replace with actual database fetch
            $books = [
                ['title' => 'The Great Gatsby', 'author' => 'F. Scott Fitzgerald', 'cover' => '../../img/book1.jpg'],
                ['title' => 'To Kill a Mockingbird', 'author' => 'Harper Lee', 'cover' => '../../img/book2.jpg'],
                ['title' => '1984', 'author' => 'George Orwell', 'cover' => '../../img/book3.jpg'],
                ['title' => 'Pride and Prejudice', 'author' => 'Jane Austen', 'cover' => '../../img/book4.jpg'],
                ['title' => 'The Catcher in the Rye', 'author' => 'J.D. Salinger', 'cover' => '../../img/book5.jpg'],
                ['title' => 'The Hobbit', 'author' => 'J.R.R. Tolkien', 'cover' => '../../img/book6.jpg'],
            ];

            foreach ($books as $book) {
                echo '<div class="col-lg-4 col-md-6 mb-4">
                    <div class="card book-card h-100">
                        <img src="' . $book['cover'] . '" class="card-img-top book-cover" alt="' . $book['title'] . '">
                        <div class="card-body">
                            <h5 class="card-title">' . $book['title'] . '</h5>
                            <p class="card-text">By ' . $book['author'] . '</p>
                            <a href="#" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>';
            }
            ?>
        </div>

        <nav aria-label="Book pagination" class="mt-5">
            <ul class="pagination justify-content-center">
                <li class="page-item disabled">
                    <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                </li>
                <li class="page-item active" aria-current="page">
                    <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                    <a class="page-link" href="#">Next</a>
                </li>
            </ul>
        </nav>
        </div>

    <footer class="bg-light text-center text-lg-start mt-5">
        <div class="container p-4">
            <div class="row">
                <div class="col-lg-6 col-md-12 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Readiculous Library</h5>
                    <p>
                        Discover a world of knowledge and imagination with our extensive collection of books.
                        From classics to contemporary bestsellers, we have something for every reader.
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Quick Links</h5>
                    <ul class="list-unstyled mb-0">
                        <li><a href="#!" class="text-dark">About Us</a></li>
                        <li><a href="#!" class="text-dark">Contact</a></li>
                        <li><a href="#!" class="text-dark">FAQ</a></li>
                        <li><a href="#!" class="text-dark">Privacy Policy</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase mb-0">Follow Us</h5>
                    <ul class="list-unstyled">
                        <li><a href="#!" class="text-dark"><i class="fab fa-facebook-f"></i> Facebook</a></li>
                        <li><a href="#!" class="text-dark"><i class="fab fa-twitter"></i> Twitter</a></li>
                        <li><a href="#!" class="text-dark"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="#!" class="text-dark"><i class="fab fa-linkedin-in"></i> LinkedIn</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2024 Readiculous Library. All rights reserved.
        </div>
    </footer>

    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script>
        // Add any custom JavaScript here
        document.addEventListener('DOMContentLoaded', function() {
            // Example: Add smooth scrolling to all links
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
        });
    </script>
</body>
</html>