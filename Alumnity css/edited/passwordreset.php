<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "login_sample_db";

if(!$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname)) {
    die("Failed to connect to the database!");
}

$success = ""; // Initialize success message
$error = ""; // Initialize error message

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $special_username = $_POST['special_username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if new password matches confirm password
    if($new_password !== $confirm_password) {
        $error = "New password and confirm password do not match.";
    } else {
        // Query to check if the email exists in the student_details table
        $check_student_sql = "SELECT * FROM student_details WHERE user_email = '$email' AND special_username = '$special_username'";
        $student_result = mysqli_query($connection, $check_student_sql);

        // Query to check if the email exists in the alumni_details table
        $check_alumni_sql = "SELECT * FROM alumni_details WHERE user_email = '$email' AND special_username = '$special_username'";
        $alumni_result = mysqli_query($connection, $check_alumni_sql);

        // Check if email and special_username match in either student_details or alumni_details table
        if(mysqli_num_rows($student_result) > 0) {
            $table = 'student_details'; // Set table to student_details
        } elseif(mysqli_num_rows($alumni_result) > 0) {
            $table = 'alumni_details'; // Set table to alumni_details
        } else {
            // Email and special_username do not match
            $error = "Email and special username combination does not exist in the database.";
        }

        if(isset($table)) {
            // No need to hash the new password, store it in plain text
            $plain_password = $new_password;

            // Query to update password in the appropriate table with plain text password
            $update_sql = "UPDATE $table SET password = '$plain_password' WHERE user_email = '$email' AND special_username = '$special_username'";
            
            if(mysqli_query($connection, $update_sql)) {
                $success = "Password updated successfully.";
            } else {
                $error = "Error updating password: " . mysqli_error($connection);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #222; /* Dark background color */
            color: #ddd; /* Light text color */
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            margin-top: 50px;
            color: #007bff; /* Accent color */
        }

        fieldset {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #333; /* Darker background color */
            border: none;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Darker box shadow */
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #ddd; /* Light text color */
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #333; /* Border color */
            box-sizing: border-box;
            background-color: #444; /* Darker background color */
            color: red; /* Light text color */
            border-radius: 0px; /* Initial border-radius */
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            transition: border-radius 0.3s; /* Transition for border-radius */
            text-align: center; /* Center text */
        }

        input[type="email"]:focus,
        input[type="password"]:focus,
        input[type="text"]:focus {
            border-radius: 50px; /* Rounded border-radius when focused */
            border-color: black
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }

        input[type="submit"].success {
            animation: blinkGreen 1s infinite alternate; /* Blink animation */
        }

        @keyframes blinkGreen {
            from {
                background-color:50% #007bff; /* Start color */
            }
            to {
                background-color: #28a745; /* End color */
            }
        }

        .error {
            color: #dc3545;
            margin-top: 10px;
        }

        .success {
            color: #28a745;
            margin-top: 10px;
        }

        .gallery {
            width: 100%;
            padding: 10px;
            background-color: red;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 10px;
            text-align: center;
        }

        .gallery-text {
            display: inline-block;
            width: 100%;
            transition: opacity 0.3s;
            text-align: center;
        }

        .gallery:hover .gallery-text {
           
        }
    </style>
</head>
<body>
    <h2>Forgot Password</h2>
    <fieldset>
        <?php if(isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <?php if(isset($success) && !empty($success) && $_SERVER["REQUEST_METHOD"] == "POST"): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>
        <form id="resetForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Special Username:</label>
            <input type="text" name="special_username" required>
            <label>New Password:</label>
            <input type="password" name="new_password" required>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            <input type="submit" value="Reset Password" onclick="return confirmReset()" class="reset-button">
            <button onclick="location.href='login.php'" class="gallery">
                <span class="gallery-text">HOME</span>
            </button>
        </form>
    </fieldset>

    <script>
        function confirmReset() {
            return confirm("Are you sure about the changes?");
        }

        // Add a class to the reset button when clicked
        document.getElementById('resetForm').addEventListener('submit', function() {
            document.querySelector('.reset-button').classList.add('success');
        });
    </script>
</body>
</html>
