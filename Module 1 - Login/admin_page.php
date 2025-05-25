<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: white;
            padding: 20px;
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            display: block;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
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
            right: 10px;
            background-color: white;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content.show {
            display: block;
        }
    </style>
</head>
<body id="page-body" style="display:none;">

<!-- Sidebar -->
<div class="sidebar">
    <h4>ADMINISTRATOR</h4>
    <hr>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="../Module 1 - Login/admin_page.php" class="nav-link" onclick="showDashboard()">Dashboard</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Events</a></li>
        <li class="nav-item"><a href="../Module 1 - Login/view_student_registered.php" class="nav-link">View Student Registered</a></li>
        <li class="nav-item"><a href="../Module 1 - Login/view_event_advisor_registered.php" class="nav-link">View Event Advisor Registered</a></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Welcome, <?php echo $username; ?>!</h1>
        <div class="position-relative">
            <img src="../Images/eventadvisor.png" class="profile-icon" onclick="toggleDropdown()">
            <div id="dropdown-content" class="dropdown-content p-3">
                <a class="d-block" href="../Module 1 - Login/view_admin_profile.php">Setting Profile</a>
                <a href="logout.php" class="d-block text-danger">Logout</a>
            </div>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="row g-4">
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
                    <h5>Numbers of Event Advisor</h5>
                    <h3>55</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="mt-4">
        <h4>Notification</h4>
        <div class="card p-4">
            <h3>New Event Register</h3>
            <p>March 25 - April 02</p>
        </div>
    </div>
</div>

<!-- JS -->
<script>
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

window.addEventListener('pageshow', function(event) {
    if (event.persisted || performance.navigation.type === 2) {
        document.getElementById("page-body").style.display = "none";
        window.location.href = "../Module 1 - Login/login.php";
    }
});

window.onload = function () {
    document.getElementById("page-body").style.display = "block";
};
</script>

</body>
</html>
