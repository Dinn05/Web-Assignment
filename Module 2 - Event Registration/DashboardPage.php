<?php
session_start();

if (
    isset($_SESSION['Login']) &&
    $_SESSION['Login'] === "YES" &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'event_advisor'
) {
    //$fullname = htmlspecialchars($_SESSION['fullname']);
    $username = htmlspecialchars($_SESSION['username']);
    $id = $_SESSION['id'];
} else {
    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='login.php'>login</a> as an event advisor to access this page.</p>";  
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard - Petakom</title>
    <link rel="stylesheet" href="Style/dashboard.css">

</head>
<body>

<header class="navbar">
    <div class="logo">
      <img src="../Images/petakom logo1.png" alt="Petakom Logo">
    </div>
    <div class="logo">EVENT ADVISOR</div>
    <div class="profile-dropdown">
        <img src="../Images/eventadvisor.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()">
        <div id="dropdown-content" class="dropdown-content">
            <p><strong><?php echo $username; ?></strong></p>
            <a href="#">Setting Profile</a>
            <a href="../Module 2 - Event Registration/logout_event_advisor.php">Logout</a>
        </div>
    </div>
</header>

<script>
function toggleDropdown() {
    document.getElementById("dropdown-content").classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.profile-icon')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (var i = 0; i < dropdowns.length; i++) {
            var openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
</script>

  <!-- Sidebar -->
  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="DashboardPage.php">Dashboard</a>
    <a href="EventRegistrationForm.php">Event Registration</a>
    <a href="CommitteRegistrationForm.php">Committee</a>
    <a href="MeritApplicationForm.php">Merit</a>
    <a href="QRCodeEventPage.php">QR Code</a>
  </div>

  <!-- Main Content -->
  <div class="main">
    <!-- Top logo and menu -->
    <button class="openbtn" onclick="openNav()">☰ Menu</button>
    

    <h1 style="text-align: center;">Welcome, <?php echo $username?></h1>
    <div class="button-group" style="text-align: center;">
      <button type="button">Upcoming</button>
      <button type="button">Oncoming</button>
      <button type="button">All Events</button>
    </div>

    <h2 style="text-align: center;">Event Details</h2>
    <table>
      <thead>
        <tr>
          <th>Event Name</th>
          <th>Date</th>
          <th>Location</th>
          <th>Event Advisor</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Tech Conference 2025</td>
          <td>2025-06-01</td>
          <td>UMPSA Main Hall</td>
          <td>John Doe</td>
          <td>
            <button class="edit-btn" aria-label="Edit Tech Conference 2025">Edit</button>
            <button class="delete-btn" aria-label="Delete Tech Conference 2025">Delete</button>
          </td>
        </tr>
        <tr>
          <td>Sports Day</td>
          <td>2025-07-15</td>
          <td>UMPSA Sports Ground</td>
          <td>Jane Smith</td>
          <td>
            <button class="edit-btn" aria-label="Edit Sports Day">Edit</button>
            <button class="delete-btn" aria-label="Delete Sports Day">Delete</button>
          </td>
        </tr>
        <tr>
          <td>Charity Fundraiser</td>
          <td>2025-09-10</td>
          <td>UMPSA Auditorium</td>
          <td>Michael Lee</td>
          <td>
            <button class="edit-btn" aria-label="Edit Charity Fundraiser">Edit</button>
            <button class="delete-btn" aria-label="Delete Charity Fundraiser">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>

  <!-- JavaScript for Sidenav Push -->
  <script>
    function openNav() {
      document.getElementById("mySidenav").style.width = "250px";
      document.getElementById("main").style.marginLeft = "250px";
    }

    function closeNav() {
      document.getElementById("mySidenav").style.width = "0";
      document.getElementById("main").style.marginLeft = "0";
    }
  </script>

</body>
</html>
