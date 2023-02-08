<?php

namespace Arco\Routing;

/**
 * Methods used by the Cli
 */
trait RouterInfo {
    /**
     * Get the name by route URI. Used by the Cli.
     *
     * @param Route $route
     * @return string
     */
    protected function getRouteNameByUri(Route $route): null|string {
        foreach ($this->routeNames as $name => $check) {
            if ($route->action() == $check['action'] && $route->uri() == $check['uri']) {
                return $name;
            }
        }
        return null;
    }

    /**
     * Generate a string with the route action. Used by the Cli.
     *
     * @param Route $route
     * @return string
     */
    protected function getRouteMethodInString(Route $route): string {
        if (is_array($route->action())) {
            $classPath = str_replace(class_basename($route->action()[0]), "", $route->action()[0]);
            $className = class_basename($route->action()[0]);
            $classMethod = "<fg=#b97dd7>method</> <fg=#76aee9>{$route->action()[1]}</>";

            return $classPath."\033[33m<fg=#dec084>{$className}</> | {$classMethod}";
        } else {
            return "<fg=#b97dd7>Closure</>";
        }
    }

    /**
     * Generate a string separated with comas with the route middlewares. Used by the Cli.
     *
     * @param array $middlewares
     * @return string
     */
    protected function getRouteMiddlewaresInString(array $middlewares): string {
        $middlewareStrings = [];

        foreach ($middlewares as $middleware) {
            $className = class_basename($middleware);

            array_push($middlewareStrings, "<fg=#dec084>{$className}</>");
        }

        return implode(", ", $middlewareStrings);
    }

    /**
     * Generate an array containing all information for the specified route name. Used by the Cli.
     *
     * @param string $name
     * @return array|null
     */
    public function getRouteInfo(string $name): array|null {
        $check = isset($this->routeNames[$name]) ? $this->routeNames[$name] : null;

        if (is_null($check)) {
            return null;
        }

        $info = [];
        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route) {
                if ($route->action() == $check['action'] && $route->uri() == $check['uri']) {
                    $info['method'] = "<fg=#dec084;options=bold>$method</>";
                    $info['uri'] = "<fg=#53d0db>{$route->uri()}</>";
                    $info['name'] = "<fg=#a2c181>{$this->getRouteNameByUri($route)}</>";
                    $info['action'] = $this->getRouteMethodInString($route);
                    $info['parameters'] = $route->hasParameters()
                        ? "<fg=#c89b6e>true</>"
                        : "<fg=#c89b6e>false</>";
                    $info['middleware'] = $route->hasMiddlewares()
                        ? $this->getRouteMiddlewaresInString($route->middlewares())
                        : "<fg=#c89b6e>No middleware registered</>";
                }
            }
        }

        return [$info];
    }

    /**
     * Generate an array containing all information for the routes of the route:list command in the command line interface (CLI). Used by the Cli.
     *
     * @return array
     */
    public function getRouteList(): array {
        $routeList = [];

        foreach ($this->routes as $method => $routes) {
            foreach ($routes as $route) {
                array_push($routeList, [
                    "<fg=#dec084;options=bold>$method</>",
                    "<fg=#53d0db>{$route->uri()}</>",
                    "<fg=#a2c181>{$this->getRouteNameByUri($route)}</>",
                    $this->getRouteMethodInString($route)
                ]);
            }
        }

        usort($routeList, function ($a, $b) {
            return strcmp($a[1], $b[1]);
        });

        return $routeList;
    }
}
