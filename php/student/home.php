<?php
    include('../../php/db_config.php');
    session_start();

    if(!isset($_SESSION['valid'])){
        header("Location: ../../login.php");
    }

    if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $role = $_SESSION['role'];

        if($role == 'student'){
            $query = mysqli_query($con,"SELECT * FROM students WHERE id = '$id'");
        } else if($role == 'teacher'){
            $query = mysqli_query($con,"SELECT * FROM teacher WHERE id = '$id'");
        }

        while($result = mysqli_fetch_assoc($query)){
            $res_fName = $result['fName'];
            $res_lName = $result['lName'];
            $res_email = $result['email'];
        }
    } else {
        echo "Error: ID is not set or empty.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HOMEPAGE</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homestyle.css">
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

  <main>
    <!-- SECTION 1 -->
    <section class="text-light" id="Home" style="background-image: url(../../img/library.png); background-repeat: no-repeat; background-size: cover;">
      <div class="container vh-100">
          <div class="row align-items-center">
              <div class="text-container text-center" style="padding-top: 130px; padding-bottom: 150px;">
                <h1 class="hs-title" style="color:white">Welcome <?php echo $res_fName, " "; echo $res_lName; ?> to Readiculous Library Management System</h1>
                <p class="hs-des">Complete and Automated Library Management Software</p>
              </div>
          </div>
      </div>   
    </section>
    
    <section class="books-section py-5" style="background-color: #f8f9fa;">
          <?php
      // Fetch featured books
      $featured_books_query = mysqli_query($con, "SELECT * FROM books LIMIT 3");
      $featured_books = mysqli_fetch_all($featured_books_query, MYSQLI_ASSOC);
      ?>
    <div class="container">
        <h2 class="text-center mb-5" style="font-size: 3rem; color: #333;">Featured Books</h2>
        <div class="row">
            <?php foreach ($featured_books as $book): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="../teacher/uploads/<?= htmlspecialchars($book['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>" style="height: 300px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($book['title']) ?></h5>
                            <p class="card-text">By <?= htmlspecialchars($book['author']) ?></p>
                            <p class="card-text"><?= substr(htmlspecialchars($book['description']), 0, 100) ?>...</p>
                            <a href="read_more.php?book_id=<?= $book['id'] ?>" class="btn btn-outline-primary">Read More</a>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="books.php" class="btn btn-primary btn-lg">Explore All Books</a>
        </div>
    </div>
</section>
</section>

<section class="services-section py-5" style="background-color: #ffffff;">
  <div class="container">
    <h2 class="text-center mb-5" style="font-size: 3rem; color: #333;">Our Library Services</h2>
    <div class="row">
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-card text-center p-4">
          <h3>Easy Book Search</h3>
          <p>Find your next read quickly with our advanced search system.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-card text-center p-4">
          <h3>Online Reading</h3>
          <p>Access our extensive e-book collection anytime, anywhere.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-card text-center p-4">
          <h3>Give Feedback</h3>
          <p>Give feedback about our modules and system to make us informed.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-card text-center p-4">
          <h3>Easy to Use</h3>
          <p>Join our easy to use online Library Management System.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-card text-center p-4">
          <h3>Account Management</h3>
          <p>Get timely edit your personal infromations anytime, anywhere.</p>
        </div>
      </div>
      <div class="col-lg-4 col-md-6 mb-4">
        <div class="service-card text-center p-4">
          <h3>Digital Resources</h3>
          <p>Access a collection of digital materials and books online.</p>
        </div>
      </div>
    </div>
  </div>
</section>
</main>
<footer class="footer-section py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-4 mb-lg-0">
        <img src="../../img/logo.png" alt="Readiculous" class="footer-logo mb-3" style="max-width: 200px;">
        <p class="footer-description">Readiculous: Your gateway to a world of knowledge and imagination. Explore, learn, and grow with our comprehensive library management system.</p>
      </div>
      <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
        <h5 class="footer-heading">Quick Links</h5>
        <ul class="footer-links list-unstyled">
          <li><a href="#Home">Home</a></li>
          <li><a href="books.php">Books</a></li>
          <li><a href="../../php/profile.php">Profile</a></li>
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
  <script src="../../js/bootstrap.bundle.min.js" ></script>
  <script src="../../js/bootstrap.min.js"></script>
</body>
</html>