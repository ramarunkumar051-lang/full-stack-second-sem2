<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #000000;
            min-height: 100vh;
        }
        .card {
            background: linear-gradient(135deg, #ffffff, #f8f9fa);
            border: 1px solid #dee2e6;
            color: #000000;
        }
        .card-header {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white;
        }
        .btn-primary-custom {
            background: linear-gradient(135deg, #6c757d, #495057);
            border: none;
            color: white;
        }
        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #495057, #6c757d);
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Book</h3>
                    </div>
                    <div class="card-body">
                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <form action="add-book.php" method="post">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Book Title *</label>
                                    <input type="text" name="title" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Author *</label>
                                    <input type="text" name="author" class="form-control" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Genre *</label>
                                    <select name="genre" class="form-select" required>
                                        <option value="">Select Genre</option>
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
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Publication Year *</label>
                                    <input type="number" name="publication_year" class="form-control" min="1900" max="2024" required>
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">Price (£)</label>
                                    <input type="number" name="price" class="form-control" step="0.01" min="0">
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">ISBN</label>
                                <input type="text" name="isbn" class="form-control" placeholder="Optional">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Description *</label>
                                <textarea name="description" class="form-control" rows="4" required></textarea>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <a href="books.php" class="btn btn-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary-custom">Add Book</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>