<?php
        include('../../php/db_config.php');
        session_start();

        date_default_timezone_set('Asia/Manila');
        $currentTime = date('H:i:s'); 

        if(!isset($_SESSION['valid'])){
            header("Location: ../../login.php");
            exit();
        }

        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $role = $_SESSION['role'];
        
            if ($role == 'student') {
                $query = mysqli_query($con, "SELECT * FROM students WHERE id = '$id'");
            } else if ($role == 'teacher') {
                $query = mysqli_query($con, "SELECT * FROM teacher WHERE id = '$id'");
            }
        
            $result = mysqli_fetch_assoc($query);
            
            // Fetch profile picture
            $default_profile_picture = '../../img/admin-icon.jpg'; // Set your default profile picture path
            $profile_picture = isset($result['profile_picture']) && !empty($result['profile_picture']) ? $result['profile_picture'] : $default_profile_picture;
            
            // Fetch other user details
            $res_fName = $result['fName'];
            $res_lName = $result['lName'];
            $res_email = $result['email'];
        } else {
            echo "Error: ID is not set or empty.";
            exit();
        }
        // Get current teacher's ID
        $current_teacher_id = $_SESSION['id'];

        // Pagination settings
        $books_per_page = 10000;
        $offset = isset($_GET['page']) ? ($_GET['page'] - 1) * $books_per_page : 0;

        // Initialize search, category, and teacher filter variables
        $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';
        $selected_category = isset($_GET['category']) ? $_GET['category'] : 'All';
        
        // Modify the WHERE clause to always include current teacher's uploads
        $where_clause = "WHERE books.uploaded_by = '$current_teacher_id'";
        if ($search) {
            $where_clause .= " AND (books.title LIKE '%$search%')";
        }
        if ($selected_category !== 'All') {
            $where_clause .= " AND books.book_category = '$selected_category'";
        }

        // Count total books for pagination
        $count_sql = "SELECT COUNT(*) as total FROM books $where_clause";
        $count_result = mysqli_query($con, $count_sql);
        $total_books = mysqli_fetch_assoc($count_result)['total'];
        $total_pages = ceil($total_books / $books_per_page);

        $sql = "SELECT books.*, teacher.fName, teacher.lName 
        FROM books 
        LEFT JOIN teacher ON books.uploaded_by = teacher.id 
        $where_clause 
        ORDER BY books.upload_date DESC 
        LIMIT $offset, $books_per_page";

        $result = mysqli_query($con, $sql);
        $books = mysqli_fetch_all($result, MYSQLI_ASSOC);

        // Fetch categories
        $category_result = mysqli_query($con, "SELECT DISTINCT book_category FROM books");
        $categories = mysqli_fetch_all($category_result, MYSQLI_ASSOC);

        // Fetch teachers for filter (only those who have uploaded books)
        $teacher_result = mysqli_query($con, "
        SELECT DISTINCT t.id, t.fName, t.lName 
        FROM teacher t
        INNER JOIN books b ON t.id = b.uploaded_by
        ORDER BY t.fName
        ");
        $teachers = mysqli_fetch_all($teacher_result, MYSQLI_ASSOC);
        mysqli_close($con);

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEACHER HOMEPAGE</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/SIA/css/adminstyle.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/dashboard.css">
    <link rel="stylesheet" href="/SIA/css/bookAdmin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<style>
   
    .sidebar{
        background-color: #052659;
    }
    .my-upload {
        position: relative;
        border: 2px solid #052659 !important;
        box-shadow: 0 0 10px rgba(5, 38, 89, 0.2) !important;
    }

    .my-upload-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #052659;
        color: white;
        padding: 5px 10px;
        border-radius: 15px;
        font-size: 0.8em;
        z-index: 1;
    }

    .book.my-upload .book-content {
        background-color: rgba(5, 38, 89, 0.05);
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
                    'teacher_home.php' => ['icon' => 'fas fa-home', 'text' => 'Home'],
                    'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                    'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
                    'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                    'teacher_feedback.php' => ['icon' => 'fas fa-comment-alt', 'text' => 'Feedbacks'],
                    'teacher_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
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
            <section>
            <div class="welcome d-flex flex-column flex-md-row align-items-center justify-content-between" style="background-color:#C1E8FF; padding: 20px; border-radius: 10px;">
    <div class="text mb-3 mb-md-0 flex-grow-1">
        <h1 style="font-weight: bold;">Welcome back Teacher, <?php echo htmlspecialchars($res_fName . " " . $res_lName); ?>!</h1>
        <div class="d-flex align-items-center">
            <p style="font-size: 1.2rem; margin-right: 15px; margin-bottom: 0;">
                Current Time: <span id="currentTime"><?php echo $currentTime; ?></span>
            </p>
            <div class="date-picker-container">
                <i class="far fa-calendar-alt calendar-icon"></i>
                <input type="date" class="form-control date-input" name="date" value="<?php echo isset($date_filter) ? $date_filter : date('Y-m-d'); ?>">
            </div>
        </div>
    </div>
    <div class="circle-person ms-md-3" style="position: relative; right: 80px;"> 
        <img src="../../uploads/profiles/<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="rounded-circle profile-img">
    </div>
</div>
            <script>
                
                function updateClock() {
                    
                    const now = new Date();
                    
                    const options = {
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit',
                        hour12: true,
                        timeZone: 'Asia/Manila'
                    };
                 
                    const formatter = new Intl.DateTimeFormat('en-US', options);
                    const timeString = formatter.format(now);
                    
                    document.getElementById('currentTime').textContent = timeString;
                }

                updateClock();
                setInterval(updateClock, 1000);
            </script>


            <div class="container">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="bookshelf-title">Collection of Modules</h1>
                    <a href="upload.php"><button class="upload-btn"><i class="fas fa-upload me-2"></i>Upload New Module</button></a>
                </div>

                <div class="search-filter-form">
            <form class="row g-3 align-items-center" action="" method="get">
                <!-- Search Input -->
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input class="form-control" type="search" name="search" placeholder="Search modules..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-filter"></i></span>
                        <select name="category" class="form-select">
                            <option value="All" <?= $selected_category === 'All' ? 'selected' : '' ?>>All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= htmlspecialchars($category['book_category']) ?>" 
                                        <?= $selected_category === $category['book_category'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($category['book_category']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <!-- Search Button -->
                <div class="col-md-4">
                    <button class="btn btn-primary w-100" type="submit">
                        <i class="fas fa-search me-2"></i>Search
                    </button>
                </div>
            </form>
        </div>

            <!-- Display Books -->
        <div class="books">
            <?php if (count($books) > 0): ?>
                <?php foreach ($books as $book): ?>
                    <div class="book">
                        <img alt="<?= htmlspecialchars($book['title']) ?> book cover" 
                            src="uploads/<?= htmlspecialchars($book['cover_image']) ?>" />
                        <div class="book-content">
                            <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                            <div class="book-status">
                                <?= isset($book['status']) ? htmlspecialchars($book['status']) : 'Uploaded' ?>
                            </div>
                            <div class="book-meta">
                                <p>Category: <?= htmlspecialchars($book['book_category']) ?></p>
                                <p>Description: <?= htmlspecialchars($book['description']) ?></p>
                            </div>
                            <div class="book-actions">
                                <a href="editBook.php?id=<?= htmlspecialchars($book['id']) ?>" 
                                class="btn btn-book btn-edit">Edit</a>
                                <a href="deleteBook.php?id=<?= htmlspecialchars($book['id']) ?>" 
                                class="btn btn-book btn-delete" 
                                onclick="return confirm('Are you sure you want to delete this book ?');">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div>
                <p>No modules found matching your search or filter criteria.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<script src="../../js/bootstrap.bundle.min.js"></script>
<script src="../../js/bootstrap.min.js"></script>
    
</body>
</html>