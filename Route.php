<?php

namespace Brisa;

use Brisa\Pathfinder\Metadata;
use Closure;

class Route extends ExtendRoute
{
    use TraitRoute;
    use Metadata;

    /**
     * Construtor da classe.
     * Cria uma nova rota.
     *
     * @param string $method método http.
     * @param string $route URI da rota.
     * @param Closure|string $handler ação a ser executada (função anônima ou 'Classe:método').
     * @param string|null $name nome (atalho) para a rota (opcional).
     */
    public function __construct(string $method, string $route, Closure|string $handler, ?string $name = null)
    {
        $route = rtrim($route, '/');
        $route = !self::$group ? $route : self::$group . $route;

        $route_key = self::createPattern($route);

        [$controller, $action] = explode(':', $handler);

        $temp_route = [
            'route' => $route,
            'name' => $name,
            'namespace' => self::$namespace,
            'controller' => $controller,
            'action' => $action,
            'data' => []
        ];

        if (count(self::$metadata) > 0) {
            $temp_route['metadata'] = (object) self::$metadata;
        }

        self::$routes[$method][$route_key] = (object) $temp_route;

        unset(self::$metadata['display']);

        self::$last_route = [
            'method' => $method,
            'route' => array_key_last(self::$routes[$method])
        ];
    }

    /**
     * Define uma nova rota GET.
     *
     * @param string $route URI da rota.
     * @param Closure|string $handler ação a ser executada (função anônima ou 'Classe:método').
     * @param string|null $name nome (atalho) para a rota (opcional).
     * 
     * @return Route
     */
    public static function get(string $route, Closure|string $handler, ?string $name = null): Route
    {
        return new self('GET', $route, $handler, $name);
    }

    /**
     * Define uma nova rota POST.
     *
     * @param string $route URI da rota.
     * @param Closure|string $handler ação a ser executada (função anônima ou 'Classe:método').
     * @param string|null $name nome (atalho) para a rota (opcional).
     * 
     * @return Route
     */
    public static function post(string $route, Closure|string $handler, ?string $name = null): Route
    {
        return new self('POST', $route, $handler, $name);
    }

    /**
     * Define uma nova rota PUT.
     *
     * @param string $route URI da rota.
     * @param Closure|string $handler ação a ser executada (função anônima ou 'Classe:método').
     * @param string|null $name nome (atalho) para a rota (opcional).
     * 
     * @return Route
     */
    public static function put(string $route, Closure|string $handler, ?string $name = null): Route
    {
        return new self('PUT', $route, $handler, $name);
    }

    /**
     * Define uma nova rota DELETE.
     *
     * @param string $route URI da rota.
     * @param Closure|string $handler ação a ser executada (função anônima ou 'Classe:método').
     * @param string|null $name nome (atalho) para a rota (opcional).
     * 
     * @return Route
     */
    public static function delete(string $route, Closure|string $handler, ?string $name = null): Route
    {
        return new self('DELETE', $route, $handler, $name);
    }
}
