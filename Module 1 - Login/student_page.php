<?php
session_start();

header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (
    isset($_SESSION['Login']) &&
    $_SESSION['Login'] === "YES" &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'student'
) {
    $username = htmlspecialchars($_SESSION['username']);

    $link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed");

    $loginQuery = "SELECT login_id FROM login WHERE username = '$username'";
    $loginResult = mysqli_query($link, $loginQuery);
    if ($loginRow = mysqli_fetch_assoc($loginResult)) {
        $login_id = $loginRow['login_id'];
        $studentQuery = "SELECT student_id FROM student WHERE login_id = '$login_id'";
        $studentResult = mysqli_query($link, $studentQuery);
        if ($studentRow = mysqli_fetch_assoc($studentResult)) {
            $_SESSION['student_id'] = $studentRow['student_id'];
        } else {
            die("Student ID not found.");
        }
    } else {
        die("Login ID not found.");
    }
} else {
    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='../Module 1 - Login/login.php'>login</a> as a student to access this page.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animated Sidebar Dashboard</title>
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
            background:rgb(207, 207, 207);
            box-shadow: 2px 0 8px rgba(0, 0, 0, 0.1);
            transition: width 0.3s ease;
            z-index: 2000;
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar .top-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
        }

        .sidebar .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .sidebar .toggle-btn {
            font-size: 20px;
            background: none;
            border: none;
            cursor: pointer;
        }

        .sidebar .nav-links {
            list-style: none;
            padding: 0;
            margin-top: 30px;
        }

        .sidebar .nav-links li {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #333;
            cursor: pointer;
            transition: background 0.2s;
        }

        .sidebar .nav-links li:hover {
            background-color: #f0f0f0;
        }

        .sidebar .nav-links li i {
            font-size: 18px;
            width: 30px;
        }

        .sidebar .nav-links li span {
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .nav-links li span {
            opacity: 0;
            pointer-events: none;
        }

        .main-content {
            margin-left: 250px;
            padding: 30px;
            transition: margin-left 0.3s ease;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 70px;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid #ddd;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1500;
        }

        .topbar .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        footer {
            background:rgb(207, 207, 207);
            text-align: center;
            padding: 20px 0;
            margin-top: 30%;
            border-top: 1px solid #ddd;
        }

        
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
  <div class="logo">
    <img src="../Images/petakom logo1.png" alt="Logo" style="height: 100px; margin-left:25%;">
</div>
    <ul class="nav-links">
        <li onclick="location.href='../Module 1 - Login/student_page.php'"><i class="fas fa-home"></i><span>Dashboard</span></li>
        <li onclick="location.href='../Module 1 - Login/view_student_profile.php'"><i class="fas fa-user"></i><span>Profile</span></li>
        <li onclick="location.href='../Module 1 - Login/register_petakom.php'"><i class="fas fa-book"></i><span>Apply Membership</span></li>
        <!--<li><i class="fas fa-chart-bar"></i><span>Reports</span></li>-->
        <!--<li><i class="fas fa-cog"></i><span>Settings</span></li>-->
        <li onclick="location.href='../Module 1 - Login/logout.php'"><i class="fas fa-sign-out-alt"></i><span>Logout</span></li>
    </ul>
</div>

<!-- Main Content -->
<div class="main-content" id="mainContent">
    <div class="topbar">
        <button class="toggle-btn" onclick="toggleSidebar()">☰</button> <!-- inside sidebar -->
        <div><strong>MyPetakom Student Dashboard</strong></div>
        <div class="user-info">
            <span>Welcome, <?php echo ucfirst($username); ?>!</span>
            <a href="../Module 1 - Login/logout.php" class="btn btn-outline-primary btn-sm">Logout</a>
        </div>
    </div>

    <br>
    <h2>Dashboard</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">Student Details</div>
                <div class="card-footer"><a href="#" class="text-white">View Details</a></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">Claim Merit</div>
                <div class="card-footer"><a href="#" class="text-white">View Details</a></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">Committee</div>
                <div class="card-footer"><a href="#" class="text-white">View Details</a></div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3">
                <div class="card-body">Membership</div>
                <div class="card-footer"><a href="#" class="text-white">View Details</a></div>
            </div>
        </div>
    </div>

    <footer>
        <small>&copy; 2025 MyPetakom Portal</small> <br>
        <a href="#">Privacy Policy</a> · <a href="#">Terms & Conditions</a>
    </footer>
</div>

<!-- Scripts -->
<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
    document.getElementById("mainContent").classList.toggle("collapsed");
}
</script>
</body>
</html>
