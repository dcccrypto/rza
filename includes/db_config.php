<?php
// Database configuration
function init_db_connection() {
    // Check if running on local or production environment
    $is_local = ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_ADDR'] === '127.0.0.1');
    
    if ($is_local) {
        // Local XAMPP configuration
        $db_config = [
            'host' => 'localhost',
            'user' => 'aweb_rza',
            'pass' => 'gW1Ph$Mxn^XUxJmRsFVR',
            'name' => 'aweb_rza'
        ];
    } else {
        // cPanel production configuration
        $db_config = [
            'host' => 'localhost',
            'user' => 'aweb_rza',
            'pass' => 'gW1Ph$Mxn^XUxJmRsFVR',
            'name' => 'aweb_rza'
        ];
    }
    
    try {
        // Create PDO connection
        $dsn = "mysql:host={$db_config['host']};dbname={$db_config['name']};charset=utf8mb4";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        
        return new PDO($dsn, $db_config['user'], $db_config['pass'], $options);
    } catch (PDOException $e) {
        // Log error and display user-friendly message
        error_log("Database connection error: " . $e->getMessage());
        die("We're experiencing technical difficulties. Please try again later.");
    }
} 