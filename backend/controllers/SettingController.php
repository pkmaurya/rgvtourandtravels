<?php
require_once __DIR__ . '/../models/Setting.php';

class SettingController {
    private $db;
    private $setting;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->setting = new Setting($this->db);
    }

    public function getAll() {
        // ... (admin only technically, but for now strictly separated)
        // ... implementation above
        $this->getSettings();
    }

    // Helper or separate method
    private function getSettings() {
        $stmt = $this->setting->read();
        $num = $stmt->rowCount();
        
        $settings = [];
        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $settings[$row['key']] = $row['value'];
            }
        }
        echo json_encode($settings);
    }

    public function getPublic() {
        $stmt = $this->setting->read();
        $num = $stmt->rowCount();
        
        $settings = [];
        $allowed = [
            'site_title', 'site_description', 'site_logo', 'site_favicon', 'contact_email', 'contact_phone',
            'currency_symbol', 'razorpay_key_id', 'stripe_publishable_key'
        ];

        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if(in_array($row['key'], $allowed)) {
                    $settings[$row['key']] = $row['value'];
                }
            }
        }
        echo json_encode($settings);
    }

    public function update() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if(!empty($data)) {
            if($this->setting->updateBatch($data)) {
                echo json_encode(array("message" => "Settings updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update settings."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "No data provided."));
        }
    }
}
