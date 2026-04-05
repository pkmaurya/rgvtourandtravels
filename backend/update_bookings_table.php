<?php
require_once 'config/Database.php';

$database = new Database();
$db = $database->connect();

try {
    // Add start_date
    $sql = "ALTER TABLE bookings ADD COLUMN start_date DATE NULL AFTER booking_date";
    $db->exec($sql);
    echo "Added start_date column.\n";
} catch (PDOException $e) {
    echo "start_date column might already exist or error: " . $e->getMessage() . "\n";
}

try {
    // Add end_date
    $sql = "ALTER TABLE bookings ADD COLUMN end_date DATE NULL AFTER start_date";
    $db->exec($sql);
    echo "Added end_date column.\n";
} catch (PDOException $e) {
    echo "end_date column might already exist or error: " . $e->getMessage() . "\n";
}

try {
    // Add adults
    $sql = "ALTER TABLE bookings ADD COLUMN adults INT DEFAULT 1 AFTER end_date";
    $db->exec($sql);
    echo "Added adults column.\n";
} catch (PDOException $e) {
    echo "adults column might already exist or error: " . $e->getMessage() . "\n";
}

try {
    // Add children
    $sql = "ALTER TABLE bookings ADD COLUMN children INT DEFAULT 0 AFTER adults";
    $db->exec($sql);
    echo "Added children column.\n";
} catch (PDOException $e) {
    echo "children column might already exist or error: " . $e->getMessage() . "\n";
}

echo "Database update completed.\n";
?>
