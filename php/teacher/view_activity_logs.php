<?php
include('../../php/db_config.php');
session_start();

if(!isset($_SESSION['valid']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../../login.php");
    exit();
}

// Get the student ID from the URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $student_id = mysqli_real_escape_string($con, $_GET['id']);
} else {
    header("Location: accounts.php");
    exit();
}

// Query to fetch student information
$student_query = mysqli_query($con, "SELECT fName, lName FROM students WHERE id = '$student_id'");
$student_info = mysqli_fetch_assoc($student_query);

// Fetch unique action types for the filter
$action_types_query = mysqli_query($con, "SELECT DISTINCT action FROM activity_logs WHERE student_id = '$student_id'");
$action_types = [];
while ($type = mysqli_fetch_assoc($action_types_query)) {
    $action_types[] = $type['action'];
}

// Fetch filter inputs
$filter_action = isset($_GET['filter_action']) ? mysqli_real_escape_string($con, $_GET['filter_action']) : '';
$date_range = isset($_GET['date_range']) ? mysqli_real_escape_string($con, $_GET['date_range']) : '';

// Parse the date range if provided
$date_conditions = '';
if ($date_range) {
    $dates = explode(' - ', $date_range);
    if (count($dates) == 2) {
        $start_date = $dates[0];
        $end_date = $dates[1];
        $date_conditions = " AND timestamp BETWEEN '$start_date' AND '$end_date'";
    }
}

// Build query with filters
$where_clause = "WHERE student_id = '$student_id'";
if ($filter_action) {
    $where_clause .= " AND action = '$filter_action'";
}
$where_clause .= $date_conditions;

$activity_query = mysqli_query($con, "SELECT * FROM activity_logs $where_clause ORDER BY timestamp DESC");

function getActionIcon($action) {
    switch ($action) {
        case 'login':
            return '<i class="fas fa-sign-in-alt text-success"></i>';
        case 'logout':
            return '<i class="fas fa-sign-out-alt text-danger"></i>';
        case 'view_book':
        case 'view_module':
            return '<i class="fas fa-book-open text-info"></i>';
        case 'download_book':
        case 'download_module':
            return '<i class="fas fa-download text-primary"></i>';
        case 'update_profile':
            return '<i class="fas fa-user-edit text-warning"></i>';
        default:
            return '<i class="fas fa-info-circle"></i>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Logs of <?php echo htmlspecialchars($student_info['fName'] . ' ' . $student_info['lName']); ?></title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homeAdmin.css">
    <link rel="stylesheet" href="/SIA/css/activity_logs.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
</head>
<style>
    .sidebar{
        background-color: #052659;
    }
    .filter-container{ 
       margin-bottom: 20px;
    }
    .filter-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .filter-form .form-control,
        .filter-form .btn {
            border-radius: 8px;
        }
        .btn-filter {
            background-color: #052659;
            color: #fff;
            transition: background 0.3s ease;
        }
        .btn-filter:hover {
            background-color: #0c3d91;
        }
        .btn-reset {
            background-color: #e63946;
            color: #fff;
            transition: background 0.3s ease;
        }
        .btn-reset:hover {
            background-color: #d82f40;
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
                    $active_class = ($current_page === $page || ($current_page === 'view_activity_logs.php' && $page === 'activity_logs.php')) ? 'active' : '';
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
            <div class="container mt">
            <div class="container">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="bookshelf-title">Activity Logs of <?php echo htmlspecialchars($student_info['fName'] . ' ' . $student_info['lName']); ?></h1>
                            <a href="activity_logs.php" class="btn btn-cancel">Back</a>
                        </div>
                          <!-- FILTER -->
        <form method="GET" class="filter-form">
            <input type="hidden" name="id" value="<?php echo $student_id; ?>">
            <div class="row">
                <div class="col-md-4">
                    <label for="filter_action" class="form-label">Action</label>
                    <select name="filter_action" id="filter_action" class="form-control">
                        <option value="">All Actions</option>
                        <?php foreach ($action_types as $action): ?>
                            <option value="<?php echo htmlspecialchars($action); ?>" <?php echo $filter_action == $action ? 'selected' : ''; ?>>
                                <?php echo ucfirst($action); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="date_range" class="form-label">Date Range</label>
                    <input type="text" name="date_range" id="date_range" class="form-control" value="<?php echo htmlspecialchars($date_range); ?>" placeholder="Select Date Range">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-filter w-100 me-2">Filter</button>
                </div>
            </div>
        </form>

                    </div>
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Action</th>
                                <th>Details</th>
                                <th>Timestamp</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($activity_query) > 0): ?>
                                <?php while ($row = mysqli_fetch_assoc($activity_query)): ?>
                                    <tr>
                                        <td>
                                            <?php echo getActionIcon($row['action']); ?>
                                            <?php echo ucfirst(htmlspecialchars($row['action'])); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($row['details']); ?></td>
                                        <td><?php echo htmlspecialchars($row['timestamp']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No activity logs found for this student.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
    $(function() {
        $('#date_range').daterangepicker({
            locale: {
                format: 'YYYY-MM-DD'
            },
            autoUpdateInput: false,
            opens: 'right',
        });

        $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
        });

        $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
        });
    });
</script>
    <script src="../../js/bootstrap.bundle.min.js"></script>
</body>
</html>