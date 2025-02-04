<?php
session_start();

// Clear all session variables
$_SESSION = array();

// Destroy the session cookie
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time() - 3600, '/');
}

// Destroy the session
session_destroy();

// Redirect to login page with message
session_start();
$_SESSION['message'] = 'You have been logged out successfully.';
$_SESSION['message_type'] = 'info';
header('Location: login.php');
exit; 