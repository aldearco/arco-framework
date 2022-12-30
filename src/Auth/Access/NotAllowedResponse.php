<?php

namespace Arco\Auth\Access;

use Arco\Http\Response;

trait NotAllowedResponse {
    /**
     * Array of custom responses for all methods
     *
     * @var array<string, Response>
     */
    protected array $responses = [];

    protected function setDenyResponse(string $method, Response $response) {
        $this->responses[$method] = $response;
    }

    public function deny(string $method) {
        if (isset($this->responses[$method])) {
            app()->abort($this->responses[$method]);
        }
        app()->abort(Response::text("Not allowed")->setStatus(403));
    }
}
