<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - PETAKOM Attendance</title>
    <link rel="stylesheet" href="Style/admin_dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<!-- Header with Logo and Profile Dropdown -->
    <header class="navbar">
        <div class="logo-container">
            <img src="../Images/petakom logo1.png" alt="Petakom Logo" class="logo-img">
            <div class="logo-text">ADMINISTRATOR DASHBOARD</div>
        </div>
        <div class="profile-dropdown">
            <img src="../Images/administrator.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()">
		<div id="dropdown-content" class="dropdown-content">
                <p><strong>Administrator</strong></p>
                <a href="logout.php">Logout</a>
            </div>
        </div>
    </header>

    <!-- Sidebar Navigation -->
    <div id="mySidenav" class="sidenav">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <a href="admin_dashboard.php">Administrator Dashboard</a>
        <a href="attendance_registration.php">Attendance Registration</a>
        <a href="event_attendance.php">Event Attendance</a>
    </div>

    <!-- Menu Button -->
    <button class="openbtn" onclick="openNav()">☰ Menu</button>

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

<script>
        // Sidebar Navigation
        function openNav() {
            document.getElementById("mySidenav").style.width = "250px";
            document.querySelector(".main-content").style.marginLeft = "250px";
        }

        function closeNav() {
            document.getElementById("mySidenav").style.width = "0";
            document.querySelector(".main-content").style.marginLeft = "0";
        }

        // Profile Dropdown
        function toggleDropdown() {
            document.getElementById("dropdown-content").classList.toggle("show");
        }

        // Close dropdown when clicking outside
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

        // Copy button functionality
        document.querySelector('.copy-btn').addEventListener('click', function() {
            const copyText = document.querySelector('.qr-link-container input');
            copyText.select();
            document.execCommand('copy');
            alert('Link copied to clipboard');
        });
    </script>
</body>
</html>

