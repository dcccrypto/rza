<?php
// Start output buffering to prevent header issues
ob_start();

// Session configuration must happen before session_start()
$session_params = [
    'lifetime' => 1800,
    'path' => '/rza',
    'domain' => $_SERVER['HTTP_HOST'],
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Lax'
];

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params($session_params);
    session_start();
}

// CSRF token management
function generate_csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Authentication checks
function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        set_flash_message('Please login to access this page.', 'warning');
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: /rza/account/login.php');
        exit;
    }
    
    // Check session timeout (30 minutes)
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
        session_unset();
        session_destroy();
        set_flash_message('Your session has expired. Please login again.', 'warning');
        header('Location: /rza/account/login.php');
        exit;
    }
    $_SESSION['last_activity'] = time();
}

// Update user's last login time
function update_last_login($user_id) {
    global $conn;
    $stmt = prepare_stmt("UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();
}

// Set flash message
function set_flash_message($message, $type = 'info') {
    $_SESSION['message'] = $message;
    $_SESSION['message_type'] = $type;
}

// Get flash message and clear it
function get_flash_message() {
    if (isset($_SESSION['message'])) {
        $message = [
            'text' => $_SESSION['message'],
            'type' => $_SESSION['message_type'] ?? 'info'
        ];
        unset($_SESSION['message'], $_SESSION['message_type']);
        return $message;
    }
    return null;
}

// Form helper to add CSRF token
function csrf_field() {
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(generate_csrf_token()) . '">';
}

// Validate form submission
function validate_form() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($_POST['csrf_token']) || !verify_csrf_token($_POST['csrf_token'])) {
            set_flash_message('Invalid form submission.', 'danger');
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
    }
}

// Initialize session with secure settings
function init_secure_session() {
    // Session is already started at the beginning of this file
    // Just regenerate session ID periodically
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}
?> 