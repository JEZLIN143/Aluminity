<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Your CSS styles here */
    </style>
</head>
<body>
    <div class="container">
        <h1>Approved Events</h1>
        <?php
        // Database connection parameters
        $servername = "localhost"; // Change this to your database server hostname
        $username = "root"; // Change this to your database username
        $password = ""; // Change this to your database password
        $database = "aa"; // Change this to your database name

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
                    <!-- Delete and Approve buttons -->
                </div>
        <?php
            }
        } else {
            echo "<p>No Events found</p>";
        }

        // Close connection
        $conn->close();
        ?>
    </div>
</body>

<style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4; /* Grey background */
        }
        .container {
            max-width: 800px;
            align-items: center;
            text-align: center;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff; /* White container background */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Box shadow */
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 36px;
            margin-bottom: 20px;
        }
        .feedback-section {
            background-color: #f0f0f0; /* Light grey feedback background */
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        .feedback-section h3 {
            margin-bottom: 5px;
            color: #333;
        }
        .feedback-section p {
            margin: 5px 0;
            color: #555;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 10px;
        }
        .delete-button, .approve-button {
            background-color: red;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            margin: 0 5px; /* Added margin */
            transition: background-color 0.3s;
        }
        .approve-button {
            background-color: green;
        }
        .delete-button:hover, .approve-button:hover {
            background-color: darkred;
        }
    </style>
</html>
