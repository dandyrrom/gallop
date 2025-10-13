// Function to load HTML components
function loadComponent(url, placeholderId) {
    fetch(url)
        .then(response => response.ok ? response.text() : Promise.reject('Network response was not ok'))
        .then(data => {
            document.getElementById(placeholderId).innerHTML = data;
        })
        .catch(error => {
            console.error('Error loading component:', error);
            document.getElementById(placeholderId).innerHTML = '<p class="text-danger text-center">Error loading content.</p>';
        });
}

// Main DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function() {
    loadComponent('_includes/header.html', 'header-placeholder');
    loadComponent('_includes/footer.html', 'footer-placeholder');

    // REMOVED the scroll event listener as it is no longer needed.
});