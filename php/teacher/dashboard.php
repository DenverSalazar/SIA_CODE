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
    </style>
</head>
<body>
  <!-- HEADER -->
  <header>
    <nav class="navbar navbar-light fixed-top">
        <div class="container">
          <a class="navbar-brand"><img src="../../img/logo.png" alt="Readiculous" width=""></a>
          <form class="d-flex">
           
          </form>
          <button type="button" class="btn btn-secondary" onclick="location.href='homeAdmin.php'">Back</button>
          </div>
      </nav>
  </header>

  
  <main>
    <section>
        <div class="container vh-100 align-items-center justify-content-center d-flex">
            <div class="row">
                <div class="col-md-6">
                    <div class="card-container">
                        <div class="card card-students" style="width: 18rem;">
                            <div class="card-body">
                                <h5 class="card-title text-center">Total Students Registered</h5>
                                <hr class="divider">
                                <h6 class="card-subtitle mb-4 mt-4 text-center"><?php echo $total_students; ?></h6>
                                <a href="students.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card-container">
                        <div class="card card-teachers" style="width: 18rem;">
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