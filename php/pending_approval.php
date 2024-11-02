<?php
session_start();
include '../php/db_config.php';

if(!isset($_SESSION['pending_id']) || !isset($_SESSION['pending_role'])) {
    header("Location: login.php");
    exit();
}

$id = $_SESSION['pending_id'];
$role = $_SESSION['pending_role'];

if($role == 'student') {
    $query = mysqli_query($con, "SELECT * FROM students WHERE id = '$id'");
} else {
    // Handle other roles if needed
    header("Location: login.php");
    exit();
}

$user = mysqli_fetch_assoc($query);

// Clear the pending session variables
unset($_SESSION['pending_id']);
unset($_SESSION['pending_role']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approval</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background: linear-gradient(135deg, #2c3e50, #3498db);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .pending-box {
            background-color: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        .pending-icon {
            font-size: 64px;
            color: #f39c12;
            margin-bottom: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        p {
            color: #34495e;
            margin-bottom: 30px;
        }
        .btn-primary {
            background-color: black;
            border: none;
        }
        .btn-primary:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="pending-box">
        <div class="pending-icon">⏳</div>
        <h1>Account Pending Approval</h1>
        <p>Hello <?php echo htmlspecialchars($user['fName'] . ' ' . $user['lName']); ?>,</p>
        <p>Your account is currently pending approval from an administrator. Please check back later or contact support for more information.</p>
        <a href="login.php" class="btn btn-primary">Back to Login</a>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>