-- ==========================================================
-- Database Schema for Ngopidea Artisanal Coffee
-- Sesuai dengan spesifikasi Proyek Akhir Basis Data
-- ==========================================================

-- ==========================================================
-- A. DATA MASTER & REFERENSI
-- ==========================================================

-- 2. roles
CREATE TABLE `roles` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_name` VARCHAR(50) NOT NULL UNIQUE COMMENT 'Admin, Kasir, Pelanggan'
);

-- 1. users
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `role_id` INT NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`role_id`) REFERENCES `roles`(`id`) ON DELETE RESTRICT
);

-- 3. customers
CREATE TABLE `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20),
  `address` TEXT,
  `total_points` INT DEFAULT 0,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- 4. employees
CREATE TABLE `employees` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `position` VARCHAR(50),
  `hire_date` DATE,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
);

-- 5. categories
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_name` VARCHAR(100) NOT NULL COMMENT 'Kopi, Teh, Pastry',
  `description` TEXT
);

-- 6. products
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT NOT NULL,
  `product_name` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `price` DECIMAL(10,2) NOT NULL,
  `image_url` VARCHAR(255),
  `is_active` BOOLEAN DEFAULT TRUE,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE RESTRICT
);

-- 7. ingredients
CREATE TABLE `ingredients` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ingredient_name` VARCHAR(100) NOT NULL,
  `unit` VARCHAR(20) NOT NULL COMMENT 'g, ml, pcs',
  `stock_quantity` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `min_stock_level` DECIMAL(10,2) NOT NULL DEFAULT 10
);

-- 8. product_recipes
CREATE TABLE `product_recipes` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `ingredient_id` INT NOT NULL,
  `quantity_needed` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`) ON DELETE CASCADE
);

-- 9. suppliers
CREATE TABLE `suppliers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `supplier_name` VARCHAR(100) NOT NULL,
  `contact_name` VARCHAR(100),
  `phone` VARCHAR(20),
  `address` TEXT
);

-- 10. payment_methods
CREATE TABLE `payment_methods` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `method_name` VARCHAR(50) NOT NULL COMMENT 'Cash, QRIS, Debit',
  `is_active` BOOLEAN DEFAULT TRUE
);

-- 11. tables
CREATE TABLE `tables` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `table_number` VARCHAR(10) NOT NULL UNIQUE,
  `capacity` INT NOT NULL DEFAULT 2,
  `status` ENUM('Available', 'Occupied', 'Reserved') DEFAULT 'Available'
);

-- 12. promotions
CREATE TABLE `promotions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `promo_code` VARCHAR(50) NOT NULL UNIQUE,
  `discount_percentage` DECIMAL(5,2) NOT NULL,
  `start_date` DATE NOT NULL,
  `end_date` DATE NOT NULL,
  `min_purchase` DECIMAL(10,2) DEFAULT 0
);


-- ==========================================================
-- B. DATA TRANSAKSI
-- ==========================================================

-- 13. orders
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT,
  `employee_id` INT NOT NULL,
  `table_id` INT,
  `payment_method_id` INT NOT NULL,
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `total_amount` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `status` ENUM('Pending', 'Processing', 'Completed', 'Cancelled') DEFAULT 'Pending',
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`employee_id`) REFERENCES `employees`(`id`) ON DELETE RESTRICT,
  FOREIGN KEY (`table_id`) REFERENCES `tables`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods`(`id`) ON DELETE RESTRICT
);

-- 14. order_details
CREATE TABLE `order_details` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE RESTRICT
);

-- 15. reservations
CREATE TABLE `reservations` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `table_id` INT NOT NULL,
  `reservation_time` DATETIME NOT NULL,
  `guest_count` INT NOT NULL,
  `status` ENUM('Pending', 'Confirmed', 'Cancelled', 'Completed') DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`table_id`) REFERENCES `tables`(`id`) ON DELETE CASCADE
);

-- 16. purchase_orders
CREATE TABLE `purchase_orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `supplier_id` INT NOT NULL,
  `order_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `status` ENUM('Draft', 'Ordered', 'Received', 'Cancelled') DEFAULT 'Draft',
  `total_cost` DECIMAL(10,2) DEFAULT 0,
  FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE RESTRICT
);

-- 17. purchase_order_details
CREATE TABLE `purchase_order_details` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `purchase_order_id` INT NOT NULL,
  `ingredient_id` INT NOT NULL,
  `quantity` DECIMAL(10,2) NOT NULL,
  `unit_price` DECIMAL(10,2) NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`) ON DELETE RESTRICT
);

-- 18. inventory_transactions
CREATE TABLE `inventory_transactions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `ingredient_id` INT NOT NULL,
  `transaction_type` ENUM('In', 'Out', 'Adjustment') NOT NULL,
  `quantity` DECIMAL(10,2) NOT NULL,
  `transaction_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `remarks` TEXT,
  FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`) ON DELETE CASCADE
);

-- 19. order_promotions
CREATE TABLE `order_promotions` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `promotion_id` INT NOT NULL,
  `discount_applied` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`promotion_id`) REFERENCES `promotions`(`id`) ON DELETE RESTRICT
);

-- 20. audit_logs
CREATE TABLE `audit_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `table_name` VARCHAR(50) NOT NULL,
  `record_id` INT NOT NULL,
  `action` VARCHAR(20) NOT NULL COMMENT 'INSERT, UPDATE, DELETE',
  `old_value` TEXT,
  `new_value` TEXT,
  `changed_by` INT,
  `changed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`changed_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
);
