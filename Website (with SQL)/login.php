<?php
session_start();
include 'cafedb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($username === '' || $password === '') {
        die("Please fill in all fields.");
    }

    // Get user info from database
    $stmt = $conn->prepare("SELECT password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        $stmt->close();
        die("Username does not exist.");
    }

    $stmt->bind_result($hashedPassword, $role);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
        // Login successful
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        // Redirect based on role
        if ($role === 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: user_dashboard.php");
        }
        exit;
    } else {
        echo "Incorrect password.";
    }

    $stmt->close();
}
$conn->close();
?>
