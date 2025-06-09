<?php
session_start();

// Disable browser caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");

// Connect to DB
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

// Get form input
$username = $_POST['username'];
$password = $_POST['password'];
$selectedRole = $_POST['role'];

// Get user from login table
$stmt = mysqli_prepare($link, "SELECT * FROM login WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

// Validate user
if ($user) {
    if (password_verify($password, $user['password'])) {
        if ($selectedRole === $user['role']) {
            // ✅ Set base session values
            $_SESSION['Login'] = "YES";
            $_SESSION['login_id'] = $user['login_id'];
            $_SESSION['username'] = $username;
            $_SESSION['role'] = $user['role'];

            // ✅ Route based on role
            if ($user['role'] === 'student') {
                header("Location: ../Module 1 - Login/student_page.php");
                exit();
            }

            elseif ($user['role'] === 'administrator') {
                // ✅ Fetch staff_id using login_id
                $login_id = $user['login_id'];
                $staffQuery = mysqli_query($link, "SELECT staff_id FROM staff WHERE login_id = $login_id");
                if ($staffRow = mysqli_fetch_assoc($staffQuery)) {
                    $_SESSION['admin_id'] = $staffRow['staff_id'];
                    header("Location: ../Module 1 - Login/admin_page.php");
                    exit();
                } else {
                    // ❌ Admin not linked to staff table
                    $_SESSION['error'] = "❌ Admin account not linked to staff profile.";
                    header("Location: ../Module 1 - Login/login.php");
                    exit();
                }
            }

            elseif ($user['role'] === 'event_advisor') {
                header("Location: ../Module 2 - Event Registration/DashboardPage.php");
                exit();
            }
        } else {
            $_SESSION['error'] = "⚠ Wrong access: Role does not match.";
            header("Location: ../Module 1 - Login/login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "❌ Incorrect password.";
        header("Location: ../Module 1 - Login/login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "❌ Username not found.";
    header("Location: ../Module 1 - Login/login.php");
    exit();
}
?>
