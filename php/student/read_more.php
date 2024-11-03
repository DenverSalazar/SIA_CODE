<?php
include('../../php/db_config.php');
session_start();

// Function to check file details and handle file streaming
function streamFile($filepath, $mime_type) {
    if (file_exists($filepath) && is_readable($filepath)) {
        header('Content-Type: ' . $mime_type);
        header('Content-Disposition: inline; filename="' . basename($filepath) . '"');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
        exit;
    }
    return false;
}

// Validate and sanitize the ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: No book ID provided. Please select a valid book.");
}

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
if ($id === false || $id <= 0) {
    die("Error: Invalid book ID format.");
}

// Fetch book details
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $con->prepare($sql);
if (!$stmt) {
    die("Error preparing statement: " . $con->error);
}

$stmt->bind_param("i", $id);
if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $book = $result->fetch_assoc();
} else {
    die("Book not found in database.");
}

// Debug function (you can remove this in production)
function debug_file_info($file_path) {
    echo "File path: " . $file_path . "<br>";
    echo "File exists: " . (file_exists($file_path) ? 'Yes' : 'No') . "<br>";
    echo "Is readable: " . (is_readable($file_path) ? 'Yes' : 'No') . "<br>";
    if (file_exists($file_path)) {
        echo "File permissions: " . substr(sprintf('%o', fileperms($file_path)), -4) . "<br>";
        echo "File size: " . filesize($file_path) . " bytes<br>";
    }
}

$student_id = $_SESSION['id'];
$book_id = $_GET['id']; // Assuming you get the book ID from the URL
$book_title = $book['title']; // Assuming you have fetched the book details
$query = "INSERT INTO activity_logs (student_id, action, details, timestamp) 
          VALUES ('$student_id', 'view_module', 'Viewed Module: $book_title (ID: $book_id)', NOW())";
mysqli_query($con, $query);

$student_id = $_SESSION['id'];
$book_id = $_GET['id'];
$book_title = $book['title'];
$query = "INSERT INTO activity_logs (student_id, action, details, timestamp) 
          VALUES ('$student_id', 'download_module', 'Downloaded Module: $book_title (ID: $book_id)', NOW())";
mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($book['title']) ?> - Book Details</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
</head>
<style> 
     .navbar {
        background-color: #ffffff;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
     }

        .navbar {
        background-color: #f8f9fa;
    }

    .offcanvas-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
    }

    .offcanvas-body {
        padding: 1rem;
    }

    .btn-outline-success {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .btn-outline-success:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .offcanvas .nav-item {
        margin-bottom: 10px;
    }

    .offcanvas .nav-item a {
        color: #333;
        font-size: 16px;
    }

    .logout-link {
        color: red; 
    }

    .logout-link:hover {
        color: darkred; 
    }

    .offcanvas .nav-item a:hover {
        color: #0056b3;
        text-decoration: none;
    }

    .navbar-brand img {
        width: 150px;
    }

    .offcanvas-header h5 {
        margin-bottom: 0;
    }

    .footer-section {
        background-color: #2c3e50;
        color: #ecf0f1;
    }

    .footer-logo {
        filter: brightness(0) invert(1);
    }

    .footer-description {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .footer-heading {
        font-family: 'Merriweather', serif;
        font-size: 1.2rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: #3498db;
    }

    .footer-links a {
        color: #ecf0f1;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.3s ease;
    }

    .footer-links a:hover {
        color: #3498db;
    }

    .footer-contact {
        font-size: 0.9rem;
        opacity: 0.8;
    }

    .footer-bottom {
        background-color: #333;
        padding: 1rem 0;
    }

    .footer-divider {
        border: none;
        border-top: 1px solid #444;
        margin: 1rem 0;
    }

    .footer-copyright {
        color: white;
    }

    .about-section {
        padding: 40px 0; 
    }

    .about-text {
        overflow: hidden; 
        max-height: 400px; 
        overflow-y: auto; 
    }

    @media (max-width: 768px) {
        .about-text {
            max-height: none; 
        }
    }
</style>
<body>
        
        <!-- HEADER -->
        <header>
            <nav class="navbar navbar-light fixed-top">
                <div class="container">
                <a class="navbar-brand"><img src="../../img/logo.png" alt="Readiculous" width=""></a> 
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                </div>
            </nav>

            <!-- Offcanvas Menu -->
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><img src="../../img/logo.png" alt="Readiculous" width="150"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                <ul class="nav flex-column">
                    <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="../../php/profile.php">User Profile</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="../../php/student/feedback.php">Feedback</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="about.php">About us</a>
                    </li>
                    <li class="nav-item-x">
                    <a class="nav-link logout-link" href="../../php/logout.php">Logout</a>
                    </li>            
                </ul>
                </div>
            </div>
        </header>

                    <div class="container mt-5">
                    <h1 class="mb-4"><?= htmlspecialchars($book['title']) ?></h1>
                    <div class="row">
                        <div class="col-md-6">
                            <img src="../teacher/uploads/<?= htmlspecialchars($book['cover_image']) ?>" class="img-fluid" alt="<?= htmlspecialchars($book['title']) ?>">
                        </div>
                        <div class="col-md-6">
                <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
                <a href="books.php" class="btn btn-outline-primary">Back to Browse</a>

                <h2 class="mt-5">Learning Material</h2>
                <?php
                if (!empty($book['file_name'])) {
                    // Construct and validate file path
                    $upload_dir = realpath(__DIR__ . "/../teacher/uploads/");
                    $sanitized_filename = basename($book['file_name']);
                    $final_path = $upload_dir . DIRECTORY_SEPARATOR . $sanitized_filename;

                    // Security check
                    if (strpos(realpath($final_path), $upload_dir) !== 0) {
                        die("Invalid file path detected");
                    }

                    $file_extension = strtolower(pathinfo($sanitized_filename, PATHINFO_EXTENSION));
                    $mime_type = mime_content_type($final_path);

                    if (file_exists($final_path) && is_readable($final_path)) {
                        echo '<div class="file-viewer mb-3">';
                        
                        switch ($file_extension) {
                            case 'pdf':
                                // Directly embed PDF
                                echo '<div class="embed-responsive" style="height: 600px;">';
                                echo '<iframe class="embed-responsive-item" src="' . htmlspecialchars($final_path) . '" width="100%" height="100%" frameborder="0"></iframe>';
                                echo '</div>';
                                break;
                                
                            case 'pptx':
                            case 'ppt':
                                // Using Microsoft's Office Online viewer
                                $encoded_path = urlencode('https://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/view_file.php?id=' . $id);
                                echo '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . $encoded_path . '" width="100%" height="600px" frameborder="0"></iframe>';
                                break;
                                
                            case 'doc':
                            case 'docx':
                                echo '<div class="alert alert-info">Word documents can be viewed online or downloaded.</div>';
                                echo '<a href="view_file.php' . htmlspecialchars($final_path) . '" class="btn btn-primary" target="_blank">View Document</a>';
                                echo '<a href="download_file.php?id=' . $id . '" class="btn btn-secondary">Download Document</a>';
                                break;
                                
                            case 'txt':
                                // Display text file content
                                echo '<pre class="p-3 bg-light" style="max-height: 600px; overflow-y: auto;">';
                                echo htmlspecialchars(file_get_contents($final_path));
                                echo '</pre>';
                                break;
                                
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                            case 'gif':
                                // Display image directly
                                echo '<img src="' . htmlspecialchars($final_path) . '" class="img-fluid" alt="' . htmlspecialchars($book['title']) . '">';
                                break;

                            default:
                                echo '<div class="alert alert-warning">Preview not available for this file type.</div>';
                                echo '<a href="download_file.php?id=' . $id . '" class="btn btn-primary">Download File</a>';
                        }

                        echo '</div>';
                        
                        // Add download button below viewer
                        echo '<a href="download_file.php?id=' . $id . '" class="btn btn-secondary">Download File</a>';
                        
                    } else {
                        echo '<div class="alert alert-danger">File not found or not readable. Please contact administrator.</div>';
                    }
                } else {
                    echo '<p>No learning materials available for this book.</p>';
                }
                ?>

            </div>
        </div>
    </div>

    <hr class="featurette-divider">

<footer class="footer-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4 mb-lg-0">
                <img src="/SIA/img/logo.png" alt="Readiculous" class="footer-logo mb-3" style="max-width: 200px;">
                <p class="footer-description">Readiculous: Your gateway to a world of knowledge and imagination. Explore, learn, and grow with our comprehensive library management system.</p>
            </div>
            <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                <h5 class="footer-heading">Quick Links</h5>
                <ul class="footer-links list-unstyled">
                    <li><a href="home.php">Home</a></li>
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
                    <p><i class="fas fa-map-marker-alt me-2"></i>123 Library Street, Booktown, BK 12345</p>
                    <p><i class="fas fa-phone me-2"></i>(123) 456-7890</p>
                    <p><i class="fas fa-envelope me-2"></i>info@readiculous.com</p>
                </address>
            </div>
        </div>
    </div>
    <div class="footer-bottom text-center mt-4" style="background-color: transparent;">
        <div class="container">
            <hr class="footer-divider">
            <p class="footer-copyright">&copy; 2024 Readiculous Library Management System. All rights reserved.</p>
        </div>
    </div>
</footer>
    <script src="../../js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
