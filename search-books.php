<?php
session_start();
include("db.php");

$keywords = $_POST['keywords'] ?? '';

$sql = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY publication_year DESC";
$search_term = "%$keywords%";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("ss", $search_term, $search_term);
$stmt->execute();
$results = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #000000;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="books.php">📚 Books Collection</a>
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['loggedin'])): ?>
                    <span class="navbar-text me-3">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Search Results for "<?= htmlspecialchars($keywords) ?>"</h2>
        
        <?php if($results && $results->num_rows > 0): ?>
            <p>Found <?= $results->num_rows ?> book(s)</p>
            <div class="list-group">
                <?php while($book = $results->fetch_assoc()): ?>
                <a href="book-details.php?id=<?= $book['id'] ?>" class="list-group-item list-group-item-action">
                    <strong><?= htmlspecialchars($book['title']) ?></strong> by <?= htmlspecialchars($book['author']) ?>
                    <span class="badge bg-primary float-end"><?= $book['genre'] ?></span>
                    <br>
                    <small class="text-muted">Published: <?= $book['publication_year'] ?> | Price: £<?= $book['price'] ?></small>
                </a>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                No books found matching your search.
            </div>
        <?php endif; ?>
        
        <a href="books.php" class="btn btn-primary mt-3">Back to All Books</a>
    </div>
</body>
</html>