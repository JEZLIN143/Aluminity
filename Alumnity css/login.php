<?php
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if (isset($_POST['register'])) {
        $name = ucfirst($_POST['name']);
        $user_email = $_POST['user_email'];
        $password = $_POST['password'];
        $role = $_POST['role'];
        $batch = $_POST['batch'];
        $phone_number = isset($_POST['phone_number']) ? $_POST['phone_number'] : '';
        
        // Validate email format
        if (!filter_var($user_email, FILTER_VALIDATE_EMAIL) || !strpos($user_email, '@gmail.com')) {
            echo "<script>alert('Please enter a valid Gmail address!');</script>";
            exit(); // Exit to prevent further execution
        }

        if (!preg_match('/^[a-zA-Z\s]+$/', $name)) {
            echo "<script>alert('Name can only contain letters and spaces!');</script>";
            exit(); // Exit to prevent further execution
        }

        if (strlen($phone_number) <= 10) {
            if (!empty($name) && !empty($user_email) && !empty($password) && !empty($role) && !empty($batch) && !is_numeric($user_email)) {

                // Check if the email exists in student_details table
                $query_student_exists = "SELECT * FROM student_details WHERE user_email = '$user_email' LIMIT 1";
                $result_student_exists = mysqli_query($con, $query_student_exists);

                // Check if the email exists in alumni_details table
                $query_alumni_exists = "SELECT * FROM alumni_details WHERE user_email = '$user_email' LIMIT 1";
                $result_alumni_exists = mysqli_query($con, $query_alumni_exists);

                if (mysqli_num_rows($result_student_exists) > 0 || mysqli_num_rows($result_alumni_exists) > 0) {
                    // Email already exists, show popup message
                    echo "<script>alert('Email already exists!');</script>";
                } else {
                    // Proceed with registration
                    $status = 'pending';

                    if ($role === 'student') {
                        $query = "INSERT INTO student_details (name, user_email, password, role, user_batch, phone_no, status) 
                                  VALUES ('$name', '$user_email', '$password', '$role', '$batch', '$phone_number', '$status')";
                    } else {
                        $query = "INSERT INTO alumni_details (name, user_email, password, role, user_batch, status) 
                                  VALUES ('$name', '$user_email', '$password', '$role', '$batch', '$status')";
                    }

                    $result = mysqli_query($con, $query);

                    if ($result) {
                        echo '<p style="color: white;">Your account has been created successfully! It will be approved by the admin.</p>';

                    } else {
                        echo "Error: " . mysqli_error($con);
                    }
                }
            } else {
                echo "Please enter some valid information!";
            }
        } else {
            echo "Phone number must not exceed 10 digits.";
        }
    }
}


  

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['login'])) {
    // Retrieve form data
    $user_email = $_POST['user_email'];
    $password = $_POST['password'];

    if (!empty($user_email) && !empty($password) && !is_numeric($user_email)) {
        // Check student login
        $query_student = "SELECT * FROM student_details WHERE user_email = '$user_email' LIMIT 1";
        $result_student = mysqli_query($con, $query_student);

        if ($result_student && mysqli_num_rows($result_student) > 0) {
            $user_data = mysqli_fetch_assoc($result_student);

            if ($user_data['password'] === $password) {
                if ($user_data['status'] === 'approved') {
                    // Set session and redirect to home.php
                    $_SESSION['user_name'] = $user_data['name'];
                    header("Location: home.php");
                    exit();
                } elseif ($user_data['status'] === 'pending') {
                    echo "Your account is pending approval by the admin.";
                } else {
                    echo '<p style="color:white;">Your account has been rejected by the admin.</p>';
                }
            } else {
                echo "Wrong username or password!";
            }
        } else {
            // Check alumni login
            $query_alumni = "SELECT * FROM alumni_details WHERE user_email = '$user_email' LIMIT 1";
            $result_alumni = mysqli_query($con, $query_alumni);

            if ($result_alumni && mysqli_num_rows($result_alumni) > 0) {
                $user_data = mysqli_fetch_assoc($result_alumni);

                if ($user_data['password'] === $password) {
                    if ($user_data['status'] === 'approved') {
                        // Set session and redirect to home.php
                        $_SESSION['user_name'] = $user_data['name'];
			
			$message_query = "INSERT INTO messages (user_email) VALUES ('$user_email')";
                            mysqli_query($con, $message_query);
                        header("Location: home.php");
                        exit();
                    } elseif ($user_data['status'] === 'pending') {
                        echo "Your account is pending approval by the admin.";
                    } else {
                        echo "Your account has been rejected by the admin.";
                    }
                } else {
                    echo "Wrong username or password!";
                }
            } else {
                echo "Wrong username or password!";
            }
        }
    } else {
        echo "Please enter some valid information!";
    }
}



    if (isset($_POST['admin_login'])) {
        // Admin login logic
        $admin_name = $_POST['admin_name'];
        $admin_password = $_POST['admin_password'];

        // Query to check admin credentials
        $query_admin = "SELECT * FROM admin_details WHERE admin_name = '$admin_name' AND admin_password = '$admin_password' LIMIT 1";
        $result_admin = mysqli_query($con, $query_admin);

        if ($result_admin && mysqli_num_rows($result_admin) > 0) {
            // Admin login successful, redirect to admin dashboard
            header("Location: admin_dashboard.php");
            exit();
        } else {
            // Admin login failed, display error message
            echo "<script>alert('Admin name or password is incorrect.');</script>";
        }
    }


    // Code to handle changing user role and storing details in alumni_details
    if (isset($_POST['change_role'])) {
        $studentIds = json_decode($_POST['students']);

        // Start a transaction
        $query = "START TRANSACTION";
        mysqli_query($con, $query);

        try {
            // Move students to alumni_details table and change role
            foreach ($studentIds as $studentId) {
                // Retrieve student details
                $query_student = "SELECT * FROM student_details WHERE user_id = $studentId LIMIT 1";
                $result_student = mysqli_query($con, $query_student);
                $student_data = mysqli_fetch_assoc($result_student);

                // Insert into alumni_details
                $query_insert = "INSERT INTO alumni_details (name, user_email, password, status, role, user_batch) 
                                VALUES ('" . $student_data['name'] . "', '" . $student_data['user_email'] . "', '" . $student_data['password'] . "', 'pending', 'alumni', '" . $student_data['user_batch'] . "')";
                mysqli_query($con, $query_insert);
            }

            // Commit the transaction
            $query_commit = "COMMIT";
            mysqli_query($con, $query_commit);

            // Respond with a success message or appropriate response
            echo "Role changed successfully and details moved to alumni.";
        } catch (Exception $e) {
            // Rollback the transaction if an error occurs
            $query_rollback = "ROLLBACK";
            mysqli_query($con, $query_rollback);
            echo "Error: " . $e->getMessage();
        }
    }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login and Registration</title>
    <style>
        /* Your CSS styles here */
        /* CSS styles */
        .cre{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-form {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%); 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            animation: fadeIn 1s ease forwards; 
            align-items: center;
            text-align: center;
        }
        .dont{
            margin-left: 200px;
        }


        @keyframes fadeIn {
            from {
                opacity: 0; 
            }
            to {
                opacity: 1; 
            }
        }

        .email{
            border-color: black;
        }
        body{
            margin-top: 0;
            margin-bottom: 0;
            margin-right: 0;
            margin-left: 0;
            background-image: url(ipad2.jpg);
            background-size: cover;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            height: 100vh;
            backdrop-filter: blur(15px);


        }

        .container{
            background-color: #fff;
            border-radius: 70px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 768px;
            max-width: 100%;
            min-height: 480px;
        }


        .container p{
            font-size: 14px;
            line-height: 20px;
            letter-spacing: 0.3px;
            margin: 20px 0;
        }

        .container span{
            font-size: 12px;
        }

        .container a{
            color: #333;
            font-size: 13px;
            text-decoration: none;
            margin: 15px 0 10px;
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
        }
        .container button{
            background-color: #000000;
            color: #fff;
            font-size: 12px;
            padding: 10px 45px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            margin-top: 10px;
            cursor: pointer;
        }

        .container button.hidden{
            background-color: transparent;
            border-color: #fff;
        }

        .container form{
            background-color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 40px;
            height: 100%;
        }
        .container input{
            background-color: #f6f6f6;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            border-width: 0;
            width: 100%;
            outline: none;
        }

        .form-container{
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in{
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .container.active .sign-in{
            transform: translateX(100%);
        }

        .sign-up{
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.active .sign-up{
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: move 0.6s;
        }

        @keyframes move{
            0%, 49.99%{
                opacity: 0;
                z-index: 1;
            }
            50%, 100%{
                opacity: 1;
                z-index: 5;
            }
        }

        .social-icons{
            margin: 20px 0;
        }

        .social-icons a{
            border: 1px solid #ccc;
            border-radius: 20%;
            display: inline-flex;
            justify-content: center;
            align-items: center;
            margin: 0 3px;
            width: 40px;
            height: 40px;
        }

        .toggle-container{
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: all 0.6s ease-in-out;
            border-radius: 150px 0 0 100px;
            z-index: 1000;
        }

        .container.active .toggle-container{
            transform: translateX(-100%);
            border-radius: 0 150px 100px 0;
        }

        .toggle{
            background-color: #ffffff;
            height: 100%;
            background-image: url(ipad4.jpg);
            background-size: cover;
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: all 1.6s ease-in-out;
        }

        .container.active .toggle{
            transform: translateX(50%);
        }

        .toggle-panel{
            position: absolute;
            width: 50%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 30px;
            text-align: center;
            top: 0;
            transform: translateX(0);
            transition: all 1.6s ease-in-out;
        }

        .toggle-left{
            transform: translateX(-200%);
        }

        .container.active .toggle-left{
            transform: translateX(0);
        }

        .toggle-right{
            right: 0;
            transform: translateX(0);
        }

        .container.active .toggle-right{
            transform: translateX(200%);
        }
        .hello{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-style: italic;
        }
        .wel{
            margin-right: 90px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-style: italic;
        }
        .already{
            margin-right: 90px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-style: italic;
        }
        .dont{
            margin-right: 90px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-style: italic;
        }
        .sign{
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .content {
            position: fixed;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            color: #f1f1f1;
            width: 100%;
            padding: 20px;
        }
        .vid{
            background-size: contain;
            margin-left: -20px;
        }
        .container select[name="role"] {
            background-color: #f6f6f6;
            margin: 8px 0;
            padding: 10px 15px;
            font-size: 13px;
            border-radius: 8px;
            border-width: 0;
            width: 100%;
            outline: none;
        }

        .menu-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            cursor: pointer;
        }

        .toggle-icon {
            width: 30px;
            height: 30px;
            background-color: #333; /* Updated color */
            position: relative;
            border-radius: 4px; /* Added border radius */
        }

        .toggle-icon::before,
        .toggle-icon::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: #fff;
            top: 50%;
            transition: transform 0.3s ease;
        }

        .toggle-icon::before {
            transform: translateY(-8px);
        }

        .toggle-icon::after {
            transform: translateY(8px);
        }

        .menu-open .toggle-icon::before {
            transform: translateY(0) rotate(45deg);
        }

        .menu-open .toggle-icon::after {
            transform: translateY(0) rotate(-45deg);
        }

        .admin-panel {
            position: fixed;
            top: 0;
            left: -300px;
            width: 300px;
            height: 100%;
           background-color: aquamarine;
            z-index: 999;
            overflow: hidden;
            transition: left 0.3s ease;
        }

        .menu-open .admin-panel {
            left: 0;
        }

        .admin-content {
            padding: 20px;
        }

        .admin-content form {
            margin-top: 20px;
        }

        .admin-content input {
            width: calc(100% - 40px);
            margin-bottom: 10px;
            padding: 10px;
        }

        .admin-content button {
            width: calc(100% - 40px);
            padding: 10px;
            background-color: #000;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        .admin-content h1 {
            margin-bottom: 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

    </style>
</head>
<body>
    <div class="container">
        <div class="form-container sign-up">
            <form method="post">
                <h1>Create Account</h1>
                <input type="text" placeholder="Name" name="name" required>
                <input type="email" placeholder="Email" name="user_email" required>
                <input type="password" placeholder="Password" name="password" required>
                <!-- Inside the sign-up form -->
<input type="password" placeholder="Confirm Password" name="confirm_password" required>

                <select name="role" required>
                    <option value="">Select Role</option>
                    <option value="alumni">Alumni</option>
                    <option value="student">Student</option>
                </select>
                <input type="text" placeholder="Batch [e.g. 2021-2025]" name="batch" required> <!-- Batch input -->
                <button type="submit" name="register">Sign Up</button>
            </form>
        </div>
        <div class="form-container sign-in">
            <form method="post">
                <h1>Sign In</h1>
                <input type="email" placeholder="Email" name="user_email" required>
                <input type="password" placeholder="Password" name="password" required>
                <a href="passwordreset.php">Forgot password</a>
                <button type="submit" name="login">Sign In</button>
            </form>
        </div>
        <div class="toggle-container">
            <div class="toggle">
                <div class="toggle-panel toggle-left">
                    <h1>Hello There!</h1>
                    <p>Already have an account? <br>Sign in using the button below.</p>
                    <button id="login">Sign In</button>
                </div>
                <div class="toggle-panel toggle-right">
                    <h1>Welcome</h1>
                    <p>Don't have an account? <br>Sign up using the button below.</p>
                    <button id="register">Sign Up</button>
                </div>
            </div>
        </div>
    </div>
    <div class="admin-panel">
    <div class="admin-content">
        <h1>Admin Login</h1>
        <form method="post">
            <input type="text" placeholder="Admin Name" name="admin_name" required>
            <input type="password" placeholder="Password" name="admin_password" required>
            <button type="submit" name="admin_login" >Login</a></button>
        </form>
    </div>
</div>
<div class="menu-toggle">
    <div class="toggle-icon"></div>
</div>


    <script>
       document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('.container');
    const registerBtn = document.getElementById('register');
    const loginBtn = document.getElementById('login');
    const roleSelect = document.querySelector('select[name="role"]');
    const signUpForm = document.querySelector('.sign-up form');

    registerBtn.addEventListener('click', () => {
        container.classList.add("active");
    });

    loginBtn.addEventListener('click', () => {
        container.classList.remove("active");
    });

    roleSelect.addEventListener('change', () => {
        if (roleSelect.value === 'student') {
            // Function to prompt for phone number
            const promptPhoneNumber = () => {
                const phoneNumber = prompt("Please enter your phone number:");
                if (phoneNumber !== null) {
                    // Validate phone number length
                    if (phoneNumber.length <= 10) {
                        // Add phone number input to the form
                        const phoneInput = document.createElement('input');
                        phoneInput.setAttribute('type', 'text');
                        phoneInput.setAttribute('name', 'phone_number');
                        phoneInput.setAttribute('value', phoneNumber);
                        phoneInput.style.display = 'none'; // Hide the input

                        signUpForm.appendChild(phoneInput);
                    } else {
                        // Display error message
                        alert("Phone number must not exceed 10 digits.");
                        // Prompt again for phone number
                        promptPhoneNumber();
                    }
                }
            };

            // Initial prompt for phone number
            promptPhoneNumber();
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.querySelector('.menu-toggle');
    const adminPanel = document.querySelector('.admin-panel');

    menuToggle.addEventListener('click', function () {
        document.body.classList.toggle('menu-open');
    });
});

// Inside the JavaScript event listeners
document.addEventListener('DOMContentLoaded', function () {
    const signUpForm = document.querySelector('.sign-up form');

    signUpForm.addEventListener('submit', (event) => {
        const password = signUpForm.querySelector('input[name="password"]').value;
        const confirmPassword = signUpForm.querySelector('input[name="confirm_password"]').value;

        if (password !== confirmPassword) {
            alert("Password and Confirm Password do not match.");
            event.preventDefault(); // Prevent form submission
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const signUpForm = document.querySelector('.sign-up form');

    signUpForm.addEventListener('submit', (event) => {
        const nameInput = signUpForm.querySelector('input[name="name"]').value;

        // Regular expression to check if name contains only letters and spaces
        const namePattern = /^[a-zA-Z\s]+$/;

        if (!namePattern.test(nameInput)) {
            alert("Name can only contain letters and spaces!");
            event.preventDefault(); // Prevent form submission
        }
    });
});

    </script>
</body>
</html>