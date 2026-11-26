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
    <title>Ajax Books Modal</title>
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
            <a href="books.php" class="btn btn-outline-light">Back to Books</a>
        </div>
    </nav>

    <div class="container mt-4">
        <h1>Ajax Books Demo</h1>
        <p class="lead">Demonstrating Ajax with Bootstrap components</p>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Ajax Dropdown</h5>
            </div>
            <div class="card-body">
                <div class="dropdown" id="booksDropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Load Books via Ajax
                    </button>
                    <ul class="dropdown-menu" id="booksList"></ul>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Ajax Modal</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#booksModal">
                    Open Books Modal (Ajax)
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="booksModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Books Loaded via Ajax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <table class="table" id="ajaxBooksTable">
                        <thead>
                            <tr>
                                <th>Book Title</th>
                                <th>Author</th>
                                <th>Genre</th>
                            </tr>
                        </thead>
                        <tbody id="ajaxBooksBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    const booksDropdown = document.getElementById('booksDropdown');
    booksDropdown.addEventListener('show.bs.dropdown', function() {
        const booksList = document.getElementById('booksList');
        booksList.innerHTML = '<li><div class="dropdown-item-text"><div class="spinner-border spinner-border-sm"></div> Loading books...</div></li>';
        
        fetch('search-books.php')
            .then(response => response.json())
            .then(books => {
                booksList.innerHTML = '';
                
                if (books.length > 0) {
                    books.slice(0, 5).forEach(book => {
                        const li = document.createElement('li');
                        const a = document.createElement('a');
                        a.className = 'dropdown-item';
                        a.href = `book-details.php?id=${book.id}`;
                        a.textContent = book.title;
                        li.appendChild(a);
                        booksList.appendChild(li);
                    });
                } else {
                    booksList.innerHTML = '<li><a class="dropdown-item text-muted">No books in database</a></li>';
                }
            })
            .catch(error => {
                booksList.innerHTML = '<li><a class="dropdown-item text-danger">Error loading books</a></li>';
                console.error('Ajax error:', error);
            });
    });

    const booksModal = document.getElementById('booksModal');
    booksModal.addEventListener('show.bs.modal', function() {
        const booksBody = document.getElementById('ajaxBooksBody');
        booksBody.innerHTML = '<tr><td colspan="3" class="text-center"><div class="spinner-border"></div> Loading books...</td></tr>';
        
        fetch('search-books.php')
            .then(response => response.json())
            .then(books => {
                booksBody.innerHTML = '';
                books.slice(0, 5).forEach(book => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${book.title}</td>
                        <td>${book.author}</td>
                        <td>${book.genre}</td>
                    `;
                    booksBody.appendChild(row);
                });
            })
            .catch(error => {
                booksBody.innerHTML = '<tr><td colspan="3" class="text-center text-danger">Error loading books</td></tr>';
            });
    });
    </script>
</body>
</html>