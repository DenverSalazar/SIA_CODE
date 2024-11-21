<?php
include('../../php/db_config.php');
session_start();

if (!isset($_SESSION['valid'])) {
    header("Location: ../../login.php");
}

$student_query = mysqli_query($con, "SELECT * FROM students WHERE is_accepted = 1");
$teacher_query = mysqli_query($con, "SELECT * FROM teacher WHERE is_accepted = 1");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/activity_logs.css">
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
    @media (min-width: 768px) {
        .search-filter-container .form-control {
            margin-bottom: 0;
        }
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

    <main class="content">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="bookshelf-title">Activity Logs of Students And Teachers</h1>
            </div>
        </div>

        <!-- Accepted Students -->
        <div class="table-container">
            <div class="search-filter-container mb-3 text-center">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <input type="text" id="studentSearchInput" class="form-control" placeholder="Search students by name, email, or ID...">
                    </div>
                </div>
            </div>
            <h3 class="table-title"><i class="fas fa-user-graduate mr-2"></i>Accepted Students</h3>
            <div class="table-responsive">
                <table class="table table-hover" id="studentTable">
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
                        <?php while ($row = mysqli_fetch_assoc($student_query)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['fName']; ?></td>
                                <td><?php echo $row['lName']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['department']; ?></td>
                                <td>
                                    <a href="view_activity_logs.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary btn-action">
                                        <i class="fas fa-eye"></i> View Activity
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Accepted Teachers -->
        <div class="table-container">
            <div class="search-filter-container mb-3 text-center">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <input type="text" id="teacherSearchInput" class="form-control" placeholder="Search teachers by name, email, or ID...">
                    </div>
                </div>
            </div>
            <h3 class="table-title"><i class="fas fa-chalkboard-teacher mr-2"></i>Accepted Teachers</h3>
            <div class="table-responsive">
                <table class="table table-hover" id="teacherTable">
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
                        <?php while ($row = mysqli_fetch_assoc($teacher_query)): ?>
                            <tr>
                                <td><?php echo $row['id']; ?></td>
                                <td><?php echo $row['fName']; ?></td>
                                <td><?php echo $row['lName']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td>
                                    <a href="view_teacher_activity_logs.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary btn-action">
                                        <i class="fas fa-eye"></i> View Activity
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
    // Filter Function
    function filterTable(inputId, tableId) {
        const input = document.getElementById(inputId);
        const filter = input.value.toLowerCase();
        const table = document.getElementById(tableId);
        const rows = table.getElementsByTagName("tr");

        for (let i = 1; i < rows.length; i++) {
            const cells = rows[i].getElementsByTagName("td");
            let rowContainsFilter = false;

            for (let cell of cells) {
                if (cell.textContent.toLowerCase().includes(filter)) {
                    rowContainsFilter = true;
                    break;
                }
            }

            rows[i].style.display = rowContainsFilter ? "" : "none";
        }
    }

    // Event Listeners
    document.getElementById("studentSearchInput").addEventListener("keyup", () => {
        filterTable("studentSearchInput", "studentTable");
    });

    document.getElementById("teacherSearchInput").addEventListener("keyup", () => {
        filterTable("teacherSearchInput", "teacherTable");
    });
    </script>

    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>
