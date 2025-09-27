<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();
include 'cafedb.php';
$adminUser = 'admin';
$adminPass = 'ICTMAWD111';

$stmt = $conn->prepare("SELECT id FROM users WHERE username=?");
$stmt->bind_param("s", $adminUser);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    echo "Admin already exists.";
    exit;
}
$stmt->close();

$hash = password_hash($adminPass, PASSWORD_BCRYPT);
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'admin')");
$stmt->bind_param("ss", $adminUser, $hash);

if ($stmt->execute()) {
    $adminId = $stmt->insert_id;
    $_SESSION['user_id'] = $adminId;
    $_SESSION['username'] = $adminUser;
    $_SESSION['role'] = 'admin';
    header("Location: admin_dashboard.php");
    exit;
} else {
    echo "Error: " . $stmt->error;
}
$stmt->close();
?>
