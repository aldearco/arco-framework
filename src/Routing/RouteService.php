<?php

namespace Arco\Routing;

class RouteService {
    protected static $methods = [
        'index', 'create', 'store', 'show', 'edit', 'update', 'destroy'
    ];

    protected static function getRoutePath(string $name, string $method, string $singular) {
        $routePath = "/$name";

        if ($method=='show' || $method=='update' || $method=='destroy') {
            $routePath .= "/{{$singular}}";
        }
        if ($method=='create') {
            $routePath .= "/create";
        }
        if ($method=='edit') {
            $routePath .= "/{{$singular}}/edit";
        }

        return $routePath;
    }

    protected static function getRouteHttpMethod(string $method) {
        switch ($method) {
            case 'index':
            case 'show':
            case 'create':
            case 'edit':
                return 'get';
                break;
            case 'store':
                return 'post';
                break;
            case 'update':
                return 'put';
                break;
            case 'destroy':
                return 'delete';
                break;
        }
    }

    protected static function generateRoute($name, $controller, $method, $singular, $middlewares) {
        $routeName = "$name.$method";
        $routePath = self::getRoutePath($name, $method, $singular);
        $routeMethod = self::getRouteHttpMethod($method);

        return app()->router->$routeMethod($routePath, [$controller, $method])
            ->name($routeName)->setMiddlewares($middlewares);
    }

    public static function generateResourceRoutes(string $name, string $controller, array $methods, array $middlewares) {
        $singular = substr($name, 0, -1);

        if (empty($methods)) {
            $methods = self::$methods;
        }

        foreach ($methods as $method) {
            if (in_array($method, self::$methods)) {
                self::generateRoute($name, $controller, $method, $singular, $middlewares);
            }
        }
    }
}
