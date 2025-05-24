<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='login.php'>login</a> as an administrator.</p>";
    exit();
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());
$result = mysqli_query($link, "SELECT * FROM student INNER JOIN login ON student.login_id = login.login_id WHERE login.role = 'student'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Student Registered</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; padding: 20px; }
        h2 { text-align: center; margin-bottom: 30px; }
        .btn-top { margin-bottom: 20px; display: flex; justify-content: space-between; }
        table { background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th { background-color: #343a40; color: #fff; }
        img { width: 50px; height: auto; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Registered Students</h2>
    <div class="btn-top">
        <a href="admin_page.php" class="btn btn-secondary">Return to Dashboard</a>
        <a href="../Module 1 - Login/admin_add_student.php" class="btn btn-success">+ Add New Student</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Profile Picture</th>
                <th>Full Name</th>
                <th>Matric</th>
                <th>Email</th>
                <th>Program</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td>
                    <?php if (!empty($row['profile_picture'])): ?>
                        <img src="<?= $row['profile_picture'] ?>" alt="Profile">
                    <?php else: ?>
                        <span>No Image</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['student_matric']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['program']) ?></td>
                <td>
                    <a href="../Module 1 - Login/admin_edit_student.php?student_id=<?= $row['student_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="../Module 1 - Login/admin_delete_student.php?student_id=<?= $row['student_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
