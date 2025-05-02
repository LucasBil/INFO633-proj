<?php

class TokenManager {
    private static ?TokenManager $instance = null;

    private function __construct() {}

    public static function getInstance(): TokenManager {
        if (self::$instance === null) {
            self::$instance = new TokenManager();
        }
        return self::$instance;
    }

    public function generateToken(string $identifier, $data=[]): string {
        $payload = [
            'iat' => time(),
            'exp' => time() + 3600, // Token expires in 1 hour
            'data' => $data,
        ];
        return base64_encode(json_encode($payload)) . '.' . hash('sha256', $identifier . json_encode($payload));
    }

    public function validateToken(string $token): bool {
        list($payload, $signature) = explode('.', $token);
        $decodedPayload = json_decode(base64_decode($payload), true);
        if ($decodedPayload === null) {
            return false;
        }
        $expectedSignature =  hash('sha256', $decodedPayload['data']['email'] . json_encode($decodedPayload));
        return hash_equals($expectedSignature, $signature) && time() < $decodedPayload['exp'];
    }

    public function getTokenData(string $token): array {
        list($payload, $signature) = explode('.', $token);
        return json_decode(base64_decode($payload), true)['data'] ?? [];
    }
}