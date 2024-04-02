<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explore Alumni</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
        position: relative;
    }

    h1 {
        text-align: center;
        margin-top: 20px;
    }

    ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    li {
        background-color: #fff;
        padding: 15px;
        margin: 10px 0;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    li span {
        font-weight: bold;
        font-size: 18px;
    }

    button {
        padding: 8px 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        text-decoration: none;
    }

    button:hover {
        background-color: #0056b3;
    }

    /* Search button styles */
    #searchBtn {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 8px 15px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    #searchBtn:hover {
        background-color: #0056b3;
    }

    /* Search input styles */
    #searchInput {
        position: absolute;
        top: 20px;
        right: 100px; /* Adjusted right padding */
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
        display: none;
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
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .popup label {
        display: block;
        margin-bottom: 10px;
    }

    .popup input[type="text"],
    .popup input[type="file"] {
        width: 100%;
        padding: 4px;
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
    }

    button.edit-btn:hover {
        background-color: #218838;
    }

    /* Adjusting button spacing */
    ul li button {
        margin-left: 10px;
    }

    /* Link button style */
    button.back-btn a {
        color: #fff;
        text-decoration: none;
    }
</style>

</head>
<body>
    <h1>Explore Alumni</h1>

    <!-- Search button -->
    <button id="searchBtn" onclick="toggleSearch()">Search</button>

    <!-- Search input -->
    <input type="text" id="searchInput" placeholder="Search by name...">

    <ul>
        <?php
        // Include the connection.php file to establish a connection to the database
        include("connection.php");

        // Query to retrieve name, batch, and profile image from alumni_details table for alumni
        $query = "SELECT name, user_batch, user_email FROM alumni_details WHERE role = 'alumni' ORDER BY user_batch ASC";

        // Execute the query
        $result = mysqli_query($con, $query);

        // Check for errors
        if (!$result) {
            echo 'Error: ' . mysqli_error($con);
        } else {
            // If the query is successful, display the alumni users
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo '<li>';
                    echo '<span>Name: ' . $row["name"] . '</span>';
                    echo '<span>Batch: ' . $row["user_batch"] . '</span>';
                    echo '<button onclick="showPopup(this)">Edit</button>'; // Added edit button
                    echo '<form method="GET" action="show-details.php">'; // Modified action to show-details.php
                    echo '<input type="hidden" name="email" value="' . $row["user_email"] . '">';
                    echo '<input type="submit" value="Show Details">';
                    echo '</form>';
                    echo '</li>';
                }
            } else {
                echo 'No alumni found.';
            }
        }

        // Close the database connection
        mysqli_close($con);
        ?>
    </ul>

    <!-- Your HTML content -->
    <button><a href="alumnii.php" style="text-decoration: none;">Back to Alumni Details</a></button>

    <!-- Popup -->
    <div class="overlay" id="overlay">
        <div class="popup">
            <form id="confirmForm" onsubmit="return false;">
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" required readonly>
                <label for="private_key">Private Key:</label>
                <input type="text" id="private_key" name="private_key" required>
                <button onclick="checkCredentials()">Confirm</button>
            </form>
        </div>
    </div>

    <script>
      // JavaScript for other functionalities goes here
function toggleSearch() {
    var searchInput = document.getElementById('searchInput');
    if (searchInput.style.display === 'none') {
        searchInput.style.display = 'block';
    } else {
        searchInput.style.display = 'none';
    }
}

function showPopup(button) {
    var form = button.parentElement;
    var emailInput = form.querySelector('input[name="email"]');
    var email = emailInput.value;
    document.getElementById('overlay').style.display = 'flex';
    document.getElementById('email').value = email;
}

function checkCredentials() {
    var email = document.getElementById('email').value;
    var privateKey = document.getElementById('private_key').value;

    // AJAX request to check credentials against the database
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'check_credentials.php', true); // Update the URL here to match the correct PHP script
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function () {
        if (xhr.status == 200) {
            if (xhr.responseText.trim() === 'success') {
                // Redirect to update.php if credentials match
                window.location.href = 'update.php';
            } else {
                alert('Incorrect email or private key. Please try again.');
            }
        }
    };
    xhr.send('user_email=' + email + '&private_key=' + privateKey);
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
