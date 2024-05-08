<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection parameters
    $servername = "localhost"; // Change this to your database server hostname
    $username = "root"; // Change this to your database username
    $password = ""; // Change this to your database password
    $database = "feed_back"; // Change this to your database name

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Retrieve form data
    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $subject = $_POST["subject"];
    $feedback = $_POST["feedback"];

    // Prepare SQL statement
    $sql = "INSERT INTO feedback (name, phone, email, subject, feedback) VALUES (?, ?, ?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $name, $phone, $email, $subject, $feedback);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Redirect back to the feedback form with a success message
        header("Location: feedback_form.php?status=success");
        exit();
    } else {
        // Redirect back to the feedback form with an error message
        header("Location: feedback_form.php?status=error");
        exit();
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // If the form is not submitted, redirect back to the feedback form
    header("Location: feedback_form.php");
    exit();
}
?>
