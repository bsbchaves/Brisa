<?php

namespace Brisa\Dispatcher;

use Brisa\Http\Request;
use Brisa\Http\Response;
use ReflectionClass;
use ReflectionException;

class Dispatch
{
    private static null|int $errno = null;

    public static function execute(array $routes): bool
    {
        $uri = filter_input(INPUT_GET, 'route', FILTER_SANITIZE_URL);
        $uri = rtrim($uri, '/');
        unset($_GET['route']);

        $method = Request::method();

        if (!array_key_exists($method, $routes)) {
            self::$errno = Response::NOT_FOUND;
            return false;
        }

        $route = array_filter($routes[$method], function ($k) use ($uri, $routes, $method) {
            $current = $routes[$method][$k];

            $pattern = "~^{$k}$~";

            if (preg_match($pattern, $uri, $data_value)) {
                array_shift($data_value);

                preg_match_all('~{([\w-]+)}~', $current->route, $data_key, PREG_PATTERN_ORDER);
                array_shift($data_key);

                $routes[$method][$k]->data = array_combine($data_key[0], $data_value);

                return true;
            }

            return false;
        }, ARRAY_FILTER_USE_KEY);

        if (empty($route)) {
            self::$errno = Response::NOT_FOUND;
            return false;
        }

        $route = reset($route);

        $controller = "{$route->namespace}\\{$route->controller}";

        try {
            if (!class_exists($controller)) {
                throw new ReflectionException('', Response::NOT_IMPLEMENTED);
            }

            $reflection = new ReflectionClass($controller);

            if (!$reflection->hasMethod($route->action)) {
                throw new ReflectionException('', Response::METHOD_NOT_ALLOWED);
            }

            $action = $reflection->getMethod($route->action);

            if (!$action->isPublic()) {
                throw new ReflectionException('', Response::FORBIDDEN);
            }

            $action->invoke($reflection->newInstance(), $route);
        } catch (ReflectionException $e) {
            self::$errno = $e->getCode();

            return false;
        }

        return true;
    }

    public static function errno(): null|int
    {
        return self::$errno;
    }
}
