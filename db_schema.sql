-- Database Schema for Seminar App

CREATE DATABASE IF NOT EXISTS db_seminar;
USE db_seminar;

-- Users Table
-- role: 'user', 'admin'
-- is_active: 0 (pending), 1 (active)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    role ENUM('user', 'admin') DEFAULT 'user',
    is_active TINYINT(1) DEFAULT 0,
    otp_code VARCHAR(6),
    otp_expiry DATETIME,
    profile_pic VARCHAR(255) DEFAULT 'default_profile.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Seminars Table
CREATE TABLE IF NOT EXISTS seminars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    time TIME NOT NULL,
    location VARCHAR(200),
    latitude DECIMAL(10, 8),   -- For Geolocation
    longitude DECIMAL(11, 8),  -- For Geolocation
    max_participants INT DEFAULT 100,
    price DECIMAL(10, 2) DEFAULT 0.00,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Registrations Table
CREATE TABLE IF NOT EXISTS registrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    seminar_id INT NOT NULL,
    registration_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'confirmed',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (seminar_id) REFERENCES seminars(id) ON DELETE CASCADE
);

-- Insert Default Admin
-- Password: admin (hashed)
INSERT IGNORE INTO users (name, email, password, phone, role, is_active) 
VALUES ('Administrator', 'admin@seminar.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '08123456789', 'admin', 1);
