<?php
session_start();

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

$id = $_POST['id'];
$username = $_POST['username'];
$password = $_POST['password'];
$selectedRole = $_POST['role'];

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

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
            $_SESSION['id'] = $user['id'];
            $_SESSION['username'] = $username;
            //$_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] === 'student') {
                header("Location: student_page.php");
            } elseif ($user['role'] === 'administrator') {
                header("Location: admin_page.php");
            } elseif ($user['role'] === 'event_advisor') {
                header("Location: advisor_page.php");
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
