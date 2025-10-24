<?php
session_start(); // Start the session at the very beginning

// Check if the user is logged in
// If 'user_id' is not set in the session, redirect to login.php
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect to the login page
    exit; // Stop further script execution
}

// Get user details from session for easier use, with htmlspecialchars for security
$userId = $_SESSION['user_id'];
$username = htmlspecialchars($_SESSION['username'] ?? 'User');
$fname = htmlspecialchars($_SESSION['fname'] ?? '');
$lname = htmlspecialchars($_SESSION['lname'] ?? '');
$email = htmlspecialchars($_SESSION['email'] ?? '');
$contactNum = htmlspecialchars($_SESSION['contactNum'] ?? '');
$role = htmlspecialchars($_SESSION['role'] ?? 'user');

// Note: VK fetches contactNum separately; VD already has it. We use the VD version.
// Note: VK defines $fullName; VD does not, but VK sidebar doesn't seem to use it.
// Note: VK includes profile update messages logic; VD does not yet have this. Let's keep it out for now.

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
    <link rel="stylesheet" href="css/footer.css"> <!-- Keep link in case other parts need it, though footer removed below -->

    <!-- Page-Specific Styles -->
    <link rel="stylesheet" href="css/dashboard.css"> <!-- Assuming dashboard.css matches the visual style needed -->

    <!-- Favicon -->
    <link rel="icon" href="assets/horse-head-faviconv.png" type="image/png">

</head>
<body>

    <!-- Include the specific dashboard header (Kept from VD) -->
    <?php include '_includes/header_dashboard.php'; ?>

    <div class="dashboard-page-sidebar">
        <!-- Sidebar Navigation (Copied structure from VK) -->
        <aside class="dashboard-sidebar">
            <div class="user-info-sidebar text-center">
                <div class="mb-2">
                    <!-- Placeholder Icon/Image -->
                     <img src="https://placehold.co/80x80/5D4037/FFFFFF?text=<?php echo strtoupper(substr($fname, 0, 1)); ?>" alt="User Avatar" class="rounded-circle">
                </div>
                <h5 class="mb-1"><?php echo $fname; ?></h5>
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
                        <a class="nav-link" data-section="history"> <!-- Added History from VK -->
                            <i class="bi bi-clock-history"></i> Booking History
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-section="profile">
                            <i class="bi bi-person"></i> My Profile
                        </a>
                    </li>
                    <!-- Logout button included in sidebar -->
                    <li class="nav-item mt-auto pt-3 border-top">
                         <a class="nav-link" href="logout.php">
                            <i class="bi bi-box-arrow-left"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content Area (Keep VD's content sections for now) -->
        <main class="dashboard-content">

            <!-- == Overview Section == -->
            <section id="overview" class="content-section active">
                <div class="dashboard-header mb-4">
                    <h1 class="display-5 fw-bold">Welcome back, <?php echo $fname; ?>!</h1>
                    <p class="lead text-muted">Here's a quick look at your activity.</p>
                </div>
                <div class="row">
                    <div class="col-lg-7">
                        <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-calendar-event me-2"></i>Next Upcoming Booking</h5>
                                <!-- Placeholder -->
                                <div class="booking-item">
                                    <div class="booking-item-icon"><i class="bi bi-person-workspace"></i></div>
                                    <div class="booking-item-details">
                                        <p class="fw-bold mb-0">Solo Space</p>
                                        <p class="text-muted small mb-0">October 23, 2025 | 9:00 AM - 11:00 AM</p>
                                    </div>
                                    <div class="booking-item-actions ms-auto d-flex flex-column flex-sm-row gap-1">
                                        <button class="btn btn-sm btn-outline-secondary" onclick="navigateToSection('bookings')">Details</button>
                                        <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                    </div>
                                </div>
                                <div class="text-center pt-3 d-none">
                                    <p class="text-muted mt-2 mb-3">You have no upcoming bookings.</p>
                                    <button class="btn btn-primary" onclick="navigateToSection('book')">Book a Space</button>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="col-lg-5">
                         <div class="card dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-bar-chart-line me-2"></i>Quick Stats</h5>
                                <!-- Placeholders -->
                                <p class="small text-muted mb-1">Total Bookings:</p>
                                <h3 class="fw-bold">15</h3>
                                <p class="small text-muted mb-1 mt-3">Hours Booked (This Month):</p>
                                <h3 class="fw-bold">28</h3>
                                <button class="btn btn-sm btn-outline-dark mt-3 w-100" onclick="navigateToSection('bookings')">View Booking History</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

             <!-- == Book a Space Section == -->
            <section id="book" class="content-section">
                <h1 class="mb-4">Book a Space</h1>
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-search me-2"></i>Find Available Spaces</h5>
                        <form>
                            <div class="row g-3 align-items-end">
                                <div class="col-md-4">
                                    <label for="book-date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="book-date" value="">
                                </div>
                                <div class="col-md-3">
                                    <label for="book-start-time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="book-start-time" step="1800" value="09:00">
                                </div>
                                 <div class="col-md-3">
                                    <label for="book-end-time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="book-end-time" step="1800" value="11:00">
                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-primary w-100">Search</button>
                                </div>
                            </div>
                        </form>
                        <hr class="my-4">
                        <h6 class="text-muted mb-3">Available Spaces for [Date] from [Start Time] to [End Time]:</h6>
                        <!-- Placeholder -->
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div><h6 class="mb-1 fw-bold">Solo Space 1</h6><small class="text-muted">Capacity: 1 | Features: Monitor, Coffee</small></div>
                                <span class="badge bg-success rounded-pill">Available</span>
                            </a>
                             <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                <div><h6 class="mb-1 fw-bold">Table Space A</h6><small class="text-muted">Capacity: 4 | Features: TV, Whiteboard</small></div>
                                <span class="badge bg-success rounded-pill">Available</span>
                            </a>
                            <div class="list-group-item text-center text-muted d-none">No spaces available.</div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- == My Bookings Section == -->
            <section id="bookings" class="content-section">
                <h1 class="mb-4">My Bookings</h1>
                <div class="card dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-calendar-check me-2"></i>Upcoming Bookings</h5>
                        <!-- Placeholder -->
                        <div class="booking-list mb-4">
                            <div class="booking-item">
                                <div class="booking-item-icon"><i class="bi bi-person-workspace"></i></div>
                                <div class="booking-item-details"><p class="fw-bold mb-0">Solo Space</p><p class="text-muted small mb-0">October 23, 2025 | 9:00 AM - 11:00 AM</p></div>
                                <div class="booking-item-actions ms-auto d-flex flex-column flex-sm-row gap-1"><button class="btn btn-sm btn-outline-secondary">Details</button><button class="btn btn-sm btn-outline-danger">Cancel</button></div>
                            </div>
                             <div class="text-center pt-3 d-none"><p class="text-muted mt-4">You have no upcoming bookings.</p><button class="btn btn-primary btn-sm mt-2" onclick="navigateToSection('book')">Book a Space</button></div>
                        </div>
                         <hr>
                         <!-- NOTE: VD Merged history here, but VK has it separate. Keeping VD structure for now -->
                         <h5 class="card-title mt-4"><i class="bi bi-clock-history me-2"></i>Booking History</h5>
                         <!-- Placeholder -->
                         <div class="table-responsive">
                            <table class="table table-striped table-hover small align-middle">
                                <thead><tr><th>Space</th><th>Date</th><th>Time</th><th>Status</th><th>Action</th></tr></thead>
                                <tbody>
                                    <tr><td>Board Room</td><td>Oct 15, 2025</td><td>10:00 AM - 12:00 PM</td><td><span class="badge bg-success">Completed</span></td><td><a href="#" class="btn btn-sm btn-link text-decoration-none p-0">View</a></td></tr>
                                    <tr class="d-none"><td colspan="5" class="text-center text-muted py-3">No past bookings found.</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

             <!-- == Booking History Section (From VK, separate) == -->
             <section id="history" class="content-section">
                <h3 class="mb-4">Booking History</h3>
                <div class="dashboard-card">
                    <div class="card-body">
                        <!-- Placeholder - Content from VK's history section would go here -->
                        <p>Placeholder for Booking History Table (similar to the one in VD's 'My Bookings' section).</p>
                    </div>
                </div>
            </section>

            <!-- == My Profile Section == -->
            <section id="profile" class="content-section">
                 <h1 class="mb-4">My Profile</h1>
                <div class="card dashboard-card">
                    <div class="card-body">
                         <div class="d-flex justify-content-between align-items-center mb-3">
                             <h5 class="card-title mb-0"><i class="bi bi-person-badge me-2"></i>Account Details</h5>
                             <button id="edit-profile-button" class="btn btn-outline-dark btn-sm" onclick="toggleEditProfile(true)">
                                <i class="bi bi-pencil-square me-1"></i> Edit Profile
                             </button>
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
                            <!-- Note: The action='functions/update_profile.php' from VK is removed for now, just using JS mock -->
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
     <script src="js/dashboard.js"></script> <!-- Ensure dashboard.js is updated if needed -->
</body>
</html>

