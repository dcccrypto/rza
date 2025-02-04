<?php
require_once dirname(__FILE__) . '/db.php';
require_once dirname(__FILE__) . '/session_manager.php';

// Initialize secure session
init_secure_session();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riget Zoo Adventures - Wildlife Safari, Hotel & Education</title>
    
    <!-- Meta tags for accessibility and SEO -->
    <meta name="description" content="Experience wildlife up close at Riget Zoo Adventures. Book safari tours, luxury hotel stays, and access educational resources.">
    <meta name="keywords" content="zoo, safari, wildlife, hotel, education, conservation">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/rza/assets/css/custom.css" rel="stylesheet">
    
    <!-- Accessibility enhancements -->
    <script>
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.documentElement.setAttribute('data-bs-theme', 'dark');
        }
    </script>
</head>
<body>
    <!-- Skip to main content link for screen readers -->
    <a href="#main-content" class="visually-hidden-focusable">Skip to main content</a>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="/rza/">
                <i class="bi bi-tree-fill me-2"></i>
                Riget Zoo Adventures
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/rza/bookings/tickets.php">
                            <i class="bi bi-ticket-perforated"></i> Book Tickets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/rza/bookings/hotel.php">
                            <i class="bi bi-building"></i> Hotel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/rza/education/resources.php">
                            <i class="bi bi-book"></i> Education
                        </a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/rza/account/profile.php">
                                <i class="bi bi-person-circle"></i> My Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/rza/account/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/rza/account/login.php">
                                <i class="bi bi-box-arrow-in-right"></i> Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/rza/account/register.php">
                                <i class="bi bi-person-plus"></i> Register
                            </a>
                        </li>
                    <?php endif; ?>
                    <!-- Accessibility controls -->
                    <li class="nav-item">
                        <button class="btn btn-link nav-link" onclick="toggleDarkMode()" aria-label="Toggle dark mode">
                            <i class="bi bi-moon-stars"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if ($message = get_flash_message()): ?>
        <div class="container mt-3">
            <div class="alert alert-<?= $message['type'] ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message['text']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main content -->
    <main id="main-content">
    <div class="container mt-4">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-<?= $_SESSION['message_type'] ?? 'info' ?> alert-dismissible fade show">
                <?= $_SESSION['message'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
        <?php endif; ?> 