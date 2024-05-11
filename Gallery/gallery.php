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
    $uploadDate = $_POST['upload_date']; // Retrieve upload date
    
    // Directory where uploaded files will be saved
    $uploadDirectory = 'uploads/';

    // Check if file is uploaded
    if(isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) { // Check if file is not empty
        $photo = $_FILES['photo']['name'];
        $temp = $_FILES['photo']['tmp_name'];

        // Create directory if it doesn't exist
        if (!file_exists($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Upload photo to server
        if (move_uploaded_file($temp, $uploadDirectory . $photo)) {
            // Insert data into database
            $sql = "INSERT INTO picture (photo_data, description, date) VALUES ('$photo', '$description', '$uploadDate')";
            
            if ($conn->query($sql) === TRUE) {
                // Data successfully uploaded to the database
                $message = "Upload Successful";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            $message = "Failed to upload photo";
        }
    } else {
        $message = "Please select a photo before uploading";
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
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
    }
    h2 {
        text-align: center;
        color: #333;
        margin-top: 30px;
    }
    form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    label {
        display: block;
        font-weight: bold;
        margin-bottom: 5px;
        color: #333;
    }
    input[type="file"],
    textarea,
    input[type="date"],
    input[type="submit"] {
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
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    .message {
        text-align: center;
        margin-top: 10px;
        color: #4caf50;
    }
    #progress-bar {
        width: 100%;
        background-color: white;
        border-radius: 5px;
        margin-bottom: 20px;
        position: relative;
        /* Initially hide the progress bar */
        display: none;
    }
    #progress {
        width: 0%;
        height: 60px;
        background-color: #215E7C;
        transition: width 2s ease; /* Adjust the duration here */
    }
    .progress-message {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-weight: bold;
        font-size: 18px;
    }
    .popdown-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 20px;
            box-sizing: border-box;
            transition: top 0.5s;
            z-index: 999; /* Ensure the message appears on top of other content */
        }

        /* CSS styles for the button */
        #uploadBtn {
            margin: 20px auto;
            display: block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
</style>

</head>

<body>
    <h2>Upload Photo</h2>
    <div id="popdownMessage" class="popdown-container" style="top: -100%;">This is a pop-down message!</div>

    <form id="uploadForm" action="" method="post" enctype="multipart/form-data">
        <label for="photo">Choose Photo:</label>
        <input type="file" name="photo" id="photo">
        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4" cols="50"></textarea>
        <label for="upload-date">Upload Date:</label>
        <input type="date" name="upload_date" id="upload-date">
        <input type="submit" name="submit" id="uploadBtn" value="Upload">
    </form>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var showMessageBtn = document.getElementById('uploadBtn');
            var popdownMessage = document.getElementById('popdownMessage');

            showMessageBtn.addEventListener('click', function() {
                // Toggle the visibility of the pop-down message
                if (popdownMessage.style.top === '-100%') {
                    popdownMessage.style.top = '0';
                } else {
                    popdownMessage.style.top = '-100%';
                }

                // Hide the pop-down message after 3 seconds
                setTimeout(function() {
                    popdownMessage.style.top = '-100%';
                }, 3000); // 3000 milliseconds = 3 seconds
            });
        });
    </script>
</body>
</html>
