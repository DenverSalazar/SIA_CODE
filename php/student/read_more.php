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



if (!isset($_SESSION['valid'])) {
    header("Location: ../../login.php");
}

// Check if module ID is provided in URL
if (!isset($_GET['id'])) {
    header("Location: books.php");
    exit();
}

$module_id = mysqli_real_escape_string($con, $_GET['id']);

// Fetch module details
$query = "SELECT * FROM books WHERE id = '$module_id'";
$result = mysqli_query($con, $query);

if (mysqli_num_rows($result) == 0) {
    header("Location: books.php");
    exit();
}

$module = mysqli_fetch_assoc($result);

// Insert view module activity log
$action = 'view_module';
$details = "Viewed module: " . htmlspecialchars($module['title']);
$query = "INSERT INTO activity_logs (student_id, action, details, timestamp) VALUES (?, ?, ?, NOW())";
$stmt = $con->prepare($query);
$stmt->bind_param("iss", $_SESSION['id'], $action, $details);
$stmt->execute();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Module</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
 
 <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f7fc;
            color: #333;
        }

        .container {
            max-width: 1340px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Navbar */
        .navbar {
        background-color: #052659;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
        height: 65px;
        }
        .navbar-brand img {
            filter: brightness(0) invert(1);
            height: 20px;
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

        /* Module Details */
        .module-details {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        h2 {
            color: #052659;
            font-weight: bold;
            font-size: 2rem;
            margin-bottom: 25px;
        }

        .document-title {
            color: #052659;
            margin-bottom: 20px;
            font-size: 1.5em;
            font-weight: bold;
        }

        .document-container {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .document-preview {
            position: relative;
            text-align: center;
        }

        .document-preview a {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            color: #fff;
            background-color: #052659;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 1.1em;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .document-preview a:hover {
            background-color: #1c3f70;
        }

        .info-group {
            margin-bottom: 20px;
        }

        .info-label {
            font-weight: bold;
            color: #333;
        }

        .info-value {
            color: #555;
        }

        .btn {
            border-radius: 8px;
            padding: 12px 25px;
            font-size: 1rem;
            background-color: #052659;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #1c3f70;
        }

        .btn-back {
            margin-top: 20px;
        }

        /* Footer */
        .footer-section {
            background-color: #2c3e50;
            color: #ecf0f1;
        }

        .footer-section h5 {
            color: #fff;
            margin-bottom: 0px;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #f1f1f1;
        }

        .footer-logo {
            max-width: 180px;
            filter: brightness(0) invert(1);
        }

        .footer-description {
            font-size: 0.9rem;
            color: #ddd;
        }

        .footer-contact p {
            font-size: 0.9rem;
            color: #ddd;
        }
        .footer-copyright {
            font-size: 0.8rem;
            opacity: 0.6;
        }

        /* Cover Image */
        .cover-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            margin-bottom: 25px;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
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


    <div class="container">
        <h2><i class="fas fa-book"></i> Module Details</h2>
        <a href="books.php" class="btn btn-back"><i class="fas fa-arrow-left"></i> Back to Books</a>
        <div class="module-details">
            <?php if (!empty($module['cover_photo'])): ?>
                <img src="../../php/teacher/uploads/<?php echo htmlspecialchars($module['cover_photo']); ?>" alt="Module Cover" class="cover-image">
            <?php else: ?>
                <p>No cover photo available.</p>
            <?php endif; ?>

            <div class="info-group">
                <span class="info-label">Module ID:</span>
                <span class="info-value"><?php echo htmlspecialchars($module['id']); ?></span>
            </div>
            <div class="info-group">
                <span class="info-label">Title:</span>
                <span class="info-value"><?php echo htmlspecialchars($module['title']); ?></span>
            </div>
            <div class="info-group">
                <span class="info-label">Description:</span>
                <span class="info-value"><?php echo htmlspecialchars($module['description']); ?></span>
            </div>

            <div class="document-section">
    <h4 class="document-title"><i class="fas fa-file-alt"></i> Module Document</h4>
    <div class="document-container">
        <?php if (!empty($module['file_name'])): ?>
            <div class="document-preview">
                <?php
                // Set the file path relative to the server root
                $file_path = '../../php/teacher/uploads/' . $module['file_name']; 

                if (file_exists($file_path)): 
                    // Only allow PDF files for embedding
                    $file_extension = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
                    if ($file_extension == 'pdf'): ?>
                        <embed src="<?php echo htmlspecialchars($file_path); ?>" type="application/pdf" width="100%" height="500px" />
                    <?php else: ?>
                        <p>Non-PDF file detected. Click below to download the file:</p>
                    <?php endif; ?>
                <?php else: ?>
                    <span class="text-danger">File not found at path: <?php echo htmlspecialchars($file_path); ?></span>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <span class="text-danger">No document available for this module.</span>
        <?php endif; ?>
    </div>
     <!-- Button to trigger download -->
    <div class="align-items-center d-flex justify-content-center"><a href="download_module.php?id=<?php echo htmlspecialchars($module['id']); ?>" class="btn"><i class="fas fa-download"></i> Download File</a></div>
</div>


</div>
    </div>

   <!-- FOOTER -->
 <footer class="footer-section py-5">
  <div class="container">
    <div class="row">
      <div class="col-lg-4 mb-4 mb-lg-0">
        <img src="../../img/logo.png" alt="Readiculous" class="footer-logo mb-3" style="max-width: 200px;">
        <p class="footer-description">Readiculous: Your gateway to a world of knowledge and imagination. Explore, learn, and grow with our comprehensive library management system.</p>
      </div>
      <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
        <h5 class="footer-heading">Quick Links</h5>
        <ul class="footer-links list-unstyled">
          <li><a href="#Home">Home</a></li>
          <li><a href="books.php">Modules</a></li>
          <li><a href="../../php/profile.php">Profile</a></li>
          <li><a href="about.php">About Us</a></li>
        </ul>
      </div>
      <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
        <h5 class="footer-heading">Services</h5>
        <ul class="footer-links list-unstyled">
          <li>Book Search</li>
          <li>Online Reading</li>
          <li>Give Feedback</li>
          <li>Digital Resources</li>
        </ul>
      </div>
      <div class="col-lg-4 col-md-4">
        <h5 class="footer-heading">Contact Us</h5>
        <address class="footer-contact">
          <p><i class="fas fa-map-marker-alt me-2"></i>BSU Lipa Batangas</p>
          <p><i class="fas fa-phone me-2"></i>0985-982-2196</p>
          <p><i class="fas fa-envelope me-2"></i>readiculous@gmail.com</p>
        </address>
      </div>
    </div>
  </div>
  <div class="footer-bottom text-center mt-4" style="background-color: transparent;">
    <div class="container">
      <p class="footer-copyright">&copy; 2024 Readiculous Library Management System. All rights reserved.</p>
    </div>
  </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
