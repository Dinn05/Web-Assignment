<?php
session_start();

$conn = new mysqli("localhost", "root", "", "mypetakom");

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Fetch events for dropdown
$event_result = $conn->query("SELECT event_id, title FROM EVENT");

// Fetch students for dropdown - make sure to select student_id and name
$student_result = $conn->query("SELECT student_id, name FROM STUDENT");

// Process form submission
if (isset($_POST['submit'])) {
    $event_id = $_POST['event_id'];
    $student_id = $_POST['student_id'];
    $role = $_POST['role'];

    // Check if student already registered for this event committee
    $check = $conn->prepare("SELECT * FROM COMMITTEE WHERE event_id = ? AND student_id = ?");
    $check->bind_param("ii", $event_id, $student_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = "❌ Pelajar ini sudah didaftarkan.";
    } else {
        // Insert new committee member
        $stmt = $conn->prepare("INSERT INTO COMMITTEE (event_id, student_id, position) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $event_id, $student_id, $role);

        if ($stmt->execute()) {
            $_SESSION['success_message'] = "✅ Jawatankuasa berjaya didaftarkan!";
            header("Location: CommitteRegistrationForm.php");
            exit();
        } else {
            $_SESSION['error_message'] = "❌ Ralat: " . $stmt->error;
        }
    }

    // Redirect back to form to prevent resubmission
    header("Location: CommitteRegistrationForm.php");
    exit();
}

// Show messages
if (isset($_SESSION['success_message'])) {
    $message = "<p style='color:green;'>" . $_SESSION['success_message'] . "</p>";
    unset($_SESSION['success_message']);
} elseif (isset($_SESSION['error_message'])) {
    $message = "<p style='color:red;'>" . $_SESSION['error_message'] . "</p>";
    unset($_SESSION['error_message']);
} else {
    $message = "";
}

if (isset($_GET['message'])) {
    if ($_GET['message'] === 'updated') {
        echo "<p style='color:green;'>Committee member updated successfully.</p>";
    } elseif ($_GET['message'] === 'deleted') {
        echo "<p style='color:red;'>Committee member deleted successfully.</p>";
    }
}
?>


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
    <!--Success Message---------------------->
    <div id="success-alert" style="text-align: center">
        <?php echo $message; ?>
    </div>
    <form action="check_committe.php" method="POST">

    <label for="event_id">Event:</label>
    <select name="event_id" id="event_id" required>
        <option value="" disabled selected>Sila pilih acara</option>
        <?php while ($event = $event_result->fetch_assoc()): ?>
            <option value="<?= $event['event_id'] ?>">
                <?= htmlspecialchars($event['title']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <label for="student_id">Student:</label>
    <select name="student_id" id="student_id" required>
        <option value="" disabled selected>Sila pilih pelajar</option>
        <?php while ($student = $student_result->fetch_assoc()): ?>
            <option value="<?= $student['student_id'] ?>">
                <?= htmlspecialchars($student['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>
    <br><br>

    <label for="role">Role:</label>
    <input type="text" name="role" id="role" required>
    <br><br>

    <button type="submit" name="submit">Daftar</button>
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
function checkCommittee() {
  const eventId = document.querySelector('[name="event_id"]').value;
  const studentId = document.querySelector('[name="student_id"]').value;

  if (eventId && studentId) {
    const formData = new FormData();
    formData.append('event_id', eventId);
    formData.append('student_id', studentId);

    fetch('check_committe.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.text())
    .then(data => {
      const statusMsg = document.getElementById('statusMsg');
      if (data === 'exists') {
        statusMsg.innerHTML = "<span style='color:red;'>Pelajar ini sudah berdaftar untuk aktiviti ini.</span>";
      } else {
        statusMsg.innerHTML = "<span style='color:green;'>Pelajar boleh didaftarkan.</span>";
      }
    });
  }
}
  setTimeout(function () {
    var alert = document.getElementById("success-alert");
    if (alert) {
      alert.style.display = "none";
    }
  }, 3000); // 5000 milisaat = 5 saat
</script>

</body>
</html>