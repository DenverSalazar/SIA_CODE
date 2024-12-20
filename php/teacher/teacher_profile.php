<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];

// Fetch current user data
if($role == 'student'){
    $query = mysqli_query($con,"SELECT * FROM students WHERE id = '$id'");
} else if($role == 'teacher'){
    $query = mysqli_query($con,"SELECT * FROM teacher WHERE id = '$id'");
}

$result = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Profile</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/admin_profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
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
                            'teacher_home.php' => ['icon' => 'fas fa-home', 'text' => 'Home'],
                            'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                            'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                            'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                            'teacher_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                            'teacher_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
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
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <?php if(isset($result['profile_picture']) && !empty($result['profile_picture'])): ?>
                    <img src="../../uploads/profiles/<?php echo htmlspecialchars($result['profile_picture']); ?>" 
                         alt="Profile Picture" class="profile-img">
                <?php else: ?>
                    <img src="../../img/admin-icon.jpg" alt="Default Profile Picture" class="profile-img">
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($result['fName'] . ' ' . $result['lName']); ?></h2>
                <p><?php echo ucfirst($role); ?></p>
            </div>
            
            <div class="profile-info">
                <div class="info-item">
                    <div class="info-label">Email</div>
                    <div class="info-value"><?php echo htmlspecialchars($result['email']); ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">First Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($result['fName']); ?></div>
                </div>
                
                <div class="info-item">
                    <div class="info-label">Last Name</div>
                    <div class="info-value"><?php echo htmlspecialchars($result['lName']); ?></div>
                </div>
                
                <div class="text-center mt-4 ">
                    <a href="teacher_edit_profile.php" class="edit-btn link-underline link-underline-opacity-0">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>