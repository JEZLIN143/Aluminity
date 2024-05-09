<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // No password set
$dbname = "picture";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize the message variable
$message = "";

// Form submission handling
if(isset($_POST['submit'])) {
    $description = $_POST['description'];
    $photo = $_FILES['photo']['name'];
    $temp = $_FILES['photo']['tmp_name'];
    $uploadDate = $_POST['upload_date']; // Retrieve upload date
    
    // Directory where uploaded files will be saved
    $uploadDirectory = 'uploads/';

    // Create directory if it doesn't exist
    if (!file_exists($uploadDirectory)) {
        mkdir($uploadDirectory, 0777, true);
    }

    // Upload photo to server
    if (move_uploaded_file($temp, $uploadDirectory . $photo)) {
        // Insert data into database
        $sql = "INSERT INTO picture (photo_data, description, date) VALUES ('$photo', '$description', '$uploadDate')";
        
        if ($conn->query($sql) === TRUE) {
            $message = "Photo uploaded successfully";
        } else {
            $message = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        $message = "Failed to upload photo";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Upload</title>
    <style>
        /* CSS for fade-in animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
            overflow: hidden; /* Hide overflow to prevent scrolling */
        }

        .container {
            position: absolute;
            top: 50%; /* Center vertically */
            left: 50%; /* Center horizontally */
            transform: translate(-50%, -50%); /* Center horizontally and vertically */
            max-width: 500px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            box-sizing: border-box;
        }

        h2 {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="file"],
        textarea,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        .success-message {
            opacity: 0; /* Initially transparent */
            background-color: #4caf50; /* Initial color */
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            text-align: center;
            animation: fadeIn 0.5s ease-in-out; /* Apply fade in animation */
            display: none; /* Initially hidden */
            transition: background-color 0.5s; /* Smooth transition for background color */
        }

        .show-message {
            opacity: 1; /* Make it fully opaque */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Upload Photo</h2>
        <form id="upload-form" action="" method="post" enctype="multipart/form-data">
            <label for="photo">Choose Photo:</label>
            <input type="file" name="photo" id="photo">
            <label for="description">Description:</label>
            <textarea name="description" id="description" rows="4" cols="50"></textarea>
            <label for="upload-date">Upload Date:</label>
            <input type="date" name="upload_date" id="upload-date">
            <input type="submit" name="submit" value="Upload">
            <!-- Initially hide the success message -->
            <p id="success-message" class="success-message"><?php echo $message; ?></p>
        </form>
    </div>

    <script>
        document.getElementById('upload-form').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent form submission
            
            // Display the success message with fade in effect
            var successMessage = document.getElementById('success-message');
            successMessage.style.display = 'block'; // Show the message
            successMessage.classList.add('show-message'); // Apply fade in animation
            
            // Toggle background color between grey and green
            successMessage.style.backgroundColor = 'grey'; // Initial color
            
            // Function to toggle between grey and green colors every 250 milliseconds
            var intervalId = setInterval(function() {
                successMessage.style.backgroundColor = (successMessage.style.backgroundColor === 'grey') ? '#4caf50' : 'grey';
            }, 250);

            // Remove the fade in class and clear interval after a delay to trigger fade out
            setTimeout(function() {
                successMessage.classList.remove('show-message');
                clearInterval(intervalId); // Clear the interval
            }, 2000); // Adjust the delay as needed
        });
    </script>
</body>
</html>
