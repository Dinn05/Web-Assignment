<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='login.php'>login</a> as an administrator.</p>";
    exit();
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

// Fetch all usernames for JS validation
$usernameList = [];
$res = mysqli_query($link, "SELECT username FROM login");
while ($row = mysqli_fetch_assoc($res)) {
    $usernameList[] = strtolower($row['username']);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $fullname = mysqli_real_escape_string($link, $_POST['fullname']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone_num']);
    $username = htmlspecialchars($_POST['username'], ENT_QUOTES, 'UTF-8');
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profile_picture = null;

    $check = mysqli_prepare($link, "SELECT login_id FROM login WHERE LOWER(username) = LOWER(?)");
    mysqli_stmt_bind_param($check, "s", $username);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    if (mysqli_stmt_num_rows($check) > 0) {
        echo "<script>alert('Username already exists (case-insensitive check).'); window.history.back();</script>";
        exit();
    }

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

    $insertLogin = "INSERT INTO login (username, password, role) VALUES ('$username', '$password', 'event_advisor')";
    if (mysqli_query($link, $insertLogin)) {
        $login_id = mysqli_insert_id($link);
        $insertStaff = "INSERT INTO staff (login_id, fullname, email, phone_num, profile_picture) 
                        VALUES ('$login_id', '$fullname', '$email', '$phone', " .
                        ($profile_picture ? "'$profile_picture'" : "NULL") . ")";
        if (mysqli_query($link, $insertStaff)) {
            echo "<script>alert('New advisor added successfully'); window.location.href='../Module 1 - Login/view_event_advisor_registered.php';</script>";
            exit();
        }
    }

    echo "<script>alert('Failed to add advisor'); window.history.back();</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add New Event Advisor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        const existingUsernames = <?php echo json_encode($usernameList); ?>;

        function validateUsername(input) {
            const feedback = document.getElementById("usernameFeedback");
            const val = input.value.trim().toLowerCase();
            if (existingUsernames.includes(val)) {
                feedback.innerText = "❌ Username already exists.";
                feedback.style.color = "red";
            } else {
                feedback.innerText = "✔ Username is available.";
                feedback.style.color = "green";
            }
        }
    </script>
</head>
<body class="bg-light p-5">
<div class="container bg-white p-4 shadow rounded">
    <h2 class="text-center mb-4">Add New Event Advisor</h2>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Full Name</label>
            <input type="text" name="fullname" class="form-control" required>
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
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required onkeyup="validateUsername(this)">
            <small id="usernameFeedback" class="form-text"></small>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" name="add" class="btn btn-success">Add Advisor</button>
        <a href="../Module 1 - Login/view_event_advisor_registered.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
