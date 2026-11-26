<?php
include("db.php");

$username = "admin";
$password = "password"; 

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $mysqli->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hashed_password);

if ($stmt->execute()) {
    echo "User 'admin' created successfully with hashed password!";
    echo "<br>Username: admin";
    echo "<br>Password: password";
} else {
    echo "Error: " . $stmt->error;
}
?>