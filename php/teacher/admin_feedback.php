<html>
<head>
  <style>
    body {
      background-color: white;
      font-family: Arial, sans-serif;
    }
    .container {
      width: 400px;
      margin: 50px auto;
      background-color: #d9d9d9;
      padding: 20px;
      border-radius: 10px;
      position: relative;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .header h1 {
      font-size: 20px;
      font-weight: bold;
      margin: 0;
    }
    .close-btn {
      background-color: #ff4d4d;
      color: white;
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      font-size: 20px;
      cursor: pointer;
    }
    .feedback-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #e6e6e6;
      padding: 10px;
      margin: 10px 0;
      border-radius: 5px;
    }
    .feedback-item img {
      width: 30px;
      height: 30px;
      border-radius: 50%;
    }
    .delete-btn {
      background-color: #ff4d4d;
      color: white;
      border: none;
      border-radius: 5px;
      padding: 5px 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="container" id="feedbackContainer">
    <div class="header">
      <h1>Feedback</h1>
      <a href="homeAdmin.php"><button class="close-btn" id="closeBtn">X</button></a>
    </div>
    <div class="feedback-item">
      <img alt="User icon" src="https://storage.googleapis.com/a1aa/image/xfhGiaRGUBQlQKhyN7eXGGejhzQMs7XwmBrh8wEu9NoqpxRnA.jpg"/>
      <button class="delete-btn">Delete</button>
    </div>
    <div class="feedback-item">
      <img alt="User icon" src="https://storage.googleapis.com/a1aa/image/xfhGiaRGUBQlQKhyN7eXGGejhzQMs7XwmBrh8wEu9NoqpxRnA.jpg"/>
      <button class="delete-btn">Delete</button>
    </div>
    <div class="feedback-item">
      <img alt="User icon" src="https://storage.googleapis.com/a1aa/image/xfhGiaRGUBQlQKhyN7eXGGejhzQMs7XwmBrh8wEu9NoqpxRnA.jpg"/>
      <button class="delete-btn">Delete</button>
    </div>
  </div>

  <script>
    // Close button functionality
    document.getElementById('closeBtn').addEventListener('click', function() {
      document.getElementById('feedbackContainer').style.display = 'none';
    });

    // Delete button functionality
    document.querySelectorAll('.delete-btn').forEach(function(button) {
      button.addEventListener('click', function() {
        button.parentElement.remove();
      });
    });
  </script>
</body>
</html>
