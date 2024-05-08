<!DOCTYPE html>
<html lang="en">
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .input-row {
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
        }

        .input-group {
            flex: 1;
            margin-right: 10px;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }

        input[type="text"],
        input[type="email"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            resize: none;
        }

        .button {
            background-color: #4caf50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            align-items: center;
            text-align: center;
            display: block;
            margin: 0 auto;
            transition: background-color 0.3s;
        }

        .button:hover {
            background-color: #0080ff;
            animation: blink 1s infinite alternate;
        }

        @keyframes blink {
            from {
                opacity: 1;
            }
            to {
                opacity: 0.5;
            }
        }

        #submit-error {
            display: block;
            margin-top: 10px;
        }

        .dropdown {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: opacity 0.3s ease-in;
            opacity: 0;
            z-index: 999;
        }

        .show {
            opacity: 1;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Event Scheduling Form</h1>
        <form id="feedback-form" method="post" action="submit_event.php">
                    <div class="input-row">
                        <div class="input-group">
                            <label >Event Name: </label>
                            <input type="text" placeholder="Enter event name here" id="contact-name" onkeyup="validateName()" name="name">
                            <span id="name-error"></span>
                        </div>
                        <div class="input-group">
                            <label >Venue:</label> 
                            <input type="text" placeholder="Enter venue here" id="contact-phone"onkeyup="validatePhone()" name="phone">
                            <span id="phone-error"> </span>

                        </div>

                    </div>
                    <div class="input-row">
                        <div class="input-group">
                            <label >Date and Time:</label>
                            <input type="text" placeholder="Event date and time here" id="contact-email" onkeyup="validateEmail()" name="email">
                            <span id="email-error"></span>
                        </div>
                        <div class="input-group">
                            <label> Guests:</label> 
                            <input type="text" placeholder="Guest names here" name="subject">
                            <span id="subject-error"></span>
                        </div>

                    
                    </div>


                    <div class="input-group">
                    <label>About Event:</label>
                    <textarea rows="5"placeholder="Enter the informations about the event" id="contact-message" onkeyup="validateMessage()" name="feedback"></textarea>
                    <span id="message-error" style="position: absolute;
                    margin-left:-150px;margin-top: 70px;font-size: 10px;color: red;"></span>
                    </div>
                
                    <div class="g-recaptcha" data-sitekey="6LfF8agpAAAAAMf16xgMrD5GTEmyoQmAavasFFAr"></div>

                   
                  
                    <input class="button" type="submit" value="Submit" id="button" onclick="showSuccessDropdown();">
                    <span id="submit-error" style="color: red;"></span>
                </form>
    </div>
    <div id="success-dropdown" class="dropdown"></div>

    <script>
        function showSuccessDropdown() {
            var dropdown = document.getElementById('success-dropdown');
            dropdown.textContent = 'Submitted successfully';
            dropdown.classList.add('show');
            setTimeout(function() {
                dropdown.classList.remove('show');
            }, 3000); // Remove the "show" class after 3 seconds (3000 milliseconds)
        }
    </script>
     <script>
        // Function to handle form submission
        function submitForm(event) {
            event.preventDefault(); // Prevent default form submission
            var form = document.getElementById('feedback-form');
            var formData = new FormData(form);

            // Make an AJAX request to submit the form data
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        // On success, show success message
                        showSuccessDropdown();
                        form.reset(); // Optionally, reset the form fields
                    } else {
                        // On error, display error message
                        document.getElementById('submit-error').textContent = '';
                    }
                }
            };
            xhr.send(new URLSearchParams(formData)); // Send form data
        }

        // Function to show success message
        function showSuccessDropdown() {
            var dropdown = document.getElementById('success-dropdown');
            dropdown.textContent = 'Submitted successfully';
            dropdown.classList.add('show');
            setTimeout(function() {
                dropdown.classList.remove('show');
            }, 3000); // Remove the "show" class after 3 seconds
        }

        // Attach submitForm function to form submit event
        var form = document.getElementById('feedback-form');
        form.addEventListener('submit', submitForm);
    </script>
</body>
</html>
