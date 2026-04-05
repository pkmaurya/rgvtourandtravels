<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $phone;
    public $password;
    public $role;

    public $profile_image;
    public $bio;
    public $social_links;

    public $otp;
    public $otp_expiry;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Register User
    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET 
            first_name = :first_name, 
            middle_name = :middle_name, 
            last_name = :last_name, 
            email = :email, 
            phone = :phone, 
            password = :password, 
            role = :role';
        
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        $this->middle_name = htmlspecialchars(strip_tags($this->middle_name));
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->role = htmlspecialchars(strip_tags($this->role));

        // Bind
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':middle_name', $this->middle_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':role', $this->role);

        // Hash password
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Login (Check if email exists)
    public function emailExists() {
        $query = 'SELECT id, first_name, last_name, password, role, profile_image FROM ' . $this->table . ' WHERE email = :email LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $this->email = htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->password = $row['password']; // Hashed
            $this->role = $row['role'];
            $this->profile_image = $row['profile_image'];
            return true;
        }
        return false;
    }

    // Get Single User by ID
    public function getSingleUser() {
        $query = 'SELECT id, first_name, middle_name, last_name, email, phone, role, profile_image, bio, social_links FROM ' . $this->table . ' WHERE id = ? LIMIT 0,1';
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if($row) {
            $this->first_name = $row['first_name'];
            $this->middle_name = $row['middle_name'];
            $this->last_name = $row['last_name'];
            $this->email = $row['email'];
            $this->phone = $row['phone'];
            $this->role = $row['role'];
            $this->profile_image = $row['profile_image'];
            $this->bio = $row['bio'];
            $this->social_links = $row['social_links'];
            return true;
        }
        return false;
    }

    // Get All Users
    public function read() {
        $query = 'SELECT id, first_name, middle_name, last_name, email, phone, role, profile_image FROM ' . $this->table . ' ORDER BY id DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Update User (Profile)
    public function update() {
        $query = 'UPDATE ' . $this->table . '
                SET first_name = :first_name, 
                    middle_name = :middle_name, 
                    last_name = :last_name, 
                    email = :email, 
                    phone = :phone,
                    profile_image = :profile_image,
                    bio = :bio,
                    social_links = :social_links
                WHERE id = :id';
        
        $stmt = $this->conn->prepare($query);

        // Sanitize
        $this->first_name = htmlspecialchars(strip_tags($this->first_name));
        // Handle optional fields carefully
        $this->middle_name = !empty($this->middle_name) ? htmlspecialchars(strip_tags($this->middle_name)) : null;
        $this->last_name = htmlspecialchars(strip_tags($this->last_name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->phone = htmlspecialchars(strip_tags($this->phone));
        $this->profile_image = !empty($this->profile_image) ? htmlspecialchars(strip_tags($this->profile_image)) : null;
        $this->bio = !empty($this->bio) ? htmlspecialchars(strip_tags($this->bio)) : null;
        // social_links is JSON string, don't strip tags blindly if it's JSON structure, 
        // but for safety we can htmlspecialchars it if we decode/encode properly on frontend.
        // Assuming simplistic string storage for now or properly encoded JSON.
        // Since input is JSON string from frontend, we trust existing structure but sanitize content?
        // Let's keep it defined but careful.
        $this->social_links = $this->social_links; 

        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':middle_name', $this->middle_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);
        $stmt->bindParam(':profile_image', $this->profile_image);
        $stmt->bindParam(':bio', $this->bio);
        $stmt->bindParam(':social_links', $this->social_links);
        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Delete User
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

    // Save OTP
    public function saveOtp() {
        $query = 'UPDATE ' . $this->table . ' SET otp = :otp, otp_expiry = :otp_expiry WHERE email = :email';
        $stmt = $this->conn->prepare($query);

        $this->otp = htmlspecialchars(strip_tags($this->otp));
        $this->otp_expiry = htmlspecialchars(strip_tags($this->otp_expiry));
        $this->email = htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(':otp', $this->otp);
        $stmt->bindParam(':otp_expiry', $this->otp_expiry);
        $stmt->bindParam(':email', $this->email);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Verify OTP
    public function verifyOtp() {
        $query = 'SELECT * FROM ' . $this->table . ' WHERE email = :email AND otp = :otp AND otp_expiry > NOW() LIMIT 0,1';
        $stmt = $this->conn->prepare($query);

        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->otp = htmlspecialchars(strip_tags($this->otp));

        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':otp', $this->otp);

        $stmt->execute();

        if($stmt->rowCount() > 0) {
            return true;
        }
        return false;
    }

    // Reset Password
    public function resetPassword() {
        $query = 'UPDATE ' . $this->table . ' SET password = :password, otp = NULL, otp_expiry = NULL WHERE email = :email';
        $stmt = $this->conn->prepare($query);

        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->email = htmlspecialchars(strip_tags($this->email));

        // Hash password
        $password_hash = password_hash($this->password, PASSWORD_BCRYPT);
        
        $stmt->bindParam(':password', $password_hash);
        $stmt->bindParam(':email', $this->email);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}

