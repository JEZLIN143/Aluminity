<?php
// Initialize variables
$msg = '';

// Check if the form is submitted
if(isset($_POST['submit'])) {
    // Retrieve email from the form
    $email = $_POST['email'];
    
    // Here, you would typically perform validation and sanitation of the email address.
    
    // Generate a random password reset token (you can use a more secure method)
    $token = md5(uniqid(rand(), true));
    
    // Store the token in your database along with the user's email for verification
    
    // Send the reset link via email
    // In a real-world scenario, you would use a library like PHPMailer or similar for sending emails.
    $to = $email;
    $subject = 'Password Reset Request';
    $message = 'Click the following link to reset your password: http://example.com/reset.php?email=' . urlencode($email) . '&token=' . urlencode($token);
    $headers = 'From: your@example.com' . "\r\n" .
        'Reply-To: your@example.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    // Send the email
    if(mail($to, $subject, $message, $headers)) {
        $msg = 'Password reset link has been sent to your email.';
    } else {
        $msg = 'Failed to send password reset link. Please try again later.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <h2>Password Reset</h2>
    <form method="post">
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <input type="submit" name="submit" value="Reset Password">
    </form>
    <p><?php echo $msg; ?></p>
</body>
</html>
