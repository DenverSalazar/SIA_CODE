<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN HOMEPAGE</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
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
            display: flex;
            gap: 20px;
        }
        .book {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 200px;
            padding: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .book img {
            width: 100%;
            height: auto;
            border-bottom: 1px solid #ddd;
            margin-bottom: 10px;
        }
        .book-title {
            font-size: 16px;
            margin-bottom: 5px;
        }
        .book-status {
            color: #888;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .book-meta {
            font-size: 12px;
            color: #888;
        }
        .upload-btn {
            background-color: #007bff;
            color: #fff;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .upload-btn i {
            margin-right: 5px;
        }
</style>
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


    <main>
    
    <div class="container" style="margin-top: 80px;">
   <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="bookshelf-title">
     My Book Shelf
    </div>
    <button class="upload-btn">
     <i class="fas fa-upload">
     </i>
     Upload New Book
    </button>
   </div>
   <div class="books">
    <div class="book">
     <img alt="In Jamaica Where I Live book cover" height="300" src="https://storage.googleapis.com/a1aa/image/0sTLg3T4Pr6yMJFR0SzkFn86a1DIqtWpp9NTTe7j93dcVI0JA.jpg" width="200"/>
     <div class="book-title">
      In Jamaica Where I Live
     </div>
     <div class="book-status">
      Draft
     </div>
     <div class="book-meta">
      Last Modified: Oct 19, 2016
      <br/>
      Last Modified By: Dwayne Campbell
     </div>
    </div>
    <div class="book">
     <img alt="Ginger book cover" height="300" src="https://storage.googleapis.com/a1aa/image/skvjAekKawX3J6NmeWmSZqbcaXOidCdHlPvZ7PcyCx76qQoTA.jpg" width="200"/>
     <div class="book-title">
      Ginger (Business Opportunity Profile)
     </div>
     <div class="book-status">
      Draft
     </div>
     <div class="book-meta">
      Last Modified: Sep 3, 2016
      <br/>
      Last Modified By: Dwayne Campbell
     </div>
    </div>
   </div>
  </div>
    </main>


<script src="../../js/bootstrap.bundle.min.js" ></script>
  <script src="../../js/bootstrap.min.js"></script>
</body>
</html>