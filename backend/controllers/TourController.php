<?php
require_once __DIR__ . '/../models/Tour.php';

class TourController {
    private $db;
    private $tour;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->tour = new Tour($this->db);
    }

    private function processRow($row) {
        // Decode JSON fields
        $row['itinerary'] = json_decode($row['itinerary']);
        $row['inclusions'] = json_decode($row['inclusions']);
        $row['exclusions'] = json_decode($row['exclusions']);
        $row['images'] = json_decode($row['images']);
        $row['available_dates'] = json_decode($row['available_dates'] ?? '[]');
        $row['highlights'] = json_decode($row['highlights'] ?? '[]');
        $row['featured'] = (bool)$row['featured'];
        return $row;
    }

    public function getAll() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = ($page - 1) * $limit;

        // Filters
        $filters = [];
        if(isset($_GET['destination_id']) && $_GET['destination_id'] !== '') $filters['destination_id'] = $_GET['destination_id'];
        if(isset($_GET['min_price']) && $_GET['min_price'] !== '') $filters['min_price'] = $_GET['min_price'];
        if(isset($_GET['max_price']) && $_GET['max_price'] !== '') $filters['max_price'] = $_GET['max_price'];
        if(isset($_GET['category']) && $_GET['category'] !== '') $filters['category'] = $_GET['category'];
        if(isset($_GET['status']) && $_GET['status'] !== '') $filters['status'] = $_GET['status'];
        if(isset($_GET['q']) && $_GET['q'] !== '') $filters['search'] = $_GET['q'];
        if(isset($_GET['duration']) && $_GET['duration'] !== '') $filters['duration'] = $_GET['duration'];

        $stmt = $this->tour->read($limit, $offset, $filters);
        $num = $stmt->rowCount();
        
        $tours_arr = array();
        if($num > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($tours_arr, $this->processRow($row));
            }
        }
        
        // Get Total Count
        $total_tours = $this->tour->count($filters);
        $total_pages = ceil($total_tours / $limit);

        http_response_code(200);
        echo json_encode([
            'data' => $tours_arr,
            'pagination' => [
                'current_page' => $page,
                'total_pages' => $total_pages,
                'total_items' => $total_tours,
                'items_per_page' => $limit
            ]
        ]);
    }

    public function getOne($id) {
        $stmt = $this->tour->read_single($id);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            http_response_code(200);
            echo json_encode($this->processRow($row));
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Tour not found."));
        }
    }

    private function createSlug($title) {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug . '-' . time(); // Basic unique slug
    }

    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Basic Validation
        if(empty($data['title']) || empty($data['price'])) {
             http_response_code(400);
             echo json_encode(array("message" => "Title and Price are required."));
             return;
        }
        
        // Prepare Data
        $tourData = [
            'title' => $data['title'],
            'slug' => !empty($data['slug']) ? $data['slug'] : $this->createSlug($data['title']),
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'duration' => $data['duration'] ?? '',
            'images' => isset($data['images']) ? json_encode($data['images']) : '[]',
            'featured' => isset($data['featured']) ? (int)$data['featured'] : 0,
            'destination_id' => $data['destination_id'] ?? null,
            'category' => $data['category'] ?? 'General',
            'status' => $data['status'] ?? 'active',
            'max_travelers' => $data['max_travelers'] ?? 12,
            'available_dates' => isset($data['available_dates']) ? json_encode($data['available_dates']) : '[]',
            'highlights' => isset($data['highlights']) ? json_encode($data['highlights']) : '[]',
            'itinerary' => isset($data['itinerary']) ? json_encode($data['itinerary']) : '[]',
            'inclusions' => isset($data['inclusions']) ? json_encode($data['inclusions']) : '[]',
            'exclusions' => isset($data['exclusions']) ? json_encode($data['exclusions']) : '[]',
        ];

        if($this->tour->create($tourData)) {
            http_response_code(201);
            echo json_encode(array("message" => "Tour Created", "slug" => $tourData['slug']));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to create tour."));
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);
        
        $data['id'] = $id;

        $tourData = [
            'id' => $id,
            'title' => $data['title'],
            'slug' => !empty($data['slug']) ? $data['slug'] : $this->createSlug($data['title']),
            'description' => $data['description'] ?? '',
            'price' => $data['price'],
            'sale_price' => $data['sale_price'] ?? null,
            'duration' => $data['duration'] ?? '',
            'images' => isset($data['images']) ? (is_array($data['images']) ? json_encode($data['images']) : $data['images']) : '[]',
            'featured' => isset($data['featured']) ? (int)$data['featured'] : 0,
            'destination_id' => $data['destination_id'] ?? null,
            'category' => $data['category'] ?? 'General',
            'status' => $data['status'] ?? 'active',
            'max_travelers' => $data['max_travelers'] ?? 12,
            'available_dates' => isset($data['available_dates']) ? (is_array($data['available_dates']) ? json_encode($data['available_dates']) : $data['available_dates']) : '[]',
            'highlights' => isset($data['highlights']) ? (is_array($data['highlights']) ? json_encode($data['highlights']) : $data['highlights']) : '[]',
            'itinerary' => isset($data['itinerary']) ? (is_array($data['itinerary']) ? json_encode($data['itinerary']) : $data['itinerary']) : '[]',
            'inclusions' => isset($data['inclusions']) ? (is_array($data['inclusions']) ? json_encode($data['inclusions']) : $data['inclusions']) : '[]',
            'exclusions' => isset($data['exclusions']) ? (is_array($data['exclusions']) ? json_encode($data['exclusions']) : $data['exclusions']) : '[]',
        ];
             
         if ($this->tour->update($tourData)) {
              http_response_code(200);
              echo json_encode(array("message" => "Tour Updated"));
         } else {
             http_response_code(503);
             echo json_encode(array("message" => "Unable to update tour."));
         }
    }

    public function delete($id) {
        if($this->tour->delete($id)) {
            http_response_code(200);
            echo json_encode(array("message" => "Tour Deleted"));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete tour."));
        }
    }

    public function search() {
        $keywords = isset($_GET['q']) ? $_GET['q'] : '';
        $stmt = $this->tour->search($keywords);
        $num = $stmt->rowCount();

        if($num > 0) {
            $tours_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($tours_arr, $this->processRow($row));
            }
            http_response_code(200);
            echo json_encode($tours_arr);
        } else {
            http_response_code(200); // Search returns empty array instead of 404
            echo json_encode([]);
        }
    }
    public function getFeatured() {
        $stmt = $this->tour->read_featured();
        $num = $stmt->rowCount();

        if($num > 0) {
            $tours_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($tours_arr, $this->processRow($row));
            }
            http_response_code(200);
            echo json_encode($tours_arr);
        } else {
            // Return empty array instead of 404 for UI consistency
            http_response_code(200);
            echo json_encode([]);
        }
    }
}
