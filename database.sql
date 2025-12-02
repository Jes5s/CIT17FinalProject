-- Create database (if not exists)
);


-- 2. Services
CREATE TABLE services (
service_id INT AUTO_INCREMENT PRIMARY KEY,
service_name VARCHAR(100) NOT NULL,
description TEXT,
duration INT NOT NULL,
price DECIMAL(10,2) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- 3. Appointments
CREATE TABLE appointments (
appointment_id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT NOT NULL,
therapist_id INT DEFAULT NULL,
service_id INT NOT NULL,
appointment_date DATE NOT NULL,
start_time TIME NOT NULL,
end_time TIME NOT NULL,
status ENUM('pending','confirmed','completed','canceled') DEFAULT 'pending',
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
FOREIGN KEY (therapist_id) REFERENCES users(user_id) ON DELETE SET NULL,
FOREIGN KEY (service_id) REFERENCES services(service_id) ON DELETE CASCADE
);


-- 4. Payments
CREATE TABLE payments (
payment_id INT AUTO_INCREMENT PRIMARY KEY,
appointment_id INT NOT NULL,
amount DECIMAL(10,2) NOT NULL,
payment_method ENUM('cash','credit_card','paypal') DEFAULT 'cash',
payment_status ENUM('paid','unpaid','refunded') DEFAULT 'unpaid',
transaction_id VARCHAR(100),
payment_date TIMESTAMP NULL,
FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id) ON DELETE CASCADE
);


-- 5. Availability
CREATE TABLE availability (
availability_id INT AUTO_INCREMENT PRIMARY KEY,
therapist_id INT NOT NULL,
date DATE NOT NULL,
start_time TIME NOT NULL,
end_time TIME NOT NULL,
FOREIGN KEY (therapist_id) REFERENCES users(user_id) ON DELETE CASCADE
);


-- 6. Reviews
CREATE TABLE reviews (
review_id INT AUTO_INCREMENT PRIMARY KEY,
appointment_id INT NOT NULL,
user_id INT NOT NULL,
rating INT NOT NULL CHECK (rating >= 1 AND rating <= 5),
comment TEXT,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (appointment_id) REFERENCES appointments(appointment_id) ON DELETE CASCADE,
FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);


-- Optionally add a sample admin and sample service
INSERT INTO users (full_name, email, phone_number, password, role)
VALUES ('Admin User','admin@wellness.test','', '" . md5('admin123') . "', 'admin');


INSERT INTO services (service_name, description, duration, price)
VALUES
('Relaxing Massage','60-minute relaxing full-body massage',60,120.00),
('Deep Tissue Massage','50-minute deep tissue session for sore muscles',50,150.00);