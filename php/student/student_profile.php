<?php
include('../../php/db_config.php');
session_start();

function getProfilePicturePath($profile_picture) {
    if (isset($profile_picture) && !empty($profile_picture)) {
        return "../../../uploads/profiles/" . htmlspecialchars($profile_picture);
    } else {
        return "/SIA/img/default-profile.png";
    }
}

// Fetch user data including profile picture
    $id = $_SESSION['id'];
    $query = mysqli_query($con, "SELECT * FROM students WHERE id = '$id'");
    $result = mysqli_fetch_assoc($query);
    $res_profile_picture = $result['profile_picture'];
    $res_fName = $result['fName'];
    $res_lName = $result['lName'];


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
    <link rel="stylesheet" href="/SIA/css/homestyle.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        body {
            background-color: #f8f9fa;
        }
        
        .profile-container {
            max-width: 800px;
            margin: 50px auto;
        }
        
        .profile-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        
        .profile-header {
            background: linear-gradient(135deg, #6B8DE3 0%, #5E72E4 100%);
            padding: 30px;
            color: white;
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
            background: #2dce89;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .edit-btn:hover {
            background: #26af74;
            transform: translateY(-2px);
        }

        .delete-btn {
          background: #f5365c;
            border: none;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .delete-btn:hover {
            background: #f5365c;
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

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
            <?php if(isset($result['profile_picture']) && !empty($result['profile_picture'])): ?>
                    <img src="../../../uploads/profiles/<?php echo htmlspecialchars($result['profile_picture']); ?>" 
                        alt="Profile Picture" class="profile-img">
                <?php else: ?>
                    <img src="/SIA/img/default-profile.png" alt="Default Profile Picture" class="profile-img">
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
                
                <div class="d-flex justify-content-between mt-4">
                <form class="delete-btn" action="delete_account.php" method="POST" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                    <input type="hidden" name="id" value="<?php echo $_SESSION['id']; ?>">
                    <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-trash"></i> Delete Account</button>
                </form>
                    <a href="student_edit_profile.php" class="edit-btn link-underline link-underline-opacity-0">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>

                    </div>
             
              
               
            </div>
        </div>
    </div>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>