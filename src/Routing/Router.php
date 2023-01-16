<?php

namespace Arco\Routing;

use Closure;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Http\HttpMethod;
use Arco\Http\HttpNotFoundException;
use Arco\Container\DependencyInjection;
use Arco\Routing\Exceptions\RouteNameNotFoundException;

/**
 * HTTP router.
 */
class Router {
    /**
     * HTTP routes.
     *
     * @var array<string, Route[]>
     */
    protected array $routes = [];

    /**
     * Route URIs Stored by Route Name
     *
     * @var array
     */
    protected array $routeNames = [];

    /**
     * Create a new router.
     */
    public function __construct() {
        foreach (HttpMethod::cases() as $method) {
            $this->routes[$method->value] = [];
        }
    }

    /**
     * Resolve the route of the `$request`.
     *
     * @param Request $request
     * @return Route
     * @throws HttpNotFoundException when route is not found
     */
    public function resolveRoute(Request $request): Route {
        foreach ($this->routes[$request->data("_method") ?? $request->method()->value] as $route) {
            if ($route->matches($request->uri())) {
                return $route;
            }
        }

        throw new HttpNotFoundException();
    }

    /**
     * Resolve the requested route and execute middlewares if setted
     *
     * @param Request $request
     * @return Response
     */
    public function resolve(Request $request): Response {
        $route = $this->resolveRoute($request);
        $request->setRoute($route);
        $action = $route->action();

        $middlewares = $route->middlewares();

        if (is_array($action)) {
            $controller = new $action[0]();
            $action[0] = $controller;
            $middlewares = array_merge($middlewares, $controller->middlewares());
        }

        $params = DependencyInjection::resolveParameters($action, $request->routeParameters());

        return $this->runMiddlewares(
            $request,
            $middlewares,
            fn () => call_user_func($action, ...$params)
        );
    }

    /**
     * Run all middlewares setted for a route
     *
     * @param Request $request
     * @param array $middlewares Setted middlewares
     * @param mixed $target Final action of the route
     * @return Response
     */
    protected function runMiddlewares(Request $request, array $middlewares, $target): Response {
        if (count($middlewares) == 0) {
            return $target();
        }

        return $middlewares[0]->handle(
            $request,
            fn ($request) => $this->runMiddlewares($request, array_slice($middlewares, 1), $target)
        );
    }

    /**
     * Register a new route with the given `$method` and `$uri`.
     *
     * @param HttpMethod $method
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    protected function registerRoute(HttpMethod $method, string $uri, Closure|array $action): Route {
        $route = new Route($uri, $action);
        $this->routes[$method->value][] = $route;

        return $route;
    }

    /**
     * Register a named route with the router.
     *
     * @param Route $route
     * @param string $name
     * @return Route
     */
    protected function registerName(Route $route, string $name): Route {
        $this->routeNames[$name] = $route->uri();

        return $route;
    }

    /**
     * Get the URI for a named route.
     *
     * @param string $name
     * @return string
     */
    public function getRouteUriByName(string $name): string {
        if (isset($this->routeNames[$name])) {
            return $this->routeNames[$name];
        }

        return $name;
    }

    /**
     * Alias for registerName method
     *
     * @param Route $route
     * @param string $name
     * @return Route
     */
    public function name(Route $route, string $name): Route {
        return $this->registerName($route, $name);
    }

    /**
     * Register a GET route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param \Closure $action
     * @return Route
     */
    public function get(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::GET, $uri, $action);
    }

    /**
     * Register a POST route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function post(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::POST, $uri, $action);
    }

    /**
     * Register a PUT route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function put(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::PUT, $uri, $action);
    }

    /**
     * Register a PATCH route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function patch(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::PATCH, $uri, $action);
    }

    /**
     * Register a DELETE route with the given `$uri` and `$action`.
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public function delete(string $uri, Closure|array $action): Route {
        return $this->registerRoute(HttpMethod::DELETE, $uri, $action);
    }
}
