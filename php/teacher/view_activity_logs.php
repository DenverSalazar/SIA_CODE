<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

// Get the student ID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = mysqli_real_escape_string($con, $_GET['id']);
} else {
    header("Location: accounts.php");
    exit();
}

// Query to fetch student information
$student_query = mysqli_query($con, "SELECT fName, lName FROM students WHERE id = '$student_id'");
$student_info = mysqli_fetch_assoc($student_query);

// Query to fetch activity logs for the specific student
$activity_query = mysqli_query($con, "SELECT * FROM activity_logs WHERE student_id = '$student_id' ORDER BY timestamp DESC");

function getActionIcon($action) {
    switch ($action) {
        case 'login':
            return '<i class="fas fa-sign-in-alt text-success"></i>';
        case 'logout':
            return '<i class="fas fa-sign-out-alt text-danger"></i>';
        case 'view_book':
        case 'view_module':
            return '<i class="fas fa-book-open text-info"></i>';
        case 'download_book':
        case 'download_module':
            return '<i class="fas fa-download text-primary"></i>';
        case 'update_profile':
            return '<i class="fas fa-user-edit text-warning"></i>';
        default:
            return '<i class="fas fa-info-circle"></i>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs of <?php echo htmlspecialchars($student_info['fName'] . ' ' . $student_info['lName']); ?></title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/activity_logs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<style>
    .sidebar{
        background-color: #052659;
    }
</style>
<body>
        <div class="sidebar">
            <h5 class="sidebar-title mb-5">
                <img src="../../img/logo.png" alt="Logo" width="190" height="20">
            </h5>
            <ul class="nav flex-column">
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
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
                    $active_class = ($current_page === $page || ($current_page === 'view_activity_logs.php' && $page === 'activity_logs.php')) ? 'active' : '';
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
        <main class="content">
            <div class="container mt">
            <div class="container">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="bookshelf-title">Activity Logs of <?php echo htmlspecialchars($student_info['fName'] . ' ' . $student_info['lName']); ?></h1>
                            <a href="activity_logs.php" class="btn btn-cancel">Back</a>
                        </div>
                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Details</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($activity_query) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($activity_query)): ?>
                                    <tr>
                                        <td>
                                            <?php echo getActionIcon($row['action']); ?>
                                            <?php echo ucfirst(htmlspecialchars($row['action'])); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['details']); ?></td>
                                        <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No activity logs found for this student.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>