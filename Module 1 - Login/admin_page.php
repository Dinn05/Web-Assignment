<?php
session_start();
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

if (
    isset($_SESSION['Login']) &&
    $_SESSION['Login'] === "YES" &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'administrator'
) {
    $username = htmlspecialchars($_SESSION['username']);
} else {
    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='../Module 1 - Login/login.php'>login</a> as an administrator to access this page.</p>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Administrator Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../Module 3 - Event Attendance/Style/admin_dashboard.css">
    <link rel="stylesheet" href="../Module 1 - Login/Style/admin_page.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <img src="../Images/petakom logo1.png" alt="Logo">
    <ul>
        <li onclick="location.href='../Module 1 - Login/admin_page.php'"><i class="fas fa-home"></i><span> Dashboard</span></li>
        <li onclick="location.href='#'"><i class="fas fa-calendar-alt"></i><span> Events</span></li>
        <li onclick="location.href='../Module 1 - Login/view_student_registered.php'"><i class="fas fa-users"></i><span> Student List</span></li>
        <li onclick="location.href='../Module 1 - Login/view_applied_membership.php'"><i class="fa fa-book"></i><span> Verify Student Membership</span></li>
        <li onclick="location.href='../Module 1 - Login/view_event_advisor_registered.php'"><i class="fas fa-chalkboard-teacher"></i><span> Advisor List</span></li>
        <li onclick="location.href='../Module 1 - Login/logout.php'"><i class="fas fa-sign-out-alt"></i><span> Logout</span></li>
    </ul>
</div>

<!-- Topbar -->
<div class="main-content" id="mainContent">
    <div class="dashboard-topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <h5>Administrator Dashboard</h5>
        <div class="position-relative">
            <img src="../Images/eventadvisor.png" class="profile-icon" onclick="toggleDropdown()" alt="profile">
            <div id="dropdown-content" class="dropdown-content p-3">
                <a class="d-block" href="../Module 1 - Login/view_admin_profile.php" style="white-space: nowrap;">Setting Profile</a>
                <a href="../Module 1 - Login/logout.php" class="d-block text-danger">Logout</a>
            </div>
        </div>
    </div>
    <br>
    <h2>Welcome, <?php echo ucfirst($username); ?>!</h2>

    <div class="dashboard-container">
        <h1>📊 PETAKOM Attendance Dashboard</h1>

        <!-- A. Overview Cards -->
        <div class="overview-cards">
            <div class="card">
                <h2>Total Events</h2>
                <p>12</p>
            </div>
            <div class="card">
                <h2>Total Attendance Slots</h2>
                <p>325</p>
            </div>
            <div class="card">
                <h2>Total Student Check-ins</h2>
                <p>278</p>
            </div>
            <div class="card">
                <h2>Top Event</h2>
                <p>Career Fair 2025</p>
            </div>
        </div>

        <!-- B. Charts -->
        <div class="charts-section">
            <div class="chart-card">
                <h3>📊 Event vs Number of Attendees</h3>
                <canvas id="barChart"></canvas>
            </div>

            <div class="chart-card">
                <h3>🟢 Attendance by Category</h3>
                <canvas id="pieChart"></canvas>
            </div>

            <div class="chart-card">
                <h3>📈 Attendance Trends</h3>
                <canvas id="lineChart"></canvas>
            </div>
        </div>

        <!-- C. Filters -->
        <div class="filters-section">
            <h3>🔍 Filter Records</h3>
            <form>
                <label for="date-range">Date Range:</label>
                <input type="date" id="start-date">
                <input type="date" id="end-date">

                <label for="event">Event:</label>
                <input type="text" id="event" placeholder="Event name">

                <label for="student">Student ID:</label>
                <input type="text" id="student" placeholder="Student ID">

                <button type="submit">Apply Filter</button>
            </form>
        </div>

        <!-- D. Export -->
        <div class="export-section">
            <h3>📁 Export Report</h3>
            <button>Download CSV</button>
            <button>Download PDF</button>
        </div>
    </div>

    <!-- Dummy Chart.js Script -->
    <script>
        const ctx1 = document.getElementById('barChart').getContext('2d');
        const barChart = new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Career Fair', 'Tech Talk', 'Hackathon', 'Workshop'],
                datasets: [{
                    label: 'No. of Attendees',
                    data: [120, 85, 70, 50],
                    backgroundColor: '#4caf50'
                }]
            }
        });

        const ctx2 = document.getElementById('pieChart').getContext('2d');
        const pieChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Career', 'Academic', 'Technical', 'Social'],
                datasets: [{
                    label: 'Attendance %',
                    data: [35, 25, 20, 20],
                    backgroundColor: ['#2196f3', '#ff9800', '#4caf50', '#e91e63']
                }]
            }
        });

        const ctx3 = document.getElementById('lineChart').getContext('2d');
        const lineChart = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May'],
                datasets: [{
                    label: 'Attendance Over Time',
                    data: [20, 40, 60, 80, 70],
                    backgroundColor: '#4caf50',
                    borderColor: '#4caf50',
                    fill: false,
                    tension: 0.3
                }]
            }
        });
    </script>

    <footer>
        &copy; 2025 MyPetakom Portal<br>
        <a href="#">Privacy Policy</a> · <a href="#">Terms & Conditions</a>
    </footer>
</div>

<script>
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const content = document.getElementById("mainContent");

    sidebar.classList.toggle("collapsed");
    content.classList.toggle("collapsed");
}

function toggleDropdown() {
    document.getElementById("dropdown-content").classList.toggle("show");
}

window.onclick = function(e) {
    if (!e.target.matches('.profile-icon')) {
        var dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            dropdowns[i].classList.remove('show');
        }
    }
};
</script>

</body>
</html>
