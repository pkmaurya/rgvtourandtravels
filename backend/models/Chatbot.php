<?php
class Chatbot {
    private $conn;
    private $table = 'chatbot_qa';

    public $id;
    public $question;
    public $answer;
    public $category;
    public $is_active;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get All Q&A (can filter by category)
    public function getAll($category = null) {
        $query = 'SELECT * FROM ' . $this->table;
        
        if ($category) {
             $query .= ' WHERE category = :category AND is_active = 1';
        }
        
        $query .= ' ORDER BY category, id ASC';
        
        $stmt = $this->conn->prepare($query);

        if ($category) {
            $category = htmlspecialchars(strip_tags($category));
            $stmt->bindParam(':category', $category);
        }

        $stmt->execute();
        return $stmt;
    }

    // Create Q&A
    public function create() {
        $query = 'INSERT INTO ' . $this->table . '
                  SET question = :question,
                      answer = :answer,
                      category = :category,
                      is_active = :is_active';

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->question = htmlspecialchars(strip_tags($this->question));
        $this->answer = htmlspecialchars(strip_tags($this->answer));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));

        // Bind data
        $stmt->bindParam(':question', $this->question);
        $stmt->bindParam(':answer', $this->answer);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':is_active', $this->is_active);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Update Q&A
    public function update() {
        $query = 'UPDATE ' . $this->table . '
                  SET question = :question,
                      answer = :answer,
                      category = :category,
                      is_active = :is_active
                  WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->question = htmlspecialchars(strip_tags($this->question));
        $this->answer = htmlspecialchars(strip_tags($this->answer));
        $this->category = htmlspecialchars(strip_tags($this->category));
        $this->is_active = htmlspecialchars(strip_tags($this->is_active));
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind data
        $stmt->bindParam(':question', $this->question);
        $stmt->bindParam(':answer', $this->answer);
        $stmt->bindParam(':category', $this->category);
        $stmt->bindParam(':is_active', $this->is_active);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete Q&A
    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
