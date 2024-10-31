<?php
include('../../php/db_config.php');
session_start();
if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $admin_id = $_SESSION['id'];
} else {
    echo "Error: ID is not set or empty.";
}

// Fetch student emails from the database
$student_query = "SELECT id, email FROM students"; // Adjust table name as needed
$student_result = mysqli_query($con, $student_query);
$students = [];
while ($row = mysqli_fetch_assoc($student_result)) {
    $students[] = $row; // Store the student id and email
}

// Fetch conversations
$conversations_query = "
    SELECT DISTINCT s.id, s.email, s.fName, s.lName,
    (SELECT message FROM messages 
     WHERE (student_id = s.id AND admin_id = ?) 
     OR (student_id = ? AND admin_id = s.id) 
     ORDER BY created_at DESC LIMIT 1) as last_message,
    (SELECT created_at FROM messages 
     WHERE (student_id = s.id AND admin_id = ?) 
     OR (student_id = ? AND admin_id = s.id) 
     ORDER BY created_at DESC LIMIT 1) as last_message_time,
    (SELECT COUNT(*) FROM messages 
     WHERE student_id = s.id AND admin_id = ? AND is_read = 0) as unread_count
    FROM students s
    INNER JOIN messages m ON (s.id = m.student_id OR s.id = m.admin_id)
    WHERE m.admin_id = ? OR m.student_id = ?
    GROUP BY s.id
    ORDER BY last_message_time DESC
";

$stmt = $con->prepare($conversations_query);
$stmt->bind_param("iiiiiii", $admin_id, $admin_id, $admin_id, $admin_id, $admin_id, $admin_id, $admin_id);
$stmt->execute();
$conversations_result = $stmt->get_result();
$conversations = $conversations_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
         .conversation-list {
            height: 530px;
            overflow-y: auto;
        }
        .conversation-item {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #eee;
        }
        .conversation-item:hover {
            background-color: #f8f9fa;
        }
        .unread {
            font-weight: bold;
        }
        .unread-badge {
            background-color: #007bff;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.8em;
        }
        body {
            background-color: #f0f2f5;
        }
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            transition: 0.3s;
            z-index: 1000;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 10px;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
        }
        .sidebar ul li a:hover {
            background-color: #495057;
            border-radius: 5px;
            padding: 8px;
        }
        .content {
            margin-left: 260px;
            padding: 20px;
            transition: 0.3s;
        }
        .chat-container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            height: calc(100vh - 40px);
            display: flex;
            flex-direction: column;
        }
        .chat-header {
            padding: 15px;
            background-color: #f0f2f5;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .chat-box {
            flex-grow: 1;
            padding: 15px;
            overflow-y: auto;
        }
        .message {
            margin-bottom: 10px;
            max-width: 70%;
            padding: 10px;
            border-radius: 18px;
        }
        .message.admin {
            align-self: flex-end;
            background-color: #0084ff;
            color: white;
            margin-left: auto;
        }
        .message.student {
            align-self: flex-start;
            background-color: #e4e6eb;
            color: black;
        }
        .chat-input {
            padding: 15px;
            background-color: #f0f2f5;
            border-bottom-left-radius: 10px;
            border-bottom-right-radius: 10px;
        }
        .input-group {
            background-color: white;
            border-radius: 20px;
            overflow: hidden;
        }
        #message {
            border: none;
            border-radius: 20px;
            padding-left: 20px;
        }
        #message:focus {
            box-shadow: none;
        }
        .btn-send {
            background-color: transparent;
            border: none;
            color: #0084ff;
        }
        .btn-send:hover {
            color: #0056b3;
        }
        .sidebar-title {
            filter: brightness(0) invert(1);
            text-align: center;
        }
        /* Add these styles to your existing style section */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            transition: 0.3s;
            z-index: 1000;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }

        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 12px 20px;
            margin: 4px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .sidebar .nav-link:hover {
            color: #fff;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 8px;
        }

        .sidebar-title {
            padding: 0 20px;
            margin-bottom: 30px;
        }

        .sidebar-title img {
            filter: brightness(0) invert(1);
            transition: all 0.3s ease;
        }

        .sidebar-title img:hover {
            transform: scale(1.05);
        }

        /* Add a nice hover effect for the logout button */
        .sidebar .nav-link.text-danger:hover {
            background-color: rgba(220,53,69,0.1);
            color: #dc3545;
        }

        /* Add a subtle divider between nav items */
        .sidebar .nav-item {
            position: relative;
        }

        .sidebar .nav-item:not(:last-child)::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 16px;
            right: 16px;
            height: 1px;
            background: rgba(255,255,255,0.1);
        }

        /* Make the last nav item (logout) stick to bottom */
        .sidebar .nav {
            height: calc(100vh - 100px);
            display: flex;
            flex-direction: column;
        }

        /* Adjust the content margin to accommodate the sidebar */
        .content {
            margin-left: 250px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        /* Add some nice transitions */
        .sidebar, .content {
            transition: all 0.3s ease;
        }

        /* Make the chat container take full height */
        .chat-container {
            height: calc(100vh - 40px);
            margin-right: 20px;
        }

        .chat-header {
            padding: 15px;
            background-color: #f0f2f5;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            align-items: center;
        }

        .back-button {
            margin-right: 10px;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .back-button:hover {
            color: #007bff;
        }

        #chat-header-name {
            margin: 0;
            font-weight: bold;
        }
        /* Add this to your existing CSS */
        .message-container {
            width: 100%;
            margin-bottom: 10px;
            display: flex;
        }

        .message-container.right {
            justify-content: flex-end;
        }

        .message-container.left {
            justify-content: flex-start;
        }

        .message {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
            position: relative;
            word-wrap: break-word;
        }

        .message.admin {
            background-color: #0084ff;
            color: #fff;
            border-bottom-right-radius: 5px;
        }

        .message.student {
            background-color: #f0f0f0;
            color: #000;
            border-bottom-left-radius: 5px;
        }

        .message-time {
            font-size: 11px;
            margin-top: 5px;
            opacity: 0.7;
        }

        .message.admin .message-time {
            color: rgba(255, 255, 255, 0.8);
        }

        .message.student .message-time {
            color: #666;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
   <!-- Add this part back in the sidebar div -->
        <div class="sidebar">
            <h5 class="sidebar-title mb-5">
                <img src="../../img/logo.png" alt="Logo" width="190" height="20">
            </h5>
            <ul class="nav flex-column">
                <?php
                $current_page = basename($_SERVER['PHP_SELF']);
                $nav_items = [
                    'homeAdmin.php' => ['icon' => 'fas fa-home', 'text' => 'Dashboard'],
                    'dashboard.php' => ['icon' => 'fas fa-users', 'text' => 'Accounts'],
                    'bookAdmin.php' => ['icon' => 'fas fa-book', 'text' => 'Bookshelf'],
                    'teacher_messages.php' => ['icon' => 'fas fa-envelope', 'text' => 'Messages'],
                    'admin_feedback.php' => ['icon' => 'fas fa-envelope', 'text' => 'Feedbacks'],
                    '/SIA/php/profile.php' => ['icon' => 'fas fa-user', 'text' => 'Profile'],
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
   
  <!-- Modify the main content structure right after the sidebar div -->
        <div class="content">
            <div class="row">
                <div class="col-md-4">
                    <!-- Chat list section -->
                    <div class="card">
                        <div class="card-header">
                            <h5>Messages</h5>
                            <select name="student_email" id="student_email" class="form-control mb-3">
                                <option value="">Select a student</option>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?php echo $student['id']; ?>"><?php echo htmlspecialchars($student['email']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="card-body">
                            <div class="conversation-list">
                                <?php foreach ($conversations as $conversation): ?>
                                    <div class="conversation-item <?php echo $conversation['unread_count'] > 0 ? 'unread' : ''; ?>" 
                                        data-student-id="<?php echo $conversation['id']; ?>">
                                        <strong><?php echo htmlspecialchars($conversation['fName'] . ' ' . $conversation['lName']); ?></strong>
                                        <?php if ($conversation['unread_count'] > 0): ?>
                                            <span class="unread-badge"><?php echo $conversation['unread_count']; ?></span>
                                        <?php endif; ?>
                                        <br>
                                        <small><?php echo htmlspecialchars(substr($conversation['last_message'], 0, 30)) . '...'; ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <!-- Chat container -->
                    <div class="chat-container">
                    <div class="chat-header">
                        <button class="back-button">
                            <a href="teacher_messages.php"><i class="fas fa-arrow-left"></i></a>
                        </button>
                        <h5 id="chat-header-name"></h5>
                    </div>
                    <div class="chat-box">
                        <div class="default-message" style="display: block;">
                            <h4 class="text-muted align-items-center justify-content-center d-flex" style="margin-top:30%">Select a conversation to start chatting.</h4>
                        </div>
                        <div class="messages-list" style="display: none;">
                            <!-- Messages will be loaded here -->
                        </div>
                    </div>
                    <div class="chat-input">
                        <form id="sendMessage">
                            <div class="input-group">
                                <input type="text" id="message" name="message" class="form-control" placeholder="Type a message..." required>
                                <div class="input-group-append">
                                    <button type="submit" class="btn btn-send">
                                        <i class="fas fa-paper-plane"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            var selectedUserId = null;

            function showConversation(userId, userName) {
            selectedUserId = userId;
            $('#chat-header-name').text(userName);
            $('.chat-container').addClass('show');
            loadMessages(userId);
            
            $('.conversation-item').removeClass('active');
            $('.conversation-item[data-student-id="' + userId + '"]').addClass('active');
        }

            function hideConversation() {
                selectedUserId = null;
                $('.chat-container').removeClass('show');
                $('#chat-header-name').text(''); // Clear the header name when hiding conversation
            }

            $('.back-button').click(function() {
                hideConversation();
            });

            function scrollToBottom() {
                var chatBox = $('.chat-box');
                chatBox.scrollTop(chatBox[0].scrollHeight);
            }

            function loadMessages(userId) {
            $.ajax({
                url: "load_messages.php",
                method: "POST",
                data: {
                    student_id: userId,
                    admin_id: <?php echo isset($admin_id) ? $admin_id : 'null'; ?>
                },
                success: function(data) {
                    $('.messages-list').html(data);
                    $('.default-message').hide();
                    $('.messages-list').show();
                    scrollToBottom();
                    markMessagesAsRead(userId);
                }
            });
        }

            function markMessagesAsRead(userId) {
                $.ajax({
                    url: "mark_messages_read.php",
                    method: "POST",
                    data: {
                        student_id: userId,
                        admin_id: <?php echo isset($admin_id) ? $admin_id : 'null'; ?>
                    },
                    success: function() {
                        // Update UI to reflect read status
                        updateConversationList();
                    }
                });
            }

            function updateConversationList() {
                $.ajax({
                    url: "get_conversations.php",
                    method: "GET",
                    success: function(data) {
                        $('.conversation-list').html(data);
                        // Reattach click event to new conversation items
                        attachConversationClickEvents();
                    }
                });
            }

            function attachConversationClickEvents() {
                $('.conversation-item').click(function() {
                    var userId = $(this).data('student-id');
                    var userName = $(this).find('strong').text();
                    showConversation(userId, userName);
                });
            }

            // Initial attachment of click events
            attachConversationClickEvents();

            $('#student_email').change(function() {
                selectedUserId = $(this).val();
                if (selectedUserId) {
                    var userName = $(this).find('option:selected').text();
                    showConversation(selectedUserId, userName);
                } else {
                    hideConversation();
                }
            });

            $('#sendMessage').on('submit', function(e) {
                e.preventDefault();
                var message = $('#message').val();
                if (message != '' && selectedUserId != null) {
                    $.ajax({
                        url: "send_message.php",
                        method: "POST",
                        data: {
                            message: message,
                            student_id: selectedUserId,
                            admin_id: <?php echo isset($admin_id) ? $admin_id : 'null'; ?>,
                            sender: 'admin'
                        },
                        success: function(response) {
                            $('#message').val('');
                            loadMessages(selectedUserId);
                        }
                    });
                }
            });

            // Handle Enter key press
            $('#message').keypress(function(e) {
                if(e.which == 13 && !e.shiftKey) {
                    e.preventDefault();
                    $('#sendMessage').submit();
                }
            });

            // Auto refresh messages every 5 seconds if a conversation is selected
            setInterval(function() {
                if(selectedUserId) {
                    loadMessages(selectedUserId);
                }
            }, 5000);
        });
        </script>
    <script src="../../ js/script.js"></script>
</body>
</html>