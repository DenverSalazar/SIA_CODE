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
    <title>User Profile</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .profile-container {
            max-width: 1220px;
            margin-left: 280px;
        }
        
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .profile-header {
            background: #C1E8FF;
            padding: 30px;
            color: black;
            text-align: center;
        }
        
        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            margin-bottom: 15px;
            object-fit: cover;
        }
        
        .profile-info {
            padding: 30px;
        }
        
        .info-item {
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 10px;
            background: #f8f9fa;
        }
        
        .info-label {
            color: #8898aa;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .info-value {
            color: #32325d;
            font-size: 1.1rem;
            font-weight: 500;
        }
        
        .edit-btn {
            background: #5E72E4;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .edit-btn:hover {
            background: #324cdd;
            transform: translateY(-2px);
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
                        $current_page = basename($_SERVER['PHP_SELF']);
                        $nav_items = [
                            'homeAdmin.php' => ['icon' => 'fas fa-chart-bar', 'text' => 'Dashboard'],
                            'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                            'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                            'bookAdmin.php' => ['icon' => 'fas fa-book', 'text' => 'Modules'],
                            'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                            'admin_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                            'admin_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
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
                    <a href="admin_edit_profile.php" class="edit-btn link-underline link-underline-opacity-0">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>