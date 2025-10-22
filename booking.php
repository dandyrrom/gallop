<?php
$success_message = '';
$error_message = '';

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');
    $date = $_POST['date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $end_time = $_POST['end_time'] ?? '';
    $guests = $_POST['guests'] ?? '';
    $special_requirements = trim($_POST['special_requirements'] ?? '');
    
    // Simple validation
    if (empty($first_name) || empty($last_name) || empty($email) || empty($contact_number) || 
        empty($date) || empty($start_time) || empty($end_time) || empty($guests)) {
        $error_message = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Please enter a valid email address.';
    } else {
        $success_message = 'Booking request submitted successfully! We will contact you soon to confirm your reservation.';
        // Clear form data
        $_POST = array();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book a Workspace - Gallop</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/booking.css">
</head>
<body>
    <div class="booking-container">
        <div class="container">
            <div class="booking-header">
                <h1>Book Your Workspace</h1>
                <p>Choose from our premium co-working spaces and reserve your spot for productive work sessions.</p>
            </div>
            
            <div class="booking-card">
                <?php if ($error_message): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    
                    <!-- Personal Information -->
                    <div class="form-section">
                        <h3>Your Information</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First Name *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="first_name" 
                                       name="first_name" 
                                       placeholder="Enter first name"
                                       value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last Name *</label>
                                <input type="text" 
                                       class="form-control" 
                                       id="last_name" 
                                       name="last_name" 
                                       placeholder="Enter last name"
                                       value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="email">Email Address *</label>
                                <input type="email" 
                                       class="form-control" 
                                       id="email" 
                                       name="email" 
                                       placeholder="Enter your email"
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="contact_number">Contact Number *</label>
                                <input type="tel" 
                                       class="form-control" 
                                       id="contact_number" 
                                       name="contact_number" 
                                       placeholder="Enter your phone number"
                                       value="<?php echo htmlspecialchars($_POST['contact_number'] ?? ''); ?>"
                                       required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Booking Details -->
                    <div class="form-section">
                        <h3>Booking Details</h3>
                        <div class="form-group">
                            <label for="date">Date *</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="date" 
                                   name="date" 
                                   value="<?php echo htmlspecialchars($_POST['date'] ?? ''); ?>"
                                   required>
                        </div>
                        
                        <div class="form-row-3">
                            <div class="form-group">
                                <label for="start_time">Start Time *</label>
                                <input type="time" 
                                       class="form-control" 
                                       id="start_time" 
                                       name="start_time" 
                                       value="<?php echo htmlspecialchars($_POST['start_time'] ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="end_time">End Time *</label>
                                <input type="time" 
                                       class="form-control" 
                                       id="end_time" 
                                       name="end_time" 
                                       value="<?php echo htmlspecialchars($_POST['end_time'] ?? ''); ?>"
                                       required>
                            </div>
                            <div class="form-group">
                                <label for="guests">Number of Guests *</label>
                                <select class="form-control" id="guests" name="guests" required>
                                    <option value="">Select guests</option>
                                    <option value="1" <?php echo ($_POST['guests'] ?? '') == '1' ? 'selected' : ''; ?>>1 Person</option>
                                    <option value="2" <?php echo ($_POST['guests'] ?? '') == '2' ? 'selected' : ''; ?>>2 People</option>
                                    <option value="3" <?php echo ($_POST['guests'] ?? '') == '3' ? 'selected' : ''; ?>>3 People</option>
                                    <option value="4" <?php echo ($_POST['guests'] ?? '') == '4' ? 'selected' : ''; ?>>4 People</option>
                                    <option value="5+" <?php echo ($_POST['guests'] ?? '') == '5+' ? 'selected' : ''; ?>>5+ People</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="special_requirements">Special Requirements</label>
                            <textarea class="form-control" 
                                      id="special_requirements" 
                                      name="special_requirements" 
                                      rows="3" 
                                      placeholder="Any special requirements or requests..."><?php echo htmlspecialchars($_POST['special_requirements'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <!-- Amenities Selection -->
                    <div class="form-section">
                        <h3>Want to Add Amenities?</h3>
                        <div class="workspace-options">
                            <div class="workspace-option" onclick="selectAmenity('projector')">
                                <h4>Projector</h4>
                                <p>HD projector for presentations</p>
                                <div class="price">₱100/hour</div>
                                <input type="radio" name="amenity" value="projector" style="display: none;">
                            </div>
                            
                            <div class="workspace-option" onclick="selectAmenity('wifi')">
                                <h4>WiFi</h4>
                                <p>High-speed internet connection</p>
                                <div class="price">₱10/hour</div>
                                <input type="radio" name="amenity" value="wifi" style="display: none;">
                            </div>
                            
                            <div class="workspace-option" onclick="selectAmenity('sound-system')">
                                <h4>Sound System</h4>
                                <p>Professional audio equipment</p>
                                <div class="price">₱100/hour</div>
                                <input type="radio" name="amenity" value="sound-system" style="display: none;">
                            </div>
                            
                            <div class="workspace-option" onclick="selectAmenity('whiteboard')">
                                <h4>Whiteboard</h4>
                                <p>Large whiteboard for brainstorming</p>
                                <div class="price">₱15/hour</div>
                                <input type="radio" name="amenity" value="whiteboard" style="display: none;">
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        Book Workspace
                    </button>
                </form>
            </div>
            
            <div class="navigation-links">
                <a href="login.php">Login</a>
                <a href="signup.php">Sign Up</a>
                <a href="booking.php">Book Workspace</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectAmenity(type) {
            // Remove selected class from all options
            document.querySelectorAll('.workspace-option').forEach(option => {
                option.classList.remove('selected');
                option.querySelector('input[type="radio"]').checked = false;
            });
            
            // Add selected class to clicked option and check radio
            event.currentTarget.classList.add('selected');
            event.currentTarget.querySelector('input[type="radio"]').checked = true;
        }
        
        // Set minimum date to today
        document.getElementById('date').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>
