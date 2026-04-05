<?php
require_once __DIR__ . '/../models/Chatbot.php';

class ChatbotController {
    private $db;
    private $chatbot;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->chatbot = new Chatbot($this->db);
    }

    // Get All Q&A (Public/Admin)
    public function index() {
        $category = isset($_GET['category']) ? $_GET['category'] : null;

        $stmt = $this->chatbot->getAll($category);
        $num = $stmt->rowCount();

        if($num > 0) {
            $chatbots_arr = array();
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                $chatbot_item = array(
                    'id' => $id,
                    'question' => $question,
                    'answer' => $answer,
                    'category' => $category,
                    'is_active' => (bool)$is_active,
                    'created_at' => $created_at
                );
                array_push($chatbots_arr, $chatbot_item);
            }
            echo json_encode($chatbots_arr);
        } else {
            echo json_encode(array());
        }
    }

    // Ask Question (Public)
    public function ask() {
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->message)) {
            require_once __DIR__ . '/../models/ChatAgent.php';
            $agent = new ChatAgent($this->db);
            
            $response = $agent->processMessage($data->message);
            
            echo json_encode($response);
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Message is required."));
        }
    }

    // Create Q&A (Admin)
    public function create() {
        $data = json_decode(file_get_contents("php://input"));

        if(
            !empty($data->question) &&
            !empty($data->answer) &&
            !empty($data->category)
        ) {
            $this->chatbot->question = $data->question;
            $this->chatbot->answer = $data->answer;
            $this->chatbot->category = $data->category;
            $this->chatbot->is_active = isset($data->is_active) ? $data->is_active : 1;

            if($this->chatbot->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Q&A created successfully."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to create Q&A."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    // Update Q&A (Admin)
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));

        if(
            !empty($data->question) &&
            !empty($data->answer) &&
            !empty($data->category)
        ) {
            $this->chatbot->id = $id;
            $this->chatbot->question = $data->question;
            $this->chatbot->answer = $data->answer;
            $this->chatbot->category = $data->category;
            $this->chatbot->is_active = isset($data->is_active) ? $data->is_active : 1;

            if($this->chatbot->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Q&A updated successfully."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Unable to update Q&A."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Incomplete data."));
        }
    }

    // Delete Q&A (Admin)
    public function delete($id) {
        $this->chatbot->id = $id;

        if($this->chatbot->delete()) {
            http_response_code(200);
            echo json_encode(array("message" => "Q&A deleted."));
        } else {
            http_response_code(503);
            echo json_encode(array("message" => "Unable to delete Q&A."));
        }
    }
}
?>
