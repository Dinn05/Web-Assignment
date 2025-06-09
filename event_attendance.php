<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Event Attendance - MyPetakom</title>
    <link rel="stylesheet" href="Style/event_attendance.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Header with Logo and Profile Dropdown -->
    <header class="navbar">
        <div class="logo-container">
            <img src="../Images/petakom logo1.png" alt="Petakom Logo" class="logo-img">
            <div class="logo-text">EVENT ADVISOR</div>
        </div>
        <div class="profile-dropdown">
            <img src="../Images/eventadvisor.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()">
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


    <!-- Main Content -->
    <div class="main-content">
        <h1>Manage Event Attendance</h1>

        <!-- Attendance Slot Form -->
        <section class="form-section">
            <h2>Create New Attendance Slot</h2>
            <form>
                <label for="event">Select Event:</label>
                <select id="event" name="event">
                    <option value="">-- Select Event --</option>
                    <option>Coding Workshop (1 June 2025)</option>
                    <option>AI Seminar (15 July 2025)</option>
                </select>

                <label for="datetime">Attendance Date & Time:</label>
                <input type="datetime-local" id="datetime" name="datetime">

                <label for="location">Geolocation:</label>
                <input type="text" id="location" name="location" placeholder="Enter latitude,longitude">

                <label for="duration">QR Duration (minutes):</label>
                <input type="number" id="duration" name="duration" min="1" value="30">

                <button type="submit">Generate QR Slot</button>
            </form>
        </section>

        <!-- QR Code Section -->
        <section class="qr-section">
            <h2>QR Code for Attendance</h2>
            <img src="https://api.qrserver.com/v1/create-qr-code/?data=https://example.com/attendance_register.php?slot_id=123&size=150x150" alt="QR Code">
            <div class="qr-link-container">
                <strong>Link:</strong> 
                <input type="text" readonly value="https://example.com/attendance_register.php?slot_id=123">
                <button class="copy-btn">Copy</button>
            </div>
            <a href="#" class="download-btn">Download QR Code</a>
        </section>

        <!-- Attendance Slots Table -->
        <section class="slot-list">
            <h2>Your Attendance Slots</h2>
            <table>
                <thead>
                    <tr>
                        <th>Event Name</th>
                        <th>Slot Time</th>
			<th>Slot ID</th>
			<th>Event ID</th>
                        <th>QR Code</th>
			<th>Group ID</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Coding Workshop</td>
                        <td>2025-06-10 09:00:00</td>
			<td>1</td>
			<td>1</td>
                        <td><a href="#">View QR</a></td>
			<td>G1</td>
                        <td>Active</td>
                        <td>
                            <button class="edit-btn">Update</button>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                    <tr>
                        <td>AI Seminar</td>
                        <td>2025-06-15 08:30:00</td>
			<td>1</td>
			<td>1</td>
                        <td><a href="#">View QR</a></td>
			<td>G1</td>
                        <td>Inactive</td>
                        <td>
                            <button class="edit-btn">Update</button>
                            <button class="delete-btn">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>
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