<?php
include 'check_committe.php';

$event_result = $conn->query("SELECT event_id, title FROM EVENT");
$student_result = $conn->query("SELECT login_id, student_id FROM STUDENT");

// Proses borang
if (isset($_POST['submit'])) {
    $event_id = $_POST['event_id'];
    $student_id = $_POST['student_id'];
    $role = $_POST['role'];

    // Semak jika sudah berdaftar
    $check = $conn->prepare("SELECT * FROM COMMITTEE WHERE event_id = ? AND student_id = ?");
    $check->bind_param("ii", $event_id, $student_id);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        $message = "<p style='color:red;'>❌ Pelajar ini sudah didaftarkan sebagai jawatankuasa untuk aktiviti ini.</p>";
    } else {
        // Masukkan ke dalam COMMITTEE
        $stmt = $conn->prepare("INSERT INTO COMMITTEE (event_id, student_id, position) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $event_id, $student_id, $role);

        if ($stmt->execute()) {
            $message = "<p style='color:green;'>✅ Jawatankuasa berjaya didaftarkan!</p>";
        } else {
            $message = "<p style='color:red;'>❌ Ralat: " . $stmt->error . "</p>";
        }
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

      <select name="event_id" onchange="check_committe()" required>
        <option value="">--Pilih Aktiviti--</option>
          <?php while ($row = $event_result->fetch_assoc()): ?>
            <option value="<?= $row['event_id'] ?>"><?= $row['title'] ?></option>
          <?php endwhile; ?>
      </select><br><br>

  <label for="student_id">Student:</label>
  <select name="student_id" onchange="checkCommittee()" required>
    <option value="">--Pilih Pelajar--</option>
    <?php while ($row = $student_result->fetch_assoc()): ?>
      <option value="<?= $row['login_id'] ?>"><?= $row['student_id'] ?></option>
    <?php endwhile; ?>
  </select><br><br>

  <label for="role">Role in Committee:</label>
  <input type="text" name="role" required><br><br>

  <button type="submit" name="submit">Assign Committee Member</button>
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