<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h1>Welcome Admin, <?php echo $_SESSION['username']; ?>!</h1>
    <p>You are logged in with admin privileges.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
