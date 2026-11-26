<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit;
}
include("db.php");

$id = $_GET['id'];
$sql = "DELETE FROM books WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);

if($stmt->execute()) {
    $_SESSION['success_message'] = "Book deleted successfully";
    header("location: books.php");
} else {
    $_SESSION['error_message'] = "Error deleting book: " . $mysqli->error;
    header("location: books.php");
}
?>