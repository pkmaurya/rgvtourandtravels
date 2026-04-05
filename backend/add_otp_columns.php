<?php
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

$queries = [
    "ALTER TABLE users ADD COLUMN otp VARCHAR(6) NULL",
    "ALTER TABLE users ADD COLUMN otp_expiry DATETIME NULL"
];

foreach ($queries as $query) {
    try {
        $db->exec($query);
        echo "Executed: $query\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate column name") !== false) {
             echo "Skipped (Column exists): $query\n";
        } else {
            echo "Error executing: $query - " . $e->getMessage() . "\n";
        }
    }
}

echo "Schema update for OTP completed.\n";
