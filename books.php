<?php
session_start();
include("db.php");

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $sql = "DELETE FROM books WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Book deleted successfully";
    } else {
        $_SESSION['error_message'] = "Error deleting book";
    }
    header("location: books.php");
    exit;
}

$page_title = "My Books Collection";
$include_ajax = true;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
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
        .genre-badge {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: #ffffff;
            font-weight: bold;
        }
        .search-container {
            position: relative;
        }
        .search-suggestions {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            max-height: 300px;
            overflow-y: auto;
            z-index: 1000;
            color: #000000;
        }
        .search-result-item:hover {
            background-color: #f8f9fa;
        }
        .table {
            color: #000000;
        }
        .text-muted {
            color: #6c757d !important;
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

    <section class="hero-section" style="background: linear-gradient(135deg, #6c757d 0%, #495057 50%, #343a40 100%); color: white; padding: 3rem 0; margin-bottom: 2rem; border-bottom: 3px solid #007bff;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold">📚 My Books Collection</h1>
                    <p class="lead">Discover and manage your favorite books</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <?php if (isset($_SESSION['loggedin'])): ?>
                        <a href="add-book-form.php" class="btn btn-light btn-lg me-2">
                            <i class="bi bi-plus-circle"></i> Add New Book
                        </a>
                        <a href="ajax-books.php" class="btn btn-outline-light btn-lg">
                            <i class="bi bi-lightning"></i> Ajax Demo
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8 mx-auto">
                <div class="search-container">
                    <form action="search-books.php" method="post" class="d-flex" id="searchForm">
                        <input type="text" name="keywords" class="form-control form-control-lg me-2" 
                               placeholder="Search books or authors..." id="liveSearchInput"
                               autocomplete="off">
                        <button type="submit" class="btn btn-primary-custom btn-lg">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </form>
                </div>
                <div class="text-center mt-2">
                    <a href="search-books.php" class="text-dark">🔍 Advanced Search</a>
                </div>
                <div class="text-center mt-1">
                    <small class="text-muted">Try live search - results appear as you type!</small>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0 text-white">
                    <i class="bi bi-book"></i> All Books
                    <span class="badge bg-light text-dark ms-2">
                        <?php
                        $count_sql = "SELECT COUNT(*) as total FROM books";
                        $count_result = $mysqli->query($count_sql);
                        $count_row = $count_result->fetch_assoc();
                        echo $count_row['total'];
                        ?>
                    </span>
                </h3>
            </div>
            <div class="card-body">
                <?php
                if (isset($_SESSION['success_message'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success_message'] ?></div>
                    <?php unset($_SESSION['success_message']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error_message'] ?></div>
                    <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>

                <?php
                $sql = "SELECT * FROM books ORDER BY publication_year DESC";
                $results = $mysqli->query($sql);
                
                if($results && $results->num_rows > 0): ?>
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
                                <td>
                                    <i class="bi bi-book me-2"></i>
                                    <strong><?= htmlspecialchars($book['title']) ?></strong>
                                </td>
                                <td><?= htmlspecialchars($book['author']) ?></td>
                                <td>
                                    <span class="badge genre-badge">
                                        <?= htmlspecialchars($book['genre']) ?>
                                    </span>
                                </td>
                                <td><?= $book['publication_year'] ?></td>
                                <td>£<?= $book['price'] ?></td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="book-details.php?id=<?= $book['id'] ?>" class="btn btn-outline-info">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="edit-book.php?id=<?= $book['id'] ?>" class="btn btn-warning">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <a href="books.php?delete_id=<?= $book['id'] ?>" class="btn btn-outline-danger" 
                                           onclick="return confirm('Are you sure you want to delete <?= htmlspecialchars($book['title']) ?>?')">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-5">
                    <i class="bi bi-emoji-frown display-1 text-muted"></i>
                    <h3 class="text-muted mt-3">No books found</h3>
                    <p class="text-muted">Start by adding your first book to the collection!</p>
                    <?php if (isset($_SESSION['loggedin'])): ?>
                        <a href="add-book-form.php" class="btn btn-primary-custom">
                            <i class="bi bi-plus-circle"></i> Add Your First Book
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary-custom">
                            <i class="bi bi-person"></i> Login to Add Books
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <?php if (isset($include_ajax) && $include_ajax): ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('liveSearchInput');
        if (!searchInput) return;
        
        const searchResults = document.createElement('div');
        searchResults.className = 'search-suggestions position-absolute';
        searchResults.style.display = 'none';
        searchResults.style.zIndex = '1000';
        searchResults.style.maxHeight = '300px';
        searchResults.style.overflowY = 'auto';
        
        searchInput.parentNode.appendChild(searchResults);

        let debounceTimer;
        function debounce(callback, delay) {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(callback, delay);
        }

        function showLoading() {
            searchResults.innerHTML = '<div class="p-2 text-muted"><i class="bi bi-hourglass-split"></i> Searching books...</div>';
            searchResults.style.display = 'block';
        }

        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            if (query.length < 2) {
                searchResults.style.display = 'none';
                return;
            }

            showLoading();
            
            debounce(() => {
                fetch(`ajax-search.php?q=${encodeURIComponent(query)}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(books => {
                        displaySearchResults(books);
                    })
                    .catch(error => {
                        console.error('Search error:', error);
                        searchResults.innerHTML = '<div class="p-2 text-danger">Search failed. Please try again.</div>';
                        searchResults.style.display = 'block';
                    });
            }, 300);
        });

        function displaySearchResults(books) {
            if (!books || books.length === 0) {
                searchResults.innerHTML = '<div class="p-2 text-muted">No books found</div>';
                searchResults.style.display = 'block';
                return;
            }

            searchResults.innerHTML = books.map(book => `
                <a href="book-details.php?id=${book.id}" 
                   class="search-result-item d-block p-2 text-dark text-decoration-none border-bottom">
                    <div class="fw-bold">${book.title}</div>
                    <small class="text-muted">by ${book.author} • ${book.genre} • ${book.publication_year}</small>
                </a>
            `).join('');

            searchResults.style.display = 'block';
        }

        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.style.display = 'none';
            }
        });

        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                searchResults.style.display = 'none';
                searchInput.blur();
            }
        });
    });
    </script>
    <?php endif; ?>
</body>
</html>