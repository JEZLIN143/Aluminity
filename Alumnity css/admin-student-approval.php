<?php
include("connection.php"); 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Start session to track user login status
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // Function to generate a random username
    function generateUsername($con, $role)
    {
        $prefix = ($role === 'alumni') ? 'alumni_' : 'student_';
        $randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number
        $username = $prefix . $randomNumber;
        
        // Check if the username already exists
        $query = "SELECT COUNT(*) as count FROM student_details WHERE special_username='$username'";
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

    // Function to send email to user
    function sendApprovalEmail($con, $id, $email, $role)
    {
        // Generate a random username
        $username = generateUsername($con, $role);
        
        // Store the username in the database
        $updateQuery = "UPDATE student_details SET special_username='$username', status='approved' WHERE id='$id'";
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
        
        // Include special username in the email body
        $mail->Body    = 'Congratulations! Your registration on Alumnity has been approved. You can now login to the website. Your Alumnity username is ' . $username . '.'; // Email body

        $mail->send(); // Send email
    }

    // Handle approve and deny actions for students
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'approve_') !== false) {
            $id = str_replace('approve_', '', $key);

            // Get user email
            $getUserEmailQuery = "SELECT user_email FROM student_details WHERE id='$id'";
            $userResult = mysqli_query($con, $getUserEmailQuery);
            $userData = mysqli_fetch_assoc($userResult);
            $userEmail = $userData['user_email'];

            if ($userEmail) {
                sendApprovalEmail($con, $id, $userEmail, 'student'); // Pass the $con variable to the function
            }
        } elseif (strpos($key, 'deny_') !== false) {
            $id = str_replace('deny_', '', $key);

            // Delete user from the database
            $select = "DELETE FROM student_details WHERE id='$id'";
            $result = mysqli_query($con, $select);
        }
    }
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
        <table id="student_details">
        <tr>
    <th>User ID</th>
    <th>Email</th>
    <th>Role</th> <!-- Add a new column for Role -->
    <th>Action</th>
</tr>
<?php
$query = "SELECT * FROM student_details WHERE status='pending' ORDER BY id ASC";
$result = mysqli_query($con, $query);
while($row = mysqli_fetch_array($result)) {
    ?>
    <tr>
        <td><?php echo $row['id']; ?></td>
        <td><?php echo $row['user_email']; ?></td>
        <td><?php echo $row['role']; ?></td> <!-- Display the role -->
        <td>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <input type="hidden" name="approve_<?php echo $row['id']; ?>" value="approve_<?php echo $row['id']; ?>">
                <input type="submit" value="Approve">
            </form>
            <form action="" method="POST">
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
