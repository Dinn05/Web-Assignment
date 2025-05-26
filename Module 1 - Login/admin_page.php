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
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            background: #f5f7fb;
        }

        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: #cce4ff;
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
            overflow: hidden;
            z-index: 1000;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar img {
            height: 100px;
            margin: 10px auto;
            display: block;
        }

        .sidebar ul {
            list-style: none;
            padding-left: 0;
            width: 100%;
        }

        .sidebar ul li {
            padding: 15px 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #333;
            transition: background 0.2s;
        }

        .sidebar ul li:hover {
            background-color: #b3d7ff;
        }

        .sidebar ul li i {
            width: 20px;
            text-align: center;
        }

        .sidebar ul li span {
            transition: opacity 0.3s ease;
        }

        .sidebar.collapsed ul li span {
            opacity: 0;
            pointer-events: none;
        }

        .main-content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            padding: 30px;
        }

        .main-content.collapsed {
            margin-left: 70px;
        }

        .dashboard-topbar {
            background: white;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #ddd;
        }

        .dashboard-topbar h5 {
            margin: 0;
            flex-grow: 1;
            text-align: center;
        }

        .toggle-btn {
            font-size: 20px;
            background: #f0f0f0;
            border: none;
            padding: 8px 12px;
            border-radius: 5px;
            cursor: pointer;
        }

        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background-color: white;
            border: 1px solid #ddd;
            min-width: 160px;
            z-index: 1001;
        }

        .dropdown-content.show {
            display: block;
        }

        footer {
            text-align: center;
            padding: 15px 0;
            background: #cce4ff;
            font-size: 14px;
            border-top: 1px solid #bbb;
            margin-top: 30%;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <img src="../Images/petakom logo1.png" alt="Logo">
    <ul>
        <li onclick="location.href='../Module 1 - Login/admin_page.php'"><i class="fas fa-home"></i><span> Dashboard</span></li>
        <li onclick="location.href='#'"><i class="fas fa-calendar-alt"></i><span> Events</span></li>
        <li onclick="location.href='../Module 1 - Login/view_student_registered.php'"><i class="fas fa-users"></i><span> Student List</span></li>
        <li onclick="location.href='../Module 1 - Login/view_event_advisor_registered.php'"><i class="fas fa-chalkboard-teacher"></i><span> Advisor List</span></li>
        <li onclick="location.href='../Module 1 - Login/logout.php'"><i class="fas fa-sign-out-alt"></i><span> Logout</span></li>
    </ul>
</div>

<!-- Topbar -->
<div class="main-content" id="mainContent">
    <div class="dashboard-topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button>
        <h5>MyPetakom Administrator Dashboard</h5>
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

    <div class="row g-4 mt-3">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5>Total Registered Student</h5>
                    <h3>5,421</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5>Total Approved Members</h5>
                    <h3>1,303</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5>Total Events</h5>
                    <h3>230</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <h5>Number of Event Advisors</h5>
                    <h3>55</h3>
                </div>
            </div>
        </div>
    </div>

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
