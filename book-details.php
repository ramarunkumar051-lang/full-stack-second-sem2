<?php
include("db.php");

$id = $_GET['id'];
$sql = "SELECT * FROM books WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$book = $stmt->get_result()->fetch_assoc();

$page_title = htmlspecialchars($book['title']) . " - Details";
?>
<?php include('header.php'); ?>

<div class="container mt-4">
    <a href="books.php" class="btn btn-secondary mb-3">← Back to Books</a>
    
    <div class="card">
        <div class="card-body">
            <h2 class="card-title"><?= htmlspecialchars($book['title']) ?></h2>
            <p class="lead">by <?= htmlspecialchars($book['author']) ?></p>
            
            <div class="row mt-4">
                <div class="col-md-6">
                    <p><strong>Genre:</strong> <span class="badge bg-primary"><?= htmlspecialchars($book['genre']) ?></span></p>
                    <p><strong>Publication Year:</strong> <?= htmlspecialchars($book['publication_year']) ?></p>
                    <p><strong>Price:</strong> £<?= htmlspecialchars($book['price']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?: 'N/A' ?></p>
                    <p><strong>Added Date:</strong> <?= date('F j, Y', strtotime($book['created_at'])) ?></p>
                </div>
            </div>
            
            <div class="mt-4">
                <h5>Description:</h5>
                <p><?= nl2br(htmlspecialchars($book['description'])) ?></p>
            </div>
        </div>
    </div>
    
    <div class="mt-3">
        <a href="edit-book.php?id=<?= $book['id'] ?>" class="btn btn-warning">Edit Book</a>
        <a href="books.php?delete_id=<?= $book['id'] ?>" class="btn btn-danger" 
           onclick="return confirm('Are you sure you want to delete this book?')">Delete Book</a>
    </div>
</div>

<?php include('footer.php'); ?>