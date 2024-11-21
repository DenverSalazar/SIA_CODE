<?php
    include('../../php/db_config.php');
    session_start();


    function getProfilePicturePath($profile_picture) {
      if (isset($profile_picture) && !empty($profile_picture)) {
          return "../../../uploads/profiles/" . htmlspecialchars($profile_picture);
      } else {
          return "/SIA/img/default-profile.png";
      }
  }

  // Fetch user data including profile picture
      $id = $_SESSION['id'];
      $query = mysqli_query($con, "SELECT * FROM students WHERE id = '$id'");
      $result = mysqli_fetch_assoc($query);
      $res_profile_picture = $result['profile_picture'];
      $res_fName = $result['fName'];
      $res_lName = $result['lName'];

      if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'student') {
        header("Location: ../../login.php");
        exit();
    }
    
    // Check if student's account is accepted
    $student_id = $_SESSION['id'];
    $check_query = mysqli_query($con, "SELECT is_accepted FROM students WHERE id = '$student_id'");
    $student = mysqli_fetch_assoc($check_query);
    
    if(!$student || $student['is_accepted'] == 0) {
        session_destroy();
        header("Location: ../../login.php?error=not_accepted");
        exit();
    }


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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
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
        <h2 class="text-center mb-5" style="font-size: 3rem; color: #333;">Featured Modules</h2>
        <div class="row">
            <?php foreach ($featured_books as $book): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm">
                        <img src="../teacher/uploads/<?= htmlspecialchars($book['cover_image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($book['title']) ?>" style="height: 300px; object-fit: cover;">
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
        </div>
        
        <div class="text-center mt-4">
            <a href="books.php" class="btn btn-primary btn-lg">Explore All Modules</a>
        </div>
    </div>
</section>
</section>

<section class="services-section py-5" style="background-color: #ffffff;">
  <div class="container">
    <h2 class="text-center mb-5" style="font-size: 3rem; color: #333;">Our System Services</h2>
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
          <li><a href="books.php">Modules</a></li>
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
          <p><i class="fas fa-map-marker-alt me-2"></i>BSU Lipa Batangas</p>
          <p><i class="fas fa-phone me-2"></i>0985-982-2196</p>
          <p><i class="fas fa-envelope me-2"></i>readiculous@gmail.com</p>
        </address>
      </div>
    </div>
  </div>
  <div class="footer-bottom text-center mt-4" style="background-color: transparent;">
    <div class="container">
      <p class="footer-copyright">&copy; 2024 Readiculous Library Management System. All rights reserved.</p>
    </div>
  </div>
</footer>

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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>