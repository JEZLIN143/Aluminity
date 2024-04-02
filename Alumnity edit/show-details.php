<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumni Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #3498db, #8e44ad);
        }

        .details-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            padding: 20px;
            max-width: 600px;
            width: 100%;
            text-align: left;
            animation: fadeIn 0.5s ease;
            position: relative; /* Make the container position relative */
        }

        .details-container .profile-image {
            position: absolute; /* Position the image absolutely */
    top: 80px; /* Adjust top position */
    right: 20%; /* Center horizontally */
    transform: translateX(50%); /* Center horizontally */
    width: 150px; /* Increase width */
    height: 150px; /* Increase height */
    border-radius: 50%; /* Make it circular */
    object-fit: cover; /* Maintain aspect ratio */
    border: 2px solid #fff; /* Add border */
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3); /* Add shadow */
        }

        h1 {
            margin-bottom: 20px;
            color: #2c3e50;
            text-align: center;
        }

        p {
            margin: 10px 0;
            color: #34495e;
            line-height: 1.6;
        }

        .highlight {
            font-weight: bold;
            color: #2980b9;
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
<?php
// Include database connection
include("connection.php"); 

// Check if email is provided via GET method
if(isset($_GET['email'])) {
    // Retrieve email from GET parameters
    $user_email = $_GET['email'];
    
    // Query to retrieve details based on email
    $query = "SELECT * FROM alumni_details WHERE user_email='$user_email'";
    $result = mysqli_query($con, $query);

    // Check if details are found
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // Display alumni details
        echo "<div class='details-container'>";
        echo "<h1>Alumni Details</h1>";
        // Check if user has uploaded an image
        if(!empty($row['user_image'])) {
            // Display uploaded image
            echo "<img src='data:image/jpeg;base64," . base64_encode($row['user_image']) . "' alt='User Image' class='profile-image'>";
        } else {
            // Display default image if no image is uploaded
            echo "<img src='user.jpg' alt='User Image' class='profile-image'>";
        }
        echo "<p><span class='highlight'>Name:</span> " . $row['name'] . "</p>";
        echo "<p><span class='highlight'>Batch:</span> " . $row['user_batch'] . "</p>";
        echo "<p><span class='highlight'>Phone Number:</span> " . $row['phone_no'] . "</p>";
        echo "<p><span class='highlight'>Email:</span> " . $row['user_email'] . "</p>";
        echo "<p><span class='highlight'>Job:</span> " . $row['prev_job'] . "</p>";
        echo "<p><span class='highlight'>Current Job:</span> " . $row['current_job'] . "</p>";
        echo "<p><span class='highlight'>Vacant Job:</span> " . $row['vacant_job'] . "</p>";
        echo "</div>";
    } else {
        echo "<p>No details found for this alumni.</p>";
    }
} else {
    echo "<p>No alumni selected.</p>";
}
?>

</body>
</html>
