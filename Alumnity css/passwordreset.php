<?php
$dbhost = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "login_sample_db";

if(!$connection = mysqli_connect($dbhost, $dbuser, $dbpassword, $dbname)) {
    die("Failed to connect to the database!");
}

$success = ""; // Initialize success message

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $special_username = $_POST['special_username'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

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
        // Validate if new password matches the confirm password
        if($new_password !== $confirm_password) {
            $error = "New password and confirm password do not match.";
        } else {
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
            font-family: Arial, sans-serif;
            background-color: #1f1f1f;
            color: #fff;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
        }

        fieldset {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background-color: #333;
            border: none;
            border-radius: 8px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #fff;
        }

        input[type="email"],
        input[type="password"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #fff;
            border-radius: 4px;
            box-sizing: border-box;
            background-color: #444;
            color: #fff;
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

        .error {
            color: red;
            margin-top: 10px;
        }

        .success {
            color: green;
            margin-top: 10px;
        }
        #button{
            color: white;
            background-color: blue;
            font-size: 30px;
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
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label>Email:</label>
            <input type="email" name="email" required>
            <label>Special Username:</label>
            <input type="text" name="special_username" required>
            <label>New Password:</label>
            <input type="password" name="new_password" required>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            <input type="submit" value="Reset Password">
            <button onclick="location.href='login.php'">Gallery</button>
        </form>
    </fieldset>
</body>
</html>
