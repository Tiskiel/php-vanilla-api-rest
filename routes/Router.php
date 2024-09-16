<?php

namespace Routes;

use App\Helpers\Json;
use Closure;

final class Router
{
    protected array $routes = [];

    public function addRoute(string $method, string $url, Closure $target, array $middlewares = []): void
    {
        $this->routes[$method][$url] = [
            'target' => $target,
            'middlewares' => $middlewares
        ];

    }

    public function matchRoute() {
        // Don't try to run this code in the CLI environment
        if (php_sapi_name() !== 'cli' || getenv('APP_ENV') === 'testing') {
            $method = $_SERVER['REQUEST_METHOD'];
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $queryParams = $_GET;
            $bodyParams = [];
            $headers = [];

            foreach ($_SERVER as $key => $value) {
                if (substr($key, 0, 5) === 'HTTP_') {
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))))] = $value;
                }
            }

            if ($method === 'POST' || $method === 'PUT') {
                $bodyParams = json_decode(file_get_contents('php://input'), true) ?? $_POST;
            }

            if (isset($this->routes[$method])) {
                foreach ($this->routes[$method] as $routeUrl => $route) {
                    $routePattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([a-zA-Z0-9_-]+)', $routeUrl);
                    // The # character is used as a delimiter instead of the usual / character to avoid escaping the / character in the regex
                    $routePattern = '#^' . $routePattern . '$#';

                    if (preg_match($routePattern, $uri, $matches)) {
                        array_shift($matches);

                        $params = $method === 'GET' ? $queryParams : $bodyParams;

                        foreach ($route['middlewares'] as $middlewares) {
                            try {
                                if(!class_exists($middlewares)) {
                                    throw new \Exception('Middleware not found');
                                }

                                $reflector = new \ReflectionClass($middlewares);

                                if($reflector->getName() === 'App\Middlewares\JwtMiddleware') {
                                    new $middlewares($headers);
                                }

                            } catch (\Exception $th) {
                                return Json::response(['message' => $th->getMessage()], 401);
                            }
                        }

                        return call_user_func_array($route['target'], array_merge($matches, [$params]));
                    }
                }
            }

            return Json::response(['message' => 'Route not found'], 404);
        }
    }
}
