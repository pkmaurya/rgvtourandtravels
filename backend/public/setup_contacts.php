<?php
require_once __DIR__ . '/../config/Database.php';

$database = new Database();
$db = $database->connect();

// Create Contacts Table
$sql = "CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

try {
    $db->exec($sql);
    echo "Contacts table created successfully.\n";
} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}
?>
