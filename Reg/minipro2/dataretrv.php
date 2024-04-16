<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <style>
        html {
            background: linear-gradient(132deg, rgb(2, 108, 223) 0.00%, rgb(0, 246, 253) 74.95%);
        }
        .feedback-section {
            background: linear-gradient(132deg, rgb(115, 93, 142) 0.00%, rgb(58, 33, 86) 100.00%);
            padding: 20px;
            color: white;
            margin-bottom: 20px;
            align-items: center;
            text-align: center;
            border-radius: 12px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
        }
        h1 {
            align-items: center;
            text-align: center;
            color: black;
            font-size: 80px;
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
        .delete-button {
            background-color: red;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Feedback</h1>
    <?php
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

    // SQL query to retrieve data
    $sql = "SELECT * FROM feedback";

    // Execute SQL query
    $result = $conn->query($sql);

    // Check if there are any rows returned
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
    ?>
            <div class="feedback-section">
                <h3>Name: <?php echo $row["name"]; ?></h3>
                <p><strong>Phone:</strong> <?php echo $row["phone"]; ?></p>
                <p><strong>Email:</strong> <?php echo $row["email"]; ?></p>
                <p><strong>Subject:</strong> <?php echo $row["subject"]; ?></p>
                <p><strong>Id:</strong> <?php echo $row["id"]; ?></p>
                <p><strong>Feedback:</strong> <?php echo $row["feedback"]; ?></p>
                <!-- Delete button -->
                <form method="post" action="delete_feedback.php">
                    <input type="hidden" name="feedback_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="delete-button">Delete</button>
                </form>
            </div>
    <?php
        }
    } else {
        echo "<p>No feedback found</p>";
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
