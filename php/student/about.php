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

if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/about.css">
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
    <header class="head pt-4" >
        <h1>About Us</h1>
    </header>
    <main class="container">
        <section class="intro">
            <h2>Welcome to Readiculous</h2>
            <div class="about-text">
                        <p class="lead">Welcome to Readiculous! We are dedicated to transforming the learning experience in Information Technology through our innovative online learning system. Our mission is to empower learners with the knowledge and skills needed to thrive in the rapidly evolving tech landscape. At Readiculous, we understand that the journey of learning can be both exciting and challenging, which is why we've crafted a platform that makes education accessible and engaging for everyone.</p>

                        <p class="lead">Our comprehensive courses are designed by industry experts who bring a wealth of experience and knowledge to the table. We blend interactive learning methods with real-world applications to ensure that our students not only grasp theoretical concepts but also understand how to apply them in practical scenarios. Join our vibrant community of learners, where collaboration and support thrive. Explore, learn, and grow with us in an environment that fosters creativity and encourages exploration. We believe in the power of learning together, and our dedicated instructors are always here to guide you. Whether you're a beginner or looking to advance your skills, Readiculous offers a supportive environment tailored to your unique learning journey.</p>
                    </div>
        </section>

        <section class="features">
            <h2>Our Services</h2>
            <ul>
                <li>Easy Book Search</li>
                <li>Online Reading</li>
                <li>Give Feedback</li>
                <li>Easy to Use</li>
                <li>Account Management</li>
                <li>Digital Resources</li>
            </ul>
        </section>

        <section class="team">
    <h2>Meet the Team</h2>
    <p>Our dedicated team is passionate about reading and technology, committed to enhancing your reading experience.</p>
    <div class="container marketing">
        <div class="row">
            <div class="col-lg-3 text-center">
                <div class="rounded-circle img-container">
                    <img src="/SIA/img/jannie.jpg" alt="Zurbano Jannie B." />
                </div>
                <h2 class="fw-normal">ZURBANO JANNIE B.</h2>
                <p>Documentation / Assistant Leader.</p>
                <p style="font-style: italic; font-family:'Times New Roman', Times, serif" >"Online learning empowers knowledge without borders."</p>
            </div>
            
            <div class="col-lg-3 text-center">
                <div class="rounded-circle img-container">
                    <img src="/SIA/img/denver.jpg" alt="Salazar Denver T." />
                </div>
                <h2 class="fw-normal">SALAZAR DENVER T.</h2>
                <p>Web Developer / Back-end / Documentation.</p>
                <p style="font-style: italic; font-family:'Times New Roman', Times, serif" >"Education in the digital age: learn anywhere, grow everywhere."</p>
            </div>

            <div class="col-lg-3 text-center">
                <div class="rounded-circle img-container">
                    <img src="/SIA/img/alleon.jpg" alt="Perez Alleon John I." />
                </div>
                <h2 class="fw-normal">PEREZ ALLEON JOHN I.</h2>
                <p>Leader / Front-End / Documentation.</p>
                <p style="font-style: italic; font-family:'Times New Roman', Times, serif" >"Learning never stops, even when you're online."</p>
            </div>

            <div class="col-lg-3 text-center">
                <div class="rounded-circle img-container">
                    <img src="/SIA/img/berna.png" alt="Rodriguez Bernadette Anne H." />
                </div>
                <h2 class="fw-normal">RODRIGUEZ BERNADETTE ANNE H.</h2>
                <p>Documentation.</p>
                <p style="font-style: italic; font-family:'Times New Roman', Times, serif" >"Online learning turns curiosity into endless possibilities."</p>
            </div>
        </div>
    </div>
</section>
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
    <div class="footer-bottom text-center">
      <div class="container">
        <hr class="footer-divider">
    </div>
    </div>
        <p>&copy; <?php echo date("Y"); ?> Readiculous Library Management System. All rights reserved.</p>
    </footer>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
