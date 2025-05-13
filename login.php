<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="Style/login.css">
</head>
<body>
    <div class="logo-container">
        <img src="Images/umpsa logo1.png" alt="UMPSA Logo" class="logo">
        <img src="Images/petakom logo1.png" alt="PETAKOM Logo" class="logo">
    </div>

    <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo "<div class='error-box'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']); 
        }
    ?>

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
                <a href="#">Forgot password?</a>
            </div>

            <input type="submit" class="login-btn" value="Login">

            <div class="register-link">
                Don't have an account?
                <a href="register_user">Register</a>
            </div>

        </form>
    </div>
</body>
</html>
