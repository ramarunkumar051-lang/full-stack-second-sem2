<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
<?php if (isset($include_ajax) && $include_ajax): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('liveSearchInput');
    if (!searchInput) return;
    
    const searchResults = document.createElement('div');
    searchResults.className = 'search-suggestions position-absolute bg-white border mt-1 w-100 shadow';
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
            fetch(`https://mi-linux.wlv.ac.uk/~2439648/search-books.php?search=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(books => {
                    displaySearchResults(books);
                })
                .catch(error => {
                    console.error('Search error:', error);
                    searchResults.innerHTML = '<div class="p-2 text-danger">Search failed</div>';
                });
        }, 300);
    });

    function displaySearchResults(books) {
        if (books.length === 0) {
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