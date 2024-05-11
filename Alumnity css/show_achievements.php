<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Show Achievements</title>
    <style>
        body {
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    flex-direction: column;
    background-image: url('ach4.jpg');
    background-size: cover;
    background-position: center;
    background-repeat: repeat; /* Repeat background image */
    overflow-y: auto; /* Add scroll option */
}

.achievement {
    display: flex;
    justify-content: space-between;
    background-color: rgba(255, 255, 255, 0.9); /* Lighter white */
    box-shadow: 0 0 10px rgba(255, 165, 0, 0.7); /* Orange shadow color */
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    max-width: 800px; /* Increase max-width */
}

.achievement-info {
    flex: 1;
}

.achievement-image {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.achievement h3 {
    font-size: 28px;
    margin-top: 0;
    color: #333;
}

.achievement p {
    font-size: 20px;
    color: #666;
}

.profile-image {
    max-width: 250px; /* Increased max-width */
    border-radius: 50%; /* Rounded profile image */
    margin-bottom: 20px; /* Increased margin */
}

.like-button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 8px 15px;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    font-size: 18px;
}

.like-button:hover {
    background-color: #0056b3;
}

.liked {
    background-color: #28a745 !important;
}

/* Animated message */
.animated-message {
    position: fixed;
    top: 50%;
    left: -100%;
    transform: translate(-50%, -50%);
    background-color: #28a745;
    color: white;
    padding: 15px 25px;
    border-radius: 5px;
    transition: left 0.5s ease-in-out;
}

.show-message {
    left: 50%;
}

/* Keyframes for color changing animation */
@keyframes changeColor {
    0% {
        background-color: #28a745;
    }
    50% {
        background-color: #007bff;
    }
    100% {
        background-color: #dc3545;
    }
}

/* Apply color changing animation to animated message */
.animated-message.color-changing {
    animation: changeColor 2s infinite;
}

    </style>
</head>
<body>

<?php
include 'connection.php'; // Include the database connection file

// Start the session
session_start();

// Function to check if the achievement is liked by the current user
function isLiked($achievementId) {
    return isset($_SESSION['liked'][$achievementId]);
}

// Check if student name is provided in the URL
if (isset($_GET['student'])) {
    // Sanitize and get the student name from the URL
    $studentName = mysqli_real_escape_string($con, $_GET['student']);
    
    // Fetch achievements for the selected student from the database
    $sql = "SELECT id, name, achievement_text, user_image FROM achievements WHERE name = '$studentName'";
    $result = mysqli_query($con, $sql);

    // Check if there are any achievements for the selected student
    if (mysqli_num_rows($result) > 0) {
        // Output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            // Display achievement details
            echo "<div class='achievement'>";
            
            // First box for name and achievement text
            echo "<div class='achievement-info'>";
            echo "<h3>" . $row["name"] . "</h3>";
            echo "<p>" . $row["achievement_text"] . "</p>";
            echo "</div>";
            
            // Second box for image and like button
            echo "<div class='achievement-image'>";
            // Display image if available
            if (!empty($row['user_image'])) {
                // Display uploaded image
                echo '<img src="data:image/jpeg;base64,'.$row['user_image'].'" alt="User Image" class="profile-image">';
            } else {
                // Display default image if no image is uploaded
                echo "<img src='user.jpg' alt='User Image' class='profile-image'>";
            }
            // Like button
            echo "<button class='like-button' data-id='{$row['id']}'>Like</button>";
            echo "</div>";
            
            echo "</div>"; // Close .achievement div
        }
    } else {
        echo "No achievements found for $studentName.";
    }
} else {
    echo "Student name not provided.";
}

// Close connection
mysqli_close($con);
?>

<!-- Animated message -->
<div id="animatedMessage" class="animated-message">YAYY!!!</div>

<script>
// Add click event to like button
document.querySelectorAll('.like-button').forEach(function(button) {
    button.addEventListener('click', function() {
        if (!button.classList.contains('liked')) {
            // Show animated message
            document.getElementById('animatedMessage').classList.add('show-message', 'color-changing');
            setTimeout(function() {
                document.getElementById('animatedMessage').classList.remove('show-message', 'color-changing');
            }, 2000); // Hide message after 2 seconds

            // Update like count in the database
            var id = button.getAttribute('data-id');
            fetch('update_likes.php?id=' + id);

            // Add liked class to button
            button.classList.add('liked');
        }
    });
});
</script>

</body>
</html>
