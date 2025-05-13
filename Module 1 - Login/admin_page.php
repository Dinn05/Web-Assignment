<?php
session_start();

if (
    isset($_SESSION['Login']) &&
    $_SESSION['Login'] === "YES" &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'administrator'
) {
    $fullname = htmlspecialchars($_SESSION['fullname']);
    $username = htmlspecialchars($_SESSION['username']);
    $id = $_SESSION['id'];
} else {
    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='login.php'>login</a> as an administrator to access this page.</p>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Page</title>
    <link rel="stylesheet" href="style/administrator.css">
</head>
<body>

<header class="navbar">
    <div class="logo">ADMINISTRATOR</div>
    <div class="profile-dropdown">
        <img src="../Images/administrator.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()">
        <div id="dropdown-content" class="dropdown-content">
            <p><strong><?php echo $username; ?></strong></p>
            <a href="#">Setting Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="register-box">
        <p>Welcome, <strong><?php echo $fullname; ?>!</strong></p>
    </div>
</main>

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