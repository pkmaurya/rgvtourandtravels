<?php
require_once __DIR__ . '/../config/Database.php';

$database = new Database();
$db = $database->connect();

$sql = "CREATE TABLE IF NOT EXISTS settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `key` VARCHAR(255) NOT NULL UNIQUE,
    `value` TEXT,
    `group` VARCHAR(50) DEFAULT 'general',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

try {
    $db->exec($sql);
    echo "Table 'settings' created successfully.\n";
    
    // Seed default settings
    $defaults = [
        ['site_title', 'Incredible India Tours', 'general'],
        ['site_description', 'Discover the beauty of India with our curated tours.', 'general'],
        ['site_logo', '', 'general'],
        ['contact_email', 'info@incredibleindia.com', 'general'],
        ['contact_phone', '+91 1234567890', 'general'],
        ['currency_symbol', '₹', 'payment'],
        ['razorpay_key_id', '', 'payment'],
        ['razorpay_key_secret', '', 'payment'],
        ['stripe_publishable_key', '', 'payment'],
        ['stripe_secret_key', '', 'payment'],
        ['smtp_host', '', 'email'],
        ['smtp_port', '587', 'email'],
        ['smtp_user', '', 'email'],
        ['smtp_pass', '', 'email']
    ];

    $stmt = $db->prepare("INSERT IGNORE INTO settings (`key`, `value`, `group`) VALUES (?, ?, ?)");
    
    foreach ($defaults as $setting) {
        $stmt->execute($setting);
    }
    echo "Default settings seeded.\n";

} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage();
}
?>
