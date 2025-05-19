<?php
session_start();

// Prevent browser caching (forces reload, blocks back-button access after logout)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (
    isset($_SESSION['Login']) &&
    $_SESSION['Login'] === "YES" &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'administrator'
) {
    //$fullname = htmlspecialchars($_SESSION['fullname']);
    $username = htmlspecialchars($_SESSION['username']);
} else {
    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='login.php'>login</a> as an event advisor to access this page.</p>";  
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Advisor Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            background-color: #343a40;
            color: #fff;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card-icon {
            font-size: 1.5rem;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            right: 10px;
            background-color: #fff;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content.show {
            display: block;
        }
        .profile-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-white">ADMINISTRATOR</h4>
    <hr class="text-white">
    <ul class="nav nav-pills flex-column">
        <li class="nav-item"><a href="#" class="nav-link active">Dashboard</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Events</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Merit Approval</a></li>
        <li class="nav-item"><a href="#" class="nav-link">Charts</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Welcome, <?php echo $username; ?>!</h1>
        <div class="position-relative">
            <img src="../Images/eventadvisor.png" class="profile-icon" onclick="toggleDropdown()">
            <div id="dropdown-content" class="dropdown-content p-3">
                <p><strong><?php echo $username; ?></strong></p>
                <a class="d-block">Setting Profile</a>
                <a href="logout.php" class="d-block text-danger">Logout</a>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Total Registered Student</h5>
                            <h3>5,421</h3>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Total Approved Members</h5>
                            <h3>1,303</h3>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Total Events</h5>
                            <h3>230</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h5>Numbers of Event Advisor</h5>
                            <h3>55</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <h4>Notification</h4>
        <div class="card p-4">
            <h3>New Event Register</h3>
            <p>March 25 - April 02</p>
        </div>
    </div>
</div>

<script>
function toggleDropdown() {
    document.getElementById("dropdown-content").classList.toggle("show");
}

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
</script>

</body>
</html>