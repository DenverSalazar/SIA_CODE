<?php
include('../../php/db_config.php');
session_start();

if (!isset($_SESSION['valid'])) {
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/accounts.css">
</head>
<style>
    .sidebar {
        background-color: #052659;
    }

    .search-filter-container {
        background-color: #c1e8ff;
        padding: 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .search-filter-container .form-control {
        margin-bottom: 10px;
    }
</style>

<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h5 class="sidebar-title mb-5">
            <img src="../../img/logo.png" alt="Logo" width="190" height="20">
        </h5>
        <ul class="nav flex-column">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']);
            $nav_items = [
                'admin_home.php' => ['icon' => 'fas fa-chart-bar', 'text' => 'Dashboard'],
                'accounts.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                'activity_logs.php' => ['icon' => 'fas fa-history', 'text' => 'Activity Logs'],
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

    <!-- Main Content -->
    <main class="content">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="bookshelf-title">Accounts Management</h1>
            </div>

            <!-- Student Accounts Table -->
            <div class="table-container">
                <div class="search-filter-container mb-3 text-center">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <input type="text" id="studentSearchInput" class="form-control" placeholder="Search students...">
                        </div>
                        <div class="col-md-3">
                            <select id="studentStatusFilter" class="form-control">
                                <option value="">All Status</option>
                                <option value="1">Accepted</option>
                                <option value="0">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>

                <h3 class="table-title"><i class="fas fa-user-graduate mr-2"></i>Student Accounts</h3>
                <div class="table-responsive">
                    <table class="table table-hover student-table">
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
                            <?php while ($row = mysqli_fetch_assoc($student_query)) : ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['fName']; ?></td>
                                    <td><?php echo $row['lName']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td><?php echo $row['department']; ?></td>
                                    <td data-status="<?php echo $row['is_accepted']; ?>">
                                        <?php if ($row['is_accepted'] == 0) : ?>
                                            <a href="accept_account.php?id=<?php echo $row['id']; ?>&type=student" class="btn btn-sm btn-outline-success btn-action mr-2"><i class="fas fa-check"></i>Accept</a>
                                        <?php else : ?>
                                            <span class="text-success"><i class="fas fa-check-circle"></i>Accepted</span>
                                        <?php endif; ?>
                                        <a href="view_accounts.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary btn-action mr-2"><i class="fas fa-eye"></i> View</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Teacher Accounts Table -->
            <div class="table-container">
                <div class="search-filter-container mb-3 text-center">
                    <div class="row justify-content-center">
                        <div class="col-md-4">
                            <input type="text" id="teacherSearchInput" class="form-control" placeholder="Search teachers...">
                        </div>
                        <div class="col-md-3">
                            <select id="teacherStatusFilter" class="form-control">
                                <option value="">All Status</option>
                                <option value="1">Accepted</option>
                                <option value="0">Pending</option>
                            </select>
                        </div>
                    </div>
                </div>

                <h3 class="table-title"><i class="fas fa-user-shield mr-2"></i>Teacher Accounts</h3>
                <div class="table-responsive">
                    <table class="table table-hover teacher-table">
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
                            <?php while ($row = mysqli_fetch_assoc($teacher_query)) : ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo $row['fName']; ?></td>
                                    <td><?php echo $row['lName']; ?></td>
                                    <td><?php echo $row['email']; ?></td>
                                    <td data-status="<?php echo $row['is_accepted']; ?>">
                                        <?php if ($row['is_accepted'] == 0) : ?>
                                            <a href="accept_account.php?id=<?php echo $row['id']; ?>&type=teacher" class="btn btn-sm btn-outline-success btn-action mr-2"><i class="fas fa-check"></i>Accept</a>
                                        <?php else : ?>
                                            <span class="text-success"><i class="fas fa-check-circle"></i>Accepted</span>
                                        <?php endif; ?>
                                        <a href="view_teacher.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary btn-action mr-2"><i class="fas fa-eye"></i> View</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Search and Filter Function
        function filterTable(tableId, searchInput, filters = {}) {
            const input = document.getElementById(searchInput);
            const table = document.querySelector(tableId + ' tbody');
            const rows = table.getElementsByTagName('tr');

            for (let row of rows) {
                let text = row.textContent.toLowerCase();
                let showRow = true;

                // Check search text
                if (input.value) {
                    if (!text.includes(input.value.toLowerCase())) {
                        showRow = false;
                    }
                }

                // Check additional filters
                for (let key in filters) {
                    if (filters[key].value) {
                        let cell = row.querySelector(`td[data-${key}]`);
                        if (cell && cell.getAttribute(`data-${key}`) !== filters[key].value) {
                            showRow = false;
                        }
                    }
                }

                row.style.display = showRow ? '' : 'none';
            }
        }

        // For Students
        if (document.getElementById('studentSearchInput')) {
            document.getElementById('studentSearchInput').addEventListener('keyup', () => {
                filterTable('.student-table', 'studentSearchInput', {
                    'status': document.getElementById('studentStatusFilter')
                });
            });

            document.getElementById('studentStatusFilter')?.addEventListener('change', () => {
                filterTable('.student-table', 'studentSearchInput', {
                    'status': document.getElementById('studentStatusFilter')
                });
            });
        }

        // For Teachers
        if (document.getElementById('teacherSearchInput')) {
            document.getElementById('teacherSearchInput').addEventListener('keyup', () => {
                filterTable('.teacher-table', 'teacherSearchInput', {
                    'status': document.getElementById('teacherStatusFilter')
                });
            });

            document.getElementById('teacherStatusFilter')?.addEventListener('change', () => {
                filterTable('.teacher-table', 'teacherSearchInput', {
                    'status': document.getElementById('teacherStatusFilter')
                });
            });
        }
    </script>
</body>

</html>
