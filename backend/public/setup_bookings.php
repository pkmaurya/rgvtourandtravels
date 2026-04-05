<?php
echo "Current script directory: " . __DIR__ . "\n";
$path = __DIR__ . '/../config/Database.php';
echo "Trying to require: " . $path . "\n";

if (file_exists($path)) {
    echo "File exists.\n";
    require_once $path;
} else {
    echo "File does NOT exist.\n";
    exit(1);
}

$database = new Database();
$db = $database->connect();

// Create Bookings Table
$sqlDrop = "DROP TABLE IF EXISTS bookings";
$db->exec($sqlDrop);

$sql = "CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    tour_id INT,
    tour_title VARCHAR(255),
    total_price DECIMAL(10, 2),
    status ENUM('pending', 'confirmed', 'rejected', 'completed') DEFAULT 'pending',
    booking_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (tour_id) REFERENCES tours(id) ON DELETE SET NULL
)";

try {
    $db->exec($sql);
    echo "Bookings table created successfully.\n";
} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}

// Seed Data
$bookings = [
    [
        'user_id' => 2, // John Doe
        'tour_id' => 1,
        'tour_title' => 'Goa Beach Party & Relax',
        'total_price' => 15000.00,
        'status' => 'confirmed',
        'booking_date' => date('Y-m-d H:i:s', strtotime('-2 days'))
    ],
    [
        'user_id' => 2,
        'tour_id' => 2,
        'tour_title' => 'Kerala Backwaters Bliss',
        'total_price' => 22000.00,
        'status' => 'pending',
        'booking_date' => date('Y-m-d H:i:s', strtotime('-1 day'))
    ],
    [
        'user_id' => 2,
        'tour_id' => 3,
        'tour_title' => 'Royal Rajasthan Tour',
        'total_price' => 35000.00,
        'status' => 'completed',
        'booking_date' => date('Y-m-d H:i:s', strtotime('-10 days'))
    ],
    [
        'user_id' => 2,
        'tour_id' => 1,
        'tour_title' => 'Goa Beach Party & Relax',
        'total_price' => 15000.00,
        'status' => 'rejected',
        'booking_date' => date('Y-m-d H:i:s', strtotime('-5 days'))
    ],
     [
        'user_id' => 2,
        'tour_id' => 2,
        'tour_title' => 'Kerala Backwaters Bliss',
        'total_price' => 22000.00,
        'status' => 'confirmed',
        'booking_date' => date('Y-m-d H:i:s', strtotime('-3 hours'))
    ]
];

$insertSql = "INSERT INTO bookings (user_id, tour_id, tour_title, total_price, status, booking_date) VALUES (:user_id, :tour_id, :tour_title, :total_price, :status, :booking_date)";
$stmt = $db->prepare($insertSql);

try {
    foreach ($bookings as $booking) {
        $stmt->execute($booking);
    }
    echo "Seeded " . count($bookings) . " bookings.\n";
} catch (PDOException $e) {
    echo "Insertion Error: " . $e->getMessage() . "\n";
}
?>
