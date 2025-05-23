<?php
session_start();

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Expires: Fri, 01 Jan 1990 00:00:00 GMT");


$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

$username = $_POST['username'];
$password = $_POST['password'];
$selectedRole = $_POST['role'];


// Get user from DB
$stmt = mysqli_prepare($link, "SELECT * FROM login WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user) {
    if (password_verify($password, $user['password'])) {
        if ($selectedRole === $user['role']) {
            $_SESSION['Login'] = "YES";
            $_SESSION['login_id'] = $user['login_id'];
            $_SESSION['username'] = $username;
            //$_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'student') {
                header("Location: ../Module 1 - Login/student_page.php");
            } elseif ($user['role'] === 'administrator') {
                header("Location: ../Module 1 - Login/admin_page.php");
            } elseif ($user['role'] === 'event_advisor') {
                header("Location: ../Module 2 - Event Registration/DashboardPage.php");
            }
            exit();
        } else {
            $_SESSION['error'] = "⚠ Wrong access: Role does not match.";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "❌ Incorrect password.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "❌ Username not found.";
    header("Location: login.php");
    exit();
}
?>
