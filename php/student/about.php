<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
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
        background-color:#0056b3;
        border-color: #0056b3;
    }

    .offcanvas .nav-item {
        margin-bottom: 10px;
        text-align: start;
    }
    .offcanvas .nav-item-x{
        margin-bottom: 10px;
        text-align: start;
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
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        color: #333;
        line-height: 1.6;
        margin: 0;
        padding: 0;
    }

    header {
        background: #2c3e50;
        color: #fff;
        padding: 20px;
        text-align: center;
    }

    h1 {
        margin: 0;
        font-size: 2.5rem;
    }
    section {
        margin-bottom: 20px;
    }

    h2 {
        color: #3498db;
        font-size: 1.8rem;
    }

    ul {
        list-style-type: square;
        padding-left: 20px;
    }

    footer {
        text-align: center;
        padding: 10px;
        background: #333;
        color: #fff;
        position: relative;
        bottom: 0;
        width: 100%;
    }
    .team .container {
        margin-top: 20px;
    }

    .team .col-lg-3 {
        margin-bottom: 30px;
    }

    .team .text-center {
        text-align: center;
    }

    .team svg {
        margin-bottom: 10px;
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
    .img-container {
    width: 140px; 
    height: 140px;
    overflow: hidden;
    border-radius: 50%; 
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 0 auto 10px; 
    border: 3px solid #3498db; /* Rounded border */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Optional shadow */
}

.img-container img {
    width: 100%;
    height: auto; 
    object-fit: cover;
}


    .img-container img {
        width: 100%;
        height: auto; 
        object-fit: cover;
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
    <header class="pt-5" >
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
