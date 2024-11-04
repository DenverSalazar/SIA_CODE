<?php
include('../../php/db_config.php');
session_start();

if(isset($_POST['delete_feedback'])) {
    $feedback_id = mysqli_real_escape_string($con, $_POST['feedback_id']);
    
    // Debug output
    error_log("Attempting to delete feedback ID: " . $feedback_id);
    
    $delete_query = "DELETE FROM feedback WHERE id = ?";
    $stmt = mysqli_prepare($con, $delete_query);
    mysqli_stmt_bind_param($stmt, "i", $feedback_id);
    
    if(mysqli_stmt_execute($stmt)) {
        error_log("Feedback deleted successfully");
        header("Location: admin_feedback.php?msg=deleted");
        exit();
    } else {
        error_log("Failed to delete feedback: " . mysqli_error($con));
        header("Location: admin_feedback.php?error=delete_failed");
        exit();
    }
    mysqli_stmt_close($stmt);
}

if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

// Initialize filter variables
$rating_filter = isset($_GET['rating']) ? $_GET['rating'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';

// Base query
$query = "SELECT f.*, f.id as feedback_id, s.fName, s.lName, s.email 
          FROM feedback f 
          JOIN students s ON f.student_id = s.id 
          WHERE 1=1";

// Add filters to query
if($rating_filter !== '') {
    $query .= " AND f.rating = '$rating_filter'";
}

if($date_filter !== '') {
    $query .= " AND DATE(f.created_at) = '$date_filter'";
}

if($search !== '') {
    $query .= " AND (s.fName LIKE '%$search%' OR s.lName LIKE '%$search%' OR f.comment LIKE '%$search%')";
}

// Add sorting
switch($sort) {
    case 'oldest':
        $query .= " ORDER BY f.created_at ASC";
        break;
    case 'highest':
        $query .= " ORDER BY f.rating DESC";
        break;
    case 'lowest':
        $query .= " ORDER BY f.rating ASC";
        break;
    default: // newest
        $query .= " ORDER BY f.created_at DESC";
}

$result = mysqli_query($con, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Feedback Page</title>
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/SIA/css/admin_feedback.css">
</head>
<style>
    .bookshelf-title {
    font-size: 36px;
    font-weight: 700;
    color: #4a90e2;
    margin-bottom: 30px;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
}

</style>
<body>
            <div class="sidebar">
                        <h5 class="sidebar-title mb-5">
                            <img src="../../img/logo.png" alt="Logo" width="190" height="20">
                        </h5>
                        <ul class="nav flex-column">
                            <?php
                            $current_page = basename($_SERVER['PHP_SELF']);
                            $nav_items = [
                                'homeAdmin.php' => ['icon' => 'fas fa-chart-bar', 'text' => 'Dashboard'],
                                'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                                'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                                'bookAdmin.php' => ['icon' => 'fas fa-book', 'text' => 'Modules'],
                                'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                                'admin_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                                'admin_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
                            ];

                            foreach ($nav_items as $page => $item) {
                                $active_class = ($current_page === $page) ? 'active' : '';
                                echo "<li class='nav-item'>
                                        <a class='nav-link {$active_class}' href='{$page}'>
                                            <i class='{$item['icon']}'></i> {$item['text']}
                                        </a>
                                    </li>";
                            }
                            ?>
                            <li class="nav-item mt-auto">
                                <a class="nav-link text-danger" href="../../php/logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </a>
                            </li>
                        </ul>
                    </div>

                    <main class="content">
                    <div id="content">
                        <div class="container">
                            <div class="feedback-container">
                            <div class="container">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h1 class="bookshelf-title">Student Feedback</h1>
                                </div>
                            </div>

                                <div class="filter-container">
                            <form method="GET" class="row g-3">
                                <!-- Search Box -->
                                <div class="col-md-4">
                                    <div class="search-box">
                                        <input type="text" class="form-control" name="search" placeholder="Search by name or comment" value="<?php echo htmlspecialchars($search); ?>">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </div>

                            <!-- Rating Filter -->
                            <div class="col-md-2">
                                <select class="form-select" name="rating">
                                    <option value="">All Ratings</option>
                                    <?php for($i = 5; $i >= 1; $i--): ?>
                                        <option value="<?php echo $i; ?>" <?php if($rating_filter == $i) echo 'selected'; ?>>
                                            <?php echo $i; ?> Stars
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-md-2">
                                <input type="date" class="form-control" name="date" value="<?php echo $date_filter; ?>">
                            </div>

                            <!-- Sort Filter -->
                            <div class="col-md-2">
                                <select class="form-select" name="sort">
                                    <option value="newest" <?php if($sort == 'new est') echo 'selected'; ?>>Newest First</option>
                                    <option value="oldest" <?php if($sort == 'oldest') echo 'selected'; ?>>Oldest First</option>
                                    <option value="highest" <?php if($sort == 'highest') echo 'selected'; ?>>Highest Rating</option>
                                    <option value="lowest" <?php if($sort == 'lowest') echo 'selected'; ?>>Lowest Rating</option>
                                </select>
                            </div>

                            <!-- Apply Filters Button -->
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                            </div>
                        </form>
                    </div>

                    <?php if(!empty($message)) echo $message; ?>

                    <!-- Add this right after your filter-container div -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Feedback List</h3>
                            <!-- Delete All Button with confirmation modal trigger -->
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAllModal">
                                <i class="fas fa-trash-alt"></i>Delete All 
                            </button>
                        </div>

                        <!-- Add this after your filter container -->
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                Feedback has been successfully deleted.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Failed to delete feedback. Please try again.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Add success/error messages -->
                        <?php if(isset($_GET['msg']) && $_GET['msg'] == 'all_deleted'): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                All feedback has been successfully deleted.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(isset($_GET['error']) && $_GET['error'] == 'delete_failed'): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Failed to delete feedback. Please try again.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Delete All Confirmation Modal -->
                        <div class="modal fade" id="deleteAllModal" tabindex="-1" aria-labelledby="deleteAllModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="deleteAllModalLabel">Confirm Delete All</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="text-center">Are you sure you want to delete all feedback? This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                        <a href="delete_all_feedback.php" class="btn btn-danger">Delete All</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php while($feedback = mysqli_fetch_assoc($result)): ?>
                        <div class="feedback-item">
                            <div class="avatar">
                                <?php
                                // Get student's profile picture
                                $student_id = $feedback['student_id'];
                                $profile_query = mysqli_query($con, "SELECT profile_picture FROM students WHERE id = '$student_id'");
                                $profile_result = mysqli_fetch_assoc($profile_query);
                                
                                if(isset($profile_result['profile_picture']) && !empty($profile_result['profile_picture'])) {
                                    echo '<img src="../../../uploads/profiles/'. htmlspecialchars($profile_result['profile_picture']) .'" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">';
                                } else {
                                    echo '<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                        <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                        <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"/>
                                    </svg>';
                                }
                                ?>
                            </div>
                            <div class="feedback-content">
                                <div class="student-info">
                                    <?php echo htmlspecialchars($feedback['fName'] . ' ' . $feedback['lName']); ?>
                                    <span class="date">- <?php echo date('Y-m-d', strtotime($feedback['created_at'])); ?></span>
                                </div>
                                <div class="feedback-rating">Rating: <?php echo str_repeat('★', $feedback['rating']) . str_repeat('☆', 5 - $feedback['rating']); ?></div>
                                <div class="feedback-text"><?php echo htmlspecialchars($feedback['comment']); ?></div>
                            </div>
                            <form method="POST" class="delete-form">
                                <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
                                <button type="submit" name="delete_feedback" class="delete-btn" onclick="return confirm('Are you sure you want to delete this feedback?');">
                                    <i class="fas fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </div>
                    <?php endwhile; ?>

                </div>
            </div>
        </div>
    </main>

    <script src="../SIA/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-hide alerts after 3 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                setTimeout(function() {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 3000); // 3000 milliseconds = 3 seconds
            });
        });
    </script>
</body>
</html>
