<?php
require_once __DIR__ . '/../models/Destination.php';

class DestinationController {
    private $db;
    private $destination;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->destination = new Destination($this->db);
    }

    public function getAll() {
        $stmt = $this->destination->read();
        $num = $stmt->rowCount();

        if($num > 0) {
            $destinations_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $dest_item = array(
                    'id' => $id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'image_url' => $image_url,
                    'featured' => (bool)$featured
                );
                array_push($destinations_arr, $dest_item);
            }
            http_response_code(200);
            echo json_encode($destinations_arr);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "No destinations found."));
        }
    }

    public function getOne($id) {
        $stmt = $this->destination->read_single($id);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $dest_item = array(
                'id' => $row['id'],
                'name' => $row['name'],
                'slug' => $row['slug'],
                'description' => $row['description'],
                'image_url' => $row['image_url'],
                'featured' => (bool)$row['featured']
            );
            http_response_code(200);
            echo json_encode($dest_item);
        } else {
            http_response_code(404);
            echo json_encode(array("message" => "Destination not found."));
        }
    }
    public function getFeatured() {
        $stmt = $this->destination->read_featured();
        $num = $stmt->rowCount();

        if($num > 0) {
            $destinations_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $dest_item = array(
                    'id' => $id,
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $description,
                    'image_url' => $image_url,
                    'featured' => (bool)$featured
                );
                array_push($destinations_arr, $dest_item);
            }
            http_response_code(200);
            echo json_encode($destinations_arr);
        } else {
            // Return empty array instead of 404 for UI consistency
            http_response_code(200); 
            echo json_encode([]);
        }
    }
    // Create Destination
    public function create() {
        $data = json_decode(file_get_contents("php://input"), true);
        
        if($this->destination->create($data)) {
            echo json_encode(['message' => 'Destination Created']);
        } else {
            echo json_encode(['message' => 'Destination Not Created']);
        }
    }

    // Update Destination
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"), true);

        if($this->destination->update($id, $data)) {
            echo json_encode(['message' => 'Destination Updated']);
        } else {
            echo json_encode(['message' => 'Destination Not Updated']);
        }
    }

    // Delete Destination
    public function delete($id) {
        if($this->destination->delete($id)) {
            echo json_encode(['message' => 'Destination Deleted']);
        } else {
            echo json_encode(['message' => 'Destination Not Deleted']);
        }
    }
}
