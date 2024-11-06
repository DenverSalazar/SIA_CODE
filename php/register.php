<?php
    session_start();
    $role = isset($_GET['role']) ? $_GET['role'] : 'student';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<style>
    body{
    /* background: linear-gradient(135deg, #2c3e50, #3498db); */
    background: gray;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    }
</style>
<body>

    <div class="container">
        <div class="box form-box">

        <?php
                include("../php/db_config.php");
                if (isset($_POST['submit'])) {
                    $fname = $_POST['fName'];
                    $lname = $_POST['lName'];
                    $email = $_POST['email'];
                    $password = $_POST['password'];
                    $confirmpassword = $_POST['confirmPassword'];
                    $role = $_POST['role'];
                    $department = ($role == 'student') ? $_POST['department'] : ''; // Only get department for students
                
                    // Check if email is already in use
                    $query = mysqli_query($con, "SELECT * FROM students WHERE email = '$email'");
                    $query2 = mysqli_query($con, "SELECT * FROM teacher WHERE email = '$email'");
                
                    if(mysqli_num_rows($query) > 0 || mysqli_num_rows($query2) > 0){
                        echo "<div class= 'message'>
                        <p class='align-items-center justify-content-center d-flex'> Email is already in use!</p> </div> <br>";
                        echo "<a href= 'javascript:self.history.back()'><button class='btn btn-outline-dark' style='margin-left: 80px; color: white;'>Go back</button>";
                    } else {
                        if (!preg_match("/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/", $email)) {
                            echo "<div class= 'message'>
                            <p> Invalid email address!</p> </div> <br>";
                            echo "<a href= 'javascript:self.history.back()'><button class='btn btn-outline-dark' style='color: white;'>Go back</button>";
                        } elseif ($role == 'student' && empty($department)) {
                            // Only check department if role is student
                            echo "<div class= 'message'>
                            <p> Please select a department!</p> </div> <br>";
                            echo "<a href= 'javascript:self.history.back()'><button class='btn btn-outline-dark' style='color: white;'>Go back</button>";
                        } else {
                            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
                
                            // CREATE ACCOUNT SQL STATEMENT
                            if($role == 'student'){
                                $query = "INSERT INTO students (fName, lName, email, password, confirmPassword, department) VALUES ('$fname', '$lname', '$email', '$hashed_password', '$hashed_password', '$department')";
                                mysqli_query($con, $query) or die("Error");
                            } else if($role == 'teacher'){
                                $query = "INSERT INTO teacher (fName, lName, email, password, confirmPassword) VALUES ('$fname', '$lname', '$email', '$hashed_password', '$hashed_password')";
                                mysqli_query($con, $query) or die("Error");
                            }
                            
                            echo "<div class= 'message'>
                            <p class='align-items-center justify-content-center d-flex'> Registration Successfully!</p> </div> <br>";
                            echo "<a href='login.php'><button class='btn btn-outline-dark' style='margin-left: 70px; color: white;'>Login Now</button>";
                        }
                    }
                }else{
            ?>

            <header>Register</header>
            <form action="" method="post">
                <div class="field input">
                    <input type="text" name="fName" id="fName" placeholder="  First Name">
                    <div id="fName-error" class="error-message"></div>
                </div>

                <div class="field input">
                    <input type="text" name="lName" id="lName" placeholder="  Last Name">
                    <div id="lName-error" class="error-message"></div>
                </div>

                <div class="field input">
                <?php if($role == 'student'): ?>
                    <div class="input-group">
                        <input type="text" name="email" id="email" placeholder="  Email Address" class="form-control">
                        <button class="btn5 btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="department"></button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" data-value="CICS">CICS</a></li>
                            <!-- Add more departments as needed -->
                        </ul>
                    </div>
                    <?php else: ?>
                        <!-- Show only email input for admin -->
                        <input type="text" name="email" id="email" placeholder="  Email Address">
                    <?php endif; ?>
                    <div id="email-error" class="error-message"></div>
                </div>

                <?php if($role == 'student'): ?>
                <!-- Hidden department input only for students -->
                <input type="hidden" name="department" id="department-input">
                <?php endif; ?>

                <div class="field input">
                    <input type="password" name="password" id="password" placeholder="  Password">
                    <div id="password-error" class="error-message"></div>
                </div>

                <div class="field input">
                    <input type="password" name="confirmPassword" id="confirmPassword" placeholder="  Confirm Password">
                    <div id="confirmPassword-error" class="error-message"></div>
                </div>

                <input type="hidden" name="role" value="<?php echo htmlspecialchars($role); ?>">

                <div class="field">
                    <button type="submit" name="submit" class="btn btn-outline-dark" style="color: white;">Create Account</button>
                </div>

                <div class="link">Already have an account? <a href="login.php">Login</a></div>
            </form>
        </div>
      <?php } ?>
    </div>

    <script>
        const fNameInput = document.getElementById('fName');
        const lNameInput = document.getElementById('lName');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const fNameError = document.getElementById('fName-error');
        const lNameError = document.getElementById('lName-error');
        const emailError = document.getElementById('email-error');
        const passwordError = document.getElementById('password-error');
        const confirmPasswordError = document.getElementById('confirmPassword-error');
        const departmentButton = document.getElementById('department');
        const departmentInput = document.getElementById('department-input');

        document.addEventListener('DOMContentLoaded', function() {
            const dropdownItems = document.querySelectorAll('.dropdown-item');

            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const selectedValue = this.getAttribute('data-value');
                    departmentButton.textContent = selectedValue;
                    departmentInput.value = selectedValue; // Set the hidden input value
                });
            });
        });

        document.addEventListener('input', () => {
            if (fNameInput.value.trim() !== '') {
                fNameError.textContent = '';
            }

            if (lNameInput.value.trim() !== '') {
                lNameError.textContent = '';
            }

            if (emailInput.value.trim() !== '') {
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (emailRegex.test(emailInput.value)) {
                    emailError.textContent = '';
                } else {
                    emailError.textContent = 'Invalid email address';
                    emailError.style.color = 'red';
                    emailError.style.fontSize = '12px';
                }
            }

            if (passwordInput.value.trim() !== '' && passwordInput.value.length >= 8) {
                passwordError.textContent = '';
            }

            if (confirmPasswordInput.value.trim() !== '' && confirmPasswordInput.value === passwordInput.value) {
                confirmPasswordError.textContent = '';
            }
        });

        document.addEventListener('submit', (e) => {
            let isValid = true;

            if (fNameInput.value.trim() === '') {
                fNameError.textContent = 'First name is required';
                fNameError.style.color = 'red';
                fNameError.style.fontSize = '12px';
                isValid = false;
            }

            if (lNameInput.value.trim() === '') {
                lNameError.textContent = 'Last name is required';
                lNameError.style.color = 'red';
                lNameError.style.fontSize = '12px';
                isValid = false;
            }

            if (emailInput.value.trim() === '') {
                emailError.textContent = 'Email is required';
                emailError.style.color = 'red';
                emailError.style.fontSize = '12px';
                isValid = false;
            } else {
                const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailRegex.test(emailInput.value)) {
                    emailError.textContent = 'Invalid email address';
                    emailError.style.color = 'red';
                    emailError.style.fontSize = '12px';
                    isValid = false;
                }
            }

            if (passwordInput.value.trim() === '' || passwordInput.value.length < 8) {
                passwordError.textContent = 'Password should be at least 8 characters long';
                passwordError.style.color = 'red';
                passwordError.style.fontSize = '12px';
                isValid = false;
            }

            if (confirmPasswordInput.value.trim() === '' || confirmPasswordInput.value !== passwordInput.value) {
                confirmPasswordError.textContent = 'Passwords do not match';
                confirmPasswordError.style.color = 'red';
                confirmPasswordError.style.fontSize = '12px';
                isValid = false;
            }

            // Only check department if role is student
            if ('<?php echo $role; ?>' === 'student' && !departmentInput.value) {
                emailError.textContent = 'Please select a department';
                emailError.style.color = 'red';
                emailError.style.fontSize = '12px';
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });
</script>
    <script src="../js/bootstrap.bundle.min.js"></script>
</body>
</html>