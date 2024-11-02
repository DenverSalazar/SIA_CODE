<?php
        include('../../php/db_config.php');
        session_start();

        date_default_timezone_set('Asia/Manila');
        $currentTime = date('H:i:s'); 

        if(!isset($_SESSION['valid'])){
            header("Location: ../../login.php");
        }

        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $role = $_SESSION['role'];

            if($role == 'student'){
                $query = mysqli_query($con,"SELECT * FROM students WHERE id = '$id'");
            } else if($role == 'teacher'){
                $query = mysqli_query($con,"SELECT * FROM teacher WHERE id = '$id'");
            }

            while($result = mysqli_fetch_assoc($query)){
                $res_fName = $result['fName'];
                $res_lName = $result['lName'];
                $res_email = $result['email'];
            }
        } else {
            echo "Error: ID is not set or empty.";
    
        }

        $query = mysqli_query($con, "SELECT * FROM students");
        $total_students = mysqli_num_rows($query);

        $query2 = mysqli_query($con, "SELECT * FROM teacher");
        $total_teachers = mysqli_num_rows($query2);

        $query3 = mysqli_query($con, "SELECT COUNT(*) as total_books FROM books");
        $result3 = mysqli_fetch_assoc($query3);
        $total_books = $result3['total_books'];


        // Get today's date and past week's date
        $today = date('Y-m-d');
        $past_week = date('Y-m-d', strtotime('-7 days'));

        // Query for new registrations in the past week
        $new_registrations_query = mysqli_query($con, "
            SELECT COUNT(*) as new_users 
            FROM students 
            WHERE DATE(created_at) BETWEEN '$past_week' AND '$today'
        ");

        // Get total logins/activity for today
        $today = date('Y-m-d');
        $today_activity_query = mysqli_query($con, "
            SELECT COUNT(*) as today_active 
            FROM login_history 
            WHERE DATE(login_time) = '$today'
        ");
        $today_active = mysqli_fetch_assoc($today_activity_query)['today_active'];

        $new_registrations = mysqli_fetch_assoc($new_registrations_query)['new_users'];

        // Query for new books/modules added this week
        $new_books_query = mysqli_query($con, "
            SELECT COUNT(*) as new_books 
            FROM books 
            WHERE DATE(upload_date) BETWEEN '$past_week' AND '$today'
        ");
        $new_books = mysqli_fetch_assoc($new_books_query)['new_books'];

       // Get today's active users
            $today = date('Y-m-d');
            $today_active_query = mysqli_query($con, "
            SELECT COUNT(DISTINCT user_id) as today_active 
            FROM login_history 
            WHERE DATE(login_time) = '$today'
            ");
            $today_active = mysqli_fetch_assoc($today_active_query)['today_active'];

            // Get yesterday's active users for comparison
            $yesterday = date('Y-m-d', strtotime('-1 day'));
            $yesterday_active_query = mysqli_query($con, "
            SELECT COUNT(DISTINCT user_id) as yesterday_active 
            FROM login_history 
            WHERE DATE(login_time) = '$yesterday'
            ");
            $yesterday_active = mysqli_fetch_assoc($yesterday_active_query)['yesterday_active'];

            // Calculate percentage change
            $active_change = $yesterday_active > 0 ? 
            (($today_active - $yesterday_active) / $yesterday_active) * 100 : 
            100;
        // For new books
        $prev_week_books_query = mysqli_query($con, "
            SELECT COUNT(*) as prev_books 
            FROM books 
            WHERE DATE(upload_date) BETWEEN DATE_SUB('$past_week', INTERVAL 7 DAY) AND '$past_week'
        ");
        $prev_week_books = mysqli_fetch_assoc($prev_week_books_query)['prev_books'];
        $books_change = $prev_week_books > 0 ? (($new_books - $prev_week_books) / $prev_week_books) * 100 : 0;

        // For new registrations
        $prev_week_reg_query = mysqli_query($con, "
            SELECT COUNT(*) as prev_reg 
            FROM students 
            WHERE DATE(created_at) BETWEEN DATE_SUB('$past_week', INTERVAL 7 DAY) AND '$past_week'
        ");
        $prev_week_reg = mysqli_fetch_assoc($prev_week_reg_query)['prev_reg'];
        $reg_change = $prev_week_reg > 0 ? (($new_registrations - $prev_week_reg) / $prev_week_reg) * 100 : 0;

       // Feedback Statistics Queries
        $total_feedback_query = mysqli_query($con, "SELECT COUNT(*) as total FROM feedback");
        $total_feedback = mysqli_fetch_assoc($total_feedback_query)['total'];

        // Average rating
        $avg_rating_query = mysqli_query($con, "SELECT AVG(rating) as avg_rating FROM feedback");
        $avg_rating = round(mysqli_fetch_assoc($avg_rating_query)['avg_rating'], 1);

        // Rating distribution
        $rating_distribution_query = mysqli_query($con, "
            SELECT rating, COUNT(*) as count 
            FROM feedback 
            GROUP BY rating 
            ORDER BY rating DESC
        ");
        $rating_distribution = [];
        while($row = mysqli_fetch_assoc($rating_distribution_query)) {
            $rating_distribution[$row['rating']] = $row['count'];
        }

        // Recent feedback count (last 7 days)
        $recent_feedback_query = mysqli_query($con, "
            SELECT COUNT(*) as recent_count 
            FROM feedback 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
        $recent_feedback = mysqli_fetch_assoc($recent_feedback_query)['recent_count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN HOMEPAGE</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/SIA/css/adminstyle.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
     .dashboard-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        padding: 20px;
        transition: all 0.3s ease;
    }
    
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
    }
    
    .dashboard-card .card-content h1 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 5px;
    }
    
    .dashboard-card .card-content p {
        font-size: 1rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    
    .dashboard-card .icon-container {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 1.5rem;
        color: #fff;
    }
    
    .dashboard-card .icon-container.blue { background-color: #007bff; }
    .dashboard-card .icon-container.green { background-color: #28a745; }
    .dashboard-card .icon-container.orange { background-color: #ffc107; }
    .dashboard-card .icon-container.red { background-color: #dc3545; }
</style>
</head>
<body>
        <div class="sidebar">
            <h5 class="sidebar-title mb-5">
                <img src="../../img/logo.png" alt="Logo" width="190" height="20">
            </h5>
            <ul class="nav flex-column">
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $nav_items = [
                    'homeAdmin.php' => ['icon' => 'fas fa-home', 'text' => 'Dashboard'],
                    'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                    'bookAdmin.php' => ['icon' => 'fas fa-book', 'text' => 'Bookshelf'],
                    'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                    'admin_feedback.php' => ['icon' => 'fas fa-envelope', 'text' => 'Feedbacks'],
                    '/SIA/php/teacher/admin_profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
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
                    <h1 style="font-weight: bold;">Welcome back, <?php echo $res_fName . " " . $res_lName; ?>!</h1>
                    <p style="font-size: 1.2rem;">Current Time: <span id="currentTime"><?php echo $currentTime; ?></span></p>
                </div>
                <div class="circle-person ms-md-3" style="position: relative; right: 80px;"> 
                    <?php 
                    // Check if user has a profile picture
                    $profile_picture_query = mysqli_query($con, "SELECT profile_picture FROM " . ($role == 'student' ? 'students' : 'teacher') . " WHERE id = '$id'");
                    $profile_result = mysqli_fetch_assoc($profile_picture_query);
                    
                    if(isset($profile_result['profile_picture']) && !empty($profile_result['profile_picture'])) {
                        $profile_picture = $profile_result['profile_picture'];
                        echo '<img src="../../uploads/profiles/' . $profile_picture . '" alt="Profile Picture" class="rounded-circle profile-img">';
                    } else {
                        // Default profile picture if none is uploaded
                        echo '<img src="../../img/admin-icon.jpg" alt="Default Profile" class="rounded-circle profile-img">';
                    }
                    ?>
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

                 <div class="container align-items-center justify-content-center mt-4">
                        <div class="row justify-content-center">
                            <!-- Students Card -->
                            <div class="col-md-4 mb-4">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="card-title text-muted">Total Students</h5>
                                                <h2 class="card-text fw-bold"><?php echo $total_students; ?></h2>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon-shape">
                                                    <i class="fas fa-user-graduate"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <span class="text-success">
                                                <i class="fas fa-arrow-up"></i> <?php echo $new_registrations; ?>
                                            </span>
                                            <span class="text-muted">New this week</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Admin Card -->
                            <div class="col-md-4 mb-4">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="card-title text-muted">Total Admin</h5>
                                                <h2 class="card-text fw-bold"><?php echo $total_teachers; ?></h2>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon-shape">
                                                    <i class="fas fa-user-shield"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <span class="text-primary">
                                                <i class="fas fa-user-check"></i>
                                            </span>
                                            <span class="text-muted">Active accounts</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modules Card -->
                            <div class="col-md-4 mb-4">
                                <div class="card dashboard-card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-8">
                                                <h5 class="card-title text-muted">Total Modules</h5>
                                                <h2 class="card-text fw-bold"><?php echo $total_books; ?></h2>
                                            </div>
                                            <div class="col-4 text-end">
                                                <div class="icon-shape">
                                                    <i class="fas fa-book"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-3">
                                            <span class="text-info">
                                                <i class="fas fa-arrow-up"></i> <?php echo $new_books; ?>
                                            </span>
                                            <span class="text-muted">New this week</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container mt-5">
                        <div class="row">
                            <!-- Bar Chart -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body chart-container">
                                        <h5 class="card-title">User Statistics</h5>
                                        <canvas id="userChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <!-- Pie Chart -->
                            <div class="col-md-6 mb-4">
                                <div class="card">
                                    <div class="card-body chart-container">
                                        <h5 class="card-title">System Overview</h5>
                                        <canvas id="systemChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> <br>

                    <div class="row">
                        <div class="col-md-12">
                            <h3>Feedback Overview</h3>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Total Feedback -->
                        <div class="col-md-3 mb-4">
                            <div class="dashboard-card">
                                <div class="card-content">
                                    <h1><?php echo $total_feedback; ?></h1>
                                    <p>Total Feedback</p>
                                </div>
                                <div class="icon-container blue">
                                    <i class="fas fa-comments"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Average Rating -->
                        <div class="col-md-3 mb-4">
                            <div class="dashboard-card">
                                <div class="card-content">
                                    <h1><?php echo $avg_rating; ?><small>/5</small></h1>
                                    <p>Average Rating</p>
                                </div>
                                <div class="icon-container green">
                                    <i class="fas fa-star"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Feedback -->
                        <div class="col-md-3 mb-4">
                            <div class="dashboard-card">
                                <div class="card-content">
                                    <h1><?php echo $recent_feedback; ?></h1>
                                    <p>Recent Feedback (7 days)</p>
                                </div>
                                <div class="icon-container orange">
                                    <i class="fas fa-clock"></i>
                                </div>
                            </div>
                        </div>

                        <!-- 5-Star Ratings -->
                        <div class="col-md-3 mb-4">
                            <div class="dashboard-card">
                                <div class="card-content">
                                    <h1><?php echo isset($rating_distribution[5]) ? $rating_distribution[5] : 0; ?></h1>
                                    <p>5-Star Ratings</p>
                                </div>
                                <div class="icon-container red">
                                    <i class="fas fa-trophy"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Rating Distribution Chart -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Rating Distribution</h3>
                                </div>
                                <div class="card-body">
                                    <canvas id="ratingDistributionChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                   <!-- Additional Statistics -->
                <div class="container mt-4">
                <div class="row">
                <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Recent Activity</h5>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Metric</th>
                                        <th>Value</th>
                                        <th>Change</th>
                                        <th>Period</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <i class="fas fa-users"></i>
                                            Active Users Today
                                        </td>
                                        <td><?php echo $today_active; ?></td>
                                        <td>
                                            <?php
                                            $arrow = $active_change >= 0 ? '↑' : '↓';
                                            $color = $active_change >= 0 ? 'success' : 'danger';
                                            echo "<span class='text-$color'>$arrow " . abs(round($active_change, 1)) . "%</span>";
                                            ?>
                                        </td>
                                        <td>vs Yesterday</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fas fa-book"></i>
                                            Modules Added
                                        </td>
                                        <td><?php echo $new_books; ?></td>
                                        <td>
                                            <?php
                                            $arrow = $books_change >= 0 ? '↑' : '↓';
                                            $color = $books_change >= 0 ? 'success' : 'danger';
                                            echo "<span class='text-$color'>$arrow " . abs(round($books_change, 1)) . "%</span>";
                                            ?>
                                        </td>
                                        <td>This Week</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <i class="fas fa-user-plus"></i>
                                            New Registrations
                                        </td>
                                        <td><?php echo $new_registrations; ?></td>
                                        <td>
                                            <?php
                                            $arrow = $reg_change >= 0 ? '↑' : '↓';
                                            $color = $reg_change >= 0 ? 'success' : 'danger';
                                            echo "<span class='text-$color'>$arrow " . abs(round($reg_change, 1)) . "%</span>";
                                            ?>
                                        </td>
                                        <td>This Week</td>
                                        </tr>
                                     </tbody>
                                 </table>
                              </div>
                                </div>
                                </div>
                               </div>
                             </div>
                            </div>
                        </section>
                    </div>
                </main>

                    <script>
                    // Bar Chart
                    const userCtx = document.getElementById('userChart').getContext('2d');
                    new Chart(userCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Students', 'Admin', 'Modules'],
                            datasets: [{
                                label: 'Total Count',
                                data: [<?php echo $total_students; ?>, <?php echo $total_teachers; ?>, <?php echo $total_books; ?>],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.5)',
                                    'rgba(255, 99, 132, 0.5)',
                                    'rgba(75, 192, 192, 0.5)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(75, 192, 192, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });

                    // Pie Chart
                    const systemCtx = document.getElementById('systemChart').getContext('2d');
                    new Chart(systemCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Students', 'Admin', 'Modules'],
                            datasets: [{
                                data: [<?php echo $total_students; ?>, <?php echo $total_teachers; ?>, <?php echo $total_books; ?>],
                                backgroundColor: [
                                    'rgba(54, 162, 235, 0.8)',
                                    'rgba(255, 99, 132, 0.8)',
                                    'rgba(75, 192, 192, 0.8)'
                                ],
                                borderColor: [
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(75, 192, 192, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });

                  // Rating Distribution Chart
                    const ratingCtx = document.getElementById('ratingDistributionChart').getContext('2d');
                    new Chart(ratingCtx, {
                        type: 'bar',
                        data: {
                            labels: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
                            datasets: [{
                                label: 'Number of Ratings',
                                data: [
                                    <?php echo isset($rating_distribution[5]) ? $rating_distribution[5] : 0; ?>,
                                    <?php echo isset($rating_distribution[4]) ? $rating_distribution[4] : 0; ?>,
                                    <?php echo isset($rating_distribution[3]) ? $rating_distribution[3] : 0; ?>,
                                    <?php echo isset($rating_distribution[2]) ? $rating_distribution[2] : 0; ?>,
                                    <?php echo isset($rating_distribution[1]) ? $rating_distribution[1] : 0; ?>
                                ],
                                backgroundColor: [
                                    '#28a745',
                                    '#20c997',
                                    '#ffc107',
                                    '#fd7e14',
                                    '#dc3545'
                                ],
                                borderColor: [
                                    '#28a745',
                                    '#20c997',
                                    '#ffc107',
                                    '#fd7e14',
                                    '#dc3545'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    display: false
                                },
                                title: {
                                    display: false
                                }
                            }
                        }
                    });

                </script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
    <script src="../../js/bootstrap.min.js"></script>
    
</body>
</html>