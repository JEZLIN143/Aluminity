<?php
// Check if feedback ID is provided via POST method
if(isset($_POST['feedback_id'])) {
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

    // Get feedback ID from POST data
    $feedback_id = $_POST['feedback_id'];

    // Prepare SQL statement to delete feedback by ID
    $sql = "DELETE FROM feedback WHERE id = ?";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $feedback_id);

    // Execute SQL statement
    if ($stmt->execute()) {
        // Feedback deleted successfully
        echo "Feedback deleted successfully";
    } else {
        // Failed to delete feedback
        echo "Error deleting feedback: " . $conn->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Feedback ID not provided
    echo "Feedback ID not provided";
}
?>
