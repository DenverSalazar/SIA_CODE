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
  body {
            background-color: #f0f2f5;
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
        .chat-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            height: calc(100vh - 40px);
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 15px;
            background-color: #f0f2f5;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .chat-box {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
        }
        .message {
            margin-bottom: 10px;
            max-width: 70%;
            padding: 10px;
            border-radius: 18px;
        }
        .message.admin {
            align-self: flex-end;
            background-color: #0084ff;
            color: white;
            margin-left: auto;
        }
        .message.student {
            align-self: flex-start;
            background-color: #e4e6eb;
            color: black;
        }
        .chat-input {
            padding: 15px;
            background-color: #f0f2f5;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .input-group {
            background-color: white;
            border-radius: 20px;
            overflow: hidden;
        }
        #message {
            border: none;
            border-radius: 20px;
            padding-left: 20px;
        }
        #message:focus {
            box-shadow: none;
        }
        .btn-send {
            background-color: transparent;
            border: none;
            color: #0084ff;
        }
        .btn-send:hover {
            color: #0056b3;
        }
        .sidebar-title {
            filter: brightness(0) invert(1);
            text-align: center;
        }
        /* Add these styles to your existing style section */
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
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 4px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 8px;
        }

        .sidebar-title {
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .sidebar-title img {
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
        }

        .sidebar-title img:hover {
            transform: scale(1.05);
        }

        /* Add a nice hover effect for the logout button */
        .sidebar .nav-link.text-danger:hover {
            background-color: rgba(220,53,69,0.1);
            color: #dc3545;
        }

        /* Add a subtle divider between nav items */
        .sidebar .nav-item {
            position: relative;
        }

        .sidebar .nav-item:not(:last-child)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            right: 16px;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        /* Make the last nav item (logout) stick to bottom */
        .sidebar .nav {
            height: calc(100vh - 100px);
            display: flex;
            flex-direction: column;
        }

        /* Adjust the content margin to accommodate the sidebar */
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        /* Add some nice transitions */
        .sidebar, .content {
            transition: all 0.3s ease;
        }

        /* Make the chat container take full height */
        .chat-container {
            height: calc(100vh - 40px);
            margin-right: 20px;
        }
    </style>
       <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="sidebar">
    <h5 class="sidebar-title mb-5">
        <img src="../../img/logo.png" alt="Logo" width="190" height="20">
    </h5>
    <ul class="nav flex-column">
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        $nav_items = [
            'homeAdmin.php' => ['icon' => 'fas fa-home', 'text' => 'Dashboard'],
            'dashboard.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
            'bookAdmin.php' => ['icon' => 'fas fa-book', 'text' => 'Bookshelf'],
            'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
            'admin_feedback.php' => ['icon' => 'fas fa-envelope', 'text' => 'Feedbacks'],
            '/SIA/php/profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
        ];

        foreach ($nav_items as $page => $item) {
            $active_class = ($current_page === $page) ? 'active' : '';
            echo "<li class='nav-item'>
                    <a class='nav-link {$active_class}' href='{$page}'>
                        <i class='{$item['icon']}'></i> {$item['text']}
                    </a>
                  </li>";
        }
        ?>
        <li class="nav-item mt-auto">
            <a class="nav-link text-danger" href="../../php/logout.php">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
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
                                <h5 class="card-title text-center">Total Admin Registered</h5>
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