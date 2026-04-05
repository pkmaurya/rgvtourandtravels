<?php
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

$queries = [
    // Create payments table
    "CREATE TABLE IF NOT EXISTS payments (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        booking_id INT NOT NULL,
        gateway VARCHAR(50) NOT NULL COMMENT 'razorpay or stripe',
        order_id VARCHAR(255) NULL COMMENT 'Razorpay order_id or Stripe payment_intent_id',
        transaction_id VARCHAR(255) NULL COMMENT 'Razorpay payment_id or Stripe charge_id',
        amount DECIMAL(10, 2) NOT NULL,
        currency VARCHAR(10) NOT NULL DEFAULT 'INR',
        status VARCHAR(50) NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE
    )",

    // Update bookings table
    "ALTER TABLE bookings ADD COLUMN payment_status VARCHAR(50) DEFAULT 'pending' AFTER status",
    "ALTER TABLE bookings ADD COLUMN payment_transaction_id VARCHAR(255) NULL AFTER payment_status"
];

foreach ($queries as $query) {
    try {
        $db->exec($query);
        echo "Executed: " . substr($query, 0, 50) . "...\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate column name") !== false) {
             echo "Skipped (Column exists): " . substr($query, 0, 50) . "...\n";
        } elseif (strpos($e->getMessage(), "already exists") !== false) {
             echo "Skipped (Table exists): " . substr($query, 0, 50) . "...\n";
        } else {
            echo "Error executing: " . substr($query, 0, 50) . "... - " . $e->getMessage() . "\n";
        }
    }
}

echo "Payment schema update completed.\n";
