<?php
include_once('connection.php'); // Include your database connection file

$success = ""; // Initialize success message

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST['email'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Query to check if the email exists in the database
    $check_email_sql = "SELECT * FROM tbl_student WHERE email = '$email'";
    $result = mysqli_query($connection, $check_email_sql);

    if(mysqli_num_rows($result) == 0) {
        // Email does not exist in the database
        $error = "Email does not exist in the database.";
    } else {
        // Validate if new password matches the confirm password
        if($new_password !== $confirm_password) {
            $error = "New password and confirm password do not match.";
        } else {
            // No need to hash the new password, store it in plain text
            $plain_password = $new_password;

            // Query to update password in the database with plain text password
            $update_sql = "UPDATE tbl_student SET password = '$plain_password' WHERE email = '$email'";
            
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
        input[type="password"] {
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
            <label>New Password:</label>
            <input type="password" name="new_password" required>
            <label>Confirm Password:</label>
            <input type="password" name="confirm_password" required>
            <input type="submit" value="Reset Password">
        </form>
    </fieldset>
</body>
</html>
