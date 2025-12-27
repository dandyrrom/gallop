<?php
session_start(); // Start the session at the very beginning
require_once 'db/db_connect.php'; // Include the database connection

$error_message = '';
// $success_message = ''; // We don't need success message here, we redirect

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = 'Please fill in both username and password.';
    } else {
        // Prepare statement to prevent SQL injection
        // Fetch all required columns
        $stmt = $conn->prepare("SELECT userId, userName, password, fname, lname, email, contactNum, role FROM user WHERE userName = ?");
        if ($stmt === false) {
            // Error preparing statement (log this error properly in a real application)
            $error_message = 'An error occurred. Please try again later.';
            // Log error: error_log("Prepare failed: (" . $conn->errno . ") " . $conn->error);
        } else {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows === 1) {
                $user = $result->fetch_assoc();

                // Verify the password
                if (password_verify($password, $user['password'])) {
                    // Password is correct, set session variables
                    $_SESSION['user_id'] = $user['userId'];
                    $_SESSION['username'] = $user['userName'];
                    $_SESSION['fname'] = $user['fname'];
                    $_SESSION['lname'] = $user['lname']; // <-- Added lname
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['contactNum'] = $user['contactNum']; // <-- Added contactNum
                    $_SESSION['role'] = $user['role'];

                    // Regenerate session ID for security
                    session_regenerate_id(true);

                    // Redirect to the dashboard
                    header('Location: dashboard.php');
                    exit; // Important to stop script execution after redirect

                } else {
                    // Invalid password
                    $error_message = 'Invalid username or password.';
                }
            } else {
                // User not found
                $error_message = 'Invalid username or password.';
            }
            $stmt->close();
        }
    }
    // Close connection only if statement preparation failed or login failed
     if ($stmt === false || $result->num_rows !== 1 || !password_verify($password, $user['password'] ?? '')) {
        $conn->close();
     }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Gallop</title>
    <!-- Use local Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Link correct CSS -->
    <link rel="stylesheet" href="css/style_inout.css">
    <link rel="stylesheet" href="css/header.css"> <!-- Link header CSS -->
    <link rel="icon" href="assets/horse-head-faviconv.png" type="image/png"> <!-- Added Favicon -->
</head>
<body>
    <?php include '_includes/header.html'; ?>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your account</p>
            </div>

            <?php if ($error_message): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <!-- No need for success message here as we redirect -->

            <form method="POST" action="login.php"> <!-- Ensure action points to self -->
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text"
                           class="form-control"
                           id="username"
                           name="username"
                           placeholder="Enter your username"
                           value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password"
                           class="form-control"
                           id="password"
                           name="password"
                           placeholder="Enter your password"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Sign In
                </button>
            </form>

            <div class="auth-links">
                <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
            </div>
        </div>
    </div>

    <!-- Footer Include -->
    <?php include '_includes/footer.html'; ?>
    <!-- Use local Bootstrap JS -->
    <script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>

