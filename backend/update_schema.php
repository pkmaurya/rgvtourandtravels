<?php
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

$queries = [
    "ALTER TABLE users ADD COLUMN first_name VARCHAR(100) NOT NULL AFTER id",
    "ALTER TABLE users ADD COLUMN middle_name VARCHAR(100) AFTER first_name",
    "ALTER TABLE users ADD COLUMN last_name VARCHAR(100) NOT NULL AFTER middle_name",
    "ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER email",
    "ALTER TABLE users DROP COLUMN name", // Dropping the old 'name' column as we have split it
    
    // Tour Table Updates
    "ALTER TABLE tours ADD COLUMN slug VARCHAR(191) UNIQUE AFTER title",
    "ALTER TABLE tours ADD COLUMN category VARCHAR(50) AFTER destination_id",
    "ALTER TABLE tours ADD COLUMN status VARCHAR(20) DEFAULT 'active' AFTER category",
    "ALTER TABLE tours ADD COLUMN max_travelers INT DEFAULT 12 AFTER duration",
    "ALTER TABLE tours ADD COLUMN available_dates JSON AFTER max_travelers",
    "ALTER TABLE tours ADD COLUMN highlights JSON AFTER description",
    "ALTER TABLE tours ADD COLUMN sale_price DECIMAL(10,2) NULL AFTER price"
];

foreach ($queries as $query) {
    try {
        $db->exec($query);
        echo "Executed: $query\n";
    } catch (PDOException $e) {
        // Ignore if column already exists or other specific errors if needed, but for now show error
        // simplified checking, mostly for adding columns.
        if (strpos($e->getMessage(), "Duplicate column name") !== false) {
             echo "Skipped (Column exists): $query\n";
        } elseif (strpos($e->getMessage(), "check that column/key exists") !== false) { // For DROP COLUMN
             echo "Skipped (Column doesn't exist): $query\n";
        } else {
            echo "Error executing: $query - " . $e->getMessage() . "\n";
        }
    }
}

echo "Schema update completed.\n";
