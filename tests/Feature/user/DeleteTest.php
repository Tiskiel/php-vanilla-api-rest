<?php

use App\Dto\UserCreateDto;
use App\Http\Controllers\UserController;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Routes\Router;

beforeEach(function () {
    $this->userDto = new UserCreateDto('John', 'Doe');
    $this->repository = new UserRepository($this->pdo);
    $this->user = $this->repository->store($this->userDto);
});

it('should delete a user', function () {
    $this->repository->delete($this->userDto->getUuid());

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('should not delete a user that does not exist', function () {
    expect($this->repository->delete('non-existent-uuid'))->toBeFalse();
});

it('should delete a user via the service', function () {
    $service = new UserService($this->pdo);

    $service->delete($this->userDto->getUuid());

    $statement = $this->pdo->prepare('SELECT uuid, first_name, last_name FROM users WHERE uuid = :uuid');
    $statement->execute([':uuid' => $this->userDto->getUuid()]);
    $user = $statement->fetch(\PDO::FETCH_OBJ);

    expect($user)->toBeFalse();
});

it('should not delete a user that does not exist via the service', function () {
    $service = new UserService($this->pdo);

    expect($service->delete('non-existent-uuid'))->toBeArray();
});

it('is protected from injection', function () {
    $service = new UserService($this->pdo);

    expect($service->delete('DROP TABLE users'))->toBeArray();
});

it('is return an array with errors', function () {
    $service = new UserService($this->pdo);
    $errors = $service->delete('non-existent-uuid');

    expect($errors)->toBeArray();
    expect($errors['uuid'])->toBe('User not found');
});

it('is return a json response', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->delete($this->userDto->getUuid());

    expect($response)->toBeString();
    expect(json_decode($response)->message)->toBe('User deleted successfully');
});

it('is return a json response with error', function () {
    $controller = new UserController($this->pdo);

    $response = $controller->delete('non-existent-uuid');

    expect($response)->toBeString();
    expect(json_decode($response)->uuid)->toBe('User not found');
});

it('is return a status code 200', function () {
    $controller = new UserController($this->pdo);

    $controller->delete($this->userDto->getUuid());

    expect(http_response_code())->toBe(200);
});

it('is return a status code 404', function () {
    $controller = new UserController($this->pdo);

    $controller->delete('non-existent-uuid');

    expect(http_response_code())->toBe(404);
});

it('should delete a user via the router', function () {
    $router = new Router();

    $router->addRoute('DELETE', '/users/{uuid}', function ($uuid) {
        $controller = new UserController($this->pdo);
        return $controller->delete($uuid);
    });

    $_SERVER['REQUEST_METHOD'] = 'DELETE';
    $_SERVER['REQUEST_URI'] = '/users/' . $this->userDto->getUuid();

    $response = $router->matchRoute();

    expect(json_decode($response, true)['message'])->toBe('User deleted successfully');
});

it('is return 200 status code when the user is deleted via the router', function () {
    $router = new Router();

    $router->addRoute('DELETE', '/users/{uuid}', function ($uuid) {
        $controller = new UserController($this->pdo);
        return $controller->delete($uuid);
    });

    $_SERVER['REQUEST_METHOD'] = 'DELETE';
    $_SERVER['REQUEST_URI'] = '/users/' . $this->userDto->getUuid();

    $router->matchRoute();

    expect(http_response_code())->toBe(200);
});

it('is return 404 status code when the user does not exist via the router', function () {
    $router = new Router();

    $router->addRoute('DELETE', '/users/{uuid}', function ($uuid) {
        $controller = new UserController($this->pdo);
        return $controller->delete($uuid);
    });

    $_SERVER['REQUEST_METHOD'] = 'DELETE';
    $_SERVER['REQUEST_URI'] = '/users/non-existent-uuid';

    $router->matchRoute();

    expect(http_response_code())->toBe(404);
});
