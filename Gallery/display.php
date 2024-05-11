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

// Fetch data from database
$sql = "SELECT * FROM picture";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photo Gallery</title>
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
        .gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 20px;
            max-width: 1200px; /* Added max-width for responsiveness */
            margin: 0 auto; /* Center the gallery */
        }
        .photo {
            margin: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            transition: transform 0.3s ease-in-out;
            flex: 0 0 calc(33.33% - 20px); /* Three columns (3 in a row) */
            max-width: calc(33.33% - 20px); /* Adjust for spacing */
            box-sizing: border-box; /* Include padding and border in width */
        }
        .photo:hover {
            transform: scale(1.05);
        }
        .photo img {
            width: 100%;
            height: auto;
            display: block;
        }
        .photo p {
            text-align: center;
            padding: 10px 0;
            background-color: #fff;
            margin: 0;
        }
        .order-select {
            text-align: center;
            margin-bottom: 20px;
        }
        .expanded {
            width: 100%;
            height: auto;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: auto;
            z-index: 9999;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.7);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 800px;
            border-radius: 16px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

    </style>
</head>
<body>
    <h2>Photo Gallery</h2>
    
    <div class="order-select">
        <form method="get" action="">
            <label for="order">Order by Date:</label>
            <select name="order" id="order">
                <option value="ASC">Ascending</option>
                <option value="DESC">Descending</option>
            </select>
            <input type="submit" value="Apply">
        </form>
    </div>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <img id="expandedImg" src="" style="width:100%">
            <div id="caption"></div>
        </div>
    </div>
    
    <div class="gallery">
    <?php
    // Check if there are any photos
    if ($result->num_rows > 0) {
        // Fetch order selection from GET parameter or set default
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        
        // Construct SQL query with the selected order
        $sql = "SELECT * FROM picture ORDER BY photo_id $order";
        
        // Execute the query
        $result = $conn->query($sql);
        
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="photo">
                <img class="expandable" src="uploads/<?php echo $row['photo_data']; ?>" alt="<?php echo $row['description']; ?>">
                <p><?php echo $row['description']; ?></p>
                <p>Date of Event: <?php echo $row['date']; ?></p> <!-- Display the date -->
            </div>
            <?php
        }
    } else {
        echo "<p>No photos found</p>";
    }
    ?>
</div>
<script>
        // Get all images with the class "expandable"
        var expandableImages = document.querySelectorAll('.expandable');

        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the image and insert it inside the modal
        var modalImg = document.getElementById("expandedImg");
        var captionText = document.getElementById("caption");

        // Loop through each image
        expandableImages.forEach(function(image) {
            // Add a click event listener to each image
            image.addEventListener('click', function() {
                // Show the modal
                modal.style.display = "block";
                document.body.classList.add('blur'); // Apply blur effect
                modalImg.src = this.src;
                captionText.innerHTML = this.alt;
            });
        });

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
            document.body.classList.remove('blur'); // Remove blur effect
        };
    </script>

    
</body>
</html>

<?php
$conn->close();
?>

