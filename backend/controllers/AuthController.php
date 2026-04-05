<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../utils/JwtHandler.php';

class AuthController {
    private $db;
    private $user;
    private $jwt;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
        $this->jwt = new JwtHandler();
    }

    public function register() {
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->first_name) && !empty($data->last_name) && !empty($data->email) && !empty($data->password) && !empty($data->phone)) {
            $this->user->first_name = $data->first_name;
            $this->user->middle_name = isset($data->middle_name) ? $data->middle_name : ''; // Optional
            $this->user->last_name = $data->last_name;
            $this->user->email = $data->email;
            $this->user->phone = $data->phone;
            $this->user->password = $data->password;
            $this->user->role = 'user'; // Default role

            if($this->user->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "User created."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create user."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    public function login() {
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->email) && !empty($data->password)) {
            $this->user->email = $data->email;
            
            if($this->user->emailExists()) {
                if(password_verify($data->password, $this->user->password)) {
                    $token_payload = [
                        'id' => $this->user->id,
                        'name' => $this->user->first_name . ' ' . $this->user->last_name,
                        'email' => $this->user->email,
                        'role' => $this->user->role,
                        'iss' => 'localhost',
                        'exp' => time() + (60*60*24) // 1 day
                    ];
                    $token = $this->jwt->encode($token_payload);

                    http_response_code(200);
                    echo json_encode(array(
                        "message" => "Login successful",
                        "token" => $token,
                        "user" => [
                            "id" => $this->user->id,
                            "name" => $this->user->first_name . ' ' . $this->user->last_name,
                            "role" => $this->user->role,
                            "profile_image" => $this->user->profile_image
                        ]
                    ));
                } else {
                    http_response_code(401);
                    echo json_encode(array("message" => "Invalid password."));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Email not found."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    // Get User Profile
    public function getProfile() {
        $headers = apache_request_headers();
        $token = null;
        if(isset($headers['Authorization'])) {
            $matches = array();
            preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
            if(isset($matches[1])) {
                $token = $matches[1];
            }
        }

        if ($token) {
            $decoded = $this->jwt->decode($token);
            if ($decoded) {
                $this->user->id = $decoded['id'];
                if ($this->user->getSingleUser()) {
                     $user_data = [
                        'id' => $this->user->id,
                        'first_name' => $this->user->first_name,
                        'middle_name' => $this->user->middle_name,
                        'last_name' => $this->user->last_name,
                        'email' => $this->user->email,
                        'phone' => $this->user->phone,
                        'role' => $this->user->role,
                        'profile_image' => $this->user->profile_image,
                        'bio' => $this->user->bio,
                        'social_links' => json_decode($this->user->social_links)
                    ];
                    echo json_encode($user_data);
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "User not found."));
                }
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Invalid token."));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "No token provided."));
        }
    }

    // Update User Profile
    public function updateProfile() {
        $headers = apache_request_headers();
        $token = null;
        if(isset($headers['Authorization'])) {
            $matches = array();
            preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
            if(isset($matches[1])) {
                $token = $matches[1];
            }
        }

        if ($token) {
            $decoded = $this->jwt->decode($token);
            if ($decoded) {
                $data = json_decode(file_get_contents("php://input"));
                
                $this->user->id = $decoded['id'];
                // Fetch existing to fill gaps if partial update not fully supported or simple mapping
                // Assuming frontend sends all fields for simplicity of this update method
                
                $this->user->first_name = $data->first_name;
                $this->user->middle_name = isset($data->middle_name) ? $data->middle_name : null;
                $this->user->last_name = $data->last_name;
                $this->user->email = $data->email;
                $this->user->phone = $data->phone;
                $this->user->profile_image = isset($data->profile_image) ? $data->profile_image : null;
                $this->user->bio = isset($data->bio) ? $data->bio : null;
                $this->user->social_links = isset($data->social_links) ? json_encode($data->social_links) : null;
                // Keep role as is? Or fetch it first. Let's assume role doesn't change here.
                // We need to fetch current user to get role if not sending it?
                // For now, let's just assume we might need to be careful with role.
                // The update() method in User.php requires role. 
                // Let's fetch the user first to be safe.
                $currentUser = new User($this->db);
                $currentUser->id = $decoded['id'];
                $currentUser->getSingleUser();
                $this->user->role = $currentUser->role; // Preserve role

                if($this->user->update()) {
                    echo json_encode(array("message" => "Profile updated successfully."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to update profile."));
                }

            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Invalid token."));
            }
        } else {
            http_response_code(401);
            echo json_encode(array("message" => "No token provided."));
        }
    }
    // Send OTP
    public function sendOtp() {
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->email)) {
            $this->user->email = $data->email;
            
            if($this->user->emailExists()) {
                // Generate OTP
                $otp = rand(100000, 999999);
                $this->user->otp = $otp;
                // Expiry 10 minutes from now
                $this->user->otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
                
                if($this->user->saveOtp()) {
                    require_once '../utils/SMTPMailer.php';
                    $mailer = new SMTPMailer();
                    $subject = "Password Reset OTP";
                    $body = "Your OTP for password reset is: <b>$otp</b>. It expires in 10 minutes.";
                    
                    if($mailer->send($this->user->email, $subject, $body)) {
                        echo json_encode(array("message" => "OTP sent to your email."));
                    } else {
                        http_response_code(500);
                        echo json_encode(array("message" => "Failed to send email."));
                    }
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to save OTP."));
                }
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Email not found."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Email is required."));
        }
    }

    // Verify OTP
    public function verifyOtp() {
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->email) && !empty($data->otp)) {
            $this->user->email = $data->email;
            $this->user->otp = $data->otp;
            
            if($this->user->verifyOtp()) {
                echo json_encode(array("message" => "OTP verified successfully."));
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Invalid or expired OTP."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    // Reset Password
    public function resetPassword() {
        $data = json_decode(file_get_contents("php://input"));
        
        if(!empty($data->email) && !empty($data->password) && !empty($data->otp)) {
            $this->user->email = $data->email;
            $this->user->otp = $data->otp;
            $this->user->password = $data->password;
            
            if($this->user->verifyOtp()) {
                if($this->user->resetPassword()) {
                    echo json_encode(array("message" => "Password reset successfully."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Unable to reset password."));
                }
            } else {
                http_response_code(400);
                echo json_encode(array("message" => "Invalid or expired OTP."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }
}
