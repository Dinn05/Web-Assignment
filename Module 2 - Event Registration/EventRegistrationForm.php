<?php
session_start();
$successMessage = "";

if (isset($_SESSION['success_message'])) {
    $successMessage = $_SESSION['success_message'];
    unset($_SESSION['success_message']); // buang selepas paparkan
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Registration - Petakom</title>
  <link rel="stylesheet" href="Style/Event.css">
</head>
<body>
<header class="navbar">
    <div class="logo">
      <img src="../Images/petakom logo1.png" alt="Petakom Logo" style="width: 100px;">
    </div>
    <div class="logo">EVENT ADVISOR</div>
    <div class="profile-dropdown">
        <img src="../Images/eventadvisor.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()">
        <div id="dropdown-content" class="dropdown-content">
            <p><strong><?php echo $username; ?></strong></p>
            <a href="#">Setting Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

 <!-- Sidebar -->
  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="DashboardPage.php">Dashboard</a>
    <a href="EventRegistrationForm.php">Event Registration</a>
    <a href="CommitteRegistrationForm.php">Committee</a>
    <a href="MeritApplicationForm.php">Merit</a>
    <a href="QRCodeEventPage.php">QR Code</a>
  </div>

    <button class="openbtn" onclick="openNav()">☰ Menu</button>
  <div class="container">
    
    <div class="container">

      <h1 style="text-align: center">Event Registration Form</h1>
       
        <?php if (!empty($successMessage)) : ?>
          <div id="success-alert" style="padding: 10px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; margin-bottom: 20px;">
          <?php echo $successMessage; ?>
          </div>
        <?php endif; ?>

      

       <form action="check_event.php" method="post" enctype="multipart/form-data">
        
        <label for="title">Event Title:</label>
        <input type="text" id="title" name="title" required>
        

        <label for="description">Event Description:</label>
        <textarea id="description" name="description" rows="4" cols="50" required></textarea>

        <label for="location">Location:</label>
        <input type="text" id="location" name="location" required>

        <label for="event_date">Event Date:</label>
        <input type="date" id="event_date" name="event_date" required>

        <label for="approval_letter">Approval Letter (PDF):</label>
        <input type="file" id="approval_letter" name="approval_letter" accept="application/pdf" required>

        <label for="event_advisor_id">Event Advisor ID:</label>
        <input type="text" id="event_advisor_id" name="event_advisor_id" required>

       
        <button type="submit" name="submit" value="Register Event">Register</button>
        
    </div>
    </form>
    </div>
  </div>
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

<script>
  // Sembunyikan mesej selepas 5 saat
  setTimeout(function () {
    var alert = document.getElementById("success-alert");
    if (alert) {
      alert.style.display = "none";
    }
  }, 3000); // 5000 milisaat = 5 saat
</script>
</body>
</html>
