<?php
include('../../php/db_config.php');
session_start();

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

    <style>
        body {
            background-color: white;
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .feedback-container {
            background-color: #fff;
            padding: 40px 30px;
            border-radius: 8px;
            max-width: 400px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.3);
            text-align: center;
            position: relative; /* Make position relative to place the close button */
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
        }

    </style>
</head>
<body>
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

                <?php
                if (isset($success_message)) {
                    echo "<p class='success-message'>$success_message</p>";
                }
                if (isset($error_message)) {
                    echo "<p class='error-message'>$error_message</p>";
                }
                ?>

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

            <script src="../SIA/js/bootstrap.bundle.min.js" ></script>
            <script src="../SIA/js/bootstrap.min.js"></script>

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