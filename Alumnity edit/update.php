<?php
include("connection.php");

// Fetching user email from the form submission
$user_email = isset($_POST['email']) ? $_POST['email'] : '';
$name = '';
$user_batch = '';
$phone_no = '';
$prev_job = '';
$current_job = '';
$vacant_job = '';

// Handling form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($user_email)) {
    // Retrieve form data
    $name = $_POST['name'] ?? '';
    $user_batch = $_POST['batch'] ?? '';
    $phone_no = $_POST['phone'] ?? '';
    $prev_job = $_POST['prev_job'] ?? '';
    $current_job = $_POST['current_job'] ?? '';
    $vacant_job = $_POST['vacant_job'] ?? '';

    // Handle file upload
    if ($_FILES['profile_image']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['profile_image']['tmp_name'])) {
        // Read the file content
        $imageData = file_get_contents($_FILES['profile_image']['tmp_name']);
        // Escape special characters
        $imageDataEscaped = mysqli_real_escape_string($con, $imageData);

        // Update alumni_details table with image data
        $update_query_alumni_details = "UPDATE alumni_details SET name='$name', user_batch='$user_batch', phone_no='$phone_no', prev_job='$prev_job', current_job='$current_job', vacant_job='$vacant_job', user_image='$imageDataEscaped' WHERE user_email='$user_email'";

        // Execute the query
        if (mysqli_query($con, $update_query_alumni_details)) {
            // Redirect to explore-alumni.php after saving the details
            header("Location: explore-alumni.php");
            exit(); // Ensure script execution stops here
        } else {
            echo "Error updating alumni_details record: " . mysqli_error($con);
        }
    } else {
        echo "Error uploading file.";
    }
}

// Fetch user details if email is provided
if (!empty($user_email)) {
    // Fetching data from the alumni_details table
    $query_alumni_details = "SELECT name, user_batch, phone_no, prev_job, current_job, vacant_job FROM alumni_details WHERE user_email='$user_email'";
    $result_alumni_details = mysqli_query($con, $query_alumni_details);

    if(mysqli_num_rows($result_alumni_details) > 0) {
        $row_alumni_details = mysqli_fetch_assoc($result_alumni_details);
        $name = $row_alumni_details['name'] ?? '';
        $user_batch = $row_alumni_details['user_batch'] ?? '';
        $phone_no = $row_alumni_details['phone_no'] ?? '';
        $prev_job = $row_alumni_details['prev_job'] ?? '';
        $current_job = $row_alumni_details['current_job'] ?? '';
        $vacant_job = $row_alumni_details['vacant_job'] ?? '';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Alumni Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #3498db, #8e44ad);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            width: 300px; /* Adjusted width */
            max-height: 80vh; /* Maximum height */
            padding: 15px; /* Adjusted padding */
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.5s ease;
            overflow-y: auto; /* Enable vertical scrolling */
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #333;
            font-size: 25px;
            font-style:initial;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        input[type="text"],
        input[type="number"],
        input[type="email"],
        input[type="tel"],
        input[type="file"] {
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: border-color 0.3s ease;
            font-size: 16px;
            outline: none;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="file"]:focus {
            border-color: #007bff;
        }

        input[type="submit"],
        input[type="button"] {
            padding: 12px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            outline: none;
        }

        input[type="submit"]:hover,
        input[type="button"]:hover {
            background-color: #0056b3;
        }

        .btn-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Alumni Profile Edit</h1>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="email" value="<?php echo $user_email; ?>">
            
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $name; ?>">
            
            <label for="batch">Batch:</label>
            <input type="text" id="batch" name="batch" value="<?php echo $user_batch; ?>" >
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user_email; ?>" >

            <label for="phone">Phone Number:</label>
            <input type="tel" id="phone" name="phone" value="<?php echo $phone_no; ?>" >
            
            <label for="prev_job">Previous Job:</label>
            <input type="text" id="prev_job" name="prev_job" value="<?php echo $prev_job; ?>">
            
            <label for="current_job">Current Job:</label>
            <input type="text" id="current_job" name="current_job" value="<?php echo $current_job; ?>" >
            
            <label for="vacant_job">Vacant Job:</label>
            <input type="text" id="vacant_job" name="vacant_job" value="<?php echo $vacant_job; ?>" >

            <label for="profile_image">Profile Image:</label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*">

            <div class="btn-container">
                <input type="submit" value="Save">
                <input type="button" value="Cancel" onclick="window.location.href='explore-alumni.php'">
            </div>
        </form>
    </div>
</body>
</html>
