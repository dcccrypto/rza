<?php
require_once 'db_config.php';

try {
    // Get database connection using Supabase credentials
    $conn = init_db_connection();
    
    // Set error mode to throw exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Helper function for prepared statements
    function prepare_stmt($sql) {
        global $conn;
        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            throw new Exception("Prepare failed: " . $conn->errorInfo()[2]);
        }
        return $stmt;
    }
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Session timeout setting (30 minutes)
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);
?> 