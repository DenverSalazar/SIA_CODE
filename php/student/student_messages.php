<?php
include('../../php/db_config.php');
session_start();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
    body {
        background-color: #f0f2f5;
    }
    .content {
        padding: 80px 20px 20px 20px;
    }
    .card, .chat-container {
        height: calc(100vh - 140px);
        display: flex;
        flex-direction: column;
    }
    .card-header, .chat-header {
        flex-shrink: 0;
    }
    .conversation-list{
        height: calc(100% - 60px);
        overflow-y: auto;
    }
    .conversation-item {
        padding: 10px;
        border-bottom: 1px solid #eee;
        background-color: white; 
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
    .chat-container {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
    }
    .chat-header {
        padding: 15px;
        background-color: #f0f2f5;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        display: flex;
        align-items: center;
    }
    .chat-box {
        height: calc(100% - 120px);
        overflow-y: auto;
        padding: 15px;
        background-color: #fff;
    }
    .chat-input {
        height: 60px;
        padding: 10px 15px;
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

    /* New Message Styles */
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
        background-color: #f0f0f0;
        color: #000;
        border-bottom-left-radius: 5px;
    }

    .message.student {
        background-color: #0084ff;
        color: #fff;
        border-bottom-right-radius: 5px;
    }

    .message-time {
        font-size: 11px;
        margin-top: 5px;
        opacity: 0.7;
    }

    .message.admin .message-time {
        color: #666;
    }

    .message.student .message-time {
        color: rgba(255, 255, 255, 0.8);
    }

    /* Default message styles */
    .default-message {
        display: flex;
        height: 100%;
        align-items: center;
        justify-content: center;
        color: #666;
        text-align: center;
    }

    /* Active conversation styles */
    .conversation-item.active {
        background-color: #e6f2ff;
    }

    /* Scrollbar styles */
    .chat-box::-webkit-scrollbar {
        width: 6px;
    }

    .chat-box::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .chat-box::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 3px;
    }

    .chat-box::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    @media (max-width: 768px) {
        .content {
            padding: 60px 10px 10px 10px;
        }
    }
</style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
            <header>
                <nav class="navbar navbar-light fixed-top">
                    <div class="container">
                    <a class="navbar-brand"><img src="../../img/logo.png" alt="Readiculous" width=""></a> 
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    </div>
                </nav>

                <!-- Offcanvas Menu -->
                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel"><img src="../../img/logo.png" alt="Readiculous" width="150"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="../../php/profile.php">User Profile</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="./student_messages.php">Messages</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="../../php/student/feedback.php">Feedback</a>
                        </li>
                        <li class="nav-item">
                        <a class="nav-link" href="about.php">About us</a>
                        </li>
                        <li class="nav-item-x">
                        <a class="nav-link logout-link" href="../../php/logout.php">Logout</a>
                        </li>            
                    </ul>
                    </div>
                </div>
            </header>

            <div class="content">
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
<script src="../../js/bootstrap.bundle.min.js" ></script>
<script src="../../js/bootstrap.min.js"></script>
</body>
</html>