<?php

use App\Dto\UserCreateDto;
use App\Dto\UserUpdateDto;
use App\Http\Controllers\UserController;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Routes\Router;

beforeEach(function () {
    $this->userDto = new UserCreateDto('John', 'Doe');
    $this->repository = new UserRepository($this->pdo);
    $this->user = $this->repository->store($this->userDto);
});

it('should update a user', function () {
    $userUpdateDto = new UserUpdateDto($this->userDto->getUuid(), 'Bat', 'man');

    $this->repository->update($userUpdateDto);

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user->uuid)->toBe($this->userDto->getUuid());
    expect($user->first_name)->toBe('Bat');
    expect($user->last_name)->toBe('man');
});

it('should not update a user that does not exist', function () {
    $userUpdateDto = new UserUpdateDto('non-existent-uuid', 'Bat', 'man');

    expect($this->repository->update($userUpdateDto))->toBeFalse();

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $userUpdateDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('should update a user via the service', function () {
    $service = new UserService($this->pdo);

    $service->update($this->userDto->getUuid(), 'Bat', 'man');

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user->uuid)->toBe($this->userDto->getUuid());
    expect($user->first_name)->toBe('Bat');
    expect($user->last_name)->toBe('man');
});

it('should not update a user that does not exist via the service', function () {
    $service = new UserService($this->pdo);

    expect($service->update('non-existent-uuid', 'Bat', 'man'))->toBeArray();

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => 'non-existent-uuid']);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('return an error when the first name is empty', function () {
    $service = new UserService($this->pdo);
    $errors = $service->update($this->userDto->getUuid(),'', 'man');

    expect($errors)->toBeArray();
    expect($errors['errors'])->toHaveKey('first_name');
    expect($errors['errors']['first_name'])->toBe('First name is required');
});

it('return an error when the last name is empty', function () {
    $service = new UserService($this->pdo);
    $errors = $service->update($this->userDto->getUuid(),'Bat', '');

    expect($errors)->toBeArray();
    expect($errors['errors'])->toHaveKey('last_name');
    expect($errors['errors']['last_name'])->toBe('Last name is required');
});

it('return an error when the first name and last name are empty', function () {
    $service = new UserService($this->pdo);
    $errors = $service->update($this->userDto->getUuid(), '', '');

    expect($errors)->toBeArray();
    expect($errors['errors'])->toHaveKey('first_name');
    expect($errors['errors']['first_name'])->toBe('First name is required');
    expect($errors['errors'])->toHaveKey('last_name');
    expect($errors['errors']['last_name'])->toBe('Last name is required');
});

it('should update a user via the controller', function () {
    $controller = new UserController($this->pdo);

    $controller->update($this->userDto->getUuid(), 'Bat', 'Man');

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user->uuid)->toBe($this->userDto->getUuid());
    expect($user->first_name)->toBe('Bat');
    expect($user->last_name)->toBe('Man');
});

it('should not update a user that does not exist via the controller', function () {
    $controller = new UserController($this->pdo);

    $controller->update('non-existent-uuid', 'Bat', 'Man');

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => 'non-existent-uuid']);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('return an error when the first name is empty via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->update($this->userDto->getUuid(), '', 'Man');

    expect($response)->toBeString();
    expect(json_decode($response, true)['errors'])->toHaveKey('first_name');
    expect(json_decode($response, true)['errors']['first_name'])->toBe('First name is required');
});

it('return an error when the last name is empty via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->update($this->userDto->getUuid(), 'Bat', '');

    expect($response)->toBeString();
    expect(json_decode($response, true)['errors'])->toHaveKey('last_name');
    expect(json_decode($response, true)['errors']['last_name'])->toBe('Last name is required');
});

it('return an error when the first name and last name are empty via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->update($this->userDto->getUuid(), '', '');

    expect($response)->toBeString();
    expect(json_decode($response, true)['errors'])->toHaveKey('first_name');
    expect(json_decode($response, true)['errors']['first_name'])->toBe('First name is required');
    expect(json_decode($response, true)['errors'])->toHaveKey('last_name');
    expect(json_decode($response, true)['errors']['last_name'])->toBe('Last name is required');
});

it('return an error when the user does not exist via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->update('non-existent-uuid', 'Bat', 'Man');

    expect($response)->toBeString();
    expect(json_decode($response, true)['errors'])->toHaveKey('uuid');
    expect(json_decode($response, true)['errors']['uuid'])->toBe('User not found');
});

it('is return a success message when the user is updated via the controller', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->update($this->userDto->getUuid(), 'Bat', 'Man');

    expect($response)->toBeString();
    expect(json_decode($response, true)['message'])->toBe('User updated successfully');
});

it('is return a status code of 200 when the user is updated via the controller', function () {
    $controller = new UserController($this->pdo);

    $controller->update($this->userDto->getUuid(), 'Bat', 'Man');

    expect(http_response_code())->toBe(200);
});

it('is return a status code of 404 when the user does not exist via the controller', function () {
    $controller = new UserController($this->pdo);

    $controller->update('non-existent-uuid', 'Bat', 'Man');

    expect(http_response_code())->toBe(404);
});

it('should update a user via the router', function () {
    $router = new Router();

    $router->addRoute('PUT', '/users/{uuid}', function ($uuid, $params) {
        $controller = new UserController($this->pdo);
        return $controller->update($uuid, $params['first_name'], $params['last_name']);
    });

    $_SERVER['REQUEST_METHOD'] = 'PUT';
    $_SERVER['REQUEST_URI'] = '/users/' . $this->userDto->getUuid();
    $_POST = ['first_name' => 'Bat', 'last_name' => 'Man'];

    $response = $router->matchRoute();

    expect(json_decode($response, true)['message'])->toBe('User updated successfully');
});

it('is return 200 status code when the user is updated via the router', function () {
    $router = new Router();

    $router->addRoute('PUT', '/users/{uuid}', function ($uuid, $params) {
        $controller = new UserController($this->pdo);
        return $controller->update($uuid, $params['first_name'], $params['last_name']);
    });

    $_SERVER['REQUEST_METHOD'] = 'PUT';
    $_SERVER['REQUEST_URI'] = '/users/' . $this->userDto->getUuid();
    $_POST = ['first_name' => 'Bat', 'last_name' => 'Man'];

    $router->matchRoute();

    expect(http_response_code())->toBe(200);
});

it('is return 404 status code when the user does not exist via the router', function () {
    $router = new Router();

    $router->addRoute('PUT', '/users/{uuid}', function ($uuid, $params) {
        $controller = new UserController($this->pdo);
        return $controller->update($uuid, $params['first_name'], $params['last_name']);
    });

    $_SERVER['REQUEST_METHOD'] = 'PUT';
    $_SERVER['REQUEST_URI'] = '/users/non-existent-uuid';
    $_POST = ['first_name' => 'Bat', 'last_name' => 'Man'];

    $router->matchRoute();

    expect(http_response_code())->toBe(404);
});
