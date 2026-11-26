<?php
include("db.php");

$mysqli->query("DROP TABLE IF EXISTS users");

$sql = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($mysqli->query($sql)) {
    echo "✅ Table 'users' created successfully.<br>";
} else {
    die("❌ Error creating table: " . $mysqli->error);
}

$password = "password";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
$username = 'admin';
$email = 'admin@example.com';
$stmt->bind_param("sss", $username, $hashed_password, $email);

if ($stmt->execute()) {
    echo "✅ User 'admin' created successfully.<br>";
    echo "✅ Password has been hashed: " . substr($hashed_password, 0, 10) . "...<br>";
    echo "✅ Username: admin<br>";
    echo "✅ Password: password<br>";
    echo "<h3><a href='login.php'>Go to Login Page</a></h3>";
} else {
    echo "❌ Error inserting user: " . $stmt->error;
}
?>