<?php
session_start();

// Connect to database
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

// Get form data
$username = $_POST['username'];
$password = $_POST['password'];
$selectedRole = $_POST['role'];

// Check if username already exists
$stmt = mysqli_prepare($link, "SELECT * FROM login WHERE username = ?");
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if ($user) {
    $_SESSION['message'] = "❌ Username already exists. Please choose another.";
} else {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert new user
    $stmt = mysqli_prepare($link, "INSERT INTO login (username, password, role) VALUES (?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sss", $username, $hashed_password, $selectedRole);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['message'] = "✅ User registered successfully!";
    } else {
        $_SESSION['message'] = "❌ Registration failed: " . mysqli_stmt_error($stmt);
    }
}

// Cleanup
mysqli_stmt_close($stmt);
mysqli_close($link);

// Redirect back to registration page
header("Location: register_user.php");
exit();
?>
