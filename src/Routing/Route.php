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
        $this->regex = preg_replace('/\{([a-zA-Z_-]+)\}/', '([a-zA-Z0-9]+)', $uri);
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
     * Register route name in the router
     *
     * @param string $name
     * @return Route
     */
    public function name(string $name): Route {
        return app()->router->name($this, $name);
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

    // protected static function generateResourceRoutes(string $name, string $controller, array $methods, array $middlewares) {
    //     $singular = substr($name, 0, -1);
    //     if (in_array('index', $methods)) {
    //         app()->router->get("/{$name}", [$controller, 'index'])->name($name.".index")->setMiddlewares($middlewares);
    //     }
    //     if (in_array('create', $methods)) {
    //         app()->router->get("/{$name}/create", [$controller, 'create'])->name($name.".create")->setMiddlewares($middlewares);
    //     }
    //     if (in_array('store', $methods)) {
    //         app()->router->post("/{$name}", [$controller, 'store'])->name($name.".store")->setMiddlewares($middlewares);
    //     }
    //     if (in_array('show', $methods)) {
    //         app()->router->get("/{$name}/{{$singular}}", [$controller, 'show'])->name($name.".show")->setMiddlewares($middlewares);
    //     }
    //     if (in_array('edit', $methods)) {
    //         app()->router->get("/{$name}/{{$singular}}/edit", [$controller, 'edit'])->name($name.".edit")->setMiddlewares($middlewares);
    //     }
    //     if (in_array('update', $methods)) {
    //         app()->router->put("/{$name}/{{$singular}}", [$controller, 'update'])->name($name.".update")->setMiddlewares($middlewares);
    //     }
    //     if (in_array('destroy', $methods)) {
    //         app()->router->delete("/{$name}/{{$singular}}", [$controller, 'destroy'])->name($name.".destroy")->setMiddlewares($middlewares);
    //     }
    // }
}
