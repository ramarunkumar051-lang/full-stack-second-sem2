<?php
session_start();

$valid_users = [
    'admin' => password_hash('password', PASSWORD_DEFAULT) 
];

function checkAuth() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
        header('Location: login.php');
        exit();
    }
}

function login($username, $password) {
    global $valid_users;
    
    if (isset($valid_users[$username]) && password_verify($password, $valid_users[$username])) {
        $_SESSION['user_id'] = $username;
        $_SESSION['logged_in'] = true;
        $_SESSION['login_time'] = time();
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit();
}

function filterInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function validateCaptcha($answer) {
    return $answer == '7'; // 3 + 4 = 7
}
?>