<?php
include("db.php");

header('Content-Type: application/json');

$searchTerm = $_GET['q'] ?? '';

if (empty($searchTerm) || strlen($searchTerm) < 2) {
    echo json_encode([]);
    exit;
}

$sql = "SELECT id, title, author, genre, publication_year FROM books 
        WHERE title LIKE ? OR author LIKE ? 
        ORDER BY publication_year DESC 
        LIMIT 10";
$search_param = "%$searchTerm%";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $search_param, $search_param);
$stmt->execute();
$results = $stmt->get_result();

$books = [];
if ($results) {
    while ($book = $results->fetch_assoc()) {
        $books[] = [
            'id' => $book['id'],
            'title' => htmlspecialchars($book['title']),
            'author' => htmlspecialchars($book['author']),
            'genre' => htmlspecialchars($book['genre']),
            'publication_year' => $book['publication_year']
        ];
    }
}

echo json_encode($books);
?>