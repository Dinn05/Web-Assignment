<?php
session_start();

if (
    isset($_SESSION['Login']) &&
    $_SESSION['Login'] === "YES" &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'event_advisor'
) {
    $username = htmlspecialchars($_SESSION['username']);

    // ✅ Connect to database
    $link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed");

    // ✅ Get login_id using username
    $loginQuery = "SELECT login_id FROM login WHERE username = '$username'";
    $loginResult = mysqli_query($link, $loginQuery);
    if ($loginRow = mysqli_fetch_assoc($loginResult)) {
        $login_id = $loginRow['login_id'];

        // ✅ Get staff_id using login_id
        $staffQuery = "SELECT staff_id FROM staff WHERE login_id = '$login_id'";
        $staffResult = mysqli_query($link, $staffQuery);
        if ($staffRow = mysqli_fetch_assoc($staffResult)) {
            $_SESSION['staff_id'] = $staffRow['staff_id'];
        } else {
            die("Staff ID not found.");
        }
    } else {
        die("Login ID not found.");
    }

} else {
    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='../Module 1 - Login/login.php'>login</a> as an event advisor to access this page.</p>";
    exit();
}

// Determine filter type
$filter = $_GET['filter'] ?? 'all';
$today = date('d-m-Y');

switch ($filter) {
    case 'upcoming':
        $eventQuery = "SELECT * FROM event WHERE event_date > '$today'";
        break;
    case 'ongoing':
        $eventQuery = "SELECT * FROM event WHERE event_date = '$today'";
        break;
    case 'past':
        $eventQuery = "SELECT * FROM event WHERE event_date < '$today'";
        break;
    default:
        $eventQuery = "SELECT * FROM event";
}
$eventResult = mysqli_query($link, $eventQuery);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Dashboard Event Advisor</title>
  <link rel="stylesheet" href="Style/dashboard.css">
</head>
<body class="sb-nav-fixed" style="display:none;" id="page-body">

<header class="navbar">
  <div class="logo"><img src="../Images/petakom logo1.png" alt="Petakom Logo"></div>
  <div class="logo">EVENT ADVISOR</div>
  <div class="profile-dropdown">
      <img src="../Images/eventadvisor.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()">
      <div id="dropdown-content" class="dropdown-content">
          <a href="../Module 2 - Event Registration/view_advisor_page.php">Setting Profile</a>
          <a href="../Module 1 - Login/logout.php">Logout</a>
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
            if (dropdowns[i].classList.contains('show')) {
                dropdowns[i].classList.remove('show');
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
  <button class="openbtn" onclick="openNav()">☰ Menu</button>

  <h1 style="text-align: center;">Welcome, <?php echo ucfirst($username); ?></h1>

<!---->
  <?php
$currentFilter = $_GET['filter'] ?? 'all';
function isActive($value, $current) {
    return $value === $current ? 'active-button' : '';
}
?>
<div class="button-group" style="text-align: center; margin-bottom: 20px;">
  <a href="DashboardPage.php?filter=upcoming">
    <button class="<?php echo isActive('upcoming', $currentFilter); ?>">Upcoming</button>
  </a>
  <a href="DashboardPage.php?filter=ongoing">
    <button class="<?php echo isActive('ongoing', $currentFilter); ?>">Ongoing</button>
  </a>
  <a href="DashboardPage.php?filter=past">
    <button class="<?php echo isActive('past', $currentFilter); ?>">Past</button>
  </a>
  <a href="DashboardPage.php">
    <button class="<?php echo isActive('all', $currentFilter); ?>">All Events</button>
  </a>
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
      <?php
      if (mysqli_num_rows($eventResult) > 0) {
          while ($event = mysqli_fetch_assoc($eventResult)) {
              echo "<tr>";
              echo "<td>" . htmlspecialchars($event['title']) . "</td>";
              echo "<td>" . htmlspecialchars($event['event_date']) . "</td>";
              echo "<td>" . htmlspecialchars($event['location']) . "</td>";
              echo "<td>" . htmlspecialchars($username) . "</td>";
              echo "<td>
                      <a href='edit_event.php?event_id=" . $event['event_id'] . "'><button class='edit-btn'>Edit</button></a>
                      <a href='delete_event.php?event_id=" . $event['event_id'] . "' onclick=\"return confirm('Are you sure you want to delete this event?');\"><button class='delete-btn'>Delete</button></a>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='5'>No events found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
  document.querySelector(".main").style.marginLeft = "250px";
}
function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.querySelector(".main").style.marginLeft = "0";
}

// Prevent showing the dashboard on back button with invalid session
window.addEventListener('pageshow', function(event) {
    if (event.persisted || performance.navigation.type === 2) {
        document.getElementById("page-body").style.display = "none";
        sessionStorage.setItem("sessionExpired", "true");
        window.location.href = "../Module 1 - Login/login.php";
    }
});
window.onload = function () {
    document.getElementById("page-body").style.display = "block";
};
</script>

</body>
</html>
