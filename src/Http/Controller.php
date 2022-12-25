<?php

namespace Arco\Http;

class Controller {
    /**
     * HTTP middlewares.
     *
     * @var Middleware[]
     */
    protected array $middlewares = [];

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
        $this->middlewares = array_map(fn ($middleware) => new $middleware(), $middlewares);
        return $this;
    }
}
