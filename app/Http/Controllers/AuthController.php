<?php

namespace App\Http\Controllers;

use App\Helpers\Json;
use App\Services\AuthService;

final class AuthController
{
    public function generateToken(array $data): string
    {
        // This a fake implementation, in a real context we need to validate the data and the service response
        return Json::response(['token' => (new AuthService())->generateToken($data)]);
    }
}
