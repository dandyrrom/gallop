<?php
session_start();
require_once '../db/db_connect.php'; 

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_SESSION['user_id'];
    $fname = trim($_POST['fname'] ?? '');
    $lname = trim($_POST['lname'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');

    if (empty($fname) || empty($lname) || empty($email) || empty($contact)) {
        $_SESSION['profile_error'] = 'All fields are required.';
        header('Location: ../dashboard.php#profile');
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['profile_error'] = 'Invalid email address.';
        header('Location: ../dashboard.php#profile');
        exit();
    }
    
    $check_stmt = $conn->prepare("SELECT userId FROM user WHERE email = ? AND userId != ?");
    $check_stmt->bind_param("si", $email, $userId);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    
    if ($check_result->num_rows > 0) {
        $_SESSION['profile_error'] = 'Email is already in use by another account.';
        header('Location: ../dashboard.php#profile');
        exit();
    }
    $check_stmt->close();

    $stmt = $conn->prepare("UPDATE user SET fname = ?, lname = ?, email = ?, contactNum = ? WHERE userId = ?");
    $stmt->bind_param("ssssi", $fname, $lname, $email, $contact, $userId);
    
    if ($stmt->execute()) {

        $_SESSION['fname'] = $fname;
        $_SESSION['lname'] = $lname;
        $_SESSION['email'] = $email;
        $_SESSION['full_name'] = $fname . ' ' . $lname;
        
        $_SESSION['profile_success'] = 'Profile updated successfully!';
    } else {
        $_SESSION['profile_error'] = 'Error updating profile. Please try again.';
    }
    
    $stmt->close();
    header('Location: ../dashboard.php#profile');
    exit();
}

header('Location: ../dashboard.php');
exit();
?>