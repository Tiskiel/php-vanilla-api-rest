<?php

namespace App\Http\Controllers;

use App\Helpers\Json;
use App\Services\UserService;

final class UserController
{
    private UserService $userService;

    public function __construct(
        private ?\PDO $pdo = null
    )
    {
        $this->userService = $this->pdo ? new UserService($this->pdo) : new UserService();
    }

    public function delete(string $uuid): string
    {
        $response = $this->userService->delete($uuid);

        if(is_array($response) && array_key_exists('uuid', $response)) {
            return Json::response($response, 404);
        }

        $response = ['message' => 'User deleted successfully'];

        return Json::response($response, 200);
    }
}
