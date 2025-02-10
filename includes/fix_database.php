<?php
require_once 'db.php';

// Temporarily disable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 0");

// Drop existing tables in reverse order of dependencies
$drop_tables = [
    "DROP TABLE IF EXISTS aweb_quiz_attempts",
    "DROP TABLE IF EXISTS aweb_quiz_questions",
    "DROP TABLE IF EXISTS aweb_quizzes",
    "DROP TABLE IF EXISTS aweb_loyalty_points",
    "DROP TABLE IF EXISTS aweb_bookings",
    "DROP TABLE IF EXISTS aweb_users"
];

foreach ($drop_tables as $sql) {
    if (!$conn->query($sql)) {
        die("Error dropping table: " . $conn->error);
    }
}

// Re-enable foreign key checks
$conn->query("SET FOREIGN_KEY_CHECKS = 1");

// Create tables in correct order
$tables = [
    "CREATE TABLE aweb_users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(255) UNIQUE NOT NULL,
        password VARCHAR(255) NOT NULL,
        last_login DATETIME DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE aweb_bookings (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        type ENUM('ticket', 'hotel') NOT NULL,
        booking_date DATE NOT NULL,
        quantity INT,
        room_type ENUM('Standard', 'VIP'),
        total_amount DECIMAL(10,2) NOT NULL,
        details JSON,
        status ENUM('active', 'cancelled') DEFAULT 'active',
        cancelled_at TIMESTAMP NULL DEFAULT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES aweb_users(id) ON DELETE CASCADE
    )",
    
    "CREATE TABLE aweb_loyalty_points (
        user_id INT PRIMARY KEY,
        points INT DEFAULT 0,
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES aweb_users(id) ON DELETE CASCADE
    )",
    
    "CREATE TABLE aweb_quizzes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE aweb_quiz_questions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        quiz_id INT NOT NULL,
        question TEXT NOT NULL,
        options JSON NOT NULL,
        correct_answer VARCHAR(255) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (quiz_id) REFERENCES aweb_quizzes(id) ON DELETE CASCADE
    )",
    
    "CREATE TABLE aweb_quiz_attempts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        quiz_id INT NOT NULL,
        score INT NOT NULL,
        max_score INT NOT NULL,
        attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES aweb_users(id) ON DELETE CASCADE,
        FOREIGN KEY (quiz_id) REFERENCES aweb_quizzes(id) ON DELETE CASCADE
    )"
];

foreach ($tables as $sql) {
    if (!$conn->query($sql)) {
        die("Error creating table: " . $conn->error);
    }
}

echo "Database structure updated successfully!";
?> 