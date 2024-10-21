<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback Page</title>
    
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa; /* Light background */
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .feedback-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            position: relative; /* For positioning the close button */
        }

        h1 {
            font-size: 28px;
            margin-bottom: 20px;
            color: #343a40;
        }

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

        .feedback-item {
            border-bottom: 1px solid #e9ecef;
            padding: 15px 0;
            display: flex;
            align-items: flex-start; /* Align items to the top */
        }

        .feedback-item:last-child {
            border-bottom: none;
        }

        .avatar {
            width: 40px;
            height: 40px;
            margin-right: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .feedback-content {
            flex: 1; /* Allows content to take available space */
        }

        .feedback-rating {
            color: #f8ce0b;
            font-size: 20px;
        }

        .feedback-text {
            margin-top: 10px;
            color: #495057;
        }

        .student-info {
            font-weight: bold;
            color: #007bff;
        }

        .date {
            font-size: 0.9em;
            color: #6c757d;
        }

        .delete-btn {
            background-color: #dc3545; /* Red */
            color: white;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            margin-left: 10px; /* Space between feedback content and button */
        }

        .delete-btn:hover {
            background-color: #c82333; /* Darker red on hover */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="feedback-container">
            <button class="close-btn" onclick="location.href='homeAdmin.php'">×</button>
            <h1>Student Feedback</h1>

            <!-- Feedback Items -->
            <div class="feedback-item">
                <div class="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                </div>
                <div class="feedback-content">
                    <div class="student-info">Student Name 1 <span class="date">- 2024-10-21</span></div>
                    <div class="feedback-rating">Rating: ★★★★☆</div>
                    <div class="feedback-text">I really enjoyed this tool! It helped me a lot.</div>
                </div>
                <button class="delete-btn">Delete</button>
            </div>

            <div class="feedback-item">
                <div class="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                </div>
                <div class="feedback-content">
                    <div class="student-info">Student Name 2 <span class="date">- 2024-10-20</span></div>
                    <div class="feedback-rating">Rating: ★★★☆☆</div>
                    <div class="feedback-text">The interface is good, but it could be more user-friendly.</div>
                </div>
                <button class="delete-btn">Delete</button>
            </div>

            <div class="feedback-item">
                <div class="avatar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                    </svg>
                </div>
                <div class="feedback-content">
                    <div class="student-info">Student Name 3 <span class="date">- 2024-10-19</span></div>
                    <div class="feedback-rating">Rating: ★★★★★</div>
                    <div class="feedback-text">Excellent tool! I found it very useful.</div>
                </div>
                <button class="delete-btn">Delete</button>
            </div>

            <!-- More feedback items can be added here -->
        </div>
    </div>

    <script src="../SIA/js/bootstrap.bundle.min.js"></script>
</body>
</html>
