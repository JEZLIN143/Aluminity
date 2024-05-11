<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Student Details</title>
   <style>
    /* styles.css */

/* General styles */
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f7f7f7;
    margin: 0;
    padding: 0;
    color: #333; /* Text color */
}

h1 {
    text-align: center;
    margin-top: 20px;
    color: #007bff; /* Heading color */
}

ul {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

li {
    background-color: #fff;
    padding: 20px;
    margin: 20px 0;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    display: flex;
    flex-direction: column; /* Align contents in a column */
    position: relative; /* Added position */
    transition: transform 0.3s ease;
}

li:hover {
    transform: translateY(-5px);
}

li span {
    font-weight: bold;
    font-size: 18px;
    margin-bottom: 10px; /* Add margin for spacing */
}

/* Back to Alumni button style */
button.back-btn {
    padding: 10px 20px;
    background-color: #6c757d;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    position: absolute;
    left: 20px;
    top: 20px; /* Adjusted position */
    transition: background-color 0.3s ease;
}

button.back-btn:hover {
    background-color: #5a6268;
}

/* Search button styles */
#searchBtn {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    position: absolute;
    top: 20px;
    right: 20px;
    transition: background-color 0.3s ease;
}

#searchBtn:hover {
    background-color: #0056b3;
}

/* Search input styles */
#searchInput {
    position: absolute;
    top: 20px;
    right: 90px; /* Adjusted right padding */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    display: none;
    z-index: 1; /* Ensure the search input is above other elements */
    transition: width 0.3s ease;
}

/* Popup */
.overlay {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: none;
    justify-content: center;
    align-items: center;
}

.popup {
    background-color: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.popup label {
    display: block;
    margin-bottom: 10px;
}

.popup input[type="text"],
.popup input[type="file"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.popup button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.popup button:hover {
    background-color: #0056b3;
}

/* Animation */
@keyframes fadeIn {
    0% { opacity: 0; }
    100% { opacity: 1; }
}

@keyframes slideIn {
    0% { transform: translateY(-100px); }
    100% { transform: translateY(0); }
}

.popup {
    animation: slideIn 0.5s ease forwards;
}

.overlay {
    animation: fadeIn 0.5s ease forwards;
}

/* Custom styles */
button.edit-btn {
    background-color: #28a745;
    padding: 10px 20px;
    margin-left: auto; /* Align to the right */
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button.edit-btn:hover {
    background-color: #218838;
}

/* Adjusting button spacing */
ul li button {
    margin-left: 10px;
}

/* Adding some animation */
@keyframes scaleIn {
    from {
        transform: scale(0);
    }
    to {
        transform: scale(1);
    }
}

li {
    animation: scaleIn 0.3s ease-out;
}

   </style>

</head>
<body>
<h1>Explore Student Details</h1>

<!-- Back to Alumni button -->
<button class="back-btn"><a href="students.php" style="text-decoration: none;">Back to Alumni</a></button>

<!-- Search button -->
<button id="searchBtn" onclick="toggleSearch()">Search</button>

<!-- Search input -->
<input type="text" id="searchInput" placeholder="Search by name...">

<ul>
    <?php
    // Include the connection.php file to establish a connection to the database
    include("connection.php");

    // Query to retrieve name, batch, and profile image from student_details table for students
    $query = "SELECT id, name, user_batch, user_email, phone_no FROM student_details ORDER BY user_batch ASC";

    // Execute the query
    $result = mysqli_query($con, $query);

    // Check for errors
    if (!$result) {
        echo 'Error: ' . mysqli_error($con);
    } else {
        // If the query is successful, display the student details
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<li>';
                echo '<span>Name: ' . $row["name"] . '</span>';
                echo '<span>Batch: ' . $row["user_batch"] . '</span>';
                echo '<span>Email: ' . $row["user_email"] . '</span>';
                echo '<span>Phone: ' . $row["phone_no"] . '</span>';
                echo '</li>';
            }
        } else {
            echo 'No student details found.';
        }
    }

    // Close the database connection
    mysqli_close($con);
    ?>
</ul>

<!-- Popup -->
<div class="overlay" id="overlay">
    <div class="popup">
        <form id="editForm" onsubmit="return false;">
            <label for="email">Email:</label>
            <input type="text" id="email" name="email" required readonly>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>
            <label for="batch">Batch:</label>
            <input type="text" id="batch" name="batch" required>
            <label for="phone">Phone:</label>
            <input type="text" id="phone" name="phone" required>
            <button onclick="saveChanges()">Save</button>
            <button class="back-btn" onclick="closePopup()">Cancel</button> <!-- Moved back button -->
        </form>
    </div>
</div>

<script>
    // JavaScript code
    function toggleSearch() {
        var searchInput = document.getElementById('searchInput');
        if (searchInput.style.display === 'none') {
            searchInput.style.display = 'block';
        } else {
            searchInput.style.display = 'none';
        }
    }

    function editStudent(button) {
        var form = button.parentElement;
        var emailInput = form.querySelector('input[name="email"]');
        var nameInput = form.querySelector('input[name="name"]');
        var batchInput = form.querySelector('input[name="batch"]');
        var phoneInput = form.querySelector('input[name="phone"]');
        var email = emailInput.value;
        var name = nameInput.value;
        var batch = batchInput.value;
        var phone = phoneInput.value;
        document.getElementById('overlay').style.display = 'flex';
        document.getElementById('email').value = email;
        document.getElementById('name').value = name;
        document.getElementById('batch').value = batch;
        document.getElementById('phone').value = phone;
    }

    function saveChanges() {
        var email = document.getElementById('email').value;
        var name = document.getElementById('name').value;
        var batch = document.getElementById('batch').value;
        var phone = document.getElementById('phone').value;

        // AJAX request to save changes in student_details table
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'save_changes.php', true); // Update the URL here to match the correct PHP script
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function () {
            if (xhr.status == 200) {
                if (xhr.responseText.trim() === 'success') {
                    alert('Changes saved successfully.');
                    window.location.reload(); // Reload the page to reflect changes
                } else {
                    alert('Failed to save changes. Please try again.');
                }
            }
        };
        xhr.send('email=' + email + '&name=' + name + '&batch=' + batch + '&phone=' + phone);
    }

    function closePopup() {
        document.getElementById('overlay').style.display = 'none'; // Close the popup
    }

    document.addEventListener("DOMContentLoaded", function () {
        var searchInput = document.getElementById("searchInput");
        searchInput.addEventListener("input", function () {
            var searchValue = this.value.toLowerCase();
            var lis = document.querySelectorAll("li");
            lis.forEach(function (li) {
                var name = li.querySelector("span:first-child").innerText.toLowerCase();
                if (searchValue && name.includes(searchValue)) {
                    li.style.backgroundColor = "#ffff99"; // Highlight with yellow color
                } else {
                    li.style.backgroundColor = "#fff"; // Reset background color
                }
            });
        });
    });

</script>
</body>
</html>
