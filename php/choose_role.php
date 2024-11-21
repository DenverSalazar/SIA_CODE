<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Role</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <style>
        body{
        background-image: url(/SIA/img/index.jpg);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        }
    
        .role-container {
            max-width: 900px;
            width: 100%;
            padding: 20px;
        }

        .role-cards {
            display: flex;
            gap: 30px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .role-card {
            background: whitesmoke;
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            width: 300px;
            color: black;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .role-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .role-icon {
            width: 100px;
            height: 100px;
            margin: 0 auto 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .role-icon img {
            width: 60px;
            height: 60px;
        }

        .role-title {
            color: black;
            font-size: 24px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .role-description {
            color: black;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .role-button {
            background: rgba(255, 255, 255, 0.2);
            color: black;
            border: none;
            padding: 10px 30px;
            border-radius: 30px;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .role-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        .page-title {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            font-size: 36px;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .back-button:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body>
    <button class="back-button" onclick="history.back()">← Back</button>
    
    <div class="role-container">
        <h1 class="page-title">Choose Your Role</h1>
        
       <!-- In choose_role.php -->
<div class="role-cards">
    <div class="role-card" onclick="window.location.href='./register.php?role=student'">
        <div class="role-icon">
            <img src="../img/student-icon.png" alt="Student">
        </div>
        <h2 class="role-title">Student</h2>
        <p class="role-description">
            Register as a student to access learning materials and read modules online.
        </p>
        <button class="role-button">Register as Student</button>
    </div>

    <div class="role-card" onclick="window.location.href='./register.php?role=teacher'">
        <div class="role-icon">
            <img src="../img/admin-icon.jpg" alt="Admin">
        </div>
        <h2 class="role-title">Teacher</h2>
        <p class="role-description">
            Register as an admin to manage modules, monitor student, and maintain the system.
        </p>
        <button class="role-button">Register as Teacher</button>
    </div>
</div>
    </div>

    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>