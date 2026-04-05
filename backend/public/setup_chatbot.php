<?php
require_once __DIR__ . '/../config/Database.php';

$database = new Database();
$db = $database->connect();

// Create Chatbot QA Table
$sql = "CREATE TABLE IF NOT EXISTS chatbot_qa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question TEXT NOT NULL,
    answer TEXT NOT NULL,
    category ENUM('tour', 'support', 'general') DEFAULT 'general',
    is_active BOOLEAN DEFAULT TRUE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

try {
    $db->exec($sql);
    echo "Chatbot QA table created successfully.\n";
} catch(PDOException $e) {
    echo "Error creating table: " . $e->getMessage() . "\n";
}

// Seed Initial Data
$seedSql = "INSERT INTO chatbot_qa (question, answer, category) VALUES 
('What tours do you offer?', 'We offer a variety of tours across India, including cultural, adventure, and relaxation packages.', 'tour'),
('How can I book a tour?', 'You can book a tour directly through our website by navigating to the Tours page and selecting a package.', 'tour'),
('What is your cancellation policy?', 'Cancellations made 48 hours before the trip are fully refundable. Please check our terms for more details.', 'support'),
('How do I contact support?', 'You can contact us via the Contact page or email us at support@rgvtourandtravels.com.', 'support')";

try {
    // Check if empty first
    $check = $db->query("SELECT COUNT(*) FROM chatbot_qa");
    if ($check->fetchColumn() == 0) {
        $db->exec($seedSql);
        echo "Seeded initial chatbot data.\n";
    }
} catch(PDOException $e) {
    echo "Error seeding data: " . $e->getMessage() . "\n";
}
?>
