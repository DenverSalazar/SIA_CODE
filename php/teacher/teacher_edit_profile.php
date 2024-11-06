<?php
include('../../php/db_config.php');
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
    exit();
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];

// Create uploads directory if it doesn't exist
$upload_directory = "../../uploads/profiles/"; // Modified path
if (!file_exists($upload_directory)) {
    mkdir($upload_directory, 0777, true);
}

// Fetch current user data
if($role == 'student'){
    $query = mysqli_query($con,"SELECT * FROM students WHERE id = '$id'");
} else if($role == 'teacher'){
    $query = mysqli_query($con,"SELECT * FROM teacher WHERE id = '$id'");
}

$result = mysqli_fetch_assoc($query);

// Handle form submission
if(isset($_POST['update'])){
    $fName = mysqli_real_escape_string($con, $_POST['fName']);
    $lName = mysqli_real_escape_string($con, $_POST['lName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    $update_query = "";
    
    // Handle profile picture upload
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0){
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)){
            $new_filename = "profile_" . $id . "_" . time() . "." . $filetype;
            $upload_path = $upload_directory . $new_filename;
            
            if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)){
                // Delete old profile picture if it exists
                if(!empty($result['profile_picture']) && file_exists($upload_directory . $result['profile_picture'])){
                    unlink($upload_directory . $result['profile_picture']);
                }
                
                // Update query with new profile picture
                if($role == 'student'){
                    $update_query = "UPDATE students SET fName='$fName', lName='$lName', 
                                   email='$email', profile_picture='$new_filename' 
                                   WHERE id='$id'";
                } else {
                    $update_query = "UPDATE teacher SET fName='$fName', lName='$lName', 
                                   email='$email', profile_picture='$new_filename' 
                                   WHERE id='$id'";
                }
            } else {
                $error = "Failed to upload file. Please check directory permissions.";
            }
        } else {
            $error = "Invalid file type. Allowed types: jpg, jpeg, png, gif";
        }
    } else {
        // Update without changing profile picture
        if($role == 'student'){
            $update_query = "UPDATE students SET fName='$fName', lName='$lName', 
                           email='$email' WHERE id='$id'";
        } else {
            $update_query = "UPDATE teacher SET fName='$fName', lName='$lName', 
                           email='$email' WHERE id='$id'";
        }
    }
    
    // Execute update query
    if(!empty($update_query)){
        try {
            if(mysqli_query($con, $update_query)){
                $_SESSION['message'] = "Profile updated successfully!";
                header("Cache-Control: no-cache, must-revalidate");
                header("Location: teacher_profile.php"); // Modified redirect path
                exit();
            } else {
                $error = "Error updating profile: " . mysqli_error($con);
            }
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/admin_edit_profile.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
</head>
<style>
     .edit-container {
            max-width: 1220px;
            margin-left: 280px;
        }
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
                            $active_class = ($current_page === $page || ($current_page === 'admin_edit_profile.php' && $page === 'admin_profile.php')) ? 'active' : '';
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

    <div class="edit-container">
        <div class="edit-card">
            <div class="edit-header">
                <h2>Edit Profile</h2>
                <p>Update your personal information</p>
            </div>

            <div class="edit-form">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
                <div class="profile-upload">
                        <?php if(isset($result['profile_picture']) && !empty($result['profile_picture'])): ?>
                            <img src="../../uploads/profiles/<?php echo htmlspecialchars($result['profile_picture']); ?>" 
                                alt="Current Profile Picture" id="preview-image">
                        <?php else: ?>
                            <img src="../../img/admin-icon.jpg" alt="Default Profile Picture" id="preview-image">
                        <?php endif; ?>
                        
                        <div class="mt-3">
                            <label for="profile_picture" class="upload-btn">
                                <i class="fas fa-camera me-2"></i>Change Photo
                            </label>
                            <input type="file" id="profile_picture" name="profile_picture" 
                                accept="image/*" style="display: none;" 
                                onchange="previewImage(this)">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">First Name</label>
                                <div class="input-group">
                                    <!-- <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span> -->
                                    <input type="text" class="form-control" name="fName" 
                                           value="<?php echo htmlspecialchars($result['fName']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <div class="input-group">
                                    <!-- <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span> -->
                                    <input type="text" class="form-control" name="lName" 
                                           value="<?php echo htmlspecialchars($result['lName']); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <!-- <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span> -->
                            <input type="email" class="form-control" name="email" 
                                   value="<?php echo htmlspecialchars($result['email']); ?>" required>
                        </div>
                    </div>

                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo $error; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="teacher_profile.php" class="cancel-btn link-underline link-underline-opacity-0">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                        <button type="submit" name="update" class="save-btn">
                            <i class="fas fa-save me-2"></i>Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                
                reader.onload = function(e) {
                    document.getElementById('preview-image').src = e.target.result;
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Auto-hide alerts after 5 seconds
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                var alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    var bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>