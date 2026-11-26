<?php include("db.php"); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Advanced Book Search</title>
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
        </div>
    </nav>

    <div class="container mt-4">
        <h2>📚 Advanced Book Search</h2>
        <form method="post" action="search-books.php">
            <div class="row g-3">
                <div class="col-md-4">
                    <label>Book Title:</label>
                    <input type="text" name="title" class="form-control" placeholder="Search by title">
                </div>
                <div class="col-md-4">
                    <label>Author:</label>
                    <input type="text" name="author" class="form-control" placeholder="Search by author">
                </div>
                <div class="col-md-4">
                    <label>Genre:</label>
                    <select name="genre" class="form-select">
                        <option value="">All Genres</option>
                        <option value="Sci-Fi">Sci-Fi</option>
                        <option value="Fantasy">Fantasy</option>
                        <option value="Mystery">Mystery</option>
                        <option value="Romance">Romance</option>
                        <option value="Thriller">Thriller</option>
                        <option value="Biography">Biography</option>
                        <option value="History">History</option>
                        <option value="Children">Children</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Publication Year From:</label>
                    <input type="number" name="year_from" class="form-control" min="1900" max="2024" placeholder="1900">
                </div>
                <div class="col-md-3">
                    <label>Publication Year To:</label>
                    <input type="number" name="year_to" class="form-control" min="1900" max="2024" placeholder="2024">
                </div>
                <div class="col-md-3">
                    <label>Min Price (£):</label>
                    <input type="number" name="min_price" class="form-control" min="0" step="0.01" placeholder="0.00">
                </div>
                <div class="col-md-3">
                    <label>Max Price (£):</label>
                    <input type="number" name="max_price" class="form-control" min="0" step="0.01" placeholder="50.00">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">🔍 Search Books</button>
            <a href="books.php" class="btn btn-secondary mt-3">← Back to All Books</a>
        </form>
    </div>
</body>
</html>