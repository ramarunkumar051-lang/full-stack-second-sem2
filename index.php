<?php
session_start();

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
    header("Location: books.php");
    exit;
}

header("Location: login.php");
exit;
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Ajax Book Search</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
    body {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        color: #000000;
        min-height: 100vh;
    }
    .search-result {
        padding: 10px;
        border-bottom: 1px solid #dee2e6;
        cursor: pointer;
        color: #000000;
    }
    .search-result:hover {
        background-color: #f8f9fa;
    }
    #results {
        max-height: 400px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        margin-top: 10px;
        background: white;
    }
</style>
</head>
<body>
<div class="container mt-4">
    <h1>Book Search</h1>
    <form class="row g-3">
        <div class="col-md-6">
            <input type="text" class="form-control" id="searchBox" placeholder="Search books by title or author...">
        </div>
    </form>
    <div id="results"></div>
</div>

<script>
document.getElementById("searchBox").addEventListener("keyup", doSearch);

function doSearch() {
    const keywords = document.getElementById("searchBox").value;
    const resultsDiv = document.getElementById("results");
    
    if (keywords.length < 2) {
        resultsDiv.innerHTML = '';
        return;
    }
    
    resultsDiv.innerHTML = '<div class="text-center p-3"><div class="spinner-border spinner-border-sm"></div> Searching...</div>';
    
    fetch('https://mi-linux.wlv.ac.uk/~2439648/search-books.php?search=' + encodeURIComponent(keywords))
    .then(response => response.json())
    .then(books => {
        resultsDiv.innerHTML = '';
        
        if (books.length === 0) {
            resultsDiv.innerHTML = '<div class="text-center p-3 text-muted">No books found</div>';
            return;
        }
        
        books.forEach(book => {
            const resultDiv = document.createElement('div');
            resultDiv.className = 'search-result';
            resultDiv.innerHTML = `
                <strong>${book.title}</strong><br>
                <small class="text-muted">by ${book.author}</small><br>
                <small class="text-muted">${book.genre} • ${book.publication_year}</small>
            `;
            resultDiv.addEventListener('click', function() {
                window.location.href = `book-details.php?id=${book.id}`;
            });
            resultsDiv.appendChild(resultDiv);
        });
    })
    .catch(error => {
        resultsDiv.innerHTML = '<div class="text-center p-3 text-danger">Error searching books</div>';
        console.error('Search error:', error);
    });
}
</script>
</body>
</html>