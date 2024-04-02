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
    margin: 0;
    padding: 0;
    background-image: url('pexels-eberhard-grossgasteiger-691668.jpg');
    background-size: cover;
    background-position: center;
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}

.form-container {
    color: #ffffff;
    backdrop-filter: blur(10px);
    width: 600px;
    max-height: 100vh;
    padding: 15px;
    background-color: rgba(100, 100, 140, 0.2);
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
    animation: fadeIn 0.5s ease;
    overflow: hidden;
    margin: auto;
}

@keyframes fadeIn {
    0% { opacity: 0; transform: translateY(-20px); }
    100% { opacity: 1; transform: translateY(0); }
}

h1 {
    text-align: center;
    margin-bottom: 30px;
    color: black;
    font-size: 25px;
    font-style: initial;
    font-family: 'Roboto', Arial, sans-serif;
    font-weight: bold;
}

form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    grid-gap: 10px;
    margin-bottom: 0;
}

label {
    font-weight: bold;
    margin-bottom: 5px;
    color: black;
    display: inline-block;
    position: relative;
}

input[type="text"],
input[type="number"],
input[type="email"],
input[type="tel"],
input[type="file"] {
    padding: 12px;
    margin-bottom: 5px;
    border: none;
    border-radius: 0;
    transition: border-color 0.3s ease;
    font-size: 16px;
    border-radius: 9px;
    outline: none;
    width: calc(100% - 24px);
    background-color: rgba(255, 255, 255, 0.5);
    transition: background-color 0.9s ease, color 0.3s ease;
}

input[type="text"]:focus,
input[type="number"]:focus,
input[type="email"]:focus,
input[type="tel"]:focus,
input[type="file"]:focus {
    border-radius: 9px;
    background-color: black; /* Change background color to black when focused */
    color: white; /* Change text color to white when focused */
    border-color: black; /* Change border color to black when focused */
}

input[type="submit"],
input[type="button"] {
    padding: 12px 20px;
    background-color: grey;
    color: #fff;
    
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    font-size: 16px;
    outline: none;
}

@keyframes blinkGreen {
    0% { background-color: #007bff; } 
    10% { background-color: green; } 
    20% { background-color: ; } 
    30% { background-color: #violet; } 
    40% { background-color: #grey; } 
    50% { background-color: #pink; } 
    60% { background-color: #purple; } 
    70% { background-color: #magenta; } 
    80% { background-color: #coral; } 
    90% { background-color: #gold; } 
    60% { background-color: #; } 
}


input[type="submit"]:hover {
    animation: blinkGreen 1s infinite; 
}

input[type="button"]:hover {
    background-color: red
}

.btn-container {
    grid-column: span 2;
    text-align: center;
    margin-top: 20px; /* Adjust margin as needed */
}

    </style>
</head>

<body>

    <div class="form-container">
    <h1>Alumni Profile Edit</h1>      

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="email" value="<?php echo $user_email; ?>">
            <label for="name">Name:</label>
<input type="text" id="name" name="name" value="<?php echo $name; ?>" required>
            
<label for="batch">Batch:</label>
<input type="text" id="batch" name="batch" value="<?php echo $user_batch; ?>" required>
            
<label for="email">Email:</label>
<input type="email" id="email" name="email" value="<?php echo $user_email; ?>" required>

<label for="phone">Phone Number:</label>
<input type="tel" id="phone" name="phone" value="<?php echo $phone_no; ?>" required pattern="[0-9]{10}" title="Please enter a 10-digit phone number">
            
<label for="prev_job">Previous Job:</label>
<input type="text" id="prev_job" name="prev_job" value="<?php echo $prev_job; ?>" required>
            
<label for="current_job">Current Job:</label>
<input type="text" id="current_job" name="current_job" value="<?php echo $current_job; ?>" required>
            
<label for="vacant_job">Vacant Job:</label>
<input type="text" id="vacant_job" name="vacant_job" value="<?php echo $vacant_job; ?>" required>

<label for="profile_image">Profile Image:</label>
<input type="file" id="profile_image" name="profile_image" accept="image/*" required>


            <div class="btn-container">
                <input type="submit" value="Save">
                <input type="button" value="Cancel" onclick="window.location.href='explore-alumni.php'">
            </div>
        </form>
    </div>
</body>
</html>