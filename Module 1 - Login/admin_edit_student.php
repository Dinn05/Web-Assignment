<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='../Module 1 - Login/login.php'>login</a> as an administrator.</p>";
    exit();
}

if (!isset($_GET['student_id'])) {
    die("No student selected.");
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("DB connection failed");
$student_id = intval($_GET['student_id']);
$query = "SELECT * FROM student WHERE student_id = '$student_id'";
$result = mysqli_query($link, $query);
$student = mysqli_fetch_assoc($result);

if (!$student) {
    die("Student not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $name = mysqli_real_escape_string($link, $_POST['name']);
    $student_matric = mysqli_real_escape_string($link, $_POST['student_matric']);
    $email = mysqli_real_escape_string($link, $_POST['email']);
    $program = mysqli_real_escape_string($link, $_POST['program']);
    $profile_picture = $student['profile_picture'];

    // Handle image update or deletion
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

    $update = "UPDATE student SET name='$name', student_matric='$student_matric', email='$email', program='$program', profile_picture=" .
              ($profile_picture ? "'$profile_picture'" : "NULL") . " WHERE student_id = '$student_id'";

    if (mysqli_query($link, $update)) {
        echo "<script>alert('Student updated successfully'); window.location.href='../Module 1 - Login/view_student_registered.php';</script>";
        exit;
    } else {
        echo "Error updating: " . mysqli_error($link);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        img.preview {
            display: block;
            width: 120px;
            margin: 10px 0;
            border-radius: 6px;
        }

        h2{
            text-align:center;

        }

        body {
        background-color: #f8f9fa;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
    }

    .form-container {
        max-width: 500px;
        width: 100%;
    }
    </style>
</head>
<body class="p-4">
    <div class="container form-container bg-white p-4 shadow rounded">
    <h2>Edit Student</h2>
    <form method="POST" enctype="multipart/form-data" class="mt-4" style="max-width: 600px; margin: auto;">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($student['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Student Matric</label>
            <input type="text" name="student_matric" class="form-control" value="<?= htmlspecialchars($student['student_matric']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($student['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Program</label>
            <input type="text" name="program" class="form-control" value="<?= htmlspecialchars($student['program']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Profile Picture</label><br>
            <?php if (!empty($student['profile_picture'])): ?>
                <img src="<?= $student['profile_picture'] ?>" alt="Current" class="preview">
                <div class="form-check mb-2">
                    <input type="checkbox" class="form-check-input" name="delete_existing_picture" value="1" id="deletePicture">
                    <label class="form-check-label" for="deletePicture">Delete Current Picture</label>
                </div>
            <?php endif; ?>
            <input type="file" name="profile_picture" class="form-control" accept="image/*">
        </div>
        <button type="submit" name="update" class="btn btn-primary">Update Student</button>
        <a href="../Module 1 - Login/view_student_registered.php" class="btn btn-secondary">Cancel</a>
    </form>
    </div>
</body>
</html>
