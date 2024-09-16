<?php

namespace App\Http\Controllers;

use App\Helpers\Json;
use App\Services\UserService;

final class UserController
{
    private UserService $_userService;

    public function __construct(
        private ?\PDO $pdo = null
    )
    {
        $this->_userService = $this->pdo ? new UserService($this->pdo) : new UserService();
    }

    public function index(?string $firstName = null, ?string $lastName = null): string
    {
        $status = 200;

        $response = $this->_userService->index($firstName, $lastName);

        if(is_array($response) && array_key_exists('errors', $response)) {
            return Json::response($response, 404);
        }

        if(is_array($response) && empty($response)) {
            $response = ['message' => 'No users found'];
            $status = 404;
        }

        return Json::response($response, $status);
    }

    public function show(string $uuid): string
    {
        $response = $this->_userService->show($uuid);

        if(is_array($response) && array_key_exists('errors', $response)) {
            return Json::response($response, 404);
        }

        return Json::response($response, 200);
    }

    public function store(string $firstName, string $lastName): string
    {
        $response = $this->_userService->store($firstName, $lastName);

        if(is_array($response) && array_key_exists('errors', $response)) {
            return Json::response($response, 404);
        }

        $response = ['message' => 'User created successfully'];

        return Json::response($response, 201);
    }

    public function update(string $uuid, string $firstName, string $lastName): string
    {
        $response = $this->_userService->update($uuid, $firstName, $lastName);

        if(is_array($response) && array_key_exists('errors', $response)) {
            return Json::response($response, 404);
        }

        $response = ['message' => 'User updated successfully'];

        return Json::response($response, 200);
    }

    public function delete(string $uuid): string
    {
        $response = $this->_userService->delete($uuid);

        if(is_array($response) && array_key_exists('uuid', $response)) {
            return Json::response($response, 404);
        }

        $response = ['message' => 'User deleted successfully'];

        return Json::response($response, 200);
    }
}
