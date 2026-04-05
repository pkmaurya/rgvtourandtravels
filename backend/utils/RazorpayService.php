<?php
require_once __DIR__ . '/../config/PaymentConfig.php';

class RazorpayService {
    private $keyId;
    private $keySecret;
    private $baseUrl = 'https://api.razorpay.com/v1';

    public function __construct($keyId, $keySecret) {
        $this->keyId = $keyId;
        $this->keySecret = $keySecret;
    }

    private function request($method, $endpoint, $data = []) {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->keyId . ':' . $this->keySecret);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local dev only

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode >= 400) {
            throw new Exception("Razorpay Error: " . ($result['error']['description'] ?? 'Unknown error'));
        }

        return $result;
    }

    public function createOrder($amount, $currency = 'INR', $receipt = null) {
        $data = [
            'amount' => $amount * 100, // Amount in paise
            'currency' => $currency,
            'receipt' => $receipt,
            'payment_capture' => 1
        ];

        return $this->request('POST', '/orders', $data);
    }

    public function verifySignature($attributes) {
        $actualSignature = $attributes['razorpay_signature'];
        $orderId = $attributes['razorpay_order_id'];
        $paymentId = $attributes['razorpay_payment_id'];

        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $this->keySecret);

        if ($expectedSignature !== $actualSignature) {
            throw new Exception("Invalid signature");
        }

        return true;
    }
}
