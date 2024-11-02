<?php
include('../php/db_config.php');
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
    <link rel="stylesheet" href="../css/bootstrap.min.css">
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
    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <?php if(isset($result['profile_picture']) && !empty($result['profile_picture'])): ?>
                    <img src="../../uploads/profiles/<?php echo htmlspecialchars($result['profile_picture']); ?>" 
                         alt="Profile Picture" class="profile-img">
                <?php else: ?>
                    <img src="../../img/default-profile.png" alt="Default Profile Picture" class="profile-img">
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
                
                <div class="text-center mt-4">
                    <a href="edit.php" class="edit-btn link-underline link-underline-opacity-0">
                        <i class="fas fa-edit me-2"></i>Edit Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>