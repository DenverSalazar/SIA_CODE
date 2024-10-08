<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link rel="stylesheet" href="../Activities/bootstrap-5.3.3-dist/css/bootstrap.min.css">
</head>
<style>
    body{
        background: #898989;
    }

    .btn{
        background: black;
    }
   
    .image-container img {
    max-width: 100%;
    height: auto;
    object-fit: cover;
    }
</style>
<body>
    
        <div class="container">
            <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
                <img src="../SIA/img/logo.png" style="height: min-content; width: 150px; " alt="Readiculous">
            </div>

            <ul class="nav col-8 col-md-auto mb-2 justify-content-center mb-md-0">
                <li><a href="#home" class="nav-link px-2" style="color: white;" >Home</a></li>
                <li><a href="#about" class="nav-link px-2" style="color: white;">About</a></li>
            </ul>

            <div class="col-md-3 text-end">
                <button type="button" class="btn  btn-outline-dark" style="color: white;" onclick="location.href= 'login.php'" >Login</button>
                <button type="button" class="btn btn-outline-dark" style="color: white;" onclick="location.href= 'register.php'">Sign-up</button>
            </div>
            </header>
        </div>


        <main class="container" id="home" >
            <div class="row featurette justify-content-center align-items-center">
            <div class="col-md-7">
                <h1 style="font-weight: 900; font-size: 59px" >Welcome to Readiculous!</h1>
                <p class="lead" style="font-weight: 400;" >Your Gateway to a World of Learning and Adventure! Here, we celebrate the joy of reading and the thrill of exploration, all within the inviting community of our library.</p>
            </div>
            <div class="col-md-5">
                <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500">
            </div>
            </div>

            <hr class="featurette-divider">

            <div class="row featurette" id="about" >
            <div class="col-md-7 order-md-2">
                <h1 style="font-weight: 500; font-size: 35px">About Us!</h1>

                <p class="lead">Welcome to Readiculous! We are dedicated to transforming the learning experience in Information Technology through our innovative online learning system. Our mission is to make high-quality IT education accessible and engaging for everyone, regardless of their background or skill level. </p>

                <p class="lead">Readiculous, we offer comprehensive courses designed by industry experts, blending interactive learning with real-world applications. Our flexible learning paths cater to both beginners and seasoned professionals, ensuring that every learner can find the right fit for their educational journey. </p>

               <p class="lead"> Join our vibrant community of learners, where collaboration and support thrive. At Readiculous, we’re committed to helping you unlock your potential and achieve your goals in the dynamic world of Information Technology. Explore, learn, and grow with us!</p>
            </div>
            <div class="col-md-5 order-md-1">
            <img class="bd-placeholder-img bd-placeholder-img-lg featurette-image img-fluid mx-auto" width="500" height="500"></div>
            </div>

            <hr class="featurette-divider">

            <footer class="container">
            <p class="float-end"><a href="#">Back to top</a></p>
            <p>&copy; READICULOUS 2024 Batangas State University - Lipa Campus
            </footer>
        </main>
    <script src="../Activities/bootstrap-5.3.3-dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>