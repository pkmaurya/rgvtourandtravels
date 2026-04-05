<?php
class Destination {
    private $conn;
    private $table = 'destinations';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Destinations
    public function read() {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get Featured Destinations (Top 10)
    public function read_featured() {
        $query = 'SELECT * FROM ' . $this->table . ' 
                  WHERE featured = 1 
                  ORDER BY created_at DESC 
                  LIMIT 10';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
    
    // Get Single Destination
    public function read_single($idOrSlug) {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE ';
        
        if (is_numeric($idOrSlug)) {
            $query .= 'id = :idOrSlug';
        } else {
             $query .= 'slug = :idOrSlug';
        }

        $query .= ' LIMIT 0,1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':idOrSlug', $idOrSlug);
        $stmt->execute();
        return $stmt;
    }
    // Create Destination
    public function create($data) {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET name = :name, 
                      slug = :slug, 
                      description = :description, 
                      image_url = :image_url, 
                      featured = :featured';

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':featured', $data['featured']);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update Destination
    public function update($id, $data) {
        $query = 'UPDATE ' . $this->table . ' 
                  SET name = :name, 
                      slug = :slug, 
                      description = :description, 
                      image_url = :image_url, 
                      featured = :featured 
                  WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // Sanitize and bind
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':image_url', $data['image_url']);
        $stmt->bindParam(':featured', $data['featured']);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete Destination
    public function delete($id) {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
