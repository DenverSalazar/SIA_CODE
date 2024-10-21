<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log</title>
    
    <link rel="stylesheet" href="../../css/bootstrap.min.css">

    <style>
        body {
            background-color: #f8f9fa; /* Light background */
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .activity-log-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
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

        .activity-item {
            border-bottom: 1px solid #e9ecef;
            padding: 15px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-content {
            flex: 1; /* Allows content to take available space */
            margin-right: 15px;
        }

        .activity-type {
            font-weight: bold;
            color: #007bff;
        }

        .activity-date {
            font-size: 0.9em;
            color: #6c757d;
        }

        .activity-description {
            margin-top: 5px;
            color: #495057;
        }

        .status {
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
        }

        .status.completed {
            background-color: #28a745; /* Green */
        }

        .status.pending {
            background-color: #ffc107; /* Yellow */
        }

        .status.failed {
            background-color: #dc3545; /* Red */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="activity-log-container">
            <button class="close-btn" onclick="location.href='homeAdmin.php'">×</button>
            <h1>Activity Log</h1>

            <!-- Activity Items -->
            <div class="activity-item">
                <div class="activity-content">
                    <div class="activity-type">Login</div>
                    <div class="activity-date">2024-10-21</div>
                    <div class="activity-description">User logged in successfully.</div>
                </div>
                <span class="status completed">Completed</span>
            </div>

            <div class="activity-item">
                <div class="activity-content">
                    <div class="activity-type">Feedback Submission</div>
                    <div class="activity-date">2024-10-20</div>
                    <div class="activity-description">User submitted feedback.</div>
                </div>
                <span class="status completed">Completed</span>
            </div>

            <div class="activity-item">
                <div class="activity-content">
                    <div class="activity-type">Data Update</div>
                    <div class="activity-date">2024-10-19</div>
                    <div class="activity-description">User updated their profile information.</div>
                </div>
                <span class="status pending">Pending</span>
            </div>

            <div class="activity-item">
                <div class="activity-content">
                    <div class="activity-type">Error</div>
                    <div class="activity-date">2024-10-18</div>
                    <div class="activity-description">An error occurred during data processing.</div>
                </div>
                <span class="status failed">Failed</span>
            </div>

            <!-- More activity items can be added here -->
        </div>
    </div>

    <script src="../SIA/js/bootstrap.bundle.min.js"></script>
</body>
</html>
