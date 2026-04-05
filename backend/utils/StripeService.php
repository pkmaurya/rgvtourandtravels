<?php
require_once __DIR__ . '/../config/PaymentConfig.php';

class StripeService {
    private $secretKey;
    private $baseUrl = 'https://api.stripe.com/v1';

    public function __construct($secretKey) {
        $this->secretKey = $secretKey;
    }

    private function request($method, $endpoint, $data = []) {
        $url = $this->baseUrl . $endpoint;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->secretKey . ':');
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // For local dev only

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($httpCode >= 400) {
            throw new Exception("Stripe Error: " . ($result['error']['message'] ?? 'Unknown error'));
        }

        return $result;
    }

    public function createPaymentIntent($amount, $currency = 'usd', $metadata = []) {
        $data = [
            'amount' => $amount * 100, // Amount in cents
            'currency' => strtolower($currency),
            'metadata' => $metadata,
            'automatic_payment_methods' => ['enabled' => 'true']
        ];

        return $this->request('POST', '/payment_intents', $data);
    }
}
