<?php
require_once __DIR__ . '/../config/Database.php';

echo "--- Incredible India Tours Database Installer ---\n";

// 1. Verify Connection
echo "Attempting to connect to database...\n";
$database = new Database();
$conn = $database->connect();

if ($conn) {
    echo "✅ Database Connection Verified!\n";
} else {
    die("❌ Connection Failed. Check config/Database.php credentials.\n");
}

// Helper to run SQL file
function runSqlFile($conn, $file) {
    if (!file_exists($file)) {
        echo "❌ File not found: $file\n";
        return;
    }
    
    echo "Reading $file...\n";
    $sql = file_get_contents($file);
    
    // Split by semicolon to get individual queries (basic splitting)
    // This is a simple implementation; complex SQL might need robust parsing
    // but for our schema/seed it's sufficient.
    
    try {
        $conn->exec($sql);
        echo "✅ Executed $file successfully.\n";
    } catch (PDOException $e) {
        echo "❌ Error executing $file: " . $e->getMessage() . "\n";
    }
}

// 2. Run Schema
echo "\n--- Creating Tables ---\n";
runSqlFile($conn, __DIR__ . '/../../database/schema.sql');

// 3. Run Seed
echo "\n--- Seeding Data ---\n";
runSqlFile($conn, __DIR__ . '/../../database/seed.sql');

echo "\n✨ Installation Complete!\n";
