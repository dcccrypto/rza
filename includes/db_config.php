<?php
// InfinityFree MySQL configuration
define('DB_HOST', 'sql312.infinityfree.com');
define('DB_USER', 'if0_35476246');
define('DB_PASS', 'rza123');
define('DB_NAME', 'if0_35476246_rza');

// Initialize database connection
function init_db_connection() {
    try {
        $pdo = new PDO(
            "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ]
        );
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed");
    }
} 