-- Select the correct database
USE aweb_rza;

-- Drop existing tables in reverse order of dependencies
DROP TABLE IF EXISTS aweb_quiz_attempts;
DROP TABLE IF EXISTS aweb_quiz_questions;
DROP TABLE IF EXISTS aweb_quizzes;
DROP TABLE IF EXISTS aweb_loyalty_points;
DROP TABLE IF EXISTS aweb_bookings;
DROP TABLE IF EXISTS aweb_users;

-- Create tables with proper prefixes
CREATE TABLE aweb_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    last_login DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE aweb_bookings (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE aweb_loyalty_points (
    user_id INT PRIMARY KEY,
    points INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES aweb_users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE aweb_quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE aweb_quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question TEXT NOT NULL,
    options JSON NOT NULL,
    correct_answer VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES aweb_quizzes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE aweb_quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score INT NOT NULL,
    max_score INT NOT NULL,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES aweb_users(id) ON DELETE CASCADE,
    FOREIGN KEY (quiz_id) REFERENCES aweb_quizzes(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Add indexes for better performance
CREATE INDEX idx_booking_date ON aweb_bookings(booking_date);
CREATE INDEX idx_user_bookings ON aweb_bookings(user_id, created_at);
CREATE INDEX idx_quiz_attempts ON aweb_quiz_attempts(user_id, quiz_id);

-- Set proper character set and collation
ALTER DATABASE aweb_rza CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci; 