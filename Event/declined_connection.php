<?php
// Database connection parameters for the new database
$newServername = "localhost"; // Change this to your new database server hostname
$newUsername = "root"; // Change this to your new database username
$newPassword = ""; // Change this to your new database password
$newDatabase = "dd"; // Change this to your new database name

// Create connection to the new database
$newConn = new mysqli($newServername, $newUsername, $newPassword, $newDatabase);

// Check connection
if ($newConn->connect_error) {
    die("Connection to new database failed: " . $newConn->connect_error);
}

// Database connection parameters for the original database
$servername = "localhost"; // Change this to your original database server hostname
$username = "root"; // Change this to your original database username
$password = ""; // Change this to your original database password
$database = "events"; // Change this to your original database name

// Create connection to the original database
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection to original database failed: " . $conn->connect_error);
}

// SQL query to retrieve data from original database
$sql = "SELECT * FROM feedback";

// Execute SQL query
$result = $conn->query($sql);

// Check if there are any rows returned
if ($result->num_rows > 0) {
    // Output data of each row
    while ($row = $result->fetch_assoc()) {
        // SQL query to insert data into new database
        $insertSql = "INSERT INTO feedback (name, phone, email, subject, feedback)
                      VALUES ('" . $row["name"] . "', '" . $row["phone"] . "', '" . $row["email"] . "', '" . $row["subject"] . "', '" . $row["feedback"] . "')";

        // Execute SQL insert query
        if ($newConn->query($insertSql) === TRUE) {
            echo "Record inserted successfully into new database";
        } else {
            echo "Error inserting record: " . $newConn->error;
        }
    }
} else {
    echo "No records found in original database";
}

// Close connections
$newConn->close();
$conn->close();
?>
