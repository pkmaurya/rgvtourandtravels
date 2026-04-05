<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../models/Offer.php';

class OfferController {
    
    private $db;
    private $offer;

    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
        $this->offer = new Offer($this->db);
    }

    // Get All Offers (Admin)
    public function index() {
        $result = $this->offer->read();
        $num = $result->rowCount();

        if($num > 0) {
            $offers_arr = array();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                
                // Skip if title or critical data is missing
                if (empty($title) || empty($discount_value)) {
                    continue;
                }

                $offer_item = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => html_entity_decode($description ?? ''),
                    'discount_type' => $discount_type,
                    'discount_value' => $discount_value,
                    'coupon_code' => $coupon_code,
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'min_booking_amount' => $min_booking_amount,
                    'priority' => $priority,
                    'status' => $status,
                    'scope' => $scope,
                    'created_at' => $created_at
                );
                array_push($offers_arr, $offer_item);
            }
            echo json_encode($offers_arr);
        } else {
            echo json_encode(array());
        }
    }

    // Get Active Offers (Public)
    public function getActive() {
        $result = $this->offer->readActive();
        $num = $result->rowCount();

        if($num > 0) {
            $offers_arr = array();
            while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                extract($row);

                if (empty($title) || empty($discount_value)) {
                    continue;
                }

                $offer_item = array(
                    'id' => $id,
                    'title' => $title,
                    'description' => html_entity_decode($description ?? ''),
                    'discount_type' => $discount_type,
                    'discount_value' => $discount_value,
                    'coupon_code' => $coupon_code, // Might want to hide this if it's not a coupon offer? Assuming public for now.
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                    'min_booking_amount' => $min_booking_amount,
                    'priority' => $priority,
                    'status' => $status,
                    'scope' => $scope
                );
                array_push($offers_arr, $offer_item);
            }
            echo json_encode($offers_arr);
        } else {
            echo json_encode(array());
        }
    }

    // Get Single Offer
    public function getOne($id) {
        $this->offer->id = $id;

        if($this->offer->readOne()) {
            $offer_arr = array(
                'id' => $this->offer->id,
                'title' => $this->offer->title,
                'description' => $this->offer->description,
                'discount_type' => $this->offer->discount_type,
                'discount_value' => $this->offer->discount_value,
                'coupon_code' => $this->offer->coupon_code,
                'start_date' => $this->offer->start_date,
                'end_date' => $this->offer->end_date,
                'min_booking_amount' => $this->offer->min_booking_amount,
                'priority' => $this->offer->priority,
                'status' => $this->offer->status,
                'scope' => $this->offer->scope
            );
            echo json_encode($offer_arr);
        } else {
            echo json_encode(array('message' => 'Offer Not Found'));
        }
    }

    // Create Offer
    public function create() {
        $data = json_decode(file_get_contents("php://input"));

        if(
            !empty($data->title) &&
            !empty($data->discount_type) &&
            !empty($data->discount_value) &&
            !empty($data->start_date) &&
            !empty($data->end_date)
        ) {
            $this->offer->title = $data->title;
            $this->offer->description = $data->description ?? '';
            $this->offer->discount_type = $data->discount_type;
            $this->offer->discount_value = $data->discount_value;
            $this->offer->coupon_code = $data->coupon_code ?? null;
            $this->offer->start_date = $data->start_date;
            $this->offer->end_date = $data->end_date;
            $this->offer->min_booking_amount = $data->min_booking_amount ?? 0;
            $this->offer->priority = $data->priority ?? 0;
            $this->offer->status = $data->status ?? 'active';
            $this->offer->scope = $data->scope ?? 'all';

            if($this->offer->create()) {
                echo json_encode(array('message' => 'Offer Created', 'id' => $this->offer->id));
            } else {
                echo json_encode(array('message' => 'Offer Not Created'));
            }
        } else {
            echo json_encode(array('message' => 'Incomplete Data'));
        }
    }

    // Update Offer
    public function update($id) {
        $data = json_decode(file_get_contents("php://input"));

        $this->offer->id = $id;
        $this->offer->title = $data->title;
        $this->offer->description = $data->description ?? '';
        $this->offer->discount_type = $data->discount_type;
        $this->offer->discount_value = $data->discount_value;
        $this->offer->coupon_code = $data->coupon_code ?? null;
        $this->offer->start_date = $data->start_date;
        $this->offer->end_date = $data->end_date;
        $this->offer->min_booking_amount = $data->min_booking_amount ?? 0;
        $this->offer->priority = $data->priority ?? 0;
        $this->offer->status = $data->status ?? 'active';
        $this->offer->scope = $data->scope ?? 'all';

        if($this->offer->update()) {
            echo json_encode(array('message' => 'Offer Updated'));
        } else {
            echo json_encode(array('message' => 'Offer Not Updated'));
        }
    }

    // Delete Offer
    public function delete($id) {
        $this->offer->id = $id;
        if($this->offer->delete()) {
            echo json_encode(array('message' => 'Offer Deleted'));
        } else {
            echo json_encode(array('message' => 'Offer Not Deleted'));
        }
    }

    // Verify Coupon Code
    public function verifyCoupon() {
        $data = json_decode(file_get_contents("php://input"));

        if(!empty($data->code)) {
            $amount = $data->amount ?? 0;
            $result = $this->offer->verifyCoupon($data->code, $amount);
            echo json_encode($result);
        } else {
            echo json_encode(array('valid' => false, 'message' => 'No coupon code provided.'));
        }
    }
}
