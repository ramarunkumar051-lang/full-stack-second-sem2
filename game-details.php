<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit;
}

include("db.php");
require_once 'vendor/autoload.php';

if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
    die("Invalid book ID");
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$book = $result->fetch_assoc();

if (!$book) {
    die("Book not found");
}

$stmt->close();

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

echo $twig->render('book-details.html.twig', [
    'session' => $_SESSION,
    'book' => $book
]);
?>