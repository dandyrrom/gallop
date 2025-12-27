const sidebarLinks = document.querySelectorAll('.sidebar-nav .nav-link[data-section]');
const contentSections = document.querySelectorAll('.content-section');
const profileDisplay = document.getElementById('profile-display');
const profileEditForm = document.getElementById('profile-edit-form');
const editProfileButton = document.getElementById('edit-profile-button');
const profileSuccessMessage = document.getElementById('profile-success-message');
const profileErrorMessage = document.getElementById('profile-error-message');

function navigateToSection(sectionId) {
    // Hide all content sections
    contentSections.forEach(section => section.classList.remove('active'));
    // Deactivate all sidebar links that have data-section
    sidebarLinks.forEach(link => link.classList.remove('active'));

    // Show the target section
    const targetSection = document.getElementById(sectionId);
    if (targetSection) {
        targetSection.classList.add('active');
    }

    // Activate the corresponding sidebar link
    const targetLink = document.querySelector(`.sidebar-nav .nav-link[data-section="${sectionId}"]`);
    if (targetLink) {
        targetLink.classList.add('active');
    }

     // Reset profile view if navigating away from profile edit
    if (sectionId !== 'profile' && !profileEditForm.classList.contains('d-none')) {
        toggleEditProfile(false);
    }
    // Set minimum date for booking date input to today
    if(sectionId === 'book') {
        const dateInput = document.getElementById('book-date');
        if (dateInput && !dateInput.value) { // Set only if empty
             dateInput.min = new Date().toISOString().split('T')[0];
             dateInput.value = new Date().toISOString().split('T')[0]; // Default to today
        }
    }
}

// Add click listeners to sidebar links
sidebarLinks.forEach(link => {
    link.addEventListener('click', (event) => {
        const sectionId = event.currentTarget.getAttribute('data-section');
        if (sectionId) {
            navigateToSection(sectionId);
        }
    });
});

 // Function to toggle between profile display and edit form
function toggleEditProfile(editing) {
    profileSuccessMessage.classList.add('d-none'); // Hide messages on toggle
    profileErrorMessage.classList.add('d-none');
    if (editing) {
        // Populate form with current display values before showing
        document.getElementById('profile-fname').value = profileDisplay.querySelector('[data-field="fname"]').textContent;
        document.getElementById('profile-lname').value = profileDisplay.querySelector('[data-field="lname"]').textContent;
        document.getElementById('profile-email').value = profileDisplay.querySelector('[data-field="email"]').textContent;
        document.getElementById('profile-contact').value = profileDisplay.querySelector('[data-field="contact"]').textContent;
        // Username is readonly, no need to update input from display

        profileDisplay.classList.add('d-none');
        profileEditForm.classList.remove('d-none');
        editProfileButton.classList.add('d-none'); // Hide Edit button
    } else {
        profileDisplay.classList.remove('d-none');
        profileEditForm.classList.add('d-none');
         editProfileButton.classList.remove('d-none'); // Show Edit button
    }
}

// Mock saving profile data
profileEditForm.addEventListener('submit', (event) => {
     event.preventDefault(); // Prevent actual form submission
     profileSuccessMessage.classList.add('d-none');
     profileErrorMessage.classList.add('d-none');

    // Simulate API call delay
    setTimeout(() => {
        // Mock success/failure randomly (replace with actual logic)
        const isSuccess = Math.random() > 0.2; // 80% chance of success

        if(isSuccess) {
            // Update display elements with form values
            profileDisplay.querySelector('[data-field="fname"]').textContent = document.getElementById('profile-fname').value;
            profileDisplay.querySelector('[data-field="lname"]').textContent = document.getElementById('profile-lname').value;
            profileDisplay.querySelector('[data-field="email"]').textContent = document.getElementById('profile-email').value;
            profileDisplay.querySelector('[data-field="contact"]').textContent = document.getElementById('profile-contact').value;

            profileSuccessMessage.classList.remove('d-none');
            // Switch back to display view after a short delay
            setTimeout(() => toggleEditProfile(false), 1500);
        } else {
            profileErrorMessage.classList.remove('d-none');
        }
    }, 500); // 0.5 second delay
});

// Initialize view
document.addEventListener('DOMContentLoaded', () => {
     // Set default active section if needed (e.g., from URL hash or default to 'overview')
     const initialSection = window.location.hash ? window.location.hash.substring(1) : 'overview';
     navigateToSection(initialSection);
});