<?php
session_start();
include '../php/db_config.php';

if (isset($_POST['submit'])) {
    // reCAPTCHA secret key
    $secretKey = "";
    $captchaResponse = $_POST['g-recaptcha-response'];

    // Verify CAPTCHA response
    $captchaVerify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captchaResponse");
    $captchaVerify = json_decode($captchaVerify);

    if (!$captchaVerify->success) {
        $error_message = "Please complete the CAPTCHA.";
    } else {
        // Process the login if CAPTCHA is verified successfully
        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);
        $role = mysqli_real_escape_string($con, $_POST['role']);

        if ($role == 'student') {
            $query = mysqli_query($con, "SELECT * FROM students WHERE email = '$email'");
            $row = mysqli_fetch_assoc($query);
            if ($row !== null) {
                if (password_verify($password, $row['password'])) {
                    if ($row['is_accepted'] == 0) {
                        // Redirect to pending approval page
                        $_SESSION['pending_id'] = $row['id'];
                        $_SESSION['pending_role'] = 'student';
                        header("Location: pending_approval.php");
                        exit();
                    } else {
                        $_SESSION['valid'] = $row['email'];
                        $_SESSION['fName'] = $row['fName'];
                        $_SESSION['lName'] = $row['lName'];
                        $_SESSION['id'] = $row['id'];
                        $_SESSION['role'] = 'student';

                        // Log the successful login
                        $student_id = $row['id'];
                        $log_query = "INSERT INTO activity_logs (student_id, action, details, timestamp) 
                                      VALUES ('$student_id', 'login', 'Student logged in', NOW())";
                        mysqli_query($con, $log_query);

                        header("Location: ../php/student/home.php");
                        exit();
                    }
                } else {
                    $error_message = "Wrong Username or Password!";
                }
            } else {
                $error_message = "Account not found!";
            }
        } else if ($role == 'teacher') {
            // First check admin table
            $query = mysqli_query($con, "SELECT * FROM admin WHERE email = '$email'");
            $row = mysqli_fetch_assoc($query);
            
            if ($row !== null) {
                // Admin login
                if (password_verify($password, $row['password'])) {
                    $_SESSION['valid'] = $row['email'];
                    $_SESSION['fName'] = $row['fName'];
                    $_SESSION['lName'] = $row['lName'];
                    $_SESSION['id'] = $row['id'];
                    $_SESSION['role'] = 'teacher'; // Keep role as teacher for compatibility
                    header("Location: ../php/admin/admin_home.php");
                    exit();
                } else {
                    $error_message = "Wrong Username or Password!";
                }
            } else {
                // If not found in admin table, check teacher table
                $query = mysqli_query($con, "SELECT * FROM teacher WHERE email = '$email'");
                $row = mysqli_fetch_assoc($query);
                if ($row !== null) {
                    if (password_verify($password, $row['password'])) {
                        if ($row['is_accepted'] == 0) {
                            // Redirect to pending approval page
                            $_SESSION['pending_id'] = $row['id'];
                            $_SESSION['pending_role'] = 'teacher';
                            header("Location: pending_approval.php");
                            exit();
                        } else {
                            $_SESSION['valid'] = $row['email'];
                            $_SESSION['fName'] = $row['fName'];
                            $_SESSION['lName'] = $row['lName'];
                            $_SESSION['id'] = $row['id'];
                            $_SESSION['role'] = 'teacher';
                            header("Location: ../php/teacher/teacher_home.php");
                            exit();
                        }
                    } else {
                        $error_message = "Wrong Username or Password!";
                    }
                } else {
                    $error_message = "Account not found!";
                }
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

</head>
<style>
    body{
    background: gray;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    }
    .g-recaptcha {
        transform: scale(0.88); /* Scale down */
        transform-origin: center; /* Center the scaled element */
        display: flex;
        justify-content: center;
        margin: auto;
    }
    .error-message {
        color: red;
        font-size: 12px;
        align-items: center;
        justify-content: center;
        display: flex;
    }
    
</style>
<body>

    <div class="container">
        <div class="box form-box">
            <header>Login</header>
            <form action="" method="post" id="login-form">
                <div class="field input">
                    <div class="input-group">
                        <input type="text" name="email" id="email" placeholder="Email Address" class="form-control custom-input">
                        <!-- <span class="input-group-text">
                            <i class="fas fa-envelope"></i>
                        </span> -->
                    </div>
                    <div id="email-error" class="error-message"></div>
                </div>

                <div class="field input">
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Password" class="form-control custom-input">
                        <!-- <span class="input-group-text" onclick="togglePassword()" style="cursor: pointer;">
                            <i class="fas fa-eye" id="togglePassword"></i>
                        </span> -->
                    </div>
                    <div id="password-error" class="error-message"></div>
                </div>
                <div class="role-selection">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="student" value="student" checked>
                    <label class="form-check-label" for="student">Student</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="teacher" value="teacher">
                    <label class="form-check-label" for="teacher">Teacher</label>
                </div>
                </div>
                <?php if (isset($error_message)) { echo "<div class='error-message'>$error_message</div>"; } ?>

                <div class="g-recaptcha" data-sitekey=""></div>
                <div id="captcha-error" class="error-message" style="color:red; font-size:12px;"></div>


                <div class="field">
                    <button type="submit" name="submit" class="btn btn-outline-dark" style="color: white;">Login</button>
                </div>

              <div class="field">
              <button type="button" class="btn btn-outline-dark" style="color: white;" onclick="location.href='/SIA/php/choose_role.php'">Sign-up</button>
              </div>

              <script src="https://www.google.com/recaptcha/api.js" async defer></script>

              <div class="link"><a class="link-offset-2 link-dark link-underline-opacity-0" href="forgot.php">Forgot Password?</a></div>
          </form>
      </div>
  </div>

  <script>
          const emailInput = document.getElementById('email');
          const passwordInput = document.getElementById('password');
          const emailError = document.getElementById ('email-error');
          const passwordError = document.getElementById('password-error');

          document.addEventListener('input', () => {
              if (emailInput.value.trim() !== '') {
                  emailError.textContent = '';
              }

              if (passwordInput.value.trim() !== '' && passwordInput.value.length >= 8) {
                  passwordError.textContent = '';
              }
          });

          document.addEventListener('submit', (e) => {
              let isValid = true;

              if (emailInput.value.trim() === '') {
                  emailError.textContent = 'Email is required';
                  emailError.style.color = 'red';
                  emailError.style.fontSize = '12px';
                  isValid = false;
              }

              if (passwordInput.value.trim() === '' || passwordInput.value.length < 8) {
                  passwordError.textContent = 'Password should be at least 8 characters long';
                  passwordError.style.color = 'red';
                  passwordError.style.fontSize = '12px';
                  isValid = false;
              }

              if (!isValid) {
                  e.preventDefault();
              }
          });

          function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePassword');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Add focus and blur event listeners for enhanced interaction
document.querySelectorAll('.custom-input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.querySelector('.input-group-text i').style.color = '#007bff';
    });

    input.addEventListener('blur', function() {
        this.parentElement.querySelector('.input-group-text i').style.color = '#666';
    });
});
  </script>

        <script src="../js/bootstrap.bundle.min.js" ></script>
        <script src="../js/bootstrap.min.js"></script>
</body>
</html>