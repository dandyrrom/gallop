<?php
session_start(); // Start the session at the very beginning
require_once 'db/db_connect.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get user details from session
$userId = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username'] ?? 'User');
$fname = htmlspecialchars($_SESSION['fname'] ?? '');
$lname = htmlspecialchars($_SESSION['lname'] ?? '');
$email = htmlspecialchars($_SESSION['email'] ?? '');
$contactNum = htmlspecialchars($_SESSION['contactNum'] ?? '');
$role = htmlspecialchars($_SESSION['role'] ?? 'user');

// Fetch facilities for the dropdown
$facilities = [];
$facility_sql = "SELECT facilityId, name FROM facility ORDER BY name ASC";
$facility_result = $conn->query($facility_sql);
if ($facility_result && $facility_result->num_rows > 0) {
    while ($row = $facility_result->fetch_assoc()) {
        $facilities[] = $row;
    }
}
// Note: It's good practice to close the connection if it's no longer needed,
// but since we might need it later on the page (e.g., for bookings), we'll leave it open for now.
// $conn->close(); // Example if closing

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest Dashboard - Gallop</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Base Site Styles -->
    <link rel="stylesheet" href="css/style.css">

    <!-- Component Styles -->
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">

    <!-- Page-Specific Styles -->
    <link rel="stylesheet" href="css/dashboard.css">

    <!-- Favicon -->
    <link rel="icon" href="assets/horse-head-faviconv.png" type="image/png">

</head>
<body>

    <!-- Include the specific dashboard header -->
    <?php include '_includes/header_dashboard.php'; ?>

    <div class="dashboard-page-sidebar">
        <!-- Sidebar Navigation (VK Structure) -->
        <aside class="dashboard-sidebar">
            <div class="user-info-sidebar text-center">
                <div class="mb-2">
                     <img src="https://placehold.co/80x80/5D4037/FFFFFF?text=<?php echo strtoupper(substr($fname, 0, 1)); ?>" alt="User Avatar" class="rounded-circle">
                </div>
                <h5 class="mb-1"><?php echo $fname. ' ' . $lname; ?></h5>
                <p class="mb-0"><?php echo $email; ?></p>
                <p class="mb-0 text-muted small"><?php echo ucfirst($role); ?></p>
            </div>
            <nav class="sidebar-nav">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" data-section="overview">
                            <i class="bi bi-speedometer2"></i> Overview
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-section="book">
                            <i class="bi bi-calendar-plus"></i> Book a Space
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-section="bookings">
                            <i class="bi bi-calendar-check"></i> My Bookings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-section="history">
                            <i class="bi bi-clock-history"></i> Booking History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-section="profile">
                            <i class="bi bi-person"></i> My Profile
                        </a>
                    </li>
                    <li class="nav-item mt-auto pt-3 border-top">
                         <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-left"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="dashboard-content">

            <!-- == Overview Section == -->
            <section id="overview" class="content-section active">
                <!-- Header part kept from VD -->
                <div class="dashboard-header mb-4">
                    <h1 class="display-5 fw-bold">Welcome back, <?php echo $fname; ?>!</h1>
                    <p class="lead text-muted">Here's a quick look at your activity.</p>
                </div>

                <!-- Content structure from VK -->
                <h3 class="mb-4">Dashboard Overview</h3>
                <div class="row g-4 mb-4">
                    <!-- Stat Cards -->
                    <div class="col-md-4">
                        <div class="dashboard-card"><div class="card-body text-center"><i class="bi bi-calendar-check text-primary" style="font-size: 2.5rem;"></i><h5 class="card-title mt-3">Upcoming Bookings</h5><p class="h2 mb-0">2</p></div></div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card"><div class="card-body text-center"><i class="bi bi-clock-history text-success" style="font-size: 2.5rem;"></i><h5 class="card-title mt-3">Past Bookings</h5><p class="h2 mb-0">5</p></div></div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card"><div class="card-body text-center"><i class="bi bi-star-fill text-warning" style="font-size: 2.5rem;"></i><h5 class="card-title mt-3">Favorite Space</h5><p class="favorite-space mb-0">Solo Space A</p></div></div>
                    </div>
                </div>
                <div class="row">
                    <!-- Upcoming Bookings List & Quick Actions -->
                     <div class="col-lg-8">
                        <div class="dashboard-card"><div class="card-body"><h5 class="card-title">Upcoming Bookings</h5><div class="booking-list"><div class="list-group-item d-flex justify-content-between align-items-center"><div><h6 class="mb-1">Solo Space A</h6><p class="mb-1 text-muted-custom small"><i class="bi bi-calendar-event me-1"></i> Oct 25, 2025 | <i class="bi bi-clock me-1"></i> 10:00 AM - 12:00 PM</p><span class="badge bg-success">Confirmed</span></div><div><button class="btn btn-sm btn-outline-primary" onclick="navigateToSection('bookings')">View</button></div></div><div class="list-group-item d-flex justify-content-between align-items-center"><div><h6 class="mb-1">Table Space 1</h6><p class="mb-1 text-muted-custom small"><i class="bi bi-calendar-event me-1"></i> Oct 28, 2025 | <i class="bi bi-clock me-1"></i> 2:00 PM - 4:00 PM</p><span class="badge bg-warning text-dark">Pending</span></div><div><button class="btn btn-sm btn-outline-primary" onclick="navigateToSection('bookings')">View</button></div></div><div class="list-group-item text-center d-none"><p class="text-muted mt-2 mb-0">No upcoming bookings found.</p></div></div></div></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-card"><div class="card-body"><h5 class="card-title">Quick Actions</h5><div class="d-grid gap-2"><button class="btn btn-primary" onclick="navigateToSection('book')"><i class="bi bi-calendar-plus me-2"></i> Book Now</button><button class="btn btn-outline-primary" onclick="navigateToSection('bookings')"><i class="bi bi-calendar-check me-2"></i> View Bookings</button><button class="btn btn-outline-primary" onclick="navigateToSection('profile')"><i class="bi bi-person me-2"></i> Edit Profile</button></div></div></div>
                    </div>
                </div>
            </section>

             <!-- == Book a Space Section == -->
            <section id="book" class="content-section">
                 <h3 class="mb-4">Book a Workspace</h3>
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-search me-2"></i>Find Available Spaces</h5>
                        <!-- Make this form POST to a booking handler later -->
                        <form id="booking-form" method="POST" action="process_booking.php"> <!-- Example action -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="book-facility" class="form-label">Select Facility</label>
                                    <select class="form-select" id="book-facility" name="facility_id" required>
                                        <option value="" selected disabled>Choose a workspace...</option>
                                        <?php if (!empty($facilities)): ?>
                                            <?php foreach ($facilities as $facility): ?>
                                                <option value="<?php echo htmlspecialchars($facility['facilityId']); ?>">
                                                    <?php echo htmlspecialchars($facility['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="" disabled>No facilities found</option>
                                        <?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="book-date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="book-date" name="booking_date" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="book-start-time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="book-start-time" name="start_time" step="1800" required> <!-- 30 min steps -->
                                </div>
                                <div class="col-md-6">
                                    <label for="book-end-time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="book-end-time" name="end_time" step="1800" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="book-pax" class="form-label">Number of People</label>
                                    <input type="number" class="form-control" id="book-pax" name="num_pax" min="1" value="1" required>
                                </div>
                            </div>
                            <!-- Search button can be removed if submitting the form directly searches/books -->
                             <!-- Or change type to button and use JS/AJAX -->
                            <button type="submit" class="btn btn-primary">Check Availability & Book</button>
                        </form>

                        <hr class="my-4">
                        <h6 class="text-muted mb-3">Available Spaces (Results Placeholder):</h6>
                        <!-- Placeholder for search results -->
                        <div id="availability-results" class="list-group">
                            <!-- Results will be loaded here later -->
                             <div class="list-group-item text-center text-muted">
                                Please select criteria and check availability.
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- == My Bookings Section == -->
            <section id="bookings" class="content-section">
                <!-- Content remains placeholder -->
                <h3 class="mb-4">My Active Bookings</h3>
                <div class="dashboard-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>Booking ID</th><th>Facility</th><th>Date</th><th>Time</th><th>Pax</th><th>Status</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <tr><td>#1001</td><td>Solo Space A</td><td>Oct 25, 2025</td><td>10:00 AM - 12:00 PM</td><td>1</td><td><span class="badge bg-success">Confirmed</span></td><td><button class="btn btn-sm btn-outline-primary">View</button> <button class="btn btn-sm btn-outline-danger">Cancel</button></td></tr>
                                    <tr><td>#1002</td><td>Table Space 1</td><td>Oct 28, 2025</td><td>2:00 PM - 4:00 PM</td><td>4</td><td><span class="badge bg-warning text-dark">Pending</span></td><td><button class="btn btn-sm btn-outline-primary">View</button> <button class="btn btn-sm btn-outline-danger">Cancel</button></td></tr>
                                    <tr class="d-none"><td colspan="7" class="text-center text-muted">No active bookings.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

             <!-- == Booking History Section == -->
             <section id="history" class="content-section">
                 <!-- Content remains placeholder -->
                 <h3 class="mb-4">Booking History</h3>
                 <div class="dashboard-card">
                     <div class="card-body">
                         <div class="table-responsive">
                            <table class="table table-hover">
                                <thead><tr><th>Booking ID</th><th>Facility</th><th>Date</th><th>Time</th><th>Status</th></tr></thead>
                                <tbody>
                                    <tr><td>#998</td><td>Board Room Executive</td><td>Oct 10, 2025</td><td>9:00 AM - 11:00 AM</td><td><span class="badge bg-secondary">Completed</span></td></tr>
                                    <tr><td>#997</td><td>Solo Space A</td><td>Oct 5, 2025</td><td>1:00 PM - 3:00 PM</td><td><span class="badge bg-secondary">Completed</span></td></tr>
                                     <tr class="d-none"><td colspan="5" class="text-center text-muted">No past bookings.</td></tr>
                                </tbody>
                            </table>
                        </div>
                     </div>
                 </div>
            </section>

            <!-- == My Profile Section == -->
            <section id="profile" class="content-section">
                 <!-- Content remains mostly placeholder/mock JS -->
                 <h1 class="mb-4">My Profile</h1>
                <div class="card dashboard-card">
                    <div class="card-body">
                         <div class="d-flex justify-content-between align-items-center mb-3">
                             <h5 class="card-title mb-0"><i class="bi bi-person-badge me-2"></i>Account Details</h5>
                             <button id="edit-profile-button" class="btn btn-outline-dark btn-sm" onclick="toggleEditProfile(true)"><i class="bi bi-pencil-square me-1"></i> Edit Profile</button>
                         </div>
                         <div id="profile-display" class="profile-details">
                            <p><strong>First Name:</strong> <span data-field="fname"><?php echo $fname; ?></span></p>
                            <p><strong>Last Name:</strong> <span data-field="lname"><?php echo $lname; ?></span></p>
                            <p><strong>Username:</strong> <span data-field="username"><?php echo $username; ?></span></p>
                            <p><strong>Email:</strong> <span data-field="email"><?php echo $email; ?></span></p>
                            <p><strong>Contact:</strong> <span data-field="contact"><?php echo $contactNum; ?></span></p>
                            <p><strong>Password:</strong> ******** <button type="button" class="btn btn-sm btn-link p-0 ms-2">(Change)</button></p>
                         </div>
                         <form id="profile-edit-form" class="d-none mt-3">
                            <div class="alert alert-success d-none" role="alert" id="profile-success-message">Profile updated successfully!</div>
                            <div class="alert alert-danger d-none" role="alert" id="profile-error-message">Error updating profile. Please try again.</div>
                            <div class="row g-3 mb-3">
                                <div class="col-md-6"><label for="profile-fname" class="form-label">First Name</label><input type="text" class="form-control" id="profile-fname" name="fname" value="<?php echo $fname; ?>"></div>
                                <div class="col-md-6"><label for="profile-lname" class="form-label">Last Name</label><input type="text" class="form-control" id="profile-lname" name="lname" value="<?php echo $lname; ?>"></div>
                                <div class="col-md-6"><label for="profile-username" class="form-label">Username</label><input type="text" class="form-control" id="profile-username" value="<?php echo $username; ?>" readonly disabled><small class="text-muted">Username cannot be changed.</small></div>
                                <div class="col-md-6"><label for="profile-email" class="form-label">Email</label><input type="email" class="form-control" id="profile-email" name="email" value="<?php echo $email; ?>"></div>
                                <div class="col-md-6"><label for="profile-contact" class="form-label">Contact Number</label><input type="tel" class="form-control" id="profile-contact" name="contact" value="<?php echo $contactNum; ?>"></div>
                            </div>
                            <div class="d-flex gap-2 mt-3"><button type="submit" class="btn btn-primary">Save Changes</button><button type="button" class="btn btn-secondary" onclick="toggleEditProfile(false)">Cancel</button></div>
                         </form>
                    </div>
                </div>
            </section>

        </main>
    </div><!-- End dashboard-page-sidebar -->

    <!-- Bootstrap JS -->
     <script src="js/bootstrap.bundle.min.js"></script>
     <!-- Dashboard Specific JS -->
     <script src="js/dashboard.js"></script>
     <script>
        // Add minimum date for date picker in booking section
        document.addEventListener('DOMContentLoaded', () => {
            const dateInput = document.getElementById('book-date');
            if (dateInput) {
                dateInput.min = new Date().toISOString().split('T')[0];
            }
        });
     </script>
</body>
</html>