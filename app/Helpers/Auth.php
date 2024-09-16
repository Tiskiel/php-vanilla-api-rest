<?php

namespace App\Helpers;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

final class Auth {
    private static $secretKey;
    private static $encryptMethod = 'HS256';

    public static function init(): void
    {
        if (empty($_ENV['SECRET_KEY'])) {
            throw new \Exception('Secret key is not set');
        }
        self::$secretKey = $_ENV['SECRET_KEY'];
    }

    /**
     * @param array<string, string> $data
     */
    public static function generateToken(array $data): string
    {
        if (empty(self::$secretKey)) {
            throw new \Exception('Secret key is not set');
        }

        $time = time();
        $payload = [
            'iat' => $time,
            'exp' => $time + (3600 * 24),
            'data' => $data
        ];

        return JWT::encode($payload, self::$secretKey, self::$encryptMethod);
    }

    public static function decodeToken(string $token)
    {
        try {
            return JWT::decode($token, new Key(self::$secretKey, self::$encryptMethod));
        } catch (\Throwable $th) {
            return false;
        }
    }
}
