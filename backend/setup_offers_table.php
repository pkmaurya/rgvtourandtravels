<?php
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

try {
    // Create offers table
    $sql_offers = "CREATE TABLE IF NOT EXISTS offers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        discount_type ENUM('percentage', 'fixed') NOT NULL,
        discount_value DECIMAL(10, 2) NOT NULL,
        coupon_code VARCHAR(50) UNIQUE DEFAULT NULL,
        start_date DATETIME NOT NULL,
        end_date DATETIME NOT NULL,
        min_booking_amount DECIMAL(10, 2) DEFAULT 0.00,
        priority INT DEFAULT 0,
        status ENUM('active', 'inactive') DEFAULT 'active',
        scope ENUM('all', 'specific_tours', 'specific_destinations') DEFAULT 'all',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $db->exec($sql_offers);
    echo "Table 'offers' created or already exists.\n";

    // Create offer_tour_map table
    $sql_map = "CREATE TABLE IF NOT EXISTS offer_tour_map (
        offer_id INT NOT NULL,
        tour_id INT NOT NULL,
        PRIMARY KEY (offer_id, tour_id),
        FOREIGN KEY (offer_id) REFERENCES offers(id) ON DELETE CASCADE,
        FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE CASCADE
    )";
    $db->exec($sql_map);
    echo "Table 'offer_tour_map' created or already exists.\n";

} catch(PDOException $e) {
    echo "Error creating tables: " . $e->getMessage() . "\n";
}
?>
