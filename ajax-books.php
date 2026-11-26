<?php
include("db.php");

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

if (!empty($search)) {
    $sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY title";
    $stmt = $mysqli->prepare($sql);
    $searchTerm = "%" . $search . "%";
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} else {
    $sql = "SELECT * FROM books ORDER BY title";
    $stmt = $mysqli->prepare($sql);
}

$stmt->execute();
$results = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

header('Content-Type: application/json');
echo json_encode($results);
?>