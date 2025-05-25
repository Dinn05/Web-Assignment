<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='../Module 1 - Login/login.php'>login</a> as an administrator.</p>";
    exit();
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

$existingUsernames = [];
$existingMatricNumbers = [];

$usersResult = mysqli_query($link, "SELECT username FROM login");
while ($row = mysqli_fetch_assoc($usersResult)) {
    $existingUsernames[] = strtolower(htmlspecialchars($row['username']));
}

$matricResult = mysqli_query($link, "SELECT student_matric FROM student");
while ($row = mysqli_fetch_assoc($matricResult)) {
    $existingMatricNumbers[] = strtolower(htmlspecialchars($row['student_matric']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = htmlspecialchars(strtolower(trim($_POST['username'])));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $matric = htmlspecialchars(strtolower(trim($_POST['student_matric'])));
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $program = mysqli_real_escape_string($link, $_POST['program']);

    // Check for duplicate username or matric
    $checkUser = mysqli_query($link, "SELECT * FROM login WHERE LOWER(username) = '$username'");
    $checkMatric = mysqli_query($link, "SELECT * FROM student WHERE LOWER(student_matric) = '$matric'");

    if (mysqli_num_rows($checkUser) > 0) {
        echo "<script>alert('Username already exists!'); window.history.back();</script>";
        exit;
    }

    if (mysqli_num_rows($checkMatric) > 0) {
        echo "<script>alert('Matric number already exists!'); window.history.back();</script>";
        exit;
    }

    // Handle image upload
    $profile_picture = NULL;
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
        $fileTmp = $_FILES['profile_picture']['tmp_name'];
        $fileName = basename($_FILES['profile_picture']['name']);
        $targetDir = "../uploads/";
        $targetFile = $targetDir . time() . "_" . $fileName;

        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        if (move_uploaded_file($fileTmp, $targetFile)) {
            $profile_picture = $targetFile;
        }
    }

    $insert_login = "INSERT INTO login (username, password, role) VALUES ('$username', '$password', 'student')";
    if (mysqli_query($link, $insert_login)) {
        $login_id = mysqli_insert_id($link);
        $insert_student = "INSERT INTO student (login_id, name, student_matric, email, program, profile_picture) VALUES ('$login_id', '$name', '$matric', '$email', '$program', " . ($profile_picture ? "'$profile_picture'" : "NULL") . ")";

        if (mysqli_query($link, $insert_student)) {
            echo "<script>alert('New student added successfully'); window.location.href='../Module 1 - Login/view_student_registered.php';</script>";
            exit;
        } else {
            echo "Student creation failed: " . mysqli_error($link);
        }
    } else {
        echo "Login creation failed: " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        h2{
            text-align:center;
        }

    </style>
    <title>Add New Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const existingUsernames = <?= json_encode($existingUsernames) ?>;
        const existingMatricNumbers = <?= json_encode($existingMatricNumbers) ?>;

        function validateUsername(input) {
            const feedback = document.getElementById("usernameFeedback");
            if (existingUsernames.includes(input.value.trim().toLowerCase())) {
                feedback.innerText = "❌ Username already exists.";
                feedback.style.color = "red";
            } else {
                feedback.innerText = "✔ Username is available.";
                feedback.style.color = "green";
            }
        }

        function validateMatric(input) {
            const feedback = document.getElementById("matricFeedback");
            if (existingMatricNumbers.includes(input.value.trim().toLowerCase())) {
                feedback.innerText = "❌ Matric number already exists.";
                feedback.style.color = "red";
            } else {
                feedback.innerText = "✔ Matric number is available.";
                feedback.style.color = "green";
            }
        }
    </script>
</head>
<body class="p-4">
    <h2>Add New Student</h2>
    <form method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: auto;">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required onkeyup="validateUsername(this)">
            <div id="usernameFeedback" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Matric Number</label>
            <input type="text" name="student_matric" class="form-control" required onkeyup="validateMatric(this)">
            <div id="matricFeedback" class="form-text"></div>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Program</label>
            <input type="text" name="program" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Picture (optional)</label>
            <input type="file" name="profile_picture" accept="image/*" class="form-control">
        </div>
        <button type="submit" name="register" class="btn btn-success">Add Student</button>
        <a href="../Module 1 - Login/view_student_registered.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
