<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f7f7f7;
      overflow: hidden; /* Prevent scroll bars due to fixed position elements */
    }

    .dashboard {
      position: fixed;
      top: -100vh;
      left: 0;
      width: 100%;
      height: 100vh;
      background-image: linear-gradient(to right, rgba(255, 0, 150, 0.5), rgba(0, 255, 255, 0.5)), url('admin.jpg'); /* Gradient overlay */
      background-size: cover;
      background-position: center;
      transition: top 0.3s ease;
      z-index: 1000;
    }

    .dashboard.active {
      top: 0;
    }

    .dashboard-content {
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
      border-radius: 20px; /* Increased border radius */
      box-shadow: 0 0 30px rgba(0, 0, 0, 0.3); /* Increased drop shadow effect */
    }

    .dashboard-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px;
      background-color: rgba(51, 51, 51, 0.8); /* Semi-transparent dark background */
      color: #fff;
      border-top-left-radius: 20px; /* Increased border radius */
      border-top-right-radius: 20px; /* Increased border radius */
    }

    .dashboard-close {
      cursor: pointer;
    }

    .dashboard-close:hover {
      transform: rotate(90deg); /* Rotate close icon on hover */
    }

    .dashboard-menu {
      list-style: none;
      padding: 0;
      margin-top: 30px; /* Increased margin top */
      text-align: center; /* Center-align the menu */
    }

    .dashboard-menu li {
      margin-bottom: 20px; /* Increased margin bottom */
    }

    .dashboard-menu li button {
      background-color: #FF6347; /* Tomato red color */
      color: white;
      border: none;
      padding: 15px 30px; /* Increased padding */
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 18px; /* Increased font size */
      cursor: pointer;
      border-radius: 10px; /* Increased border radius */
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3); /* Box shadow effect */
      transition: transform 0.3s ease, box-shadow 0.3s ease; /* Smooth transition for transform and box-shadow */
    }

    .dashboard-menu li button:hover {
      transform: translateY(-5px); /* Move button up on hover */
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5); /* Increased box shadow on hover */
    }

    .quote {
      font-style: italic;
      color: #333;
      margin-top: 30px; /* Increased margin top */
      font-size: 20px; /* Increased font size */
      text-align: center; /* Center-align the quote */
    }

    .header {
      display: flex;
      align-items: center;
      padding: 10px 20px;
      color: #fff;
    }

    .user-avatar {
      width: 50px; /* Increased avatar size */
      height: 50px; /* Increased avatar size */
      border-radius: 50%;
      margin-right: 20px; /* Increased margin right */
    }

    .admin-panel-title {
      font-size: 24px; /* Increased font size */
      font-weight: bold;
    }

    /* Centered Button */
    .centered-button {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
    }

    /* Quote Box */
    .quote-container {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      text-align: center;
      font-size: 24px;
      color: #333;
      padding: 20px;
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 10px;
      box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
      animation: fadeOut 10s forwards;
      display: none;
      z-index: 999; /* Ensure it's above other elements */
    }

    @keyframes fadeOut {
      0% { opacity: 1; }
      90% { opacity: 1; }
      100% { opacity: 0; display: none; }
    }
  </style>
</head>
<body>

<div class="dashboard" id="dashboard">
  <div class="dashboard-content">
    <div class="dashboard-header">
      <div class="header">
        <img src="user.jpg" alt="User Avatar" class="user-avatar">
        <h2 class="admin-panel-title">Admin Panel</h2>
      </div>
      <span class="dashboard-close" onclick="closeDashboard()">✖</span>
    </div>
    <ul class="dashboard-menu">
      <li><button onclick="location.href='home.php'">Home Page</button></li>
      <li><button onclick="location.href='admin-approval.php'">Alumni Approval</button></li>
      <li><button onclick="location.href='admin-student-approval.php'">Student Approval</button></li>
      <li><button onclick="location.href='admin-role-change.php'">Role Change Page</button></li>
      <li><button onclick="location.href='gallery.php'">Gallery</button></li>
       <!-- Add Gallery Section Button -->
    </ul>
    <div class="quote">
      "With great power comes great responsibility." - Uncle Ben
    </div>
  </div>
</div>

<div class="centered-button" style="top: 50%;">
  <button onclick="openDashboard()">Dashboard</button>
</div>

<div class="quote-container" id="quoteContainer"></div>

<script>
  function openDashboard() {
    document.getElementById("dashboard").classList.add("active");
    document.querySelector(".centered-button").style.display = "none";
  }

  function closeDashboard() {
    document.getElementById("dashboard").classList.remove("active");
    document.querySelector(".centered-button").style.display = "block";
  }
</script>


</body>
</html>
