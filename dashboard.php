<?php
    session_start();

    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit();
    }

    $userId = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];
    $fullName = $_SESSION['full_name'];
    $role = $_SESSION['role'];

    require_once 'db/db_connect.php';
    $stmt = $conn->prepare("SELECT contactNum FROM user WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $userData = $result->fetch_assoc();
    $contactNum = $userData['contactNum'] ?? 'N/A';
    $stmt->close();

    $profile_success = $_SESSION['profile_success'] ?? '';
    $profile_error = $_SESSION['profile_error'] ?? '';
    unset($_SESSION['profile_success'], $_SESSION['profile_error']);
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Standalone Guest Dashboard Mockup</title>
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <!-- Bootstrap Icons (CDN) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <!-- Favicon Placeholder -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🐎</text></svg>">

    <style>
        /* --- Root Variables (Mimicking Project Theme) --- */
        :root {
            --gallop-dark-wood: #5D4037; /* Dark brown */
            --gallop-accent: #FFC107; /* Yellow */
            --gallop-bg: #FFFFFF; /* White */
            --gallop-text: #4E342E; /* Medium brown */
            --gallop-serif: 'Playfair Display', serif;
            --gallop-sans-serif: 'Roboto', sans-serif;

            --bs-border-radius: 0.375rem; /* Default Bootstrap radius */
            --bs-border-radius-sm: 0.25rem;
            --bs-border-radius-lg: 0.5rem;
            --bs-border-radius-pill: 50rem;

            --sidebar-width: 260px;
            --header-height: 60px;
            --footer-height: 50px;
        }

        /* --- Base Styles --- */
        body {
            background-color: #f8f9fa; /* Light grey background */
            color: var(--gallop-text);
            font-family: var(--gallop-sans-serif);
            padding-top: var(--header-height); /* Space for fixed header */
        }

        h1, h2, h3, h4, h5 {
            font-family: var(--gallop-serif);
            color: var(--gallop-dark-wood);
        }

        /* --- Header Styles (Mockup) --- */
        #header-placeholder {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: var(--header-height);
            background-color: var(--gallop-bg);
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between; /* Space out items */
            padding: 0 2rem;
            border-bottom: 1px solid #eee;
        }
        .header-logo { color: var(--gallop-dark-wood); font-weight: bold; font-size: 1.5rem; text-decoration: none; }
        .header-nav a { color: var(--gallop-text); text-decoration: none; margin: 0 0.75rem; font-weight: 500;}
        .header-user a { color: var(--gallop-text); text-decoration: none; margin-left: 1rem; font-weight: 500;}


         /* --- Footer Styles (Mockup) --- */
         #footer-placeholder {
             padding: 1.5rem 0;
             background-color: var(--gallop-bg);
             border-top: 1px solid rgba(93, 64, 55, 0.1);
             text-align: center;
             color: #6c757d;
             margin-left: var(--sidebar-width); /* Adjust footer margin for sidebar */
             min-height: var(--footer-height);
             font-size: 0.9rem;
         }


        /* --- Dashboard Layout with Sidebar --- */
        .dashboard-page-sidebar {
            display: flex;
            min-height: calc(100vh - var(--header-height));
        }

        .dashboard-sidebar {
            width: var(--sidebar-width);
            background-color: #fff;
            border-right: 1px solid #dee2e6;
            padding: 1.5rem 1rem;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            flex-shrink: 0;
            position: sticky; /* Keep sidebar visible */
            top: var(--header-height);
            height: calc(100vh - var(--header-height));
            overflow-y: auto; /* Allow scrolling if content overflows */
        }

        .dashboard-content {
            flex-grow: 1;
            padding: 2.5rem;
            min-width: 0; /* Prevents content from overflowing flex container */
        }

        /* --- Sidebar Navigation --- */
        .sidebar-nav .nav-link {
            color: var(--gallop-text);
            padding: 0.75rem 1rem;
            border-radius: var(--bs-border-radius-sm);
            margin-bottom: 0.25rem;
            font-weight: 500;
            cursor: pointer; /* Indicate clickable links */
        }
        .sidebar-nav .nav-link:hover {
            background-color: #e9ecef;
            color: var(--gallop-dark-wood);
        }
        .sidebar-nav .nav-link.active {
            background-color: var(--gallop-dark-wood);
            color: #fff;
        }
        .sidebar-nav .nav-link i {
            margin-right: 0.75rem;
            width: 20px; /* Align icons */
        }
        .user-info-sidebar {
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
            margin-bottom: 1rem;
        }
         .user-info-sidebar h5 { font-weight: bold;}
         .user-info-sidebar p { font-size: 0.9rem; margin-bottom: 0.2rem; color: #6c757d;}

        /* --- Content Sections --- */
        .content-section {
            display: none; /* Hide sections by default */
            animation: fadeIn 0.5s ease-in-out;
        }
        .content-section.active {
            display: block; /* Show active section */
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .dashboard-card { /* General card styling */
            border: none;
            border-radius: var(--bs-border-radius-lg);
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            margin-bottom: 1.5rem;
            background-color: #fff;
        }
        .dashboard-card .card-body { padding: 1.5rem;}
        .dashboard-card .card-title {
            font-family: var(--gallop-serif);
            border-bottom: 1px solid #eee;
            padding-bottom: 0.75rem;
            margin-bottom: 1rem;
            font-size: 1.3rem;
            font-weight: bold;
        }
         /* Style buttons */
         .btn { border-radius: var(--bs-border-radius-pill); font-weight: 500; padding: 0.4rem 1.2rem; }
         .btn-primary { background-color: var(--gallop-dark-wood); border-color: var(--gallop-dark-wood); }
         .btn-primary:hover { background-color: var(--gallop-text); border-color: var(--gallop-text); }
         .btn-outline-dark { border-color: var(--gallop-dark-wood); color: var(--gallop-dark-wood); }
         .btn-outline-dark:hover { background-color: var(--gallop-dark-wood); color: white;}
         .btn-sm { padding: 0.3rem 1rem; font-size: 0.8rem;}

        /* --- Booking List Styles --- */
        .booking-list { max-height: 400px; overflow-y: auto; padding-right: 10px; }
        .booking-item { display: flex; align-items: center; background-color: #f8f9fa; border: 1px solid #eee; border-radius: var(--bs-border-radius); padding: 1rem; margin-bottom: 1rem; transition: background-color 0.2s ease; }
        .booking-item:hover { background-color: #e9ecef; }
        .booking-item-icon { font-size: 1.8rem; color: var(--gallop-dark-wood); margin-right: 1rem; flex-shrink: 0; width: 40px; text-align: center; }
        .booking-item-details { flex-grow: 1; }
        .booking-item-details .fw-bold { color: var(--gallop-dark-wood); margin-bottom: 0.1rem; }
        .booking-item-details .small { color: #6c757d;}
        .booking-item-actions .btn { font-size: 0.8rem; }
        .booking-list::-webkit-scrollbar { width: 6px; }
        .booking-list::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        .booking-list::-webkit-scrollbar-thumb { background: #ccc; border-radius: 10px; }
        .booking-list::-webkit-scrollbar-thumb:hover { background: #aaa; }

        /* Booking History Table */
        .table th {
             font-family: var(--gallop-serif);
             color: var(--gallop-dark-wood);
             font-weight: 500;
             background-color: #f8f9fa;
        }
        .table td { vertical-align: middle;}
        .table .badge { font-size: 0.75rem; padding: 0.4em 0.6em;}
        .table-hover tbody tr:hover { background-color: #f1f1f1; }

        /* Profile Section */
        .profile-details p { margin-bottom: 0.75rem; font-size: 0.95rem; }
        .profile-details strong {
            color: var(--gallop-dark-wood);
            width: 120px;
            display: inline-block;
            font-weight: 500;
        }

        /* Forms (within Profile Edit - conceptual) */
        .form-label { font-weight: 500; color: var(--gallop-dark-wood); font-size: 0.9rem; margin-bottom: 0.3rem;}
        .form-control { border-radius: var(--bs-border-radius-sm); font-size: 0.95rem; padding: 0.5rem 0.8rem;}
        .form-control:focus { border-color: var(--gallop-accent); box-shadow: 0 0 0 0.25rem rgba(255, 193, 7, 0.25); }


        /* --- Responsive Adjustments --- */
        @media (max-width: 991.98px) {
            .dashboard-page-sidebar {
                flex-direction: column;
            }
            .dashboard-sidebar {
                position: static;
                width: 100%;
                height: auto;
                border-right: none;
                border-bottom: 1px solid #dee2e6;
                box-shadow: none;
                overflow-y: visible;
            }
            .dashboard-content {
                padding: 1.5rem;
            }
             #footer-placeholder {
                 margin-left: 0;
             }
             .header-nav { display: none; } /* Hide main nav links on small screens for simplicity */
        }
         @media (max-width: 768px) {
             .dashboard-content { padding: 1rem;}
             h1, .display-5 { font-size: 1.8rem; }
             .lead { font-size: 1rem; }
             .profile-details strong { width: 100px;}
         }
    </style>
</head>
<body>

    <!-- Header Mockup -->
    <div id="header-placeholder">
        <a href="index.html" class="header-logo">
            <!-- <img src="assets/Gallop-Project-Logo.svg" alt="Gallop Logo" style="height: 40px;"> -->
            🐎 GALLOP
        </a>
        <nav class="header-nav">
            <a href="index.html">Home</a>
            <a href="our-spaces.html">Our Spaces</a>
            <a href="about.html">About</a>
        </nav>
        <div class="header-user">
            <span>Welcome, <?php echo htmlspecialchars($fname); ?>!</span>
            <a href="logout.php" class="btn btn-sm btn-outline-primary">Logout</a>
        </div>
    </div>

    <div class="dashboard-page-sidebar">

        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="user-info-sidebar text-center">
                <div class="mb-2">
                    <i class="bi bi-person-circle" style="font-size: 3rem; color: var(--gallop-dark-wood);"></i>
                </div>
                <h5 class="mb-1"><?php echo htmlspecialchars($fname); ?></h5>
                <p class="mb-0"><?php echo htmlspecialchars($email); ?></p>
                <p class="mb-0 text-muted-custom small"><?php echo ucfirst(htmlspecialchars($role)); ?></p>
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
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-content">

            <!-- Welcome Section -->
            <div class="welcome-section">
                <h2>Welcome back, <?php echo htmlspecialchars($fname); ?>! 🐎</h2>
                <p>Ready to book your next productive workspace? Let's get started!</p>
            </div>

            <!-- Overview Section -->
            <section id="overview" class="content-section active">
                <h3 class="mb-4">Dashboard Overview</h3>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="dashboard-card">
                            <div class="card-body text-center">
                                <i class="bi bi-calendar-check text-primary" style="font-size: 2.5rem;"></i>
                                <h5 class="card-title mt-3">Upcoming Bookings</h5>
                                <p class="h2 mb-0">2</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card">
                            <div class="card-body text-center">
                                <i class="bi bi-clock-history text-success" style="font-size: 2.5rem;"></i>
                                <h5 class="card-title mt-3">Past Bookings</h5>
                                <p class="h2 mb-0">5</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="dashboard-card">
                            <div class="card-body text-center">
                                <i class="bi bi-star-fill text-warning" style="font-size: 2.5rem;"></i>
                                <h5 class="card-title mt-3">Favorite Space</h5>
                                <p class="h5 mb-0">Solo Space A</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Upcoming Bookings</h5>
                                <div class="booking-list">
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Solo Space A</h6>
                                            <p class="mb-1 text-muted-custom"><i class="bi bi-calendar-event me-1"></i> Oct 25, 2025 | <i class="bi bi-clock me-1"></i> 10:00 AM - 12:00 PM</p>
                                            <span class="badge bg-success">Confirmed</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </div>
                                    </div>
                                    <div class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Table Space 1</h6>
                                            <p class="mb-1 text-muted-custom"><i class="bi bi-calendar-event me-1"></i> Oct 28, 2025 | <i class="bi bi-clock me-1"></i> 2:00 PM - 4:00 PM</p>
                                            <span class="badge bg-warning text-dark">Pending</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="dashboard-card">
                            <div class="card-body">
                                <h5 class="card-title">Quick Actions</h5>
                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary" onclick="navigateToSection('book')">
                                        <i class="bi bi-calendar-plus me-2"></i> Book Now
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="navigateToSection('bookings')">
                                        <i class="bi bi-calendar-check me-2"></i> View Bookings
                                    </button>
                                    <button class="btn btn-outline-primary" onclick="navigateToSection('profile')">
                                        <i class="bi bi-person me-2"></i> Edit Profile
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Book a Space Section -->
            <section id="book" class="content-section">
                <h3 class="mb-4">Book a Workspace</h3>
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">New Booking</h5>
                        <form id="booking-form">
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="book-facility" class="form-label">Select Facility</label>
                                    <select class="form-select" id="book-facility" required>
                                        <option value="" selected disabled>Choose a workspace...</option>
                                        <option value="solo">Solo Space</option>
                                        <option value="table">Table Space</option>
                                        <option value="board">Board Room</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="book-date" class="form-label">Date</label>
                                    <input type="date" class="form-control" id="book-date" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="book-start-time" class="form-label">Start Time</label>
                                    <input type="time" class="form-control" id="book-start-time" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="book-end-time" class="form-label">End Time</label>
                                    <input type="time" class="form-control" id="book-end-time" required>
                                </div>
                                <div class="col-md-12">
                                    <label for="book-pax" class="form-label">Number of People</label>
                                    <input type="number" class="form-control" id="book-pax" min="1" value="1" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Booking</button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- My Bookings Section -->
            <section id="bookings" class="content-section">
                <h3 class="mb-4">My Active Bookings</h3>
                <div class="dashboard-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Facility</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Pax</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#1001</td>
                                        <td>Solo Space A</td>
                                        <td>Oct 25, 2025</td>
                                        <td>10:00 AM - 12:00 PM</td>
                                        <td>1</td>
                                        <td><span class="badge bg-success">Confirmed</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                            <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>#1002</td>
                                        <td>Table Space 1</td>
                                        <td>Oct 28, 2025</td>
                                        <td>2:00 PM - 4:00 PM</td>
                                        <td>4</td>
                                        <td><span class="badge bg-warning text-dark">Pending</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">View</button>
                                            <button class="btn btn-sm btn-outline-danger">Cancel</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Booking History Section -->
            <section id="history" class="content-section">
                <h3 class="mb-4">Booking History</h3>
                <div class="dashboard-card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Facility</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>#998</td>
                                        <td>Board Room Executive</td>
                                        <td>Oct 10, 2025</td>
                                        <td>9:00 AM - 11:00 AM</td>
                                        <td><span class="badge bg-secondary">Completed</span></td>
                                    </tr>
                                    <tr>
                                        <td>#997</td>
                                        <td>Solo Space A</td>
                                        <td>Oct 5, 2025</td>
                                        <td>1:00 PM - 3:00 PM</td>
                                        <td><span class="badge bg-secondary">Completed</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Profile Section -->
            <section id="profile" class="content-section">
                <h3 class="mb-4">My Profile</h3>
                <div class="dashboard-card">
                    <div class="card-body">
                        <h5 class="card-title">Account Information
                            <button type="button" class="btn btn-sm btn-primary float-end" id="edit-profile-button" onclick="toggleEditProfile(true)">
                                <i class="bi bi-pencil-square"></i> Edit Profile
                            </button>
                        </h5>

                         <div id="profile-display">
                            <p><strong>First Name:</strong> <span data-field="fname"><?php echo htmlspecialchars($fname); ?></span></p>
                            <p><strong>Last Name:</strong> <span data-field="lname"><?php echo htmlspecialchars($lname); ?></span></p>
                            <p><strong>Username:</strong> <span data-field="username"><?php echo htmlspecialchars($username); ?></span></p>
                            <p><strong>Email:</strong> <span data-field="email"><?php echo htmlspecialchars($email); ?></span></p>
                            <p><strong>Contact:</strong> <span data-field="contact"><?php echo htmlspecialchars($contactNum); ?></span></p>
                            <p><strong>Password:</strong> ******** <button type="button" class="btn btn-sm btn-link p-0 ms-2">(Change)</button></p>
                         </div>

                        <form id="profile-edit-form" class="d-none mt-3" action="functions/update_profile.php" method="POST">
                            <?php if ($profile_success): ?>
                            <div class="alert alert-success" role="alert">
                                <?php echo htmlspecialchars($profile_success); ?>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() { toggleEditProfile(true); setTimeout(() => { toggleEditProfile(false); }, 2000);});
                            </script>
                            <?php endif; ?>
                            
                            <?php if ($profile_error): ?>
                            <div class="alert alert-danger" role="alert">
                                <?php echo htmlspecialchars($profile_error); ?>
                            </div>
                            <script> 
                                document.addEventListener('DOMContentLoaded', function() { toggleEditProfile(true);});
                            </script>
                            <?php endif; ?>
                            
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="profile-fname" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="profile-fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="profile-lname" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="profile-lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>">
                                </div>
                                <div class="col-md-6">
                                     <label for="profile-username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="profile-username" value="<?php echo htmlspecialchars($username); ?>" readonly disabled>
                                    <small class="text-muted">Username cannot be changed.</small>
                                </div>
                                 <div class="col-md-6">
                                    <label for="profile-email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="profile-email" name="email" value="<?php echo htmlspecialchars($email); ?>">
                                </div>
                                <div class="col-md-6">
                                    <label for="profile-contact" class="form-label">Contact Number</label>
                                    <input type="tel" class="form-control" id="profile-contact" name="contact" value="<?php echo htmlspecialchars($contactNum); ?>">
                                </div>
                            </div>
                            <div class="d-flex gap-2 mt-3">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                <button type="button" class="btn btn-secondary" onclick="toggleEditProfile(false)">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

        </main>
    </div><!-- End dashboard-page-sidebar -->


    <!-- Bootstrap JS (CDN) -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <!-- Basic JS for Section Navigation -->
    <script>
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
                profileDisplay.classList.add('d-none');
                profileEditForm.classList.remove('d-none');
                editProfileButton.classList.add('d-none'); // Hide Edit button
            } else {
                profileDisplay.classList.remove('d-none');
                profileEditForm.classList.add('d-none');
                 editProfileButton.classList.remove('d-none'); // Show Edit button
            }
        }

        // Initialize view
        document.addEventListener('DOMContentLoaded', () => {
             // Set default active section if needed (e.g., from URL hash or default to 'overview')
             const initialSection = window.location.hash ? window.location.hash.substring(1) : 'overview';
             navigateToSection(initialSection);
        });

    </script>
</body>
</html>

