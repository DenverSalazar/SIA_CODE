<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}

$student_query = mysqli_query($con, "SELECT * FROM students");
$teacher_query = mysqli_query($con, "SELECT * FROM teacher");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <style>
        .table-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 30px;
        }
        .table-title {
            font-size: 1.5rem;
            color: #333;
            margin-bottom: 20px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }
        .table tbody tr:hover {
            background-color: #f1f3f5;
        }
        .table td, .table th {
            vertical-align: middle;
        }
        .btn-action {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }
        .custom-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
            padding: 15px;
            border-radius: 4px;
            display: none;
            animation: slideIn 0.5s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
    </style>
</head>
<body>
        <div id="alertMessage" class="custom-alert">
                <span id="alertText"></span>
            </div>
            <?php
    // Check for success/error messages from URL parameters
    if(isset($_GET['success'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const alert = document.getElementById('alertMessage');
                const alertText = document.getElementById('alertText');
                alert.className = 'custom-alert alert-success';
                alertText.textContent = 'Account successfully accepted!';
                alert.style.display = 'block';
                
                // Hide the alert after 3 seconds
                setTimeout(function() {
                    alert.style.display = 'none';
                    // Remove the query parameter from URL
                    window.history.replaceState({}, document.title, window.location.pathname);
                }, 3000);
            });
        </script>";
    } elseif(isset($_GET['error'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const alert = document.getElementById('alertMessage');
                const alertText = document.getElementById('alertText');
                alert.className = 'custom-alert alert-danger';
                alertText.textContent = 'Error accepting account. Please try again.';
                alert.style.display = 'block';
                
                // Hide the alert after 3 seconds
                setTimeout(function() {
                    alert.style.display = 'none';
                    // Remove the query parameter from URL
                    window.history.replaceState({}, document.title, window.location.pathname);
                }, 3000);
            });
        </script>";
    }
    ?>

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
        <h2 class="mb-4">Accounts Management</h2>

        <!-- Student Accounts Table -->
        <div class="table-container">
            <h3 class="table-title"><i class="fas fa-user-graduate mr-2"></i>Student Accounts</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Department</th> 
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($student_query)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['fName']; ?></td>
                                <td><?php echo $row['lName']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['department']; ?></td> 
                               <!-- In the student accounts table -->
                                <td>
                                    <?php if($row['is_accepted'] == 0): ?>
                                        <a href="accept_account.php?id=<?php echo $row['id']; ?>&type=student" class="btn btn-sm btn-outline-success btn-action mr-2"><i class="fas fa-check"></i>Accept</a>
                                    <?php else: ?>
                                        <span class="text-success"><i class="fas fa-check-circle"></i>Accepted</span>
                                    <?php endif; ?>
                                    <a href="delete_account.php?id=<?php echo $row['id']; ?>&type=student" class="btn btn-sm btn-outline-danger btn-action" onclick="return confirm('Are you sure you want to delete this student?')"><i class="fas fa-trash-alt"></i> Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Admin Accounts Table -->
        <div class="table-container">
            <h3 class="table-title"><i class="fas fa-user-shield mr-2"></i>Admin Accounts</h3>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($row = mysqli_fetch_assoc($teacher_query)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['fName']; ?></td>
                                <td><?php echo $row['lName']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <!-- <a href="edit_admin.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary btn-action mr-2"><i class="fas fa-edit"></i> Edit</a> -->
                                    <a href="delete_account.php?id=<?php echo $row['id']; ?>&type=admin" class="btn btn-sm btn-outline-danger btn-action" onclick="return confirm('Are you sure you want to delete this admin?')"><i class="fas fa-trash-alt"></i>Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>