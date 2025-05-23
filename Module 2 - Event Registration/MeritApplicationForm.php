<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Merit Application - MyPetakom</title>
  <link rel="stylesheet" href="Style/Merit.css">
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
    <h2>Merit Application</h2>
<!--Succesful Message-->
<?php
if (isset($_GET['message'])) {
    $msg = "";
    $color = "";

    if ($_GET['message'] == 'success') {
        $msg = "🎉 Merit application submitted successfully!";
        $color = "#d4edda";
    } elseif ($_GET['message'] == 'error') {
        $msg = "❌ Database error. Please try again.";
        $color = "#f8d7da";
    } elseif ($_GET['message'] == 'empty_fields') {
        $msg = "⚠️ Please fill in all required fields.";
        $color = "#fff3cd";
    } elseif ($_GET['message'] == 'invalid_access') {
        $msg = "⛔ Unauthorized access.";
        $color = "#e2e3e5";
    }

    if ($msg != "") {
        echo "<div id='msg-box' style='background-color: $color; padding: 10px; border-radius: 5px; margin-bottom: 10px;'>$msg</div>";
    }
}
?>


    <form action="check_Merit.php" method="POST">
  <label for="event-id">Event:</label>
  <select id="event-id" name="event_id" required>
    <option value="">--Select Event--</option>
    <?php
    // Connect to DB and fetch events
    $conn = new mysqli("localhost", "root", "", "mypetakom");
    $result = $conn->query("SELECT event_id, title FROM event");
    while($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['event_id'] . "'>" . $row['title'] . "</option>";
    }
    ?>
  </select>

  <label for="student-id">Student:</label>
  <select id="student-id" name="student_id" required>
    <option value="">--Select Student--</option>
    <?php
    $students = $conn->query("SELECT login_id, name FROM student");
    while($row = $students->fetch_assoc()) {
        echo "<option value='" . $row['login_id'] . "'>" . $row['name'] . "</option>";
    }
    ?>
  </select>

  <label for="role">Role:</label>
  <select id="role" name="role" required>
    <option value="committee">Committee</option>
    <option value="participant">Participant</option>
  </select>

  <label for="meritscore">Merit Level:</label>
  <select id="meritscore" name="meritscore_id" required>
    <option value="">--Select Merit Level--</option>
    <?php
    $merits = $conn->query("SELECT meritscore_id, merit_description FROM meritscore");
    while($row = $merits->fetch_assoc()) {
        echo "<option value='" . $row['meritscore_id'] . "'>" . $row['merit_description'] . "</option>";
    }
    ?>
  </select>

  <label for="semester">Semester:</label>
  <input type="text" id="semester" name="semester" placeholder="e.g. 2/2024" required>

  <button type="submit" name="submit">Submit Application</button>
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
<script>
  // Auto-hide the message box after 3 seconds
  setTimeout(() => {
    const msgBox = document.getElementById('msg-box');
    if (msgBox) {
      msgBox.style.display = 'none';
    }
  }, 3000);
</script>

</body>
</html>
