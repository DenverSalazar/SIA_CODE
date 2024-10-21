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
    <title>ADMIN HOMEPAGE</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/SIA/css/adminstyle.css">
   
</head>
<body>
  <!-- HEADER -->
  <header>
    <nav class="navbar navbar-light fixed-top">
        <div class="container">
          <a class="navbar-brand"><img src="../../img/logo.png" alt="Readiculous" width=""></a>
          <form class="d-flex">
           
          </form>
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
  <main class="content" style="padding-top: 70px;" >
    <div id="content">
        <div class="title" style="padding-left: 20px;" >
            Library Management
        </div>
    <section>
    <div class="welcome d-flex flex-column flex-md-row align-items-center justify-content-between">
    <div class="text mb-3 mb-md-0">
        <h1 style="font-weight: bold">Welcome, <?php echo $res_fName, " "; echo $res_lName; ?>!</h1>
          </div>
          <div class="circle-person">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-gear" viewBox="0 0 16 16">
          <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0"/>
          </svg>
          </div>
      </div>
        <div style="padding-top: 20px;" > 
            <div class="row">
                <div class="col-md-3">
                    <div class="card-container">
                        <div class="card card-books" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title text-center">Books</h5>
                                <hr class="divider">
                                <a href="bookAdmin.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-container">
                        <div class="card card-dashboard" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title text-center">Dashboard</h5>
                                <hr class="divider">
                                <a href="dashboard.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-container">
                        <div class="card card-feedback" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title text-center">Feedbacks</h5>
                                <hr class="divider">
                                <a href="admin_feedback.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card-container">
                        <div class="card card-profile" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title text-center">Your Profile</h5>
                                <hr class="divider">
                                <a href="../../php/profile.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

 
  <script src="../../js/bootstrap.bundle.min.js" ></script>
  <script src="../../js/bootstrap.min.js"></script>
</body>
</html>