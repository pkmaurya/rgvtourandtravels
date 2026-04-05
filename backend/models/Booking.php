<?php
class Booking {
    private $conn;
    private $table = 'bookings';

    public $id;
    public $user_id;
    public $tour_id;
    public $tour_title;
    public $total_price;
    public $status;
    public $booking_date;
    public $start_date;
    public $end_date;
    public $adults;
    public $children;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Bookings (With Pagination & Filtering)
    public function getAll($limit = null, $offset = null, $filters = []) {
        $query = "SELECT b.id, b.tour_title as title, d.name as location, b.total_price as total, b.status, b.booking_date, 
                  b.start_date, b.end_date, b.adults, b.children,
                  CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email,
                  t.duration, t.images
                  FROM " . $this->table . " b
                  LEFT JOIN tours t ON b.tour_id = t.id
                  LEFT JOIN destinations d ON t.destination_id = d.id
                  LEFT JOIN users u ON b.user_id = u.id
                  WHERE 1=1";

        // Add filters
        if (!empty($filters['status'])) {
            $query .= " AND b.status = :status";
        }
        if (!empty($filters['user_id'])) {
            $query .= " AND b.user_id = :user_id";
        }
        if (!empty($filters['search'])) {
            $query .= " AND (b.tour_title LIKE :search OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search OR b.id LIKE :search)";
        }

        $query .= " ORDER BY b.booking_date DESC";

        if ($limit !== null && $offset !== null) {
            $query .= " LIMIT :offset, :limit";
        }

        $stmt = $this->conn->prepare($query);

        // Bind params
        if ($limit !== null && $offset !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        if (!empty($filters['status'])) {
            $stmt->bindParam(':status', $filters['status']);
        }
        if (!empty($filters['user_id'])) {
            $stmt->bindParam(':user_id', $filters['user_id']);
        }
        if (!empty($filters['search'])) {
            $searchVal = "%{$filters['search']}%";
            $stmt->bindParam(':search', $searchVal);
        }

        $stmt->execute();
        return $stmt;
    }

    // Create Booking
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                  SET user_id = :user_id, 
                      tour_id = :tour_id, 
                      tour_title = :tour_title, 
                      total_price = :total_price, 
                      status = :status,
                      start_date = :start_date,
                      end_date = :end_date,
                      adults = :adults,
                      children = :children";
        
        $stmt = $this->conn->prepare($query);

        // Fetch tour title if not set
        if(empty($this->tour_title) && !empty($this->tour_id)) {
            $tourQuery = "SELECT title FROM tours WHERE id = ? LIMIT 1";
            $tourStmt = $this->conn->prepare($tourQuery);
            $tourStmt->bindParam(1, $this->tour_id);
            $tourStmt->execute();
            $row = $tourStmt->fetch(PDO::FETCH_ASSOC);
            $this->tour_title = $row['title'];
        }

        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->bindParam(':tour_id', $this->tour_id);
        $stmt->bindParam(':tour_title', $this->tour_title);
        $stmt->bindParam(':total_price', $this->total_price);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':start_date', $this->start_date);
        $stmt->bindParam(':end_date', $this->end_date);
        $stmt->bindParam(':adults', $this->adults);
        $stmt->bindParam(':children', $this->children);

        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }

    // Get Total Count for Pagination
    public function count($filters = []) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " b
                  LEFT JOIN users u ON b.user_id = u.id
                  WHERE 1=1";
        
        if (!empty($filters['status'])) {
            $query .= " AND b.status = :status";
        }
        if (!empty($filters['user_id'])) {
            $query .= " AND b.user_id = :user_id";
        }
        if (!empty($filters['search'])) {
            $query .= " AND (b.tour_title LIKE :search OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search OR b.id LIKE :search)";
        }

        $stmt = $this->conn->prepare($query);

        if (!empty($filters['status'])) {
            $stmt->bindParam(':status', $filters['status']);
        }
        if (!empty($filters['user_id'])) {
            $stmt->bindParam(':user_id', $filters['user_id']);
        }
        if (!empty($filters['search'])) {
            $searchVal = "%{$filters['search']}%";
            $stmt->bindParam(':search', $searchVal);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get Recent Bookings (Limit 5)
    public function getRecent() {
        $query = "SELECT b.id, b.tour_title as title, d.name as location, b.total_price as total, b.status, b.booking_date as date, 
                  b.start_date, b.end_date,
                  (SELECT JSON_UNQUOTE(JSON_EXTRACT(t.images, '$[0]'))) as image
                  FROM " . $this->table . " b
                  LEFT JOIN tours t ON b.tour_id = t.id
                  LEFT JOIN destinations d ON t.destination_id = d.id
                  ORDER BY b.booking_date DESC
                  LIMIT 5";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get Statistics
    public function getStats() {
        // Total Bookings
        $queryBookings = "SELECT COUNT(*) as total_bookings FROM " . $this->table;
        $stmtBookings = $this->conn->prepare($queryBookings);
        $stmtBookings->execute();
        $totalBookings = $stmtBookings->fetch(PDO::FETCH_ASSOC)['total_bookings'];

        // Total Earnings (Confirmed & Completed)
        $queryEarnings = "SELECT SUM(total_price) as total_earnings FROM " . $this->table . " WHERE status IN ('confirmed', 'completed')";
        $stmtEarnings = $this->conn->prepare($queryEarnings);
        $stmtEarnings->execute();
        $totalEarnings = $stmtEarnings->fetch(PDO::FETCH_ASSOC)['total_earnings'];

        // Pending Bookings
        $queryPending = "SELECT COUNT(*) as pending_bookings FROM " . $this->table . " WHERE status = 'pending'";
        $stmtPending = $this->conn->prepare($queryPending);
        $stmtPending->execute();
        $pendingBookings = $stmtPending->fetch(PDO::FETCH_ASSOC)['pending_bookings'];

        // Total Services (Tours)
        $queryServices = "SELECT COUNT(*) as total_services FROM tours";
        $stmtServices = $this->conn->prepare($queryServices);
        $stmtServices->execute();
        $totalServices = $stmtServices->fetch(PDO::FETCH_ASSOC)['total_services'];

        return [
            'total_bookings' => $totalBookings,
            'total_earnings' => $totalEarnings ?: 0,
            'pending_bookings' => $pendingBookings,
            'total_services' => $totalServices
        ];
    }
    public function getBookingById($id) {
        $query = "SELECT b.*, t.title as tour_title, t.images, d.name as location, 
                  CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email, u.phone_number as user_phone
                  FROM " . $this->table . " b
                  LEFT JOIN tours t ON b.tour_id = t.id
                  LEFT JOIN destinations d ON t.destination_id = d.id
                  LEFT JOIN users u ON b.user_id = u.id
                  WHERE b.id = :id LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE " . $this->table . " SET status = :status WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

?>
