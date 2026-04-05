<?php
require_once 'config/Database.php';
require_once 'models/Booking.php';

$database = new Database();
$db = $database->connect();
$booking = new Booking($db);

echo "Fetching all bookings...\n";
$stmt = $booking->getAll(10, 0);
$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    print_r($row);
    $count++;
}
echo "Total bookings fetched: $count\n";

echo "Testing filters (status='pending')...\n";
$stmt = $booking->getAll(10, 0, ['status' => 'pending']);
$count = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $count++;
}
echo "Pending bookings: $count\n";
?>
