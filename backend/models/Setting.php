<?php
class Setting {
    private $conn;
    private $table = 'settings';

    public $id;
    public $key;
    public $value;
    public $group;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Settings
    public function read() {
        $query = 'SELECT `key`, `value`, `group` FROM ' . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update Batch
    public function updateBatch($settings) {
        $query = 'INSERT INTO ' . $this->table . ' (`key`, `value`, `group`) VALUES (:key, :value, :group) 
                  ON DUPLICATE KEY UPDATE `value` = :value_update';
        
        $stmt = $this->conn->prepare($query);

        foreach($settings as $key => $data) {
            // $data can be just value or array with group
            $value = is_array($data) ? $data['value'] : $data;
            $group = is_array($data) ? ($data['group'] ?? 'general') : 'general';

            $stmt->bindParam(':key', $key);
            $stmt->bindParam(':value', $value);
            $stmt->bindParam(':group', $group);
            $stmt->bindParam(':value_update', $value);
            
            if(!$stmt->execute()) return false;
        }
        return true;
    }
    
    // Update Single
    public function updateSingle($key, $value) {
        $query = 'UPDATE ' . $this->table . ' SET `value` = :value WHERE `key` = :key';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':value', $value);
        $stmt->bindParam(':key', $key);
        return $stmt->execute();
    }
}
