<?php
session_start();
include("db.php");

$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$genre = $_POST['genre'] ?? '';
$year_from = $_POST['year_from'] ?? '';
$year_to = $_POST['year_to'] ?? '';
$min_price = $_POST['min_price'] ?? '';
$max_price = $_POST['max_price'] ?? '';

$where = [];
$params = [];
$types = '';

if (!empty($title)) {
    $where[] = "title LIKE ?";
    $params[] = "%$title%";
    $types .= 's';
}

if (!empty($author)) {
    $where[] = "author LIKE ?";
    $params[] = "%$author%";
    $types .= 's';
}

if (!empty($genre)) {
    $where[] = "genre = ?";
    $params[] = $genre;
    $types .= 's';
}

if (!empty($year_from)) {
    $where[] = "publication_year >= ?";
    $params[] = $year_from;
    $types .= 'i';
}

if (!empty($year_to)) {
    $where[] = "publication_year <= ?";
    $params[] = $year_to;
    $types .= 'i';
}

if (!empty($min_price)) {
    $where[] = "price >= ?";
    $params[] = $min_price;
    $types .= 'd';
}

if (!empty($max_price)) {
    $where[] = "price <= ?";
    $params[] = $max_price;
    $types .= 'd';
}

$sql = "SELECT * FROM books";
if (!empty($where)) {
    $sql .= " WHERE " . implode(" AND ", $where);
}
$sql .= " ORDER BY publication_year DESC";

$stmt = $mysqli->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$results = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Advanced Search Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #000000;
            min-height: 100vh;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #495057, #6c757d);
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
        <h2>Advanced Search Results</h2>
        
        <div class="card border mb-4">
            <div class="card-body">
                <h6 class="text-muted">Search Criteria:</h6>
                <div class="row">
                    <?php if (!empty($title)): ?><div class="col-md-3"><span class="badge bg-primary me-1">Title</span> "<?= htmlspecialchars($title) ?>"</div><?php endif; ?>
                    <?php if (!empty($author)): ?><div class="col-md-3"><span class="badge bg-success me-1">Author</span> "<?= htmlspecialchars($author) ?>"</div><?php endif; ?>
                    <?php if (!empty($genre)): ?><div class="col-md-3"><span class="badge bg-info me-1">Genre</span> <?= htmlspecialchars($genre) ?></div><?php endif; ?>
                    <?php if (!empty($year_from) || !empty($year_to)): ?><div class="col-md-3"><span class="badge bg-warning me-1">Year</span> <?= $year_from ?>-<?= $year_to ?></div><?php endif; ?>
                    <?php if (!empty($min_price) || !empty($max_price)): ?><div class="col-md-3"><span class="badge bg-danger me-1">Price</span> £<?= $min_price ?>-£<?= $max_price ?></div><?php endif; ?>
                </div>
            </div>
        </div>
        
        <?php if($results && $results->num_rows > 0): ?>
            <div class="alert alert-success">
                Found <?= $results->num_rows ?> book(s) matching your criteria
            </div>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Book Title</th>
                            <th>Author</th>
                            <th>Genre</th>
                            <th>Year</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($book = $results->fetch_assoc()): ?>
                        <tr>
                            <td><strong><?= htmlspecialchars($book['title']) ?></strong></td>
                            <td><?= htmlspecialchars($book['author']) ?></td>
                            <td><span class="badge bg-primary"><?= htmlspecialchars($book['genre']) ?></span></td>
                            <td><?= $book['publication_year'] ?></td>
                            <td>£<?= $book['price'] ?></td>
                            <td>
                                <a href="book-details.php?id=<?= $book['id'] ?>" class="btn btn-outline-info btn-sm">View</a>
                                <?php if (isset($_SESSION['loggedin'])): ?>
                                    <a href="edit-book.php?id=<?= $book['id'] ?>" class="btn btn-outline-warning btn-sm">Edit</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                No books found matching your search criteria.
            </div>
        <?php endif; ?>
        
        <div class="mt-3">
            <a href="advanced-search.php" class="btn btn-primary">New Search</a>
            <a href="books.php" class="btn btn-secondary">Back to All Books</a>
        </div>
    </div>
</body>
</html>