<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit;
}
include("db.php");

$id = $_GET['id'];
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

if (!$book) {
    die("Book not found");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book - <?= htmlspecialchars($book['title']) ?></title>
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
                <span class="navbar-text me-3">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
                <a class="nav-link" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Edit Book</h2>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="update-book.php" method="post" class="row g-3">
            <input type="hidden" name="id" value="<?= $book['id'] ?>">
            
            <div class="col-md-6">
                <label class="form-label">Book Title *</label>
                <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Author *</label>
                <input type="text" name="author" class="form-control" value="<?= htmlspecialchars($book['author']) ?>" required>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Genre *</label>
                <select name="genre" class="form-select" required>
                    <option value="">Select Genre</option>
                    <option value="Sci-Fi" <?= $book['genre'] == 'Sci-Fi' ? 'selected' : '' ?>>Sci-Fi</option>
                    <option value="Fantasy" <?= $book['genre'] == 'Fantasy' ? 'selected' : '' ?>>Fantasy</option>
                    <option value="Mystery" <?= $book['genre'] == 'Mystery' ? 'selected' : '' ?>>Mystery</option>
                    <option value="Romance" <?= $book['genre'] == 'Romance' ? 'selected' : '' ?>>Romance</option>
                    <option value="Thriller" <?= $book['genre'] == 'Thriller' ? 'selected' : '' ?>>Thriller</option>
                    <option value="Biography" <?= $book['genre'] == 'Biography' ? 'selected' : '' ?>>Biography</option>
                    <option value="History" <?= $book['genre'] == 'History' ? 'selected' : '' ?>>History</option>
                    <option value="Children" <?= $book['genre'] == 'Children' ? 'selected' : '' ?>>Children</option>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Publication Year *</label>
                <input type="number" name="publication_year" class="form-control" 
                       value="<?= $book['publication_year'] ?>" min="1900" max="2024" required>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">Price (£)</label>
                <input type="number" name="price" class="form-control" 
                       value="<?= $book['price'] ?>" step="0.01" min="0">
            </div>
            
            <div class="col-12">
                <label class="form-label">ISBN</label>
                <input type="text" name="isbn" class="form-control" value="<?= htmlspecialchars($book['isbn']) ?>">
            </div>
            
            <div class="col-12">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($book['description']) ?></textarea>
            </div>
            
            <div class="col-12">
                <button type="submit" class="btn btn-warning">Update Book</button>
                <a href="books.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>