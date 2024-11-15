<?php
include('../../php/db_config.php');
session_start();
function getProfilePicturePath($profile_picture) {
    if (isset($profile_picture) && !empty($profile_picture)) {
        return "../../../uploads/profiles/" . htmlspecialchars($profile_picture);
    } else {
        return "/SIA/img/default-profile.png";
    }
}

// Fetch user data including profile picture
    $id = $_SESSION['id'];
    $query = mysqli_query($con, "SELECT * FROM students WHERE id = '$id'");
    $result = mysqli_fetch_assoc($query);
    $res_profile_picture = $result['profile_picture'];
    $res_fName = $result['fName'];
    $res_lName = $result['lName'];


if(!isset($_SESSION['valid'])){
    header("Location: ../../login.php");
}
if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
    $student_id = $_SESSION['id'];
} else {
    echo "Error: ID is not set or empty.";
}

// Fetch teacher emails from the database
$teacher_query = "SELECT id, email, fName, lName FROM teacher";
$teacher_result = mysqli_query($con, $teacher_query);
$teachers = [];
while ($row = mysqli_fetch_assoc($teacher_result)) {
    $teachers[] = $row;
}

// Fetch conversations
$conversations_query = "
    SELECT DISTINCT t.id, t.email, t.fName, t.lName,
    (SELECT message FROM messages 
     WHERE (student_id = ? AND admin_id = t.id) 
     OR (student_id = t.id AND admin_id = ?) 
     ORDER BY created_at DESC LIMIT 1) as last_message,
    (SELECT created_at FROM messages 
     WHERE (student_id = ? AND admin_id = t.id) 
     OR (student_id = t.id AND admin_id = ?) 
     ORDER BY created_at DESC LIMIT 1) as last_message_time,
    (SELECT COUNT(*) FROM messages 
     WHERE student_id = ? AND admin_id = t.id 
     AND sender = 'admin' AND is_read = 0) as unread_count
    FROM teacher t
    INNER JOIN messages m ON (t.id = m.admin_id)
    WHERE m.student_id = ?
    GROUP BY t.id
    ORDER BY last_message_time DESC
";

$stmt = $con->prepare($conversations_query);
$stmt->bind_param("iiiiii", 
    $student_id, $student_id, 
    $student_id, $student_id,
    $student_id, $student_id
);
$stmt->execute();
$conversations_result = $stmt->get_result();
$conversations = $conversations_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Messages</title>
    <link rel="stylesheet" href="../../css/bootstrap.min.css">
    <link rel="stylesheet" href="/SIA/css/homestyle.css">
    <link rel="stylesheet" href="/SIA/css/student_messages.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<style>
    .navbar {
        background-color: #052659;
        box-shadow: 0 2px 4px rgba(0,0,0,.1);
    }
    .navbar-brand img {
        filter: brightness(0) invert(1);
    }
    .navbar-nav .nav-link {
        color: rgba(255,255,255,0.8) !important;
        transition: color 0.3s ease;
    }
    .navbar-nav .nav-link:hover {
        color: #ffffff !important;
    }
    .nav-item.dropdown .user-profile {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        color: #ffffff;
        background-color: rgba(255,255,255,0.1);
        border-radius: 50px;
        transition: background-color 0.3s ease;
    }
    .nav-item.dropdown .user-profile:hover {
        background-color: rgba(255,255,255,0.2);
    }
    .nav-item.dropdown img {
        width: 32px;
        height: 32px;
        object-fit: cover;
        margin-right: 10px;
        border: 2px solid #ffffff;
    }
    .dropdown-menu {
        background-color: #ffffff;
        border: none;
        box-shadow: 0 0.5rem 1rem rgba(0,0,0,.15);
        border-radius: 0.5rem;
    }
    .dropdown-item {
        color: #052659;
        padding: 0.5rem 1.5rem;
        transition: background-color 0.3s ease;
    }
    .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #052659;
    }
    .dropdown-item i {
        margin-right: 10px;
        color: #052659;
    }
    .card-header{
        background-color: #C1E8FF;
    }
    .chat-header{
        background-color: #C1E8FF;
    }
    .chat-input{
        background-color: #C1E8FF;
    }
    .content-wrapper {
        padding-top: 20px; /* Adjust this value as needed */
    }

</style>
<body>
           <!-- HEADER -->
            <nav class="navbar navbar-expand-lg navbar-dark">
                <div class="container">
                    <a class="navbar-brand" href="#"><img src="../../img/logo.png" alt="Readiculous"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto align-items-center">
                            <li class="nav-item">
                                <a class="nav-link" href="home.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./student_messages.php">Messages</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./feedback.php">Feedback</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="./about.php">About</a>
                            </li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle user-profile" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <img src="<?php echo getProfilePicturePath($res_profile_picture); ?>" alt="Profile" class="rounded-circle">
                                    <span><?php echo $res_fName; ?></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="./student_profile.php"><i class="fas fa-user-circle"></i> View Profile</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="../../php/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>


            <div class="content-wrapper">
                <div class="row">
                    <div class="col-md-4">
                        <!-- Chat list section -->
                        <div class="card">
                            <div class="card-header">
                                <h5>Messages</h5>
                                <select name="teacher_email" id="teacher_email" class="form-control mb-3">
                                    <option value="">Select a teacher</option>
                                    <?php foreach ($teachers as $teacher): ?>
                                        <option value="<?php echo $teacher['id']; ?>"><?php echo htmlspecialchars($teacher['email']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="card-body">
                            <div class="conversation-list">
                                    <?php foreach ($conversations as $conversation): ?>
                                        <div class="conversation-item <?php echo $conversation['unread_count'] > 0 ? 'unread' : ''; ?>" 
                                            data-teacher-id="<?php echo $conversation['id']; ?>">
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
            <!-- Chat box section -->
            <div class="chat-container">
                <div class="chat-header">
                    <button class="back-button">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <h5 id="chat-header-name"></h5>
                </div>
                <div class="chat-box" id="chat-box">
                    <!-- Messages will be loaded here -->
                </div>
                <div class="chat-input">
                    <form id="message-form">
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
        </div>
                    <script>
                $(document).ready(function(){
            var selectedUserId = null;

            $('.back-button').click(function() {
                // Hide the chat container
                $('.chat-box').html('');
                $('#chat-header-name').text('');
                
                // Show default message
                $('.chat-box').html(`
                    <div class="default-message">
                        <h4 class="text-muted">Select a conversation to start chatting.</h4>
                    </div>
                `);
                
                // Clear selected user
                selectedUserId = null;
                
                // Remove active class from conversations
                $('.conversation-item').removeClass('active');
                
                // Reset the teacher email select if you have one
                $('#teacher_email').val('');

                // If you're using mobile view, you might want to show the conversation list
                if($(window).width() < 768) {
                    $('.col-md-4').show();
                    $('.col-md-8').hide();
                }
            });

            function showConversation(userId, userName) {
                selectedUserId = userId;
                $('#chat-header-name').text(userName);
                $('.chat-container').addClass('show');
                $('.default-message').hide();
                $('.messages-list').show();
                loadMessages(userId);
                
                $('.conversation-item').removeClass('active');
                $('.conversation-item[data-teacher-id="' + userId + '"]').addClass('active');
            }

            function hideConversation() {
                selectedUserId = null;
                $('.chat-container').removeClass('show');
                $('#chat-header-name').text('');
                $('.default-message').show();
                $('.messages-list').hide();
            }

            // Handle email selection
            $('#teacher_email').change(function() {
                var selectedId = $(this).val();
                if (selectedId) {
                    var selectedName = $(this).find('option:selected').text();
                    showConversation(selectedId, selectedName);
                } else {
                    hideConversation();
                }
            });

            // Handle conversation item click
            $(document).on('click', '.conversation-item', function() {
                var userId = $(this).data('teacher-id');
                var userName = $(this).find('strong').text();
                showConversation(userId, userName);
            });

            // Handle back button click
            $('.back-button').click(function() {
                hideConversation();
            });

            // Add this auto-refresh function
            setInterval(function() {
                if(selectedUserId) {
                    loadMessages(selectedUserId);
                }
            }, 5000); // Checks every 5 seconds for new messages


            function loadMessages(userId) {
            $.ajax({
                url: "load_messages.php",
                method: "POST",
                data: {
                    student_id: <?php echo $student_id; ?>,
                    admin_id: userId
                },
                success: function(data) {
                    $('.chat-box').html(data);
                    scrollToBottom();
                }
            });
        }

        function scrollToBottom() {
            var chatBox = $('.chat-box');
            chatBox.scrollTop(chatBox[0].scrollHeight);
        }

            // Make sure your loadMessages function properly displays messages
            function showConversation(userId, userName) {
                selectedUserId = userId;
                $('#chat-header-name').text(userName);
                $('.chat-container').addClass('show');
                $('.default-message').hide();
                $('.messages-list').show();
                loadMessages(userId);
                
                // Update conversation list
                $('.conversation-item').removeClass('active');
                $('.conversation-item[data-teacher-id="' + userId + '"]').addClass('active');
            }

            function scrollToBottom() {
                var chatBox = $('.chat-box');
                chatBox.scrollTop(chatBox[0].scrollHeight);
            }

            function markMessagesAsRead(userId) {
                $.ajax({
                    url: "mark_messages_read.php",
                    method: "POST",
                    data: {
                        student_id: <?php echo $student_id; ?>,
                        admin_id: userId
                    },
                    success: function() {
                        updateConversationList();
                    }
                });
            }

            // Also update your load_messages.php file to properly format the messages:
                function updateConversationList() {
                $.ajax({
                    url: "get_conversations.php",
                    method: "GET",
                    success: function(data) {
                        $('.conversation-list').html(data);
                        // Reattach click events if needed
                        attachConversationClickEvents();
                    }
                });
            }

            function attachConversationClickEvents() {
                $('.conversation-item').click(function() {
                    var userId = $(this).data('teacher-id');
                    var userName = $(this).find('strong').text();
                    showConversation(userId, userName);
                });
            }

            // Initial attachment of click events
            attachConversationClickEvents();

            // Update your message sending function to immediately show the new message
            $('#message-form').submit(function(e) {
                e.preventDefault();
                var message = $('#message').val();
                if (message.trim() !== '' && selectedUserId != null) {
                    $.ajax({
                        url: "send_message.php",
                        method: "POST",
                        data: {
                            message: message,
                            student_id: <?php echo $student_id; ?>,
                            admin_id: selectedUserId
                        },
                        success: function(response) {
                            $('#message').val('');
                            loadMessages(selectedUserId); // Reload messages immediately
                            updateConversationList(); // Update the conversation list
                        }
                    });
                }
            });

            // Handle Enter key press
            $('#message').keypress(function(e) {
                if(e.which == 13 && !e.shiftKey) {
                    e.preventDefault();
                    $('#message-form').submit();
                }
            });

            // Initial hide of conversation
            hideConversation();
        });


        </script>
        <script>
        $(document).ready(function(){
            function updateConversationList() {
                $.ajax({
                    url: "get_conversations.php",
                    method: "GET",
                    success: function(data) {
                        $('.conversation-list').html(data);
                        // Reattach click events if needed
                        attachConversationClickEvents();
                    }
                });
            }

            function attachConversationClickEvents() {
                $('.conversation-item').click(function() {
                    var userId = $(this).data('teacher-id');
                    var userName = $(this).find('strong').text();
                    showConversation(userId, userName);
                });
            }

            // Update conversation list every 5 seconds
            setInterval(updateConversationList, 5000);

            // Initial load of conversations
            updateConversationList();

            // Your existing JavaScript code...
        });
      
        </script>
         

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>