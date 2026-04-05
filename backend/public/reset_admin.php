<?php
require_once '../config/Database.php';

$database = new Database();
$db = $database->connect();

$email = 'admin@rgvtourandtravels.com';
$password = 'admin123';
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$query = "UPDATE users SET password = :password WHERE email = :email";
$stmt = $db->prepare($query);

$stmt->bindParam(':password', $hashed_password);
$stmt->bindParam(':email', $email);

if($stmt->execute()) {
    echo "Admin password reset successfully to 'admin123'. Hash: " . $hashed_password;
} else {
    echo "Failed to reset password.";
}
?>
