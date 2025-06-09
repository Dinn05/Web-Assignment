<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Attendance Check-In</title>
    <link rel="stylesheet" href="Style/attendance_registration.css">
</head>
<body>

<!-- Header with Logo and Profile Dropdown -->
    <header class="navbar">
        <div class="logo-container">
            <img src="../Images/petakom logo1.png" alt="Petakom Logo" class="logo-img">
            <div class="logo-text">ATTENDANCE REGISTRATION</div>
        </div>
        <div class="profile-dropdown">
            <img src="../Images/student.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()">
		<div id="dropdown-content" class="dropdown-content">
                <p><strong>Student</strong></p>
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


    <div class="container">
        <h1>Event Attendance Check-In</h1>

        <!-- Event Details Section -->
        <div class="event-details">
            <h2>Event: Coding Workshop</h2>
            <p><strong>Date:</strong> 1 June 2025</p>
            <p><strong>Time:</strong> 10:00 AM - 12:00 PM</p>
            <p><strong>Location:</strong> PETAKOM Lab 1, FSKTM</p>
            <div id="map-placeholder">[Mini map placeholder]</div>
            <p><em>Instructions: Enter your student ID and password to check in.</em></p>
        </div>

        <!-- Check-In Form -->
        <form class="checkin-form" method="post">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Check In</button>
        </form>

        <!-- Status Message Placeholder -->
        <div class="status-message">
            <!-- Replace this section dynamically based on result -->
            <!-- <p class="success">✅ Attendance recorded successfully</p> -->
            <!-- <p class="error">❌ Invalid location. You must be at the event venue.</p> -->
            <!-- <p class="error">❌ Incorrect ID or password</p> -->
        </div>
    </div>

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


