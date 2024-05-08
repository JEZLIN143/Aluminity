<?php
include_once('connection.php'); // Include your database connection file

if($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    // No need to hash the password
    $plain_password = $password;

    // Query to insert data into the database with plain text password
    $insert_sql = "INSERT INTO tbl_student (username, password, email) VALUES ('$username', '$plain_password', '$email')";
    
    if(mysqli_query($connection, $insert_sql)) {
        echo "Record inserted successfully.";
    } else {
        echo "Error inserting record: " . mysqli_error($connection);
    }
}

mysqli_close($connection); // Close the database connection
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
</head>
<body>
    <h2>Register</h2>
    <form method="post" action="<?php echo ($_SERVER["PHP_SELF"]); ?>">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>
        <label>Password:</label>
        <input type="password" name="password" required><br><br>
        <label>Email:</label>
        <input type="email" name="email" required><br><br>
        <input type="submit" value="Register">
    </form>
</body>
</html>
