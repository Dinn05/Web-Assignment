<?php
// Prevent back navigation and caching (must come BEFORE any output)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");

session_start();
if (isset($_SESSION['error'])) {
    echo "<div class='error-box'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>

    <!-- Prevent browser caching -->
    <meta http-equiv="cache-control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">

    <link rel="stylesheet" href="Style/login.css">
</head>
<body>
    <div class="logo-container">
        <img src="../Images/umpsa logo1.png" alt="UMPSA Logo" class="logo">
        <img src="../Images/petakom logo1.png" alt="PETAKOM Logo" class="logo">
    </div>

    <div class="login-wrapper">
        <form class="login-box" action="check_login.php" method="POST">
            <h2>Login</h2>

            <div class="input-field">
                <span class="icon">👤</span>
                <input type="text" name="username" required placeholder="Username">
            </div>

            <div class="input-field">
                <span class="icon">🔒</span>
                <input type="password" name="password" required placeholder="Password">
            </div>

            <div class="input-field">
                <span class="icon">🪪</span>
                <select name="role" required>
                    <option value="student">Student</option>
                    <option value="administrator">Administrator</option>
                    <option value="event_advisor">Event Advisor</option>
                </select>
            </div>

            <div class="options">
                <label><input type="checkbox"> Remember me</label>
            </div>
            <input type="submit" class="login-btn" value="Login">
        </form>
    </div>

    <!-- Back button prevention for Chrome -->
    <script>
        window.onload = function () {
            window.addEventListener('pageshow', function (event) {
                if (event.persisted || performance.navigation.type === 2) {
                    window.location.reload();
                }
            });
        };

        if (window.history && window.history.pushState) {
            window.history.pushState(null, '', window.location.href);
            window.addEventListener('popstate', function () {
                window.history.pushState(null, '', window.location.href);
                alert("Please log in to continue.");
            });
        }
    </script>
    <script>
    // Show alert if redirected from expired session
    if (sessionStorage.getItem("sessionExpired")) {
        alert("⚠ INVALID SESSION. Please log in again.");
        sessionStorage.removeItem("sessionExpired");
    }
</script>

</body>
</html>
