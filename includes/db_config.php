<?php
// Supabase database configuration using connection string
define('DB_CONNECTION_STRING', 'postgresql://postgres:PHk6S!#qyCQ83L76XUde@db.coaoxbogghtseqifpbob.supabase.co:5432/postgres');

// Initialize database connection
function init_db_connection() {
    try {
        $pdo = new PDO(DB_CONNECTION_STRING, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::ATTR_PERSISTENT => true
        ]);
        
        // Set the schema search path for PostgreSQL
        $pdo->exec('SET search_path TO public');
        
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database connection failed: " . $e->getMessage());
        throw new Exception("Database connection failed");
    }
} 