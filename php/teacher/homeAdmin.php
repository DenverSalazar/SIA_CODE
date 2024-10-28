<?php
    include('../../php/db_config.php');
    session_start();

    date_default_timezone_set('Asia/Manila');
    $currentTime = date('H:i:s'); 

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
    <style>
        body{
            background-color: white;
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

        .sidebar .logout-link:hover{
            color: red;
        }
        

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

            .welcome h1 {
                font-size: 1.5rem;
            }

            .card-container {
                padding: 0 5px;
            }
        }
        .sidebar-title {
            filter: brightness(0) invert(1);
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

    <main class="content">
        <div id="content">
            <section>
            <div class="welcome d-flex flex-column flex-md-row align-items-center justify-content-between" style="background-color:#C1E8FF">
                <div class="text mb-3 mb-md-0">
                    <h1 style="font-weight: bold">Welcome back, <?php echo $res_fName . " " . $res_lName; ?>!</h1>
                    <p style="font-size: 1.2rem;">Current Time: <span id="currentTime"><?php echo $currentTime; ?></span></p>
                </div>
                <div class="circle-person">
                    <!-- img DITO -->
                </div>
            </div>
            <script>
                
                function updateClock() {
                    
                    const now = new Date();
                    
                    const options = {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true,
                        timeZone: 'Asia/Manila'
                    };
                 
                    const formatter = new Intl.DateTimeFormat('en-US', options);
                    const timeString = formatter.format(now);
                    
                    document.getElementById('currentTime').textContent = timeString;
                }

                updateClock();
                setInterval(updateClock, 1000);
            </script>

                <div style="padding-top: 20px;">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card-container">
                                <div class="card card-books" style="width: 18rem; background-color:#5483b3">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Bookshelf</h5>
                                        <hr class="divider">
                                        <a href="bookAdmin.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-container">
                                <div class="card card-dashboard" style="width: 18rem; background-color:#5483b3">
                                    <div class="card-body">
                                        <h5 class="card-title text-center">Accounts</h5>
                                        <hr class="divider">
                                        <a href="dashboard.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card-container">
                                <div class="card card-feedback" style="width: 18rem; background-color:#5483b3">
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
                                <div class="card card-profile" style="width: 18rem; background-color:#5483b3">
                                    <div class="card-body">
                                        <h5 class="card-title text-center"> My Personal Info</h5>
                                        <hr class="divider">
                                        <a href="../../php/profile.php" class="btn btn-view w-100">View <i class="fas fa-arrow-circle-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    
    <!-- Add this new script for responsive functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const content = document.querySelector('.content');
            const sidebarToggle = document.querySelector('.sidebar-toggle');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                content.classList.toggle('sidebar-active');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !sidebarToggle.contains(event.target)) {
                        sidebar.classList.remove('active');
                        content.classList.remove('sidebar-active');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('active');
                    content.classList.remove('sidebar-active');
                }
            });
        });
    </script>
</body>
</html>