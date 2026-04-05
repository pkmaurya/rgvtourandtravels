<?php

class Payment {
    private $conn;
    private $table = 'payments';

    public $id;
    public $user_id;
    public $booking_id;
    public $gateway;
    public $order_id;
    public $transaction_id;
    public $amount;
    public $currency;
    public $status;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                  SET user_id = :user_id,
                      booking_id = :booking_id,
                      gateway = :gateway,
                      order_id = :order_id,
                      transaction_id = :transaction_id,
                      amount = :amount,
                      currency = :currency,
                      status = :status";

        $stmt = $this->conn->prepare($query);

        // Sanitize & Bind
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':booking_id', $this->booking_id);
        $stmt->bindParam(':gateway', $this->gateway);
        $stmt->bindParam(':order_id', $this->order_id);
        $stmt->bindParam(':transaction_id', $this->transaction_id);
        $stmt->bindParam(':amount', $this->amount);
        $stmt->bindParam(':currency', $this->currency);
        $stmt->bindParam(':status', $this->status);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function updateStatus($order_id, $status, $transaction_id = null) {
        $query = "UPDATE " . $this->table . " 
                  SET status = :status";
        
        if ($transaction_id) {
            $query .= ", transaction_id = :transaction_id";
        }
        
        $query .= " WHERE order_id = :order_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':order_id', $order_id);
        if ($transaction_id) {
            $stmt->bindParam(':transaction_id', $transaction_id);
        }

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function getPaymentByOrderId($order_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE order_id = :order_id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getAll($limit = 10, $offset = 0, $filters = []) {
        $query = "SELECT p.*, b.tour_title, CONCAT(u.first_name, ' ', u.last_name) as user_name 
                  FROM " . $this->table . " p
                  LEFT JOIN bookings b ON p.booking_id = b.id
                  LEFT JOIN users u ON p.user_id = u.id
                  WHERE 1=1";

        if (!empty($filters['gateway'])) {
            $query .= " AND p.gateway = :gateway";
        }
        if (!empty($filters['status'])) {
            $query .= " AND p.status = :status";
        }
        
        $query .= " ORDER BY p.created_at DESC LIMIT :offset, :limit";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        if (!empty($filters['gateway'])) {
            $stmt->bindParam(':gateway', $filters['gateway']);
        }
        if (!empty($filters['status'])) {
            $stmt->bindParam(':status', $filters['status']);
        }

        $stmt->execute();
        return $stmt;
    }

    public function count($filters = []) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " p WHERE 1=1";
        
        if (!empty($filters['gateway'])) {
            $query .= " AND p.gateway = :gateway";
        }
        if (!empty($filters['status'])) {
            $query .= " AND p.status = :status";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($filters['gateway'])) {
            $stmt->bindParam(':gateway', $filters['gateway']);
        }
        if (!empty($filters['status'])) {
            $stmt->bindParam(':status', $filters['status']);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
