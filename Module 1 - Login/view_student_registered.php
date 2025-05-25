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
    body {
        background: linear-gradient(to right, #e0eafc, #cfdef3);
        padding: 40px;
        font-family: 'Segoe UI', sans-serif;
    }

    h2 {
        text-align: center;
        margin-bottom: 30px;
        color: #333;
        font-weight: bold;
    }

    .btn-top {
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn {
        border-radius: 25px;
        font-weight: 500;
        padding: 10px 20px;
    }

    .table-container {
        background: #ffffff;
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }

    table {
        margin: 0 auto;
    }

    table th {
        background-color: #4a90e2;
        color: white;
        text-align: center;
        font-size: 15px;
    }

    table td {
        vertical-align: middle;
        text-align: center;
    }

    img.profile-pic {
    width: 30%;
    height: 30%;
    object-fit: cover; /* Fill the box without distortion */
    display: block;
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 15px;
    border: 3px solid #007bff;
    border-radius: 5px; /* sharp corners */
}


    .btn-sm {
        border-radius: 20px;
        padding: 6px 12px;
        font-size: 14px;
    }

    @media (max-width: 768px) {
        .btn-top {
            flex-direction: column;
            align-items: center;
        }
    }
</style>
</head>
<body>
    <h2>Registered Students</h2>
    <div class="btn-top">
        <a href="admin_page.php" class="btn btn-secondary">Return to Dashboard</a>
        <a href="../Module 1 - Login/admin_add_student.php" class="btn btn-success">+ Add New Student</a>
    </div>

    <div class="table-container">
    <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-primary">
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
                            <img src="<?= $row['profile_picture'] ?>" alt="Profile" class="profile-pic">
                        <?php else: ?>
                            <span>No Image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['student_matric']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['program']) ?></td>
                    <td>
                        <div class="d-flex justify-content-center gap-2">
                            <a href="../Module 1 - Login/admin_edit_student.php?student_id=<?= $row['student_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="../Module 1 - Login/admin_delete_student.php?student_id=<?= $row['student_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this student?');">Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
