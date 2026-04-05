<?php
require_once __DIR__ . '/../models/Booking.php';

class BookingController {
    private $db;
    private $booking;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->booking = new Booking($this->db);
    }

    public function getUserBookings() {
        require_once __DIR__ . '/../utils/JwtHandler.php';
        $jwt = new JwtHandler();
        
        $headers = apache_request_headers();
        $token = null;
        if(isset($headers['Authorization'])) {
            $matches = array();
            preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
            if(isset($matches[1])) {
                $token = $matches[1];
            }
        }

        if (!$token) {
            http_response_code(401);
            echo json_encode(array("message" => "No token provided."));
            return;
        }

        $decoded = $jwt->decode($token);
        if (!$decoded) {
            http_response_code(401);
            echo json_encode(array("message" => "Invalid token."));
            return;
        }

        $userId = $decoded['id'];

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        
        $offset = ($page - 1) * $limit;
        
        $filters = ['user_id' => $userId];
        if ($status && $status !== 'all') {
            $filters['status'] = $status;
        }

        $stmt = $this->booking->getAll($limit, $offset, $filters);
        $bookings = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $paid = 0;
             if (in_array(strtolower($row['status']), ['confirmed', 'completed', 'paid'])) {
                $paid = $row['total'];
            }
            $remain = $row['total'] - $paid;

            // Image handling
            $images = !empty($row['images']) ? json_decode($row['images']) : [];
            $image = !empty($images) ? $images[0] : null;


            $bookings[] = [
                'id' => $row['id'],
                'type' => 'Tour',
                'title' => $row['title'],
                'image' => $image,
                'location' => $row['location'],
                'booking_date' => $row['booking_date'],
                'start_date' => $row['start_date'],
                'end_date' => $row['end_date'],
                'adults' => $row['adults'],
                'children' => $row['children'],
                'total' => $row['total'],
                'status' => ucfirst($row['status'])
            ];
        }

        $total = $this->booking->count($filters);
        $totalPages = ceil($total / $limit);

        echo json_encode([
            'data' => $bookings,
            'meta' => [
                'current_page' => $page,
                'last_page' => $totalPages,
                'total' => $total
            ]
        ]);
    }

    public function index() {
        // Get query params
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $status = isset($_GET['status']) ? $_GET['status'] : null;
        $search = isset($_GET['search']) ? $_GET['search'] : null;

        $offset = ($page - 1) * $limit;

        $filters = [];
        if ($status && $status !== 'all') {
            $filters['status'] = $status;
        }
        if ($search) {
            $filters['search'] = $search;
        }

        // Get bookings
        $stmt = $this->booking->getAll($limit, $offset, $filters);
        $bookings = [];
        
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Calculate Paid/Remain based on status
            // Assuming 'confirmed' or 'completed' means fully paid for now, 
            // and 'pending' means 0 paid. 
            // This is a simplification as per requirements.
            $paid = 0;
            if (in_array(strtolower($row['status']), ['confirmed', 'completed', 'paid'])) {
                $paid = $row['total'];
            }
            $remain = $row['total'] - $paid;

            // Calculate Check-out based on travel_date + duration
            $checkIn = $row['travel_date'];
            $checkOut = null;
            if ($checkIn && $row['duration']) {
                // Parse duration (e.g., "5 Days")
                if (preg_match('/(\d+)/', $row['duration'], $matches)) {
                    $days = (int)$matches[1];
                    $checkOut = date('Y-m-d', strtotime($checkIn . " + $days days"));
                }
            }

            $bookings[] = [
                'id' => $row['id'],
                'type' => 'Tour',
                'title' => $row['title'],
                'order_date' => $row['booking_date'],
                'check_in' => $row['start_date'], // User requested From Date
                'check_out' => $row['end_date'],   // User requested To Date
                'adults' => $row['adults'],
                'children' => $row['children'],
                'total' => $row['total'],
                'paid' => $paid,
                'remain' => $remain,
                'status' => ucfirst($row['status']),
                'user' => [
                    'name' => $row['user_name'],
                    'email' => $row['user_email']
                ]
            ];
        }

        // Get total count for pagination
        $total = $this->booking->count($filters);
        $totalPages = ceil($total / $limit);

        echo json_encode([
            'data' => $bookings,
            'meta' => [
                'current_page' => $page,
                'last_page' => $totalPages,
                'total' => $total,
                'per_page' => $limit
            ]
        ]);
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"));

        if(
            !empty($data->user_id) &&
            !empty($data->tour_id) &&
            !empty($data->start_date) &&
            !empty($data->end_date) &&
            !empty($data->total_price)
        ) {
            $this->booking->user_id = $data->user_id;
            $this->booking->tour_id = $data->tour_id;
            // $this->booking->travel_date = $data->travel_date; // Removed in favor of range
            $this->booking->start_date = $data->start_date;
            $this->booking->end_date = $data->end_date;
            $this->booking->adults = !empty($data->adults) ? $data->adults : 1;
            $this->booking->children = !empty($data->children) ? $data->children : 0;
            $this->booking->total_price = $data->total_price;
            $this->booking->status = 'pending'; // Default status

            if ($this->booking->create()) {
                // Get the ID of the created booking
                $this->booking->id = $this->db->lastInsertId();
                
                http_response_code(201);
                echo json_encode([
                    "message" => "Booking created",
                    "id" => $this->booking->id
                ]);
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create booking."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data. Missing required fields."));
        }
    }


    public function show($id) {
        $booking = $this->booking->getBookingById($id);

        if($booking) {
             // Decode images if present
             if (!empty($booking['images'])) {
                 $booking['images'] = json_decode($booking['images']);
             }
             echo json_encode($booking);
        } else {
             http_response_code(404);
             echo json_encode(["message" => "Booking not found"]);
        }
    }

    public function updateStatus($id) {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->status)) {
            if ($this->booking->updateStatus($id, $data->status)) {
                http_response_code(200);
                echo json_encode(array("message" => "Booking status updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update status."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Status is required."));
        }
    }

    public function delete($id) {
        if ($this->booking->delete($id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Booking deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete booking."));
        }
    }
}
