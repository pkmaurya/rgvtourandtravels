<?php
class Offer {
    private $conn;
    private $table = 'offers';

    public $id;
    public $title;
    public $description;
    public $discount_type;
    public $discount_value;
    public $coupon_code;
    public $start_date;
    public $end_date;
    public $min_booking_amount;
    public $priority;
    public $status;
    public $scope;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Offer
    public function create() {
        $query = 'INSERT INTO ' . $this->table . '
                  SET
                    title = :title,
                    description = :description,
                    discount_type = :discount_type,
                    discount_value = :discount_value,
                    coupon_code = :coupon_code,
                    start_date = :start_date,
                    end_date = :end_date,
                    min_booking_amount = :min_booking_amount,
                    priority = :priority,
                    status = :status,
                    scope = :scope';

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->description = htmlspecialchars(strip_tags($this->description));
        // ... other fields generally don't need htmlspecialchars if validated, but good practice

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':discount_type', $this->discount_type);
        $stmt->bindParam(':discount_value', $this->discount_value);
        $stmt->bindParam(':coupon_code', $this->coupon_code);
        $stmt->bindParam(':start_date', $this->start_date);
        $stmt->bindParam(':end_date', $this->end_date);
        $stmt->bindParam(':min_booking_amount', $this->min_booking_amount);
        $stmt->bindParam(':priority', $this->priority);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':scope', $this->scope);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Get All Offers (Admin)
    public function read() {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get Active Offers (Public)
    public function readActive() {
        // Active status AND current date within range
        $query = 'SELECT * FROM ' . $this->table . ' 
                  WHERE status = "active" 
                  AND start_date <= NOW() 
                  AND end_date >= NOW()
                  ORDER BY priority DESC, created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get Single Offer
    public function readOne() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE id = ? LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if($row) {
            $this->title = $row['title'];
            $this->description = $row['description'];
            $this->discount_type = $row['discount_type'];
            $this->discount_value = $row['discount_value'];
            $this->coupon_code = $row['coupon_code'];
            $this->start_date = $row['start_date'];
            $this->end_date = $row['end_date'];
            $this->min_booking_amount = $row['min_booking_amount'];
            $this->priority = $row['priority'];
            $this->status = $row['status'];
            $this->scope = $row['scope'];
            return true;
        }
        return false;
    }

    // Update Offer
    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET
                    title = :title,
                    description = :description,
                    discount_type = :discount_type,
                    discount_value = :discount_value,
                    coupon_code = :coupon_code,
                    start_date = :start_date,
                    end_date = :end_date,
                    min_booking_amount = :min_booking_amount,
                    priority = :priority,
                    status = :status,
                    scope = :scope
                  WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':discount_type', $this->discount_type);
        $stmt->bindParam(':discount_value', $this->discount_value);
        $stmt->bindParam(':coupon_code', $this->coupon_code);
        $stmt->bindParam(':start_date', $this->start_date);
        $stmt->bindParam(':end_date', $this->end_date);
        $stmt->bindParam(':min_booking_amount', $this->min_booking_amount);
        $stmt->bindParam(':priority', $this->priority);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':scope', $this->scope);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete Offer
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Verify Coupon
    public function verifyCoupon($code, $amount) {
        $query = 'SELECT * FROM ' . $this->table . ' 
                  WHERE coupon_code = :code 
                  AND status = "active" 
                  AND start_date <= NOW() 
                  AND end_date >= NOW()
                  LIMIT 1';
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            // Check minimum amount
            if ($amount < $row['min_booking_amount']) {
                return ['valid' => false, 'message' => 'Minimum booking amount of ' . $row['min_booking_amount'] . ' required.'];
            }
            return ['valid' => true, 'offer' => $row];
        }

        return ['valid' => false, 'message' => 'Invalid or expired coupon code.'];
    }
}
