// Function to set the active navigation link
function setActiveNavLink() {
    const currentPage = window.location.pathname.split("/").pop();
    const navLinks = document.querySelectorAll('#navbarNav .nav-link');

    navLinks.forEach(link => {
        const linkPage = link.getAttribute('href');
        link.classList.remove('active');
        link.removeAttribute('aria-current');

        // Set 'index.html' or empty path as the root page
        if (linkPage === currentPage || (currentPage === '' && linkPage === 'index.html')) {
            link.classList.add('active');
            link.setAttribute('aria-current', 'page');
        }
    });
}

// Function to load HTML components
function loadComponent(url, placeholderId, callback) {
    fetch(url)
        .then(response => response.ok ? response.text() : Promise.reject('Network response was not ok'))
        .then(data => {
            document.getElementById(placeholderId).innerHTML = data;
            if (callback) {
                callback(); // Execute the callback after loading
            }
        })
        .catch(error => {
            console.error('Error loading component:', error);
            document.getElementById(placeholderId).innerHTML = '<p class="text-danger text-center">Error loading content.</p>';
        });
}

// Main DOMContentLoaded event listener
document.addEventListener("DOMContentLoaded", function() {
    // Load header and then set the active link
    loadComponent('_includes/header.html', 'header-placeholder', setActiveNavLink);
    loadComponent('_includes/footer.html', 'footer-placeholder');
});