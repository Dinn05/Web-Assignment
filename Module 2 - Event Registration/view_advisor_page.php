<?php
session_start();

// ✅ Strict session validation for event advisor
if (
    !isset($_SESSION['Login']) ||
    $_SESSION['Login'] !== "YES" ||
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'event_advisor'
) {
    echo "<h1>Access Denied</h1>";
    echo "<p>You must <a href='login.php'>login</a> as an event advisor to access this page.</p>";
    exit();
}

// ✅ Connect to database
$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("DB connection failed");

// ✅ Get staff_id from session
$staff_id = $_SESSION['staff_id'];

// ✅ Fetch advisor data
$query = "SELECT * FROM staff WHERE staff_id = '$staff_id'";
$result = mysqli_query($link, $query);
$advisor = mysqli_fetch_assoc($result);

// ✅ Handle update logic
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

    $update = "UPDATE staff 
               SET fullname='$fullname', email='$email', phone_num='$phone', profile_picture=" .
               ($profile_picture ? "'$profile_picture'" : "NULL") .
               " WHERE staff_id = '$staff_id'";

    if (mysqli_query($link, $update)) {
        echo "<script>
                alert('Profile updated successfully');
                window.location.href='../Module 2 - Event Registration/view_advisor_page.php';
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
    <title>Advisor Profile</title>
    <style>
    * {
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background: linear-gradient(to right, #e0eafc, #cfdef3);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }

    h2 {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }

    form {
        background: #ffffff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 0 25px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 400px;
        text-align: center;
    }

    img#preview {
        max-width: 50%;
        height: auto;
        margin-bottom: 15px;
        border: 3px solid #007bff;
        border-radius: 5px; /* Sharp edges */
        display: block;
        margin-left: auto;
        margin-right: auto;
    }

    label {
        text-align: left;
        display: block;
        margin-bottom: 5px;
        font-weight: 500;
        color: #333;
    }

    input[type="text"],
    input[type="email"],
    input[type="file"] {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ccc;
        border-radius: 25px;
        margin-bottom: 15px;
        font-size: 14px;
    }

    input[disabled] {
        background-color: #f5f5f5;
        cursor: not-allowed;
    }

    button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 25px;
        font-weight: bold;
        margin-top: 10px;
        transition: background-color 0.3s;
        font-size: 15px;
        cursor: pointer; 
    }

    .btn-primary {
        background-color: #4a90e2;
        color: white;
    }

    .btn-primary:hover {
        background-color: #357bd8;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .success {
        color: green;
        font-weight: bold;
        font-size: 14px;
        margin-top: -10px;
        margin-bottom: 10px;
    }

    #no-picture-note {
        color: #666;
        font-size: 0.85rem;
        margin-bottom: 10px;
    }

    .btn-group {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 10px;
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
    <h2>Event Advisor Profile</h2>

    <form method="POST" enctype="multipart/form-data">
        <img src="<?= $advisor['profile_picture'] ?? '' ?>" id="preview" style="<?= $advisor['profile_picture'] ? '' : 'display:none;' ?>">
        <p id="no-picture-note" style="<?= $advisor['profile_picture'] ? 'display:none;' : '' ?>">No profile picture uploaded.</p>
        <p id="upload-success" class="success" style="display:none;">Image successfully uploaded (not yet saved).</p>
        <p id="delete-success" class="success" style="display:none;">Image marked for deletion (will be removed on save).</p>

        <div id="upload-section" style="display:none;">
            <input type="file" name="profile_picture" accept="image/*" onchange="previewImage(event)">
            <input type="hidden" name="delete_existing_picture" id="delete_existing_picture" value="0">
            <button type="button" class="btn-secondary" onclick="removeImage()" id="remove_picture" style="display:none;">Remove Selected Picture</button>
            <?php if (!empty($advisor['profile_picture'])): ?>
                <button type="button" class="btn-danger" id="delete_current_picture" onclick="markDeletePicture()">Delete Current Picture</button>
            <?php endif; ?>
        </div>

        <label>Full Name</label>
        <input type="text" name="fullname" value="<?= $advisor['fullname'] ?>" disabled required>

        <label>Email</label>
        <input type="email" name="email" value="<?= $advisor['email'] ?>" disabled required>

        <label>Phone Number</label>
        <input type="text" name="phone_num" value="<?= $advisor['phone_num'] ?>" disabled required>

        <div class="btn-group">
            <button type="button" class="btn-primary" id="update_button" onclick="enableEdit()">Update Profile</button>
            <button type="submit" name="done" class="btn-primary" id="done_button" style="display:none;">Done</button>
            <a href="DashboardPage.php"><button type="button" class="btn-secondary">Back to Dashboard</button></a>
        </div>
    </form>
</body>
</html>
