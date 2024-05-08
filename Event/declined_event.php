<?php
// Check if feedback ID is provided via POST method
if(isset($_POST['feedback_id'])) {
    // Database connection parameters
    $servername = "localhost"; // Change this to your database server hostname
    $username = "root"; // Change this to your database username
    $password = ""; // Change this to your database password
    $database = "events"; // Change this to your database name

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
        echo "<div class='dropdown-animation'>EVENT DECLINED</div>";
    } else {
        // Failed to delete feedback
        echo "Error deleting feedback: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
} else {
    // Feedback ID not provided
    echo "Feedback ID not provided";
}
?>
<style>
    .dropdown-animation {
    margin-top: 350px;
    text-align: center;
    font-size: 54px;
    animation: dropdown 1s ease forwards;
    opacity: 0;
   font-family: 'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
}

@keyframes dropdown {
    0% {
        transform: translateY(-50px);
        opacity: 0;
    }
    100% {
        transform: translateY(0);
        opacity: 1;
    }
}
</style>
