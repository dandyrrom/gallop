<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['fname'] ?? '';
?>
<header>
    <nav id="mainNav" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top py-2">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="assets/Gallop-Project-Logo.svg" alt="Gallop Logo" style="height: 40px;" class="d-inline-block align-text-top me-2">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto navbar-nav-centered fw-bold">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="our-spaces.php">Our Spaces</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto">
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item">
                            <span class="navbar-text me-3">
                                <small>Welcome, <strong><?php echo htmlspecialchars($userName); ?></strong></small>
                            </span>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm rounded-pill px-4" href="dashboard.php">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-outline-dark btn-sm rounded-pill px-4" href="functions/logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="btn btn-outline-dark btn-sm rounded-pill px-4" href="login.php">Login</a>
                        </li>
                        <li class="nav-item ms-lg-2">
                            <a class="btn btn-primary btn-sm rounded-pill px-4" href="signup.php">Join</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>