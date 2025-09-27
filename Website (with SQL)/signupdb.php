<?php
session_start();
include 'cafedb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';
    $confirmPassword = isset($_POST['confirmPassword']) ? $_POST['confirmPassword'] : '';
    $role = isset($_POST['role']) ? $_POST['role'] : 'customer';
    $adminKey = isset($_POST['adminkey']) ? $_POST['adminkey'] : '';

    if ($username === '' || $email === '' || $password === '' || $confirmPassword === '') {
        die("Please fill in all fields.");
    }

    if ($password !== $confirmPassword) {
        die("Passwords do not match.");
    }

    if ($role === 'admin' && $adminKey !== 'ICTMAWD-111') {
        die("Invalid Admin Key.");
    }

    
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->close();
        die("Username already exists. Please choose another.");
    }
    $stmt->close();

    $hash = password_hash($password, PASSWORD_BCRYPT);

    
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hash, $role);

    if($stmt->execute()) {
        
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;

        header("Location: index.html?signup=success");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>
