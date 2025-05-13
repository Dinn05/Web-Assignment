<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Committee Registration - MyPetakom</title>
  <link rel="stylesheet" href="Style/committe.css">
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
    <h2>Assign Student to Event Committee</h2>
    <form action="#" method="POST">
      <label for="event">Event:</label>
      <input type="text" id="event" name="event" required>

      <label for="student-id">Student ID:</label>
      <input type="text" id="student-id" name="student-id" required>

      <label for="role">Role in Committee:</label>
      <input type="text" id="role" name="role" required>

      <button type="submit">Assign Committee Member</button>
    </form>
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
