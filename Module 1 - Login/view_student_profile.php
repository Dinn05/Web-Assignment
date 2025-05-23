<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    die("Access denied. Please login as a student.");
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("DB connection failed");
$student_id = $_SESSION['student_id'];

// Fetch student data
$query = "SELECT * FROM student WHERE student_id = '$student_id'";
$result = mysqli_query($link, $query);
$student = mysqli_fetch_assoc($result);

// Update profile (includes delete logic)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['done'])) {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $student_matric = mysqli_real_escape_string($link, $_POST['student_matric']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $program = mysqli_real_escape_string($link, $_POST['program']);
    $profile_picture = $student['profile_picture'];

    // If marked to delete existing image
    if (isset($_POST['delete_existing_picture']) && $_POST['delete_existing_picture'] == '1') {
        if (!empty($profile_picture) && file_exists($profile_picture)) {
            unlink($profile_picture);
        }
        $profile_picture = null;
    }

    // If new image uploaded
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

    // Update DB
    $update = "UPDATE student 
               SET name='$name', student_matric='$student_matric', email='$email', program='$program', profile_picture=" . 
               ($profile_picture ? "'$profile_picture'" : "NULL") . 
               " WHERE student_id = '$student_id'";

    if (mysqli_query($link, $update)) {
        echo "<script>
                alert('Profile updated successfully');
                window.location.href='view_student_profile.php';
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
    <title>Student Profile</title>
    <style>
        * { box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background-color: #f9f9f9; padding: 40px; margin: 0; }
        form {
            max-width: 600px;
            margin: auto;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            text-align: center;
        }
        h2 { margin-bottom: 30px; text-align: center; }
        label { display: block; margin-top: 15px; font-weight: 600; text-align: left; }
        input[type="text"], input[type="email"], input[type="file"] {
            width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; margin-top: 5px;
        }
        input[disabled] { background-color: #e9ecef; cursor: not-allowed; }
        img {
            display: block; margin: 20px auto 10px;
            width: 150px; height: auto;
            border-radius: 6px; border: 1px solid #ccc;
        }
        button {
            width: 100%; padding: 10px;
            font-weight: 600; border: none;
            border-radius: 6px; margin-top: 10px;
            cursor: pointer;
        }
        .btn-primary { background-color: #007bff; color: white; }
        .btn-secondary { background-color: #6c757d; color: white; }
        .btn-danger { background-color: #dc3545; color: white; }
        .success { color: green; font-weight: bold; }
        .note { font-size: 0.9rem; color: #666; margin-top: 5px; }
        .btn-group { display: flex; flex-direction: column; gap: 10px; margin-top: 30px; }
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
    <h2>Student Profile</h2>

    <form method="POST" enctype="multipart/form-data">
        <img src="<?= $student['profile_picture'] ?? '' ?>" id="preview" style="<?= $student['profile_picture'] ? '' : 'display:none;' ?>">
        <p id="no-picture-note" style="<?= $student['profile_picture'] ? 'display:none;' : '' ?>">No profile picture uploaded.</p>
        <p id="upload-success" class="success" style="display:none;">Image successfully uploaded (not yet saved).</p>
        <p id="delete-success" class="success" style="display:none;">Image marked for deletion (will be removed on save).</p>

        <div id="upload-section" style="display:none;">
            <input type="file" name="profile_picture" accept="image/*" onchange="previewImage(event)">
            <input type="hidden" name="delete_existing_picture" id="delete_existing_picture" value="0">
            <button type="button" class="btn-secondary" onclick="removeImage()" id="remove_picture" style="display:none;">Remove Selected Picture</button>
            <?php if (!empty($student['profile_picture'])): ?>
                <button type="button" class="btn-danger" id="delete_current_picture" onclick="markDeletePicture()">Delete Current Picture</button>
            <?php endif; ?>
        </div>

        <label>Name</label>
        <input type="text" name="name" value="<?= $student['name'] ?>" disabled required>

        <label>Student Matric</label>
        <input type="text" name="student_matric" value="<?= $student['student_matric'] ?>" disabled required>

        <label>Email</label>
        <input type="email" name="email" value="<?= $student['email'] ?>" disabled required>

        <label>Program</label>
        <input type="text" name="program" value="<?= $student['program'] ?>" disabled required>

        <div class="btn-group">
            <button type="button" class="btn-primary" id="update_button" onclick="enableEdit()">Update Profile</button>
            <button type="submit" name="done" class="btn-primary" id="done_button" style="display:none;">Done</button>
            <a href="student_page.php"><button type="button" class="btn-secondary">Back to Dashboard</button></a>
        </div>
    </form>
</body>
</html>
