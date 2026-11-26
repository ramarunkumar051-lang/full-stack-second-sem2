<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit;
}
include("db.php");

$title = isset($_POST['title']) ? htmlspecialchars(trim($_POST['title']), ENT_QUOTES, 'UTF-8') : '';
$author = isset($_POST['author']) ? htmlspecialchars(trim($_POST['author']), ENT_QUOTES, 'UTF-8') : '';
$genre = isset($_POST['genre']) ? htmlspecialchars(trim($_POST['genre']), ENT_QUOTES, 'UTF-8') : '';
$publication_year = isset($_POST['publication_year']) ? intval($_POST['publication_year']) : '';
$isbn = isset($_POST['isbn']) ? htmlspecialchars(trim($_POST['isbn']), ENT_QUOTES, 'UTF-8') : '';
$description = isset($_POST['description']) ? htmlspecialchars(trim($_POST['description']), ENT_QUOTES, 'UTF-8') : '';
$price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

$errors = [];

if (empty($title)) $errors[] = "Book title is required";
if (empty($author)) $errors[] = "Author name is required";
if (empty($genre)) $errors[] = "Genre is required";
if (empty($publication_year) || $publication_year < 1900 || $publication_year > date('Y')) {
    $errors[] = "Valid publication year (1900-" . date('Y') . ") is required";
}
if (empty($description)) $errors[] = "Book description is required";
if ($price < 0) $errors[] = "Price cannot be negative";

if (empty($errors)) {
    $sql = "INSERT INTO books (title, author, genre, publication_year, isbn, description, price) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("sssisdd", $title, $author, $genre, $publication_year, $isbn, $description, $price);

    if($stmt->execute()) {
        $_SESSION['success_message'] = "Book added successfully!";
        header("location: books.php");
    } else {
        $_SESSION['error_message'] = "Error adding book: " . $mysqli->error;
        header("location: books.php");
    }
} else {
    $_SESSION['error_message'] = implode(", ", $errors);
    header("location: add-book-form.php");
}
?>