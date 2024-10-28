<?php
    session_start();
    include('../../php/db_config.php');

    if(!isset($_SESSION['valid'])){
      header("Location: ../../index.html");
     }

    $query = mysqli_query($con, "SELECT * FROM students");
    $total_students = mysqli_num_rows($query);

    $query2 = mysqli_query($con, "SELECT * FROM teacher");
    $total_teachers = mysqli_num_rows($query2);
?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>

        body{
            background-color: white ;
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
            background-color:#0056b3;
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

        @media (max-width: 768px) {
            .navbar-toggler {
                position: absolute;
                right: 20px; 
                top: 5px;
            }
        }
        .hs-title{
          font: 44px rubik, sans-serif;
          margin: 100px 0px 0px;
          font-weight: bold;
        }

        .hs-des{
          font: 24px rubik, sans-serif;
          margin: 10px 0px 16px;
        }
        .card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        }

        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.15);
        }

        .card-students {
            background-color: #28a745;
            color: white;
        }

        .card-teachers {
            background-color: #ffa500;
            color: white;
        }

        .card-body {
            padding: 20px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }

        .card-subtitle {
            font-size: 2.5rem;
            font-weight: bold;
        }

        .btn-view {
            background-color: rgba(255,255,255,0.2);
            border: none;
            color: white;
            transition: all 0.3s ease;
        }

        .btn-view:hover {
            background-color: rgba(255,255,255,0.3);
            color: white;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            transition: 0.3s;
            z-index: 1000;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 10px;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }

        .sidebar ul li a:hover {
            background-color: #495057;
            border-radius: 5px;
            padding: 8px;
        }

        .content {
            margin-left: 260px;
            padding: 20px;
            transition: 0.3s;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        /* Toggle Button Styles */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1001;
            background-color: #343a40;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .sidebar {
                left: -250px;
            }

            .sidebar.active {
                left: 0;
            }

            .content {
                margin-left: 0 !important;
                padding-top: 70px;
            }

            .sidebar-toggle {
                display: block;
                border-radius: 5px            }

            .content.sidebar-active {
                margin-left: 250px !important;
            }

            .card-container {
                margin-bottom: 20px;
            }

            .card {
                width: 100% !important;
            }

            .row {
                margin: 0;
            }

            .col-md-3 {
                padding: 0 10px;
            }
        }
        
        @media (max-width: 576px) {
            .welcome {
                padding: 10px;
            }

            .card-container {
                padding: 0 5px;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars" style="font-size: 24px;"></i>
    </button>
    <!-- Sidebar -->
    <div class="sidebar">
        <h5 class="sidebar-title"><img src="../../img/logo.png" alt="Readiculous" width="150"></h5>
        <ul class="nav flex-column">
            <li class="nav-item pt-5">
                <a class="nav-link active" href="homeAdmin.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </li>
            <hr class="featurette-divider">
            <li class="nav-item">
                <a class="nav-link" href="dashboard.php"><i class="fas fa-users"></i> Accounts</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../../php/profile.php"><i class="fas fa-user-circle"></i> Profile</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="bookAdmin.php"><i class="fas fa-book"></i> Books</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admin_feedback.php"><i class="fas fa-comments"></i> Feedbacks</a>
            </li>
            <li class="nav-item" style="padding-top: 205PX;" >
                <a class="nav-link logout-link" href="../../php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>        
        </ul>
    </div>
  
    <main>
    <section>
        <div class="container vh-100 d-flex align-items-center justify-content-center">
            <div class="row justify-content-center">
                <div class="col-md-6 d-flex justify-content-center">
                    <div class="card-container">
                        <div class="card card-students" style="width: 18rem; background-color:#5483b3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Total Students Registered</h5>
                                <hr class="divider">
                                <h6 class="card-subtitle mb-4 mt-4 text-center"><?php echo $total_students; ?></h6>
                                <a href="students.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex justify-content-center">
                    <div class="card-container">
                        <div class="card card-teachers" style="width: 18rem; background-color:#5483b3">
                            <div class="card-body">
                                <h5 class="card-title text-center">Total Teachers Registered</h5>
                                <hr class="divider">
                                <h6 class="card-subtitle mb-4 mt-4 text-center"><?php echo $total_teachers; ?></h6>
                                <a href="teachers.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

  <script src="../SIA/js/bootstrap.bundle.min.js" ></script>
  <script src="../SIA/js/bootstrap.min.js"></script>

</body>
</html>