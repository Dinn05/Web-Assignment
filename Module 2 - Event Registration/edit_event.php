<?php
session_start();

//Connect to Database
$conn = new mysqli("localhost", "root", "", "mypetakom");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//Get username from user, default is Guest
$username = $_SESSION['username'] ?? 'Guest';

if (!isset($_GET['event_id'])) {
    die("No event ID provided.");
}

//Check event_id from database
$event_id = intval($_GET['event_id']);
$stmt = $conn->prepare("SELECT title, description, event_date FROM event WHERE event_id = ?");

//Retrieves semua data ni untuk display
$stmt->bind_param("i", $event_id);
$stmt->execute();
$stmt->bind_result($title, $description, $event_date);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Event - MyPetakom</title>
  <link rel="stylesheet" href="Style/QR.css">
  <style>
    body {
      margin: 0;
      
      background-color: #f2f2f2;
    }
    .container {
      padding: 50px;
      max-width: 600px;
      margin: auto;
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
    }
    form input, form textarea {
      width: 100%;
      padding: 10px;
      margin-top: 10px;
      margin-bottom: 20px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    form button {
      background-color: grey;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
    }
    h2 {
      text-align: center;
    }
  </style>
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
      <p><strong><?php echo htmlspecialchars($username); ?></strong></p>
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
  <h2>Edit Event</h2>
  <form action="update_event.php" method="post">
    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

    <label>Title:</label>
    <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>" required>

    <label>Description:</label>
    <textarea name="description" rows="4" required><?php echo htmlspecialchars($description); ?></textarea>

    <label>Date:</label>
    <input type="date" name="event_date" value="<?php echo $event_date; ?>" required>

    <button type="submit">Update</button>
  </form>
</div>

<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
}
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
function toggleDropdown() {
  var dropdown = document.getElementById("dropdown-content");
  dropdown.style.display = dropdown.style.display === "block" ? "none" : "block";
}
</script>

</body>
</html>
