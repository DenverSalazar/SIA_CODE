<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}

function getProfilePicturePath($profile_picture) {
    if (isset($profile_picture) && !empty($profile_picture)) {
        return "../../../uploads/profiles/" . htmlspecialchars($profile_picture);
    } else {
        return "../../../img/default-profile.png";
    }
}

// Fetch user data including profile picture
    $id = $_SESSION['id'];
    $query = mysqli_query($con, "SELECT * FROM students WHERE id = '$id'");
    $result = mysqli_fetch_assoc($query);
    $res_profile_picture = $result['profile_picture'];
    $res_fName = $result['fName'];
    $res_lName = $result['lName'];


$id = $_SESSION['id'];
$role = $_SESSION['role'];

// Fetch current user data
if($role == 'student'){
    $query = mysqli_query($con,"SELECT * FROM students WHERE id = '$id'");
} else if($role == 'teacher'){
    $query = mysqli_query($con,"SELECT * FROM teacher WHERE id = '$id'");
}

$result = mysqli_fetch_assoc($query);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fName = mysqli_real_escape_string($con, $_POST['fName']);
    $lName = mysqli_real_escape_string($con, $_POST['lName']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Handle profile picture upload
    if(isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        $filename = $_FILES['profile_picture']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if(in_array(strtolower($filetype), $allowed)) {
            // Create uploads directory if it doesn't exist
            $upload_dir = "../../../uploads/profiles/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Generate unique filename
            $new_filename = uniqid() . '.' . $filetype;
            $upload_path = $upload_dir . $new_filename;
            
            if(move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_path)) {
                // Delete old profile picture if exists
                if(!empty($result['profile_picture']) && file_exists($upload_dir . $result['profile_picture'])) {
                    unlink($upload_dir . $result['profile_picture']);
                }
                
                // Update database with new profile picture
                $profile_picture = $new_filename;
            }
        }
    }
    
    // Update query
    if($role == 'student'){
        $update_query = "UPDATE students SET 
                        fName = '$fName',
                        lName = '$lName',
                        email = '$email'";
        
        if(isset($profile_picture)) {
            $update_query .= ", profile_picture = '$profile_picture'";
        }
        
        $update_query .= " WHERE id = '$id'";
    } else if($role == 'teacher'){
        $update_query = "UPDATE teacher SET 
                        fName = '$fName',
                        lName = '$lName',
                        email = '$email'";
        
        if(isset($profile_picture)) {
            $update_query .= ", profile_picture = '$profile_picture'";
        }
        
        $update_query .= " WHERE id = '$id'";
    }
    
    if(mysqli_query($con, $update_query)) {
        $_SESSION['success_message'] = "Profile updated successfully!";
        header("Location: student_profile.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error updating profile: " . mysqli_error($con);
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
    <link rel="stylesheet" href="/SIA/css/homestyle.css">
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
        .navbar {
        background-color: #052659;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }
    .navbar-brand img {
        filter: brightness(0) invert(1);
    }
    .navbar-nav .nav-link {
        color: rgba(255,255,255,0.8) !important;
        transition: color 0.3s ease;
    }
    .navbar-nav .nav-link:hover {
        color: #ffffff !important;
    }
    .nav-item.dropdown .user-profile {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        color: #ffffff;
        background-color: rgba(255,255,255,0.1);
        border-radius: 50px;
        transition: background-color 0.3s ease;
    }
    .nav-item.dropdown .user-profile:hover {
        background-color: rgba(255,255,255,0.2);
    }
    .nav-item.dropdown img {
        width: 32px;
        height: 32px;
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid #ffffff;
    }
    .dropdown-menu {
        background-color: #ffffff;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        border-radius: 0.5rem;
    }
    .dropdown-item {
        color: #052659;
        padding: 0.5rem 1.5rem;
        transition: background-color 0.3s ease;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #052659;
    }
    .dropdown-item i {
        margin-right: 10px;
        color: #052659;
    }
</style>
<body>
  <!-- HEADER -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="../../img/logo.png" alt="Readiculous"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./student_messages.php">Messages</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./feedback.php">Feedback</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./about.php">About</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle user-profile" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo getProfilePicturePath($res_profile_picture); ?>" alt="Profile" class="rounded-circle">
                        <span><?php echo $res_fName; ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="./student_profile.php"><i class="fas fa-user-circle"></i> View Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../../php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
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
                            <img src="../../../uploads/profiles/<?php echo htmlspecialchars($result['profile_picture']); ?>" 
                                alt="Current Profile Picture" id="preview-image">
                        <?php else: ?>
                            <img src="../../../img/default-profile.png" alt="Default Profile Picture" id="preview-image">
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
                                    <input type="text" class="form-control" name="fName" 
                                           value="<?php echo htmlspecialchars($result['fName']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label">Last Name</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="lName" 
                                           value="<?php echo htmlspecialchars($result['lName']); ?>" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <div class="input-group">
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
                        <a href="./student_profile.php" class="cancel-btn link-underline link-underline-opacity-0">
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