<?php
class Tour {
    private $conn;
    private $table = 'tours';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Tours (With Pagination & Filtering)
    public function read($limit = null, $offset = null, $filters = []) {
        $query = 'SELECT t.*, d.name as destination_name 
                  FROM ' . $this->table . ' t
                  LEFT JOIN destinations d ON t.destination_id = d.id
                  WHERE 1=1';
        
        // Add filters
        if (!empty($filters['destination_id'])) {
            $query .= ' AND t.destination_id = :destination_id';
        }
        if (!empty($filters['min_price'])) {
            $query .= ' AND t.price >= :min_price';
        }
        if (!empty($filters['max_price'])) {
            $query .= ' AND t.price <= :max_price';
        }
        if (!empty($filters['search'])) {
            $query .= ' AND (t.title LIKE :search OR t.description LIKE :search OR d.name LIKE :search)';
        }
        if (!empty($filters['duration'])) {
             if ($filters['duration'] === '3-5') {
                 $query .= ' AND CAST(t.duration AS UNSIGNED) BETWEEN 3 AND 5';
             } elseif ($filters['duration'] === '6-10') {
                 $query .= ' AND CAST(t.duration AS UNSIGNED) BETWEEN 6 AND 10';
             } elseif ($filters['duration'] === '10+') {
                 $query .= ' AND CAST(t.duration AS UNSIGNED) >= 10';
             }
        }

        $query .= ' ORDER BY t.created_at DESC';
        
        if ($limit !== null && $offset !== null) {
            $query .= ' LIMIT :offset, :limit';
        }

        $stmt = $this->conn->prepare($query);

        // Bind params
        if ($limit !== null && $offset !== null) {
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        if (!empty($filters['destination_id'])) {
            $stmt->bindParam(':destination_id', $filters['destination_id']);
        }
        if (!empty($filters['min_price'])) {
            $stmt->bindParam(':min_price', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $stmt->bindParam(':max_price', $filters['max_price']);
        }
        if (!empty($filters['search'])) {
            $searchParam = "%{$filters['search']}%";
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->execute();
        return $stmt;
    }

    // Get Total Count for Pagination (With Filtering)
    public function count($filters = []) {
        $query = 'SELECT COUNT(*) as total 
                  FROM ' . $this->table . ' t 
                  LEFT JOIN destinations d ON t.destination_id = d.id
                  WHERE 1=1';
        
         if (!empty($filters['destination_id'])) {
            $query .= ' AND t.destination_id = :destination_id';
        }
        if (!empty($filters['min_price'])) {
            $query .= ' AND t.price >= :min_price';
        }
        if (!empty($filters['max_price'])) {
            $query .= ' AND t.price <= :max_price';
        }
        if (!empty($filters['search'])) {
            $query .= ' AND (t.title LIKE :search OR t.description LIKE :search OR d.name LIKE :search)';
        }
        if (!empty($filters['duration'])) {
             if ($filters['duration'] === '3-5') {
                 $query .= ' AND CAST(t.duration AS UNSIGNED) BETWEEN 3 AND 5';
             } elseif ($filters['duration'] === '6-10') {
                 $query .= ' AND CAST(t.duration AS UNSIGNED) BETWEEN 6 AND 10';
             } elseif ($filters['duration'] === '10+') {
                 $query .= ' AND CAST(t.duration AS UNSIGNED) >= 10';
             }
        }

        $stmt = $this->conn->prepare($query);
        
        if (!empty($filters['destination_id'])) {
            $stmt->bindParam(':destination_id', $filters['destination_id']);
        }
        if (!empty($filters['min_price'])) {
            $stmt->bindParam(':min_price', $filters['min_price']);
        }
        if (!empty($filters['max_price'])) {
            $stmt->bindParam(':max_price', $filters['max_price']);
        }
        if (!empty($filters['search'])) {
            $searchParam = "%{$filters['search']}%";
            $stmt->bindParam(':search', $searchParam);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    // Get Single Tour (by ID or Slug)
    public function read_single($idOrSlug) {
        $query = 'SELECT t.*, d.name as destination_name 
                  FROM ' . $this->table . ' t
                  LEFT JOIN destinations d ON t.destination_id = d.id
                  WHERE ';
        
        // Check if input is numeric (ID) or string (Slug)
        if (is_numeric($idOrSlug)) {
             $query .= 't.id = ?';
        } else {
             $query .= 't.slug = ?';
        }

        $query .= ' LIMIT 0,1';

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $idOrSlug);
        $stmt->execute();
        return $stmt;
    }
    
    // Search/Filter Tours
    public function search($keywords) {
         $query = 'SELECT t.*, d.name as destination_name 
                  FROM ' . $this->table . ' t
                  LEFT JOIN destinations d ON t.destination_id = d.id
                  WHERE t.title LIKE ? OR d.name LIKE ? OR t.description LIKE ?';
        $stmt = $this->conn->prepare($query);
        $keywords = "%{$keywords}%";
        $stmt->bindParam(1, $keywords);
        $stmt->bindParam(2, $keywords);
        $stmt->bindParam(3, $keywords);
        $stmt->execute();
        return $stmt;
    }

    // Get Featured Tours (Top 5 for Offers)
    public function read_featured() {
        $query = 'SELECT t.*, d.name as destination_name 
                  FROM ' . $this->table . ' t
                  LEFT JOIN destinations d ON t.destination_id = d.id
                  WHERE t.featured = 1
                  ORDER BY t.created_at DESC
                  LIMIT 5';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Create Tour
    public function create($data) {
        $query = 'INSERT INTO ' . $this->table . ' 
                  SET title = :title, 
                      slug = :slug,
                      description = :description, 
                      price = :price, 
                      sale_price = :sale_price,
                      duration = :duration, 
                      images = :images, 
                      featured = :featured,
                      destination_id = :destination_id,
                      category = :category,
                      status = :status,
                      max_travelers = :max_travelers,
                      available_dates = :available_dates,
                      highlights = :highlights,
                      itinerary = :itinerary,
                      inclusions = :inclusions,
                      exclusions = :exclusions';

        $stmt = $this->conn->prepare($query);

        // Sanitize & Bind
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':sale_price', $data['sale_price']);
        $stmt->bindParam(':duration', $data['duration']);
        $stmt->bindParam(':images', $data['images']); 
        $stmt->bindParam(':featured', $data['featured']);
        $stmt->bindParam(':destination_id', $data['destination_id']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':max_travelers', $data['max_travelers']);
        $stmt->bindParam(':available_dates', $data['available_dates']);
        $stmt->bindParam(':highlights', $data['highlights']);
        $stmt->bindParam(':itinerary', $data['itinerary']);
        $stmt->bindParam(':inclusions', $data['inclusions']);
        $stmt->bindParam(':exclusions', $data['exclusions']);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update Tour
    public function update($data) {
        $query = 'UPDATE ' . $this->table . ' 
                  SET title = :title, 
                      slug = :slug,
                      description = :description, 
                      price = :price, 
                      sale_price = :sale_price,
                      duration = :duration, 
                      images = :images, 
                      featured = :featured,
                      destination_id = :destination_id,
                      category = :category,
                      status = :status,
                      max_travelers = :max_travelers,
                      available_dates = :available_dates,
                      highlights = :highlights,
                      itinerary = :itinerary,
                      inclusions = :inclusions,
                      exclusions = :exclusions
                  WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // Sanitize & Bind
        $stmt->bindParam(':title', $data['title']);
        $stmt->bindParam(':slug', $data['slug']);
        $stmt->bindParam(':description', $data['description']);
        $stmt->bindParam(':price', $data['price']);
        $stmt->bindParam(':sale_price', $data['sale_price']);
        $stmt->bindParam(':duration', $data['duration']);
        $stmt->bindParam(':images', $data['images']);
        $stmt->bindParam(':featured', $data['featured']);
        $stmt->bindParam(':destination_id', $data['destination_id']);
        $stmt->bindParam(':category', $data['category']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':max_travelers', $data['max_travelers']);
        $stmt->bindParam(':available_dates', $data['available_dates']);
        $stmt->bindParam(':highlights', $data['highlights']);
        $stmt->bindParam(':itinerary', $data['itinerary']);
        $stmt->bindParam(':inclusions', $data['inclusions']);
        $stmt->bindParam(':exclusions', $data['exclusions']);
        $stmt->bindParam(':id', $data['id']);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete Tour
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
