<?php
include('../../php/db_config.php');
session_start();

function getFileType($filename) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    return in_array($extension, $imageExtensions) ? 'image' : 'pdf';
}

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
    <title>Student Details</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
</head>
<style>
    .sidebar{
        background-color: #052659;
    }
    .student-details {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .profile-header {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .detail-row {
        margin-bottom: 15px;
    }
    .label {
        font-weight: bold;
        color: #052659;
    }
    .back-button {
        margin-bottom: 20px;
    }
    .document-preview {
        max-width: 300px;
        margin-top: 10px;
    }
</style>
<body>
    <div class="sidebar">
        <!-- Same sidebar code as in accounts.php -->
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
                $active_class = ($current_page === $page || ($current_page === 'view_student.php' && $page === 'accounts.php')) ? 'active' : '';
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
            <div class="back-button">
                <a href="accounts.php" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Accounts
                </a>
            </div>

            <div class="student-details">
                <div class="profile-header">
                    <h2><i class="fas fa-user-graduate"></i> Student Details</h2>
                    <p class="text-muted">Student ID: <?php echo htmlspecialchars($student['id']); ?></p>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-row">
                            <span class="label">First Name:</span>
                            <span><?php echo htmlspecialchars($student['fName']);?></span>
                            
                        </div>
                        <div class="detail-row">
                            <span class="label">Last Name:</span>
                            <span><?php echo htmlspecialchars($student['lName']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Email:</span>
                            <span><?php echo htmlspecialchars($student['email']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Department:</span>
                            <span><?php echo htmlspecialchars($student['department']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Account Status:</span>
                            <span class="<?php echo $student['is_accepted'] ? 'text-success' : 'text-warning'; ?>">
                                <?php echo $student['is_accepted'] ? 'Accepted' : 'Pending'; ?>
                            </span>
                        </div>
                    </div>

                    <div class="col-md-6">
                    <div class="col-md-6">
                    <div class="detail-row">
                        <span class="label">Student ID Document:</span>
                        <?php if(!empty($student['student_id'])): ?>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#idModal">
                                View Full Document
                            </button>
                            <!-- Modal for Student ID -->
                            <div class="modal fade" id="idModal" tabindex="-1" aria-labelledby="idModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="idModalLabel">Student ID Document</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            $file_type = getFileType($student['student_id']);
                                            $file_path = str_replace('../', '../../', $student['student_id']);
                                            if ($file_type == 'image'):
                                            ?>
                                                <img src="<?php echo htmlspecialchars($file_path); ?>" 
                                                    class="img-fluid" alt="Student ID">
                                            <?php elseif ($file_type == 'pdf'): ?>
                                                <embed src="<?php echo htmlspecialchars($file_path); ?>" 
                                                    type="application/pdf" width="100%" height="600px" />
                                            <?php else: ?>
                                                <p>Unsupported file type</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">No ID uploaded</span>
                        <?php endif; ?>
                    </div>

                    <div class="detail-row">
                        <span class="label">COR Document:</span>
                        <?php if(!empty($student['cor'])): ?> <br>
                            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#corModal">
                                View Full Document
                            </button>
                            <!-- Modal for COR -->
                            <div class="modal fade" id="corModal" tabindex="-1" aria-labelledby="corModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="corModalLabel">Certificate of Registration</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <?php
                                            $file_type = getFileType($student['cor']);
                                            $file_path = str_replace('../', '../../', $student['cor']);
                                            if ($file_type == 'image'):
                                            ?>
                                                <img src="<?php echo htmlspecialchars($file_path); ?>" 
                                                    class="img-fluid" alt="COR">
                                            <?php elseif ($file_type == 'pdf'): ?>
                                                <embed src="<?php echo htmlspecialchars($file_path); ?>" 
                                                    type="application/pdf" width="100%" height="600px" />
                                            <?php else: ?>
                                                <p>Unsupported file type</p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php else: ?>
                            <span class="text-muted">No COR uploaded</span>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- <div class="mt-4">
                    <a href="delete_account.php?id=<?php echo $student['id']; ?>&type=student" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this account?')">
                        <i class="fas fa-trash"></i> Delete Account
                    </a>
                </div> -->
            </div>
        </div>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>