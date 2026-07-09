CREATE DATABASE IF NOT EXISTS electroshop_db;

USE electroshop_db;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','seller','buyer') NOT NULL DEFAULT 'buyer',
    status ENUM('active','pending','blocked') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    seller_id INT,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(12,2) NOT NULL,
    stock INT NOT NULL DEFAULT 0,
    category_id INT,
    image VARCHAR(255),
    warranty_months INT DEFAULT 12,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (seller_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE SET NULL
);

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    buyer_id INT,
    invoice_no VARCHAR(30),
    total_amount DECIMAL(12,2),
    shipping_address TEXT,
    shipping_cost DECIMAL(12,2),
    status VARCHAR(50) DEFAULT 'pending_payment',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (buyer_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    product_id INT,
    quantity INT,
    price DECIMAL(12,2),
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
);

CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT,
    payment_method VARCHAR(50),
    proof VARCHAR(255),
    status VARCHAR(50) DEFAULT 'waiting_verification',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT,
    user_id INT,
    rating INT,
    comment TEXT,
    photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE warranties (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_item_id INT,
    user_id INT,
    serial_number VARCHAR(100),
    claim_description TEXT,
    proof VARCHAR(255),
    status VARCHAR(50) DEFAULT 'submitted',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS email_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    subject VARCHAR(150),
    message TEXT,
    status VARCHAR(30) DEFAULT 'sent',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS system_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) NOT NULL UNIQUE,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO system_settings(setting_key,setting_value) VALUES
('store_name','Digital Zone'),
('default_shipping_regular','15000'),
('default_shipping_express','30000'),
('email_notification','active')
ON DUPLICATE KEY UPDATE setting_value=VALUES(setting_value);

INSERT INTO users(name,email,password,role,status) VALUES
('Administrator','admin@digitalzone.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','admin','active'),
('Seller Digital Zone','seller@digitalzone.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','seller','active'),
('Buyer Demo','buyer@digitalzone.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','buyer','active');

INSERT INTO categories(name,description) VALUES
('Laptop','Laptop kerja, gaming, dan kuliah'),
('Smartphone','HP Android dan iOS'),
('Aksesoris','Keyboard, mouse, headset, charger');

INSERT INTO products
(seller_id,name,description,price,stock,category_id,image,warranty_months)
VALUES
(2,'Samsung Galaxy A56 5G','Smartphone kelas menengah premium dengan layar Super AMOLED 120Hz, kamera 50MP, baterai 5000mAh, dan dukungan update Android jangka panjang',6999000,10,2,'samsung-a56-5g.jpg',12),
(2,'Xiaomi 14T','Smartphone performa tinggi dengan chipset flagship, kamera Leica 50MP, layar AMOLED 144Hz, dan fast charging 67W',7999000,10,2,'xiaomi-14t.jpg',12),
(2,'Realme GT 6','Smartphone gaming dan multitasking dengan Snapdragon 8s Gen 3, layar AMOLED 120Hz, baterai 5500mAh, dan pengisian cepat 120W',8999000,10,2,'realme-gt6.jpg',12),

(2,'ASUS Vivobook 14 A1404','Laptop ringan untuk kuliah dan kerja dengan Intel Core terbaru, RAM 8GB, SSD 512GB, layar FHD 14 inci',8449000,10,1,'asus-vivobook-a1404.jpg',24),
(2,'Acer Aspire Lite 14','Laptop produktivitas dengan Intel Core i5, RAM 16GB, SSD 512GB, cocok untuk coding dan multitasking',9949000,10,1,'acer-aspire-lite14.jpg',12),
(2,'ASUS Vivobook 15 Touch','Laptop premium dengan layar sentuh 15.6 inci, Intel Core 5, RAM 16GB, SSD 1TB untuk produktivitas dan desain',13689000,10,1,'asus-vivobook15-touch.jpg',24),

(2,'iPhone 13 128GB','iPhone dengan chip A15 Bionic, layar Super Retina XDR 6.1 inci, kamera ganda 12MP, cocok untuk kuliah, fotografi, dan multitasking',10299000,10,2,'iphone13.jpg',12),
(2,'iPhone 15 128GB','iPhone terbaru dengan Dynamic Island, kamera 48MP, chip A16 Bionic, dan port USB-C',12999000,10,2,'iphone15.jpg',12),

(2,'Logitech K120 Keyboard','Keyboard kabel yang nyaman untuk mengetik tugas, tahan lama dan cocok untuk pelajar maupun mahasiswa',280000,20,3,'logitech-k120.jpg',12),
(2,'Rexus SH600 Mechanical Keyboard','Keyboard mechanical 65 persen dengan desain compact, nyaman untuk belajar dan gaming ringan',229000,15,3,'rexus-sh600.jpg',12),
(2,'Logitech G203 Gaming Mouse','Mouse dengan sensor akurat dan desain ergonomis, cocok untuk kuliah, desain, dan gaming',177000,20,3,'logitech-g203.jpg',12),
(2,'MOFii Wireless Mouse','Mouse wireless rechargeable yang ringan dan praktis untuk penggunaan sehari-hari',85000,25,3,'mofii-wireless.jpg',12);