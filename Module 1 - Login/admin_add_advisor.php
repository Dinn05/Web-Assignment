<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='login.php'>login</a> as an administrator.</p>";
    exit();
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

$existingUsernames = [];
$usersResult = mysqli_query($link, "SELECT username FROM login");
while ($row = mysqli_fetch_assoc($usersResult)) {
    $existingUsernames[] = strtolower(htmlspecialchars($row['username']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = htmlspecialchars(strtolower(trim($_POST['username'])));
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone_num']);

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

    $check = mysqli_prepare($link, "SELECT login_id FROM login WHERE LOWER(username) = LOWER(?)");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        echo "<script>alert('Username already exists (case-insensitive check).'); window.history.back();</script>";
        exit();
    }

    $insert_login = "INSERT INTO login (username, password, role) VALUES ('$username', '$password', 'event_advisor')";
    if (mysqli_query($link, $insert_login)) {
        $login_id = mysqli_insert_id($link);
        $insert_staff = "INSERT INTO staff (login_id, profile_picture, fullname, email, phone_num) VALUES ('$login_id', " . ($profile_picture ? "'$profile_picture'" : "NULL") . ", '$name', '$email', '$phone')";

        if (mysqli_query($link, $insert_staff)) {
            echo "<script>alert('New advisor added successfully'); window.location.href='../Module 1 - Login/view_event_advisor_registered.php';</script>";
            exit;
        } else {
            echo "Staff creation failed: " . mysqli_error($link);
        }
    } else {
        echo "Login creation failed: " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Advisor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const existingUsernames = <?= json_encode($existingUsernames) ?>;

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
    </script>
</head>
<body class="p-4">
    <h2>Add New Advisor</h2>
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
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone Number</label>
            <input type="text" name="phone_num" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Picture (optional)</label>
            <input type="file" name="profile_picture" accept="image/*" class="form-control">
        </div>
        <button type="submit" name="register" class="btn btn-success">Add Advisor</button>
        <a href="../Module 1 - Login/view_event_advisor_registered.php" class="btn btn-secondary">Cancel</a>
    </form>
</body>
</html>
