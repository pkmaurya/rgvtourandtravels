<?php
class Contact {
    private $conn;
    private $table = 'contacts';

    public $id;
    public $name;
    public $email;
    public $subject;
    public $message;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Create Contact
    public function create() {
        $query = 'INSERT INTO ' . $this->table . '
                  SET name = :name,
                      email = :email,
                      subject = :subject,
                      message = :message';

        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->subject = htmlspecialchars(strip_tags($this->subject));
        $this->message = htmlspecialchars(strip_tags($this->message));

        // Bind data
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':subject', $this->subject);
        $stmt->bindParam(':message', $this->message);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Get All Contacts
    public function getAll() {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Delete Contact
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
