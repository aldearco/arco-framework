<?php

namespace Arco\Routing;

use Closure;

/**
 * This class stores the URI definition and action.
 */
class Route {
    /**
     * URI defined in the format `"/route/{param}"`.
     *
     * @var string
     */
    protected string $uri;

    /**
     * Action associated to this URI.
     *
     * @var Closure
     */
    protected Closure|array $action;

    /**
     * Route name.
     *
     * @var null|string
     */
    protected ?string $name = null;

    /**
     * Regular expression used to match incoming requests URIs.
     *
     * @var string
     */
    protected string $regex;

    /**
     * Route parameter names.
     *
     * @var string[]
     */
    protected array $parameters;

    /**
     * HTTP middlewares.
     *
     * @var \Arco\Http\Middleware[]
     */
    protected array $middlewares = [];

    /**
     * Create a new route with the given URI and action.
     *
     * @param string $uri
     * @param Closure $action
     */
    public function __construct(string $uri, Closure|array $action) {
        $this->uri = $uri;
        $this->action = $action;
        $this->regex = preg_replace('/\{([a-zA-Z_-]+)\}/', '([a-zA-Z0-9\-]+)', $uri);
        preg_match_all('/\{([a-zA-Z_-]+)\}/', $uri, $parameters);
        $this->parameters = $parameters[1];

        if (config("http.csrf.enabled", false)) {
            $this->setMiddlewares([config("http.csrf.middleware")]);
        }
    }

    /**
     * Get the URI definition for this route.
     *
     * @return string
     */
    public function uri(): string {
        return $this->uri;
    }

    /**
     * Action that handles requests to this route URI.
     *
     * @return Closure
     */
    public function action(): Closure|array {
        return $this->action;
    }

    /**
     * Get all HTTP middlewares for this route.
     *
     * @return \Arco\Http\Middleware[]
     */
    public function middlewares(): array {
        return $this->middlewares;
    }

    /**
     * Set middleware for the route
     *
     * @param array $middlewares
     * @return self
     */
    public function setMiddlewares(array $middlewares): self {
        $middlewaresToAdd = array_map(fn ($middleware) => new $middleware(), $middlewares);
        $this->middlewares = array_merge($this->middlewares, $middlewaresToAdd);
        return $this;
    }

    /**
     * Check if route has middlewares
     *
     * @return boolean
     */
    public function hasMiddlewares(): bool {
        return count($this->middlewares) > 0;
    }

    /**
     * Set route name and register in the router.
     *
     * @param string $name
     * @return Route
     */
    public function name(string $name): Route {
        $this->name = $name;
        return app()->router->name($this, $name);
    }

    /**
     * Get route name.
     *
     * @return null|string
     */
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * Check if the given `$uri` matches the regex of this route.
     *
     * @param string $uri
     * @return boolean
     */
    public function matches(string $uri): bool {
        return preg_match("#^$this->regex/?$#", $uri);
    }

    /**
     * Check if this route has variable paramaters in its definition.
     *
     * @return boolean
     */
    public function hasParameters(): bool {
        return count($this->parameters) > 0;
    }

    /**
     * Get the key-value pairs from the `$uri` as defined by this route.
     *
     * @param string $uri
     * @return array
     */
    public function parseParameters(string $uri): array {
        preg_match("#^$this->regex$#", $uri, $arguments);

        return array_combine($this->parameters, array_slice($arguments, 1));
    }

    /**
     * Load routes from routes directory files
     *
     * @param string $routesDirectory
     * @return void
     */
    public static function load(string $routesDirectory) {
        foreach (glob("$routesDirectory/*.php") as $routes) {
            require_once $routes;
        }
    }

    /**
     * Store HTTP Method GET route in the App Router container
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public static function get(string $uri, Closure|array $action): Route {
        return app()->router->get($uri, $action);
    }

    /**
     * Store HTTP Method POST route in the App Router container
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public static function post(string $uri, Closure|array $action): Route {
        return app()->router->post($uri, $action);
    }

    /**
     * Store HTTP Method PUT route in the App Router container
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public static function put(string $uri, Closure|array $action): Route {
        return app()->router->put($uri, $action);
    }

    /**
     * Store HTTP Method PATCH route in the App Router container
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public static function patch(string $uri, Closure|array $action): Route {
        return app()->router->patch($uri, $action);
    }

    /**
     * Store HTTP Method DELETE route in the App Router container
     *
     * @param string $uri
     * @param Closure $action
     * @return Route
     */
    public static function delete(string $uri, Closure|array $action): Route {
        return app()->router->delete($uri, $action);
    }

    /**
     * Store all resource routes for a Model by specifying their corresponding controller.
     *
     * @param string $name
     * @param string $controller
     * @return void
     */
    public static function quiver(string $name, string $controller, array $options = []) {
        return RouteService::generateResourceRoutes(
            $name,
            $controller,
            isset($options['methods']) ? $options['methods'] : [],
            isset($options['middlewares']) ? $options['middlewares'] : []
        );
    }

    /**
     * Add middleware to a bundle of routes.
     *
     * @param array $middlewares
     * @param array<Route> $routes
     * @return void
     */
    public static function middleware(array $middlewares, array $routes) {
        foreach ($routes as $route) {
            $route->setMiddlewares($middlewares);
        }
    }
}
