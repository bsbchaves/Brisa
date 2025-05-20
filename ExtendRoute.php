<?php

namespace Brisa;

use Closure;
use stdClass;

trait ExtendRoute
{
    /** @var null|string */
    private static null|string $namespace = null;

    /** @var array */
    private static array $routes;

    /** @var null|string */
    private static null|string $group = null;
    
    /** @var array */
    private static array $last_route;

    /**
     * Retorna o array de rotas.
     *
     * @return array
     */
    public static function routes(): array
    {
        return self::$routes;
    }

    /**
     * Busca os arquivos de rotas no diretório indicado.
     *
     * @param string $directory diretório de rotas.
     * @param array $route_files nome(s) do(s) arquivo(s).
     * 
     * @return void
     */
    public static function getRouteFiles(string $directory, array $route_files): void
    {
        foreach ($route_files as $file_name) {
            $path = $directory . DIRECTORY_SEPARATOR . $file_name . '.routes.php';
            if (is_readable($path)) {
                require_once($path);
                if (self::$group) self::$group = null;
            }
        }

        return;
    }

    /**
     * Define o namespace da rota.
     *
     * @param string $namespace nome do namespace.
     * @param Closure|null $callback
     * @return void
     */
    public static function namespace(string $namespace, ?Closure $callback = null): void
    {
        self::$namespace = $namespace;

        if (is_callable($callback)) {
            $callback();
            self::$namespace = null;
        }

        return;
    }

    /**
     * Define o nome de um grupo de rotas.
     *
     * @param string $group nome do grupo.
     * @param Closure|null $callback 
     * 
     * @return void
     */
    public static function group(string $group, ?Closure $callback = null): void
    {
        self::$group = $group;

        if (is_callable($callback)) {
            $callback();
            self::$group = null;
        }

        return;
    }

    public static function addRoutesItem(string $item): void
    {
        $content = (array) self::$routes[self::$last_route['method']][self::$last_route['route']];
        self::$routes[self::$last_route['method']][self::$last_route['route']] = (object) array_merge($content, [$item => new stdClass]);
    }
}
