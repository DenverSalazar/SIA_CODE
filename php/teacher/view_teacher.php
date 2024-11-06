<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}

if(!isset($_GET['id'])){
    header("Location: accounts.php");
}

$teacher_id = $_GET['id'];
$query = mysqli_query($con, "SELECT * FROM teacher WHERE id = '$teacher_id'");
$teacher = mysqli_fetch_assoc($query);

// Fetch the total count of books
$books_query = mysqli_query($con, "SELECT COUNT(*) as book_count FROM books");
$books_count = mysqli_fetch_assoc($books_query)['book_count'];

// Fetch the count of students
$students_query = mysqli_query($con, "SELECT COUNT(*) as student_count FROM students");
$students_count = mysqli_fetch_assoc($students_query)['student_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Teacher Profile</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <style>
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .profile-header {
            background-color: #052659;
            color: #fff;
            padding: 30px;
            text-align: center;
        }
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid #fff;
            margin-bottom: 20px;
            object-fit: cover;
        }
        .profile-name {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .profile-role {
            font-size: 18px;
            opacity: 0.8;
        }
        .profile-body {
            padding: 30px;
        }
        .info-group {
            margin-bottom: 20px;
        }
        .info-label {
            font-weight: bold;
            color: #052659;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #052659;
        }
        .stat-label {
            font-size: 14px;
            color: #6c757d;
        }
        .sidebar{
        background-color: #052659;
    }
    </style>
</head>
<body>
    <div class="sidebar">
        <h5 class="sidebar-title mb-5">
            <img src="../../img/logo.png" alt="Logo" width="190" height="20">
        </h5>
        <ul class="nav flex-column">
            <?php
            $nav_items = [
                'teacher_home.php' => ['icon' => 'fas fa-chart-bar', 'text' => 'Dashboard'],
                'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                'teacher_book.php' => ['icon' => 'fas fa-book', 'text' => 'Modules'],
                'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                'teacher_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                'teacher_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
            ];

            foreach ($nav_items as $page => $item) {
                echo "<li class='nav-item'>
                        <a class='nav-link' href='{$page}'>
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

    <main class="content">
        <div class="container">
            <div class="profile-container">
                <div class="profile-header">
                    <img src="../../img/default-profile.png" alt="Profile Picture" class="profile-img">
                    <h2 class="profile-name"><?php echo htmlspecialchars($teacher['fName'] . ' ' . $teacher['lName']); ?></h2>
                    <p class="profile-role">Teacher</p>
                </div>
                <div class="profile-body">
                    <div class="info-group">
                        <p><span class="info-label">Email:</span> <?php echo htmlspecialchars($teacher['email']); ?></p>
                    </div>
                    <div class="info-group">
                        <p><span class="info-label">Department:</span> <?php echo htmlspecialchars($teacher['department'] ?? 'Not specified'); ?></p>
                    </div>
                    <div class="stats">
                        <div class="stat-item">
                            <div class="stat-value"><?php echo $books_count; ?></div>
                            <div class="stat-label">Total Modules</div>
                        </div>
                        <div class="stat-item">
                            <div class="stat-value"><?php echo $students_count; ?></div>
                            <div class="stat-label">Total Students</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-center mt-4">
                <a href="accounts.php" class="btn btn-primary"><i class="fas fa-arrow-left mr-2"></i>Back to Accounts</a>
            </div>
        </div>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>