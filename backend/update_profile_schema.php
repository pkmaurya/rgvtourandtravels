<?php
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

$queries = [
    "ALTER TABLE users ADD COLUMN profile_image VARCHAR(255) DEFAULT NULL AFTER role",
    "ALTER TABLE users ADD COLUMN bio TEXT DEFAULT NULL AFTER profile_image",
    "ALTER TABLE users ADD COLUMN social_links TEXT DEFAULT NULL AFTER bio" // Storing JSON as TEXT
];

foreach ($queries as $query) {
    try {
        $db->exec($query);
        echo "Executed: $query\n";
    } catch (PDOException $e) {
        if (strpos($e->getMessage(), "Duplicate column name") !== false) {
            echo "Skipped (Column exists): $query\n";
        } else {
            echo "Error executing $query: " . $e->getMessage() . "\n";
        }
    }
}

echo "Schema update completed.\n";
?>
