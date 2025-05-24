<?php
session_start();

if (!isset($_SESSION['Login']) || $_SESSION['Login'] !== "YES" || $_SESSION['role'] !== 'administrator') {
    echo "<h1>Access Denied</h1><p>You must <a href='login.php'>login</a> as an administrator.</p>";
    exit();
}

$link = mysqli_connect("localhost", "root", "", "mypetakom") or die("Connection failed: " . mysqli_connect_error());
$result = mysqli_query($link, "SELECT * FROM staff INNER JOIN login ON staff.login_id = login.login_id WHERE login.role = 'event_advisor'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Event Advisor Registered</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f0f2f5; padding: 20px; }
        h2 { text-align: center; margin-bottom: 30px; }
        .btn-top { margin-bottom: 20px; display: flex; justify-content: space-between; }
        table { background-color: #fff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        th { background-color: #343a40; color: #fff; text-align: center; }
        td { vertical-align: middle; text-align: center; }
        img.profile-pic {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            display: block;
            margin: auto;
        }
    </style>
</head>
<body>
    <h2>Registered Event Advisors</h2>
    <div class="btn-top">
        <a href="admin_page.php" class="btn btn-secondary">Return to Dashboard</a>
        <a href="../Module 1 - Login/admin_add_advisor.php" class="btn btn-success">+ Add New Advisor</a>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Profile Picture</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone Number</th>
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
                <td><?= htmlspecialchars($row['fullname']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone_num']) ?></td>
                <td>
                    <a href="../Module 1 - Login/admin_edit_advisor.php?staff_id=<?= $row['staff_id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="../Module 1 - Login/admin_delete_advisor.php?staff_id=<?= $row['staff_id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this advisor?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
