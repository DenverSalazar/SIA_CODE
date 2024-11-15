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

if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../login.php");
    exit();
}

$student_id = $_SESSION['id'];
$success = false; // Add this flag

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $con->prepare("INSERT INTO feedback (student_id, rating, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $student_id, $rating, $comment);

    if ($stmt->execute()) {
        $success = true; // Set success flag
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Page</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homestyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
    <style>
        body {
        background-color: white;
        background-size: cover;
        background-position: center;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }
    .content {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }
    .feedback-container {
        background-color: #fff;
        padding: 40px 30px;
        border-radius: 8px;
        max-width: 400px;
        box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.3);
        text-align: center;
    }

        .feedback-container h1 {
            font-size: 24px;
            margin-bottom: 20px;
            color: #000;
        }

        .feedback-container p {
            margin-bottom: 20px;
            color: #333;
        }

        /* Close Button Styling */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 15px;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #aaa;
        }

        .close-btn:hover {
            color: #000; /* Change color on hover */
        }

        /* Star rating styling */
        .star-rating {
            direction: rtl;
            font-size: 2rem;
            unicode-bidi: bidi-override;
            width: fit-content;
            margin: 0 auto;
        }

        .star-rating input[type="radio"] {
            display: none;
        }

        .star-rating label {
            color: #ccc;
            font-size: 30px;
            padding: 0;
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }

        .star-rating input[type="radio"]:checked ~ label {
            color: #f8ce0b;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: #f8ce0b;
        }

        .textarea-container textarea {
            width: 100%;
            height: 100px;
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 10px;
            resize: none;
            font-size: 14px;
        }

        .btn-container {
            margin-top: 20px;
        }

        /* Send and Cancel button styles */
        .btn-send, .btn-cancel {
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: all 0.3s ease;
            font-weight: bold;
            font-size: 16px;
            width: 120px;
        }

        /* Send button styling */
        .btn-send {
            background-color: #0d6efd; /* Blue */
            color: white;
        }

        .btn-send:hover {
            background-color: #084298; /* Darker Blue on Hover */
        }

        /* Cancel button styling */
        .btn-cancel {
            background-color: #6c757d; /* Gray */
            color: white;
        }

        .btn-cancel:hover {
            background-color: #5a6268; /* Darker Gray on Hover */
        }

        .success-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .success-message {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            max-width: 400px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-100px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .success-icon {
            width: 70px;
            height: 70px;
            background-color: #28a745; /* Green circle background */
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 20px;
            padding: 15px; /* Add padding to make the check image smaller within the circle */
            overflow: hidden;
        }

        .success-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* This will maintain the aspect ratio */
        }

            .success-message h2 {
                color: #28a745;
                margin-bottom: 15px;
        }

        .success-message p {
            color: #666;
            margin-bottom: 20px;
        }

        .btn-return {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-return:hover {
            background-color: #218838;
            color: white;
        }   .navbar {
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

    <div class="content">
        <?php if ($success): ?>
            <div class="success-overlay">
                <div class="success-message">
                    <div class="success-icon">
                        <img src="../../img/check.png" alt="Success Check">
                    </div>
                    <h2>Thank You!</h2>
                    <p>Your feedback has been successfully submitted. We appreciate your Feedback!</p>
                    <a href="home.php" class="btn-return">Return to Homepage</a>
                </div>
            </div>
        <?php else: ?>
            <div class="feedback-container">
                <h1>Give Feedback</h1>
                <p>Rate your Experience!</p>

                <form method="POST" action="">
                    <div class="star-rating">
                        <input type="radio" name="rating" id="star5" value="5" required>
                        <label for="star5">&#9733;</label>
                        <input type="radio" name="rating" id="star4" value="4">
                        <label for="star4">&#9733;</label>
                        <input type="radio" name="rating" id="star3" value="3">
                        <label for="star3">&#9733;</label>
                        <input type="radio" name="rating" id="star2" value="2">
                        <label for="star2">&#9733;</label>
                        <input type="radio" name="rating" id="star1" value="1">
                        <label for="star1">&#9733;</label>
                    </div>

                    <div class="textarea-container">
                        <p>Do you have any thoughts you'd like to share?</p>
                        <textarea name="comment" placeholder="Type your feedback here..." required></textarea>
                    </div>

                    <div class="btn-container">
                        <button type="submit" class="btn-send">Send</button>
                        <a href="../../php/student/home.php"><button type="button" class="btn-cancel">Cancel</button></a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script src="/SIA/js/bootstrap.bundle.min.js"></script>

    <script>
        // Optional: Handle the star rating interaction
        const ratingStars = document.querySelectorAll('.star-rating input');
        ratingStars.forEach(star => {
            star.addEventListener('change', (event) => {
                console.log(`Rated: ${event.target.value} stars`);
            });
        });
    </script> 
</body>
</html>