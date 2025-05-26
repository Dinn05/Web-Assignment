<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='../Module 1 - Login/login.php'>login</a> as an administrator.</p>";
    exit();
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());

if (!isset($_GET['staff_id'])) {
    die("Invalid request.");
}

$staff_id = intval($_GET['staff_id']);
$result = mysqli_query($link, "SELECT * FROM staff WHERE staff_id = '$staff_id'");
$advisor = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['done'])) {
    $fullname = mysqli_real_escape_string($link, $_POST['fullname']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone_num']);
    $profile_picture = $advisor['profile_picture'];

    if (isset($_POST['delete_existing_picture']) && $_POST['delete_existing_picture'] == '1') {
        if (!empty($profile_picture) && file_exists($profile_picture)) {
            unlink($profile_picture);
        }
        $profile_picture = null;
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
            if (!empty($profile_picture) && file_exists($profile_picture)) {
                unlink($profile_picture);
            }
            $profile_picture = $targetFile;
        }
    }

    $update = "UPDATE staff SET fullname='$fullname', email='$email', phone_num='$phone', profile_picture=" .
               ($profile_picture ? "'$profile_picture'" : "NULL") .
               " WHERE staff_id = '$staff_id'";

    if (mysqli_query($link, $update)) {
        echo "<script>alert('Advisor Info updated successfully'); window.location.href='../Module 1 - Login/view_event_advisor_registered.php';</script>";
        exit;
    } else {
        echo "Update failed: " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Advisor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            background-color: #f8f9fa;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .form-container {
            width: 100%;
            max-width: 450px;
            background: #fff;
            padding: 30px 25px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        img {
            display: block;
            margin: 0 auto 15px;
            width: 120px;
            height: auto;
            border-radius: 6px;
            border: 2px solid #007bff;
        }

        .btn {
            width: 100%;
            margin-bottom: 10px;
        }

        .form-label {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2 class="text-center mb-4">Edit Event Advisor</h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if (!empty($advisor['profile_picture'])): ?>
                <img src="<?= $advisor['profile_picture'] ?>" alt="Profile Picture">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="delete_existing_picture" value="1" id="deleteImage">
                    <label class="form-check-label" for="deleteImage">Delete current image</label>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label class="form-label">Profile Picture</label>
                <input type="file" class="form-control" name="profile_picture">
            </div>

            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" class="form-control" name="fullname" value="<?= htmlspecialchars($advisor['fullname']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($advisor['email']) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" name="phone_num" value="<?= htmlspecialchars($advisor['phone_num']) ?>" required>
            </div>

            <button type="submit" name="done" class="btn btn-primary">Update Advisor</button>
            <a href="../Module 1 - Login/view_event_advisor_registered.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>
