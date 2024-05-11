<?php
include("connection.php"); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Function to generate a random key
    function generateKey() {
        // Generate a random 4-digit key
        return str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
    }
    
    // Function to generate a random username
    function generateUsername($con, $role)
    {
        $prefix = ($role === 'alumni') ? 'alumni_' : 'student_';
        $randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number
        $username = $prefix . $randomNumber;
        
        // Check if the username already exists
        $query = "SELECT COUNT(*) as count FROM alumni_details WHERE special_username='$username'";
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_assoc($result);
        $count = $data['count'];
        
        // If username already exists, generate a new one recursively
        if ($count > 0) {
            return generateUsername($con, $role);
        } else {
            return $username;
        }
    }

    // Function to send email to user with different messages based on role
    function sendApprovalEmail($con, $id, $email, $role)
    {
        $key = generateKey(); // Generate a random key
        
        // Generate a random username
        $username = generateUsername($con, $role);
        
        // Store the key and username in the database
        $updateQuery = "UPDATE alumni_details SET private_key='$key', special_username='$username' WHERE id='$id'";
        $updateResult = mysqli_query($con, $updateQuery);

        // Send email to the user
        $mail = new PHPMailer(true);

        // Server settings
        $mail->isSMTP(); // Send using SMTP
        $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
        $mail->SMTPAuth   = true; // Enable SMTP authentication
        $mail->Username   = 'jeslinsanthosh2613@gmail.com'; // SMTP username
        $mail->Password   = 'enic hlma iuto qlgx'; // SMTP password
        $mail->SMTPSecure = 'ssl'; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 465; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        // Recipients
        $mail->setFrom('your-email@example.com', 'AlumnITy'); // Your email and name
        $mail->addAddress($email); // Add a recipient

        // Content
        $mail->isHTML(true); // Set email format to HTML
        $mail->Subject = 'Your registration is approved'; // Email subject
        
        // Get special username from the database
        $getUsernameQuery = "SELECT special_username FROM alumni_details WHERE id='$id'";
        $usernameResult = mysqli_query($con, $getUsernameQuery);
        $userData = mysqli_fetch_assoc($usernameResult);
        $specialUsername = $userData['special_username'];

        // Include special username in the email body
        if ($role === 'alumni') {
            $mail->Body    = 'Congratulations! Your registration on Alumnity has been approved. You can now login to the website. Your Alumnity username is ' . $specialUsername . ' and your private key is ' . $key . '.'; // Email body for alumni
        } else {
            $mail->Body    = 'Congratulations! Your registration on Alumnity has been approved. You can now login to the website. Your Alumnity username is ' . $specialUsername . '.'; // Email body for other roles
        }

        $mail->send(); // Send email
    }

    // Handle approve and deny actions
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'approve_') !== false) {
            $id = str_replace('approve_', '', $key);

            // Update user status to 'approved' in the database
            $select = "UPDATE alumni_details SET status='approved' WHERE id='$id'";
            $result = mysqli_query($con, $select);

            // Get user role
            $getUserRoleQuery = "SELECT role FROM alumni_details WHERE id='$id'";
            $userRoleResult = mysqli_query($con, $getUserRoleQuery);
            $userData = mysqli_fetch_assoc($userRoleResult);
            $userRole = $userData['role'];

            // Send approval email to the user with role-specific message
            $getUserEmailQuery = "SELECT user_email FROM alumni_details WHERE id='$id'";
            $userResult = mysqli_query($con, $getUserEmailQuery);
            $userData = mysqli_fetch_assoc($userResult);
            $userEmail = $userData['user_email'];

            if ($userEmail && $userRole) {
                sendApprovalEmail($con, $id, $userEmail, $userRole); // Pass the $con variable to the function
            }
        } elseif (strpos($key, 'deny_') !== false) {
            $id = str_replace('deny_', '', $key);

            // Delete user from the database
            $select = "DELETE FROM alumni_details WHERE id='$id'";
            $result = mysqli_query($con, $select);
        }
    }

    // Redirect to admin approval page
    header("Location: admin-approval.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Approval</title>
    <style>
        /* Your CSS styles */
        body {
            font-family: Arial, sans-serif;
        }
        .register {
            margin: 20px;
        }
        h1 {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
        form {
            display: inline-block;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }

    </style>
</head>
<body>
    <div class="register">
        <h1>User register</h1>
        <table id="alumni_details">
            <tr>
                <th>User ID</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
            <?php
            $query = "SELECT * FROM alumni_details WHERE role='alumni' AND status='pending' ORDER BY id ASC";
            $result = mysqli_query($con, $query);
            while($row = mysqli_fetch_array($result)) {
                ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['user_email']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <form action="admin-approval.php" method="POST">
                            <input type="hidden" name="approve_<?php echo $row['id']; ?>" value="approve_<?php echo $row['id']; ?>">
                            <input type="submit" value="Approve">
                        </form>
                        <form action="admin-approval.php" method="POST">
                            <input type="hidden" name="deny_<?php echo $row['id']; ?>" value="deny_<?php echo $row['id']; ?>">
                            <input type="submit" value="Deny">
                        </form>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </div>
</body>
</html>
