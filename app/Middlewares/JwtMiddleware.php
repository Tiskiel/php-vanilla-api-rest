<?php

namespace App\Middlewares;

use App\Helpers\Auth;
use App\Helpers\Json;

final class JwtMiddleware extends Middleware
{
    public function __construct(
        /**
         * @var array<string, string>|null
         */
        private ?array $headers,
    )
    {
        parent::__construct();
    }

    public function init(): void
{
    if (!$this->headers || !isset($this->headers['Authorization'])) {
        throw new \Exception('Token not found', 401);
    }

    $authHeader = $this->headers['Authorization'];

    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
    } else {
        throw new \Exception('Malformed Authorization header', 400);
    }

    $decoded = Auth::decodeToken($token);

    if (!$decoded) {
        throw new \Exception('Unauthorized', 401);
    }
}

}
