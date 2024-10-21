<?php
include('../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $role = $_SESSION['role'];

    if($role == 'student'){
        $query = mysqli_query($con,"SELECT * FROM students WHERE id = '$id'");
    } else if($role == 'teacher'){
        $query = mysqli_query($con,"SELECT * FROM teacher WHERE id = '$id'");
    }

    while($result = mysqli_fetch_assoc($query)){
        $res_fName = $result['fName'];
        $res_lName = $result['lName'];
        $res_email = $result['email'];
    }
} else {
    echo "Error: ID is not set or empty.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../../SIA/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .profile-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
            text-align: center;
        }
        .profile-card h2 {
            margin-bottom: 20px;
        }
        .profile-detail {
            margin: 15px 0;
            font-size: 18px;
        }
        .btn2, .btn1 {
            width: 100%;
            margin: 10px 0;
        }
        .link {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="profile-card">     
            <header><h2>My Profile</h2></header>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                </svg>
            </div>

            <div class="profile-detail">
                <strong>First Name:</strong> <?php echo htmlspecialchars($res_fName); ?>
            </div>
            <div class="profile-detail">
                <strong>Last Name:</strong> <?php echo htmlspecialchars($res_lName); ?>
            </div>
            <div class="profile-detail">
                <strong>Email Address:</strong> <?php echo htmlspecialchars($res_email); ?>
            </div>
            <div class="profile-detail">
                <strong>User Type:</strong> <?php echo ucfirst(htmlspecialchars($role)); ?>
            </div>

            <div class="field">
                <button class="btn2 btn-outline-dark" style="color:white;" onclick="location.href='edit.php'">Edit Profile</button>
            </div>

            <div class="field">
                <button type="button" class="btn1 btn-danger" style="color:white;" data-bs-toggle="modal" data-bs-target="#deleteModal">Delete Account</button>
            </div>

            <div class="link">
                <a class="link-dark link-underline-opacity-0" href="<?php echo ($role == 'student') ? '../../SIA/php/student/home.php' : '../../SIA/php/teacher/homeAdmin.php'; ?>">Back to Home</a>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete your account?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"data-bs-dismiss="modal">Cancel</button>
                    <form action="../php/delete.php" method="post">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit" name="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../SIA/js/bootstrap.bundle.min.js"></script>
</body>
</html>
