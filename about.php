<?php 
session_start(); 
include '_includes/header.php';
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Gallop | Collaboration at Full Speed</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/about.css">
    <link rel="icon" href="assets/gallop-favicon.svg" type="image/svg+xml">
</head>
<body>

    <div id="header-placeholder"></div>

    <main class="about-page">
        <header class="about-header text-center">
            <div class="container">
                <h1 class="display-3 fw-bold">About Gallop</h1>
                <p class="lead text-muted">A place where ideas, caffeine, and community sprint together toward success.</p>
            </div>
        </header>

        <section class="container about-section">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h2 class="section-title">Our Mission</h2>
                    <p>At Gallop, our mission is simple: to help ambitious people move fast — without falling off the horse. We believe great ideas shouldn’t be slowed down by slow Wi-Fi, noisy coffee shops, or questionable office chairs.</p>
                    <p>Our goal is to build a co-working community that feels less like “just work” and more like “let’s conquer the world together — with snacks.”</p>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="assets/hero-team-meeting.jpg" alt="A team collaborating in a Gallop meeting room" class="img-fluid rounded-3 shadow-lg">
                </div>
            </div>
        </section>
        
        <section class="bg-light about-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <h2 class="section-title">Our Story</h2>
                        <p>Once upon a coffee-fueled evening, a group of freelancers found themselves competing for the last power outlet in a crowded café. That night, the idea of Gallop was born.</p>
                        <p>We thought: what if there were a place where ideas could run free — like wild horses — but with better chairs and reliable internet? So we built it. A place where designers, developers, and doers can gallop toward their goals, side by side.</p>
                    </div>
                    <div class="col-lg-6 order-lg-1 text-center">
                         <img src="assets/table-room.png" alt="An empty collaborative table space" class="img-fluid rounded-3 shadow-lg">
                    </div>
                </div>
            </div>
        </section>

        <section class="container about-section text-center">
            <h2 class="section-title mb-5">Our Values</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="value-card">
                        <i class="bi bi-people-fill value-icon"></i>
                        <h5>Community Over Competition</h5>
                        <p>Success is faster (and more fun) when we cheer each other on instead of racing solo.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="value-card">
                        <i class="bi bi-lightning-charge-fill value-icon"></i>
                        <h5>Speed with Purpose</h5>
                        <p>We move fast — but we never hurry. There’s a difference.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="value-card">
                        <i class="bi bi-cup-hot-fill value-icon"></i>
                        <h5>Caffeine and Kindness</h5>
                        <p>Good vibes and good coffee are the fuel of all great ideas.</p>
                    </div>
                </div>
                 <div class="col-md-6 col-lg-3">
                    <div class="value-card">
                        <i class="bi bi-gem value-icon"></i>
                        <h5>Quality in Every Gallop</h5>
                        <p>From our ergonomic chairs to our event playlists, we’re serious about details.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-dark text-white text-center about-section join-section">
            <div class="container">
                <h2 class="section-title">Ready to stop trotting and start Galloping?</h2>
                <p class="lead my-4">Whether you’re a solo entrepreneur, a remote team, or someone just tired of working in pajama pants — your next chapter starts here.</p>
                <a href="our-spaces.html" class="btn btn-primary btn-lg">Find Your Stride With Us</a>
            </div>
        </section>
    </main>

    <div id="footer-placeholder"></div>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
</body>
</html>