<?php
require_once dirname(__FILE__) . '/db_config.php';

try {
    // Get database connection using configuration
    $conn = init_db_connection();
    
    // Set error mode to throw exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Helper function for prepared statements
    function prepare_stmt($sql) {
        global $conn;
        try {
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                throw new Exception("Failed to prepare statement: " . implode(" ", $conn->errorInfo()));
            }
            return $stmt;
        } catch (PDOException $e) {
            error_log("Database prepare error: " . $e->getMessage());
            throw new Exception("Database error occurred");
        }
    }
    
    // Helper function for executing prepared statements with parameters
    function execute_stmt($stmt, $params = []) {
        try {
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Database execute error: " . $e->getMessage());
            throw new Exception("Database error occurred");
        }
    }
    
} catch (Exception $e) {
    error_log("Database connection error: " . $e->getMessage());
    die("We're experiencing technical difficulties. Please try again later.");
}

// Session timeout setting (30 minutes)
ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);
?> 