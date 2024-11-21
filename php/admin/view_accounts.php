<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}

// Check if student ID is provided in URL
if(!isset($_GET['id'])) {
    header("Location: accounts.php");
    exit();
}

$student_id = mysqli_real_escape_string($con, $_GET['id']);

// Fetch student details
$query = "SELECT * FROM students WHERE id = '$student_id'";
$result = mysqli_query($con, $query);

if(mysqli_num_rows($result) == 0) {
    header("Location: accounts.php");
    exit();
}

$student = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student Account</title>
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
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
    .document-preview {
        max-width: 100%;
        height: auto;
        margin-top: 10px;
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
    }
    .document-preview img,
    .document-preview embed {
        max-width: 100%;
        margin: 0 auto;
        display: block;
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
        border-top: 1px solid #eee;
        padding-top: 20px;
    }
    .action-buttons .btn {
        padding: 8px 20px;
    }
    .document-title {
        color: #052659;
        margin-bottom: 15px;
        font-size: 1.2em;
    }
    .document-container {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 20px;
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
                $active_class = ($current_page === $page || ($current_page === 'view_accounts.php' && $page === 'accounts.php')) ? 'active' : '';
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
                <h2><i class="fas fa-user-graduate"></i> Student Account Details</h2>
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
                            <span class="info-label">Student ID:</span>
                            <span class="info-value"><?php echo htmlspecialchars($student['id']); ?></span>
                        </div>
                        <div class="info-group">
                            <span class="info-label">First Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($student['fName']); ?></span>
                        </div>
                        <div class="info-group">
                            <span class="info-label">Last Name:</span>
                            <span class="info-value"><?php echo htmlspecialchars($student['lName']); ?></span>
                        </div>
                        <div class="info-group">
                            <span class="info-label">Email:</span>
                            <span class="info-value"><?php echo htmlspecialchars($student['email']); ?></span>
                        </div>
                       <div class="info-group">
                        <span class="info-label">Status:</span>
                        <span class="info-value">
                            <?php if(isset($student['is_accepted'])): ?>
                                <?php if($student['is_accepted'] == 1): ?>
                                    <span class="status-badge status-accepted">
                                        <i class="fas fa-check-circle"></i> Accepted
                                    </span>
                                <?php else: ?>
                                    <span class="status-badge status-pending">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock"></i> Pending
                                </span>
                            <?php endif; ?>
                        </span>
                    </div>

                <div class="document-section">
                    <h4 class="document-title"><i class="fas fa-id-card"></i> Student ID Document</h4>
                    <div class="document-container">
                        <?php if(!empty($student['student_id'])): ?>
                            <div class="document-preview">
                                <?php
                                $file_path = '../../uploads/' . $student['student_id'];
                                if (file_exists($file_path)):
                                    $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                                    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                        <img src="<?php echo htmlspecialchars($file_path); ?>" class="img-fluid" alt="Student ID Document">
                                    <?php elseif ($file_extension == 'pdf'): ?>
                                        <embed src="<?php echo htmlspecialchars($file_path); ?>" type="application/pdf" width="100%" height="500px" />
                                    <?php else: ?>
                                        <p>Unsupported file type. <a href="<?php echo htmlspecialchars($file_path); ?>" target="_blank">Download file</a></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-danger">File not found: <?php echo htmlspecialchars($file_path); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-danger">No document available</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="document-section">
                    <h4 class="document-title"><i class="fas fa-file-alt"></i> Certificate of Registration (COR)</h4>
                    <div class="document-container">
                        <?php if(!empty($student['cor'])): ?>
                            <div class="document-preview">
                                <?php
                                $cor_path = '../../uploads/' . $student['cor'];
                                if (file_exists($cor_path)):
                                    $file_extension = strtolower(pathinfo($cor_path, PATHINFO_EXTENSION));
                                    if (in_array($file_extension, ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                        <img src="<?php echo htmlspecialchars($cor_path); ?>" class="img-fluid" alt="Certificate of Registration">
                                    <?php elseif ($file_extension == 'pdf'): ?>
                                        <embed src="<?php echo htmlspecialchars($cor_path); ?>" type="application/pdf" width="100%" height="500px" />
                                    <?php else: ?>
                                        <p>Unsupported file type. <a href="<?php echo htmlspecialchars($cor_path); ?>" target="_blank">Download file</a></p>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-danger">File not found: <?php echo htmlspecialchars($cor_path); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <span class="text-danger">No COR document available</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- DELETE ACCOUNT -->
        <div class="action-buttons">
                    <a href="delete_account.php?id=<?php echo $student['id']; ?>&type=student" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this student account?')">
                        <i class="fas fa-trash-alt"></i> Delete Account
                    </a>
                </div>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>