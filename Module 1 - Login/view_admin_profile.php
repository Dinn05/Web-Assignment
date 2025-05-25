<?php
session_start();

// ✅ Strict session validation for administrator
if (
    !isset($_SESSION['Login']) ||
    $_SESSION['Login'] !== "YES" ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'administrator'
) {
    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='login.php'>login</a> as an administrator to access this page.</p>";
    exit();
}

// ✅ Connect to DB
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("DB connection failed");

// ✅ Get login_id from session
$login_id = $_SESSION['login_id'];

// ✅ Fetch admin profile data
$query = "SELECT * FROM staff WHERE login_id = '$login_id'";
$result = mysqli_query($link, $query);
$admin = mysqli_fetch_assoc($result);

if (!$admin) {
    die("⚠️ Admin profile not found.");
}

// ✅ Handle update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['done'])) {
    $fullname = mysqli_real_escape_string($link, $_POST['fullname']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $phone = mysqli_real_escape_string($link, $_POST['phone_num']);
    $profile_picture = $admin['profile_picture'];

    // ✅ Delete picture if requested
    if (isset($_POST['delete_existing_picture']) && $_POST['delete_existing_picture'] == '1') {
        if (!empty($profile_picture) && file_exists($profile_picture)) {
            unlink($profile_picture);
        }
        $profile_picture = null;
    }

    // ✅ Handle new upload
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

    // ✅ Update query
    $update = "UPDATE staff 
               SET fullname='$fullname', email='$email', phone_num='$phone', profile_picture=" .
               ($profile_picture ? "'$profile_picture'" : "NULL") .
               " WHERE login_id = '$login_id'";

    if (mysqli_query($link, $update)) {
        echo "<script>
                alert('Profile updated successfully');
                window.location.href='view_admin_profile.php';
              </script>";
        exit;
    } else {
        echo "Update failed: " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Profile</title>
    <style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background-color: #dbeafe;
        margin: 0;
        padding: 40px;
    }

    form {
        max-width: 400px;
        margin: auto;
        background: white;
        padding: 30px 25px;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        text-align: center;
    }

    h2 {
        margin-bottom: 25px;
        font-size: 22px;
        font-weight: bold;
        color: #1e3a8a;
        text-align:center;
    }

    img {
    max-width: 50%;
    height: auto;
    margin-bottom: 15px;
    border: 3px solid #007bff;
    border-radius: 5px; /* sharp corners */
    display: block;
    margin-left: auto;
    margin-right: auto;
}


    label {
        text-align: left;
        display: block;
        margin: 10px 0 5px;
        font-weight: 500;
    }

    input[type="text"],
    input[type="email"],
    input[type="file"] {
        width: 100%;
        padding: 10px 12px;
        border-radius: 20px;
        border: 1px solid #ccc;
        background-color: #f8fafc;
        margin-bottom: 10px;
    }

    input[disabled] {
        background-color: #e5e7eb;
        cursor: not-allowed;
    }

    .btn-group {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .btn-primary,
    .btn-secondary {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        text-align: center;
    }

    .btn-primary {
        background-color: #3b82f6;
        color: white;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
        text-decoration: none;
        display: inline-block;
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
        padding: 10px;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        font-weight: 600;
        width: 100%;
    }

    .success {
        color: #16a34a;
        font-weight: 500;
        margin: 5px 0;
    }
</style>

    <script>
        function enableEdit() {
            document.querySelectorAll("input").forEach(el => el.disabled = false);
            document.getElementById("upload-section").style.display = 'block';
            document.getElementById("update_button").style.display = 'none';
            document.getElementById("done_button").style.display = 'inline-block';
        }

        function previewImage(event) {
            const preview = document.getElementById('preview');
            if (event.target.files.length > 0) {
                preview.src = URL.createObjectURL(event.target.files[0]);
                preview.style.display = 'block';
                document.getElementById("remove_picture").style.display = 'inline-block';
                document.getElementById("delete_current_picture").style.display = 'none';
                document.getElementById("no-picture-note").style.display = 'none';
                document.getElementById("upload-success").style.display = 'block';
                document.getElementById("delete-success").style.display = 'none';
            }
        }

        function removeImage() {
            document.getElementById('preview').src = '';
            document.getElementById('preview').style.display = 'none';
            document.querySelector("input[type=file]").value = '';
            document.getElementById("remove_picture").style.display = 'none';
            document.getElementById("upload-success").style.display = 'none';
            document.getElementById("no-picture-note").style.display = 'block';
        }

        function markDeletePicture() {
            document.getElementById('preview').src = '';
            document.getElementById('preview').style.display = 'none';
            document.getElementById('delete_current_picture').style.display = 'none';
            document.getElementById('no-picture-note').style.display = 'block';
            document.getElementById('delete_existing_picture').value = '1';
            document.getElementById('upload-success').style.display = 'none';
            document.getElementById('delete-success').style.display = 'block';
        }
    </script>
</head>
<body>
    <h2>Administrator Profile</h2>

    <form method="POST" enctype="multipart/form-data">
        <img src="<?= $admin['profile_picture'] ?? '' ?>" id="preview" style="<?= !empty($admin['profile_picture']) ? '' : 'display:none;' ?>">
        <p id="no-picture-note" style="<?= !empty($admin['profile_picture']) ? 'display:none;' : '' ?>">No profile picture uploaded.</p>
        <p id="upload-success" class="success" style="display:none;">Image uploaded (not saved yet).</p>
        <p id="delete-success" class="success" style="display:none;">Marked for deletion.</p>

        <div id="upload-section" style="display:none;">
            <input type="file" name="profile_picture" accept="image/*" onchange="previewImage(event)">
            <input type="hidden" name="delete_existing_picture" id="delete_existing_picture" value="0">
            <button type="button" class="btn-secondary" onclick="removeImage()" id="remove_picture" style="display:none;">Remove Selected Picture</button>
            <?php if (!empty($admin['profile_picture'])): ?>
                <button type="button" class="btn-danger" id="delete_current_picture" onclick="markDeletePicture()">Delete Current Picture</button>
            <?php endif; ?>
        </div>

        <label>Full Name</label>
        <input type="text" name="fullname" value="<?= $admin['fullname'] ?>" disabled required>

        <label>Email</label>
        <input type="email" name="email" value="<?= $admin['email'] ?>" disabled required>

        <label>Phone Number</label>
        <input type="text" name="phone_num" value="<?= $admin['phone_num'] ?>" disabled required>

        <div class="btn-group">
            <button type="button" class="btn-primary" id="update_button" onclick="enableEdit()">Update Profile</button>
            <button type="submit" name="done" class="btn-primary" id="done_button" style="display:none;">Done</button>
            <a href="admin_page.php"><button type="button" class="btn-secondary">Back to Dashboard</button></a>
        </div>
    </form>
</body>
</html>
