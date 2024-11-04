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
    <link rel="stylesheet" href="/SIA/css/admin_message.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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