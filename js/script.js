// This function will be responsible for fetching and loading our HTML partials.
function loadComponent(url, placeholderId) {
    // Use the fetch API to get the content of the HTML file
    fetch(url)
        .then(response => {
            // Check if the request was successful
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            // Convert the response to text
            return response.text();
        })
        .then(data => {
            // Select the placeholder element by its ID and inject the fetched HTML
            document.getElementById(placeholderId).innerHTML = data;
        })
        .catch(error => {
            // Log an error message to the console if the fetch fails
            console.error('Error loading component:', error);
            // Optionally, display an error message in the placeholder
            document.getElementById(placeholderId).innerHTML = '<p class="text-danger text-center">Error loading content.</p>';
        });
}

// This event listener waits for the entire HTML document to be loaded and parsed
// before running our script. This is a crucial step to prevent errors.
document.addEventListener("DOMContentLoaded", function() {
    // Load the header into the placeholder with the ID "header-placeholder"
    loadComponent('_includes/header.html', 'header-placeholder');

    // Load the footer into the placeholder with the ID "footer-placeholder"
    loadComponent('_includes/footer.html', 'footer-placeholder');
});