<?php
$page_title = $page_title ?? 'My Books Collection';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
            color: #000000;
            min-height: 100vh;
        }
        .hero-section {
            background: linear-gradient(135deg, #6c757d 0%, #495057 50%, #343a40 100%);
            color: white;
            padding: 3rem 0;
            margin-bottom: 2rem;
            border-bottom: 3px solid #007bff;
        }
        .book-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 10px;
            background: linear-gradient(145deg, #ffffff, #f8f9fa);
            color: #000000;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(108, 117, 125, 0.3);
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
        
        /* Ajax Search Styles */
        .search-suggestions {
            border-radius: 5px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 1px solid #dee2e6 !important;
        }
        .search-result-item {
            transition: background-color 0.2s ease;
        }
        .search-result-item:hover {
            background: linear-gradient(135deg, #6c757d, #495057);
            color: white !important;
        }
        .search-result-item:hover small {
            color: #ccc !important;
        }
        .search-container {
            position: relative;
        }
        
        /* Navigation Styles */
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="books.php">📚 Books Collection</a>
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
                    <span class="navbar-text me-3">Welcome, <?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a class="nav-link" href="logout.php">Logout</a>
                <?php else: ?>
                    <a class="nav-link" href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if (!isset($hide_hero) || !$hide_hero): ?>
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1 class="display-4 fw-bold">📚 My Books Collection</h1>
                    <p class="lead">Discover and manage your favorite books</p>
                </div>
                <div class="col-lg-4 text-lg-end">
                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']): ?>
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
    <?php endif; ?>