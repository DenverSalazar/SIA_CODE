<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}

// Check if teacher ID is provided in URL
if(!isset($_GET['id'])) {
    header("Location: accounts.php");
    exit();
}

$teacher_id = mysqli_real_escape_string($con, $_GET['id']);

// Fetch teacher details
// Fetch teacher details
$query = "SELECT * FROM teacher WHERE id = '$teacher_id'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: accounts.php");
    exit();
}

$teacher = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Teacher Account</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
</head>
<style>
    .sidebar{
        background-color: #052659;
    }
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }
    .account-details {
        background-color: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        margin-top: 20px;
    }
    .profile-section {
        display: flex;
        align-items: start;
        gap: 30px;
        margin-bottom: 30px;
    }
    .profile-image {
        width: 200px;
        height: 200px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #052659;
    }
    .info-section {
        flex: 1;
    }
    .info-group {
        margin-bottom: 20px;
    }
    .info-label {
        font-weight: bold;
        color: #052659;
        width: 150px;
        display: inline-block;
    }
    .info-value {
        color: #333;
    }
    .document-section {
        margin-top: 30px;
    }
    .document-preview {
        max-width: 100%;
        height: auto;
        margin-top: 10px;
    }
    .status-badge {
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.9em;
        display: inline-block;
    }
    .status-accepted {
        background-color: #d4edda;
        color: #155724;
    }
    .status-pending {
        background-color: #fff3cd;
        color: #856404;
    }
    .action-buttons {
        margin-top: 30px;
        display: flex;
        gap: 10px;
    }
    .action-buttons .btn {
        padding: 8px 20px;
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
                'admin_home.php' => ['icon' => 'fas fa-chart-bar', 'text' => 'Dashboard'],
                'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
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

    <main class="content">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="fas fa-user-circle"></i> Teacher Account Details</h2>
                <a href="accounts.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Accounts
                </a>
            </div>

            <div class="account-details">
                <div class="profile-section">
                    <div class="profile-image-container">
                        <img src="../../img/default-profile.png" alt="Profile" class="profile-image">
                    </div>
                    <div class="info-section">
                        <div class="info-group">
                            <span class="info-label">Teacher ID:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacher['id']); ?></span>
                        </div>
                        <div class="info-group">
                            <span class="info-label">First Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacher['fName']); ?></span>
                        </div>
                        <div class="info-group">
                            <span class="info-label">Last Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacher['lName']); ?></span>
                        </div>
                        <div class="info-group">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($teacher['email']); ?></span>
                        </div>
                        <div class="info-group">
                            <span class="info-label">Account Status:</span>
                            <span class="status-badge <?php echo $teacher['is_accepted'] ? 'status-accepted' : 'status-pending'; ?>">
                                <?php echo $teacher['is_accepted'] ? 'Accepted' : 'Pending'; ?>
                            </span>
                        </div>
                    </div>
                </div>

               <div class="document-section">
    <h4><i class="fas fa-file-alt"></i> Teacher ID Document</h4>
    <?php if (!empty($teacher['teacher_id'])): ?>
        <div class="document-preview">
            <?php
            $file_path = '../' . $teacher['teacher_id']; // Ensure this path is correct
            if (file_exists($file_path)): // Check if the file exists
                $file_extension = pathinfo($file_path, PATHINFO_EXTENSION);
                if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                    <img src="<?php echo htmlspecialchars($file_path); ?>" class="img-fluid" alt="Teacher ID Document">
                <?php else: ?>
                    <iframe src="<?php echo htmlspecialchars($file_path); ?>" width="100%" height="500px" frameborder="0"></iframe>
                <?php endif; ?>
            <?php else: ?>
                <span class="text-danger">File not found at path: <?php echo htmlspecialchars($file_path); ?></span>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <span class="text-danger">No document available</span>
    <?php endif; ?>
</div>

                <!-- <div class="action-buttons">
                    <a href="delete_account.php?id=<?php echo $teacher['id']; ?>&type=teacher" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this teacher account?')">
                        <i class="fas fa-trash-alt"></i> Delete Account
                    </a>
                </div> -->
            </div>
        </div>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>