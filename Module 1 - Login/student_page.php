<?php
session_start();

if (
    isset($_SESSION['Login']) &&
    $_SESSION['Login'] === "YES" &&
    isset($_SESSION['role']) &&
    $_SESSION['role'] === 'student'
) {
    //$fullname = htmlspecialchars($_SESSION['fullname']);
    $username = htmlspecialchars($_SESSION['username']);
    $id = $_SESSION['user_id'];
} else {

    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='login.php'>login</a> as a student to access this page.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Page</title>
    <link rel="stylesheet" href="style/student_page.css">
</head>
<body>

<header class="navbar">
    <div class="logo">STUDENT</div>
    <div class="profile-dropdown">
        <img src="../Images/student.png" alt="Profile" class="profile-icon" onclick="toggleDropdown()">
        <div id="dropdown-content" class="dropdown-content">
            <p><strong><?php echo $username; ?></strong></p>
            <a href="#">Setting Profile</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>

<main class="main-content">
    <div class="register-box">
        <p>Welcome, <strong><?php echo $username; ?>!</strong></p>
        <form action="register_petakom.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="submit" class="register-btn" value="Register for PETAKOM">
        </form>
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
