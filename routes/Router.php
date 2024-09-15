<?php

namespace Routes;

use App\Helpers\Json;
use Closure;

final class Router
{
    protected array $routes = [];

    public function addRoute(string $method, string $url, Closure $target) {
        $this->routes[$method][$url] = $target;
    }

    public function matchRoute() {

        // Don't try to run this code in the CLI environment
        if (php_sapi_name() !== 'cli') {
            $method = $_SERVER['REQUEST_METHOD'];
            $url = $_SERVER['REQUEST_URI'];


            if (isset($this->routes[$method])) {
                foreach ($this->routes[$method] as $routeUrl => $target) {
                    if ($routeUrl === $url) {
                        return call_user_func($target);
                    }
                }
            }
            return Json::response(['message' => 'Route not found'], 404);
        }
    }
}
