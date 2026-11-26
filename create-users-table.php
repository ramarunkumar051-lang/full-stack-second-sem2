<?php
include("db.php");

$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($mysqli->query($sql)) {
    echo "✅ Users table created successfully!<br>";
} else {
    echo "❌ Error creating table: " . $mysqli->error . "<br>";
}

$username = "admin";
$password = "password";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
$email = "admin@example.com";
$stmt->bind_param("sss", $username, $hashed_password, $email);

if ($stmt->execute()) {
    echo "✅ Admin user created successfully!<br>";
    echo "Username: <strong>admin</strong><br>";
    echo "Password: <strong>password</strong><br>";
    echo "<a href='login.php'>Go to Login Page</a>";
} else {
    echo "❌ Error creating user: " . $stmt->error . "<br>";
}
?>