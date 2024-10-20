<?php
include('../php/db_config.php');
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: login.php");
}

if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $role = $_SESSION['role'];

    if ($role == 'student') {
        $query = mysqli_query($con, "SELECT * FROM students WHERE id = '$id'");
    } else if ($role == 'teacher') {
        $query = mysqli_query($con, "SELECT * FROM teacher WHERE id = '$id'");
    }

    while ($result = mysqli_fetch_assoc($query)) {
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
    <title>Edit Profile</title>
    <link rel="stylesheet" href="../../SIA/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 30px;
        }
        .form-box {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            padding: 20px;
            max-width: 400px; /* Adjust width */
            margin: auto; /* Center align */
        }
        .form-box header {
            font-size: 20px;
            margin-bottom: 15px;
        }
        .icon {
            margin: 15px 0;
        }
        .field {
            margin: 10px 0;
        }
        .field input {
            width: 100%;
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ced4da;
        }
        .btn2 {
            width: 100%;
            margin-top: 10px;
        }
        .link {
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="form-box">     
            <header>Edit Profile</header>
            <div class="icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                </svg>
            </div>
            <form action="" method="post">
                <div class="field">
                    <label for="fName">First Name:</label>
                    <input type="text" name="fName" id="fName" value="<?php echo htmlspecialchars($res_fName); ?>" required>
                </div>

                <div class="field">
                    <label for="lName">Last Name:</label>
                    <input type="text" name="lName" id="lName" value="<?php echo htmlspecialchars($res_lName); ?>" required>
                </div>

                <div class="field">
                    <label for="email">Email Address:</label>
                    <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($res_email); ?>" required>
                </div>

                <div class="field">
                    <button type="submit" name="submit" class="btn2 btn-outline-dark" style="color:white;" >Update</button>
                </div>
            </form>

            <div class="link">
                <a class="link-dark link-underline-opacity-0" href="profile.php">Back to Profile</a>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['submit'])) {
        $fName = $_POST['fName'];
        $lName = $_POST['lName'];
        $email = $_POST['email'];

        if ($role == 'student') {
            $update_query = mysqli_query($con, "UPDATE students SET fName = '$fName', lName = '$lName', email = '$email' WHERE id = '$id'");
        } else if ($role == 'teacher') {
            $update_query = mysqli_query($con, "UPDATE teacher SET fName = '$fName', lName = '$lName', email = '$email' WHERE id = '$id'");
        }

        if ($update_query) {
            echo "<script>alert('Profile Updated!'); window.location.href='profile.php';</script>";
        } else {
            echo "<script>alert('Error updating profile!'); window.location.href='edit.php';</script>";
        }
    }
    ?>
    
    <script src="../../SIA/js/bootstrap.bundle.min.js"></script>
</body>
</html>
