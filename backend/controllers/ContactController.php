<?php
require_once __DIR__ . '/../models/Contact.php';

class ContactController {
    private $db;
    private $contact;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->contact = new Contact($this->db);
    }

    // Submit Contact Form (Public)
    public function submit() {
        $data = json_decode(file_get_contents("php://input"));

        if(
            !empty($data->name) &&
            !empty($data->email) &&
            !empty($data->subject) &&
            !empty($data->message)
        ) {
            $this->contact->name = $data->name;
            $this->contact->email = $data->email;
            $this->contact->subject = $data->subject;
            $this->contact->message = $data->message;

            if($this->contact->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Message sent successfully."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to send message."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    // Get All Contacts (Admin)
    public function index() {
        $stmt = $this->contact->getAll();
        $num = $stmt->rowCount();

        if($num > 0) {
            $contacts_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $contact_item = array(
                    'id' => $id,
                    'name' => $name,
                    'email' => $email,
                    'subject' => $subject,
                    'message' => $message,
                    'created_at' => $created_at
                );
                array_push($contacts_arr, $contact_item);
            }
            echo json_encode($contacts_arr);
        } else {
            echo json_encode(array());
        }
    }

    // Delete Contact (Admin)
    public function delete($id) {
        $this->contact->id = $id;

        if($this->contact->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Message deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete message."));
        }
    }
}
?>
