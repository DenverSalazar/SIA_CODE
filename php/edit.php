<?php
include('../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}

$id = $_SESSION['id'];
$role = $_SESSION['role'];

// Use your original path structure
$upload_directory = "../../uploads/profiles/";

// Create uploads directory if it doesn't exist
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
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allowed extensions
        $allowed_ext = array('jpg', 'jpeg', 'png', 'gif');
        
        if(in_array($file_ext, $allowed_ext)){
            // Generate new filename
            $new_filename = 'profile_' . $id . '_' . time() . '.' . $file_ext;
            
            // Set complete upload path using your original path structure
            $upload_path = $upload_directory . $new_filename;
            
            // Try to move the uploaded file
            if(move_uploaded_file($file_tmp, $upload_path)){
                // File uploaded successfully, update database
                if($role == 'student'){
                    $update_query = "UPDATE students SET 
                                   fName='$fName', 
                                   lName='$lName', 
                                   email='$email', 
                                   profile_picture='$new_filename' 
                                   WHERE id='$id'";
                } else {
                    $update_query = "UPDATE teacher SET 
                                   fName='$fName', 
                                   lName='$lName', 
                                   email='$email', 
                                   profile_picture='$new_filename' 
                                   WHERE id='$id'";
                }
            } else {
                $error = "Failed to upload file.";
            }
        } else {
            $error = "Invalid file type. Allowed types: jpg, jpeg, png, gif";
        }
    } else {
        // No new profile picture, just update other fields
        if($role == 'student'){
            $update_query = "UPDATE students SET 
                           fName='$fName', 
                           lName='$lName', 
                           email='$email' 
                           WHERE id='$id'";
        } else {
            $update_query = "UPDATE teacher SET 
                           fName='$fName', 
                           lName='$lName', 
                           email='$email' 
                           WHERE id='$id'";
        }
    }
    
    // Execute update query
    if(!empty($update_query)){
        if(mysqli_query($con, $update_query)){
            if($_SESSION['role'] == 'student') {
                $student_id = $_SESSION['id'];
                $log_query = "INSERT INTO activity_logs (student_id, action, details, timestamp) 
                             VALUES ('$student_id', 'update_profile', 'Updated profile information', NOW())";
                mysqli_query($con, $log_query);
            }
            $_SESSION['message'] = "Profile updated successfully!";
            header("Location: profile.php");
            exit();
        } else {
            $error = "Error updating profile: " . mysqli_error($con);
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .edit-container {
            max-width: 800px;
            margin: 50px auto;
        }
        
        .edit-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .edit-header {
            background: linear-gradient(135deg, #6B8DE3 0%, #5E72E4 100%);
            padding: 30px;
            color: white;
            text-align: center;
        }
        
        .edit-form {
            padding: 30px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            color: #8898aa;
            font-size: 0.9rem;
            margin-bottom: 8px;
        }
        
        .form-control {
            border-radius: 5px;
            border: 1px solid #e4e7eb;
            padding: 12px;
            transition: all 0.3s;
        }
        
        .form-control:focus {
            border-color: #5E72E4;
            box-shadow: 0 0 0 0.2rem rgba(94,114,228,0.25);
        }
        
        .profile-upload {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .profile-upload img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid white;
            margin-bottom: 15px;
            object-fit: cover;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .upload-btn {
            background: #5E72E4;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .upload-btn:hover {
            background: #324cdd;
        }
        
        .save-btn {
            background: #2dce89;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .save-btn:hover {
            background: #26af74;
            transform: translateY(-2px);
        }
        
        .cancel-btn {
            background: #f5365c;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .cancel-btn:hover {
            background: #f01744;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <div class="edit-card">
            <div class="edit-header">
                <h2>Edit Profile</h2>
                <p>Update your personal information</p>
            </div>

            <div class="edit-form">
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="profile-upload">
                        <?php if(isset($result['profile_picture']) && !empty($result['profile_picture'])): ?>
                            <img src="../../uploads/profiles/<?php echo htmlspecialchars($result['profile_picture']); ?>" 
                                alt="Current Profile Picture" id="preview-image">
                        <?php else: ?>
                            <img src="../../img/default-profile.png" alt="Default Profile Picture" id="preview-image">
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
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" name="fName" 
                                           value="<?php echo htmlspecialchars($result['fName']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input type="text" class="form-control" name="lName" 
                                           value="<?php echo htmlspecialchars($result['lName']); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-envelope"></i>
                            </span>
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
                        <a href="profile.php" class="cancel-btn link-underline link-underline-opacity-0">
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