<?php
require_once __DIR__ . '/../models/User.php';

class UserController {
    private $db;
    private $user;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->user = new User($this->db);
    }

    public function index() {
        $result = $this->user->read();
        $num = $result->rowCount();

        if($num > 0) {
            $users_arr = array();
            $users_arr['data'] = array();

            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $user_item = array(
                    'id' => $id,
                    'first_name' => $first_name,
                    'middle_name' => $middle_name,
                    'last_name' => $last_name,
                    'email' => $email,
                    'phone' => $phone,
                    'role' => $role,
                    'profile_image' => $profile_image
                );
                array_push($users_arr['data'], $user_item);
            }
            echo json_encode($users_arr);
        } else {
            echo json_encode(array('data' => []));
        }
    }

    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->first_name) && !empty($data->last_name) && !empty($data->email) && !empty($data->phone)) {
            $this->user->id = $id;
            $this->user->first_name = $data->first_name;
            $this->user->middle_name = isset($data->middle_name) ? $data->middle_name : '';
            $this->user->last_name = $data->last_name;
            $this->user->email = $data->email;
            $this->user->phone = $data->phone;
            $this->user->role = isset($data->role) ? $data->role : 'user';

            if($this->user->update()) {
                echo json_encode(array("message" => "User updated."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update user."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    public function delete($id) {
        $this->user->id = $id;

        if($this->user->delete()) {
            echo json_encode(array("message" => "User deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete user."));
        }
    }
}
