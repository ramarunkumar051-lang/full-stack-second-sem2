<?php
session_start();
if (isset($_SESSION['loggedin'])) {
    header("location: books.php");
    exit;
}

require_once 'vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$error_code = isset($_GET['error']) ? intval($_GET['error']) : 0;

echo $twig->render('login.html.twig', [
    'session' => $_SESSION,
    'error_code' => $error_code
]);
?>