<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Role Change</title>
    <style>
        /* CSS styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        h2 {
            color: #333;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        select,
        input[type="submit"],
        input[type="button"] {
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: white;
        }

        input[type="button"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Improved styling for the select all button */
        #selectAllButton {
            background-color: #008CBA;
            color: white;
        }

        #selectAllButton:hover {
            background-color: #005f79;
        }
    </style>
</head>
<body>
<h2>Select Batch to Change Role to Alumni</h2>
<form id="batchForm" method="post">
    <label for="batch">Select Batch:</label>
    <select name="batch" id="batch">
        <?php
        // Generate batch options starting from 2008 and every year up to 2024
        for ($year = 2008; $year <= 2024; $year++) {
            $endYear = $year + 4;
            echo "<option value='$year-$endYear'>$year-$endYear</option>";
        }
        ?>
    </select>
    <input type="submit" name="submit" value="Fetch Students">
</form>

<div id="actionBox"></div>

<script>
    document.getElementById("batchForm").addEventListener("submit", function(event) {
        event.preventDefault();
        var form = this;
        var formData = new FormData(form);
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "fetch_students.php", true);
        xhr.onload = function() {
            if (xhr.status === 200) {
                document.getElementById("actionBox").innerHTML = xhr.responseText;
                addChangeRoleButton(); // Add the change role button
            }
        };
        xhr.send(formData);
    });

    // Function to add the change role button
    function addChangeRoleButton() {
        // Remove any existing change role button
        var existingButton = document.getElementById("changeRoleButton");
        if (existingButton) {
            existingButton.parentNode.removeChild(existingButton);
        }

        // Create change role button
        var changeRoleButton = document.createElement("input");
        changeRoleButton.type = "button";
        changeRoleButton.value = "Change Role";
        changeRoleButton.name = "change_role";
        changeRoleButton.id = "changeRoleButton"; // Set unique ID

        // Append button to the action box
        var actionBox = document.getElementById("actionBox");
        actionBox.appendChild(changeRoleButton);

        // Add event listener to the button
        changeRoleButton.addEventListener("click", function() {
            var students = document.querySelectorAll("#actionBox input[name='students[]']:checked");
            if (students.length === 0) {
                alert("Please select at least one student.");
                return;
            }

            var studentIds = [];
            students.forEach(function(student) {
                studentIds.push(student.value);
            });

            // Send selected student IDs to the server to change their roles and send email
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // Sending to the same file
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function() {
                if (xhr.status === 200) {
                    alert("Role changed successfully and email sent.");
                    document.getElementById("batchForm").submit(); // Refresh student list
                }
            };
            xhr.send("students=" + JSON.stringify(studentIds));
        });
    }

    // Function to select all checkboxes in the action box
    
    function selectAll() {
        var selectAllCheckbox = document.getElementById("selectAllCheckbox");
        var studentCheckboxes = document.getElementsByName("students[]");
        for (var i = 0; i < studentCheckboxes.length; i++) {
            studentCheckboxes[i].checked = selectAllCheckbox.checked;
        }
    }


    // Add event listener to the select all button
    document.getElementById("selectAllButton").addEventListener("click", selectAll);

</script>

<!-- Select All button -->


</body>
</html>

<?php
include 'connection.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer classes
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

// Function to delete student details from the database and send email
function changeRoleAndDelete($con, $studentIds)
{
    $mail = new PHPMailer(true);

    // Server settings
    $mail->isSMTP(); // Send using SMTP
    $mail->Host       = 'smtp.gmail.com'; // Set the SMTP server to send through
    $mail->SMTPAuth   = true; // Enable SMTP authentication
    $mail->Username   = 'jeslinsanthosh2613@gmail.com'; // SMTP username
    $mail->Password   = 'enic hlma iuto qlgx'; // SMTP password
    $mail->SMTPSecure = 'ssl'; // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
    $mail->Port       = 465; // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

    // Email content
    $subject = "Role Change Request";
    $message = "Hello Alumnity user,\n\nYou are now requested to re-register as an alumni role on our Alumnity website.";

    foreach ($studentIds as $id) {
        // Fetch student email
        $query = "SELECT user_email FROM student_details WHERE id='$id'";
        $result = mysqli_query($con, $query);
        $data = mysqli_fetch_assoc($result);
        $email = $data['user_email'];

        // Send email to student
        $mail->setFrom('jeslinsanthosh2613@gmail.com', 'AlumnITy');
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->send();

        // Delete student details
        $deleteQuery = "DELETE FROM student_details WHERE id='$id'";
        mysqli_query($con, $deleteQuery);
    }
}

// Check if student IDs are provided
if (isset($_POST['students'])) {
    $studentIds = json_decode($_POST['students']);

    // Change role and delete student details
    changeRoleAndDelete($con, $studentIds);

    // Return success response
    echo json_encode(['success' => true]);
} 
?>
