<?php
session_start();
include 'check_QR.php';  // This file contains your DB connection in $conn

$username = $_SESSION['username'] ?? 'Guest';

$sql = "SELECT event_id, title, description, event_date FROM event ORDER BY event_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Event QR Codes - MyPetakom</title>
  <link rel="stylesheet" href="Style/QR.css" />
  <style>
    .container {
      padding: 20px;
    }
    .event-card {
      border: 1px solid #ddd;
      padding: 15px;
      margin-bottom: 20px;
      border-radius: 10px;
      background-color: #f9f9f9;
      position: relative;
    }
    .qr-code {
      float: right;
      margin-left: 20px;
    }
    .clear {
      clear: both;
    }
  </style>
</head>
<body>
<header class="navbar">
  <div class="logo">
    <img src="../Images/petakom logo1.png" alt="Petakom Logo" style="width: 100px;" />
  </div>
  <div class="logo">EVENT ADVISOR</div>
  <div class="profile-dropdown">
    <img src="../Images/eventadvisor.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()" />
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
  <h2>Event QR Codes</h2>
<?php if (isset($_GET['message']) && $_GET['message'] == 'deleted'): ?>
  <div id="success-message" style="padding: 15px; background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px; margin-bottom: 20px;">
    Event deleted successfully.
  </div>
<?php endif; ?>
  <?php
  if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $event_id = $row['event_id'];
      $title = htmlspecialchars($row['title']);
      $description = nl2br(htmlspecialchars($row['description']));
      $event_date = htmlspecialchars($row['event_date']);

      // Link to event info page, encoded in QR code
      $link = "http://localhost/mypetakom/event_info.php?event_id=" . $event_id;
      $qr_url = "https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=" . urlencode($link);
      ?>

      <div class="event-card">
        <div class="qr-code">
          <img src="<?php echo $qr_url; ?>" alt="QR Code for <?php echo $title; ?>" />
        </div>
        <h3><?php echo $title; ?></h3>
        <p><strong>Date:</strong> <?php echo $event_date; ?></p>
        <p><?php echo $description; ?></p>

        <div style="margin-top: 10px;">
          <a href="edit_event.php?event_id=<?php echo $event_id; ?>"
             style="display: inline-block; padding: 10px 20px; background-color: grey; color: white; text-decoration: none; border-radius: 5px; margin-right: 10px;">
             Edit Event
          </a>

          <a href="edit_committe.php?event_id=<?php echo $event_id; ?>"
   style="display: inline-block; padding: 10px 20px; background-color: green; color: white; text-decoration: none; border-radius: 5px;">
   Edit Committee
</a>


    <!------------------------------------------->
          <a href="delete_event.php?event_id=<?php echo $event_id; ?>" 
   onclick="return confirmDelete();" 
   style="display: inline-block; padding: 10px 20px; background-color: red; color: white; text-decoration: none; border-radius: 5px;">
   Delete
</a>

<script>
function confirmDelete() {
  return confirm('Are you sure you want to delete this event?');
}
</script>
        </div>
        <div class="clear"></div>
      </div>

      <?php
    }
  } else {
    echo "<p>No events available.</p>";
  }
  $conn->close();
  ?>
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
<script>
  // Hide success message after 3 seconds
  window.onload = function () {
    const message = document.getElementById('success-message');
    if (message) {
      setTimeout(() => {
        message.style.transition = 'opacity 0.5s ease';
        message.style.opacity = '0';
        setTimeout(() => message.remove(), 500); // Remove after fade out
      }, 3000);
    }
  };
</script>

</body>
</html>
