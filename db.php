<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$mysqli = new mysqli("localhost","2444582","arun1234","db2444582");
if ($mysqli->connect_errno) {
    error_log("Database connection failed");
    die("Connection error");
}

$mysqli->set_charset("utf8mb4");

function filterInput($data) {
    if (is_array($data)) {
        return array_map('filterInput', $data);
    }
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

function validateCaptcha($answer) {
    $expected = 7; // 3 + 4
    return intval($answer) === $expected;
}

function safeQuery($mysqli, $sql, $params = []) {
    $stmt = $mysqli->prepare($sql);
    if (!$stmt) return false;
    
    if (!empty($params)) {
        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }
        $stmt->bind_param($types, ...$params);
    }
    
    $stmt->execute();
    return $stmt->get_result();
}

function checkAuth() {
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
        header('Location: login.php');
        exit();
    }
}

function loginUser($mysqli, $username, $password) {
    $stmt = $mysqli->prepare("SELECT id, username, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (password_verify($password, $row['password'])) {
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['user_id'] = $row['id'];
            return true;
        }
    }
    return false;
}

function logoutUser() {
    session_destroy();
    header('Location: login.php');
    exit();
}
?>