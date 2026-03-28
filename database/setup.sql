CREATE DATABASE IF NOT EXISTS hotel_reservation_db;
USE hotel_reservation_db;

-- Drop existing tables to start fresh
DROP TABLE IF EXISTS reservations;
DROP TABLE IF EXISTS rooms;
DROP TABLE IF EXISTS admin_users;
DROP TABLE IF EXISTS contacts;

-- Rooms table
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    capacity_type ENUM('Single','Double','Family') NOT NULL,
    room_type ENUM('Regular','De Luxe','Suite') NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255),
    amenities TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Reservations table
CREATE TABLE reservations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    guest_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    room_id INT NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    guests INT NOT NULL DEFAULT 1,
    num_days INT NOT NULL DEFAULT 1,
    payment_type ENUM('Cash','Check','Credit Card') NOT NULL DEFAULT 'Cash',
    special_requests TEXT,
    base_price DECIMAL(10,2),
    discount_percent DECIMAL(5,2) DEFAULT 0,
    additional_charge_percent DECIMAL(5,2) DEFAULT 0,
    total_price DECIMAL(10,2),
    status ENUM('Pending','Confirmed','Cancelled','Completed') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- Admin users table
CREATE TABLE admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contact messages table
CREATE TABLE contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(200),
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- SEED DATA
-- ========================================

-- Single Rooms
INSERT INTO rooms (name, capacity_type, room_type, description, price, image, amenities) VALUES
('Single Regular Room', 'Single', 'Regular', 'A cozy and comfortable room designed for solo travelers. Features a single bed, clean modern furnishings, and essential amenities for a pleasant stay.', 100.00, 'assets/images/room_standard.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV'),
('Single De Luxe Room', 'Single', 'De Luxe', 'An upgraded single room with premium bedding, elegant decor, and enhanced amenities. Enjoy a more refined stay with added comforts and style.', 300.00, 'assets/images/room_deluxe.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV, Mini Bar, Coffee Maker'),
('Single Suite', 'Single', 'Suite', 'A luxurious single suite featuring a spacious layout, premium furnishings, and top-tier amenities. The ultimate solo retreat for discerning guests.', 500.00, 'assets/images/room_suite.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV, Mini Bar, Coffee Maker, Bathrobe, Lounge Access');

-- Double Rooms
INSERT INTO rooms (name, capacity_type, room_type, description, price, image, amenities) VALUES
('Double Regular Room', 'Double', 'Regular', 'A comfortable room with a double bed, perfect for couples or guests who prefer extra space. Clean design with all essential amenities included.', 200.00, 'assets/images/room_standard.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV, Safe'),
('Double De Luxe Room', 'Double', 'De Luxe', 'A spacious double room with premium furnishings, upgraded bedding, and elegant touches. Ideal for couples seeking comfort and sophistication.', 500.00, 'assets/images/room_deluxe.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV, Mini Bar, Coffee Maker, Safe, Balcony'),
('Double Suite', 'Double', 'Suite', 'An expansive double suite with separate living area, premium amenities, and luxurious finishes. Perfect for couples celebrating a special occasion.', 800.00, 'assets/images/room_suite.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV, Mini Bar, Coffee Maker, Safe, Balcony, Bathrobe, Jacuzzi');

-- Family Rooms
INSERT INTO rooms (name, capacity_type, room_type, description, price, image, amenities) VALUES
('Family Regular Room', 'Family', 'Regular', 'A spacious room designed for families, featuring multiple beds and ample space for everyone. Comfortable and practical for a family getaway.', 500.00, 'assets/images/room_standard.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV, Safe, Extra Beds'),
('Family De Luxe Room', 'Family', 'De Luxe', 'An upgraded family room with premium furnishings, extra space, and enhanced amenities. Your family deserves the best during their vacation.', 750.00, 'assets/images/room_deluxe.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV, Mini Bar, Coffee Maker, Safe, Extra Beds, Balcony'),
('Family Suite', 'Family', 'Suite', 'Our most spacious accommodation with a full living area, multiple bedrooms, and premium amenities. The ultimate family experience in luxury.', 1000.00, 'assets/images/room_suite.png', 'Free Wi-Fi, Air Conditioning, Flat-Screen TV, Mini Bar, Coffee Maker, Safe, Extra Beds, Balcony, Bathrobe, Lounge Access');

-- Seed admin (password: admin123)
INSERT INTO admin_users (username, password, full_name) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator');


-- Create guests table
CREATE TABLE IF NOT EXISTS guests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(20),
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Add guest_user_id column to reservations (links booking to guest account)
ALTER TABLE reservations ADD COLUMN guest_user_id INT NULL AFTER id;
ALTER TABLE reservations ADD FOREIGN KEY (guest_user_id) REFERENCES guests(id) ON DELETE SET NULL;