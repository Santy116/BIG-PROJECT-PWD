-- Database: walbayexpress_crud

CREATE DATABASE IF NOT EXISTS walbayexpress_crud;
USE walbayexpress_crud;

-- Admin table
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    last_login TIMESTAMP NULL,
    is_active BOOLEAN DEFAULT TRUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Products table
CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255),
    stock INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin account (password: admin123)
INSERT INTO admins (name, email, password) VALUES 
('Admin', 'admin@walbayexpress.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample products
INSERT INTO products (name, description, price, category, stock) VALUES
('Smartphone X', 'Latest smartphone with advanced features', 599.99, 'electronics', 50),
('Wireless Headphones', 'Noise cancelling wireless headphones', 199.99, 'electronics', 30),
('Running Shoes', 'Comfortable running shoes for all terrains', 89.99, 'sports', 100),
('Coffee Maker', 'Automatic coffee maker with timer', 49.99, 'home', 25),
('Smart Watch', 'Fitness tracker with heart rate monitor', 129.99, 'electronics', 15);


-- Tambah kolom diskon di tabel products
ALTER TABLE products ADD discount DECIMAL(5,2) DEFAULT 0.00;

-- Buat tabel baru untuk pesan kontak
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Buat tabel users untuk pelanggan
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tambah data produk contoh dengan diskon
UPDATE products SET discount = 15.00 WHERE id = 1;
UPDATE products SET discount = 20.00 WHERE id = 2;