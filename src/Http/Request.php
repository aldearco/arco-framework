<?php

namespace Arco\Http;

use Arco\Storage\File;
use Arco\Routing\Route;
use Arco\Validation\Validator;

/**
 * HTTP request.
 */
class Request {
    /**
     * URI requested by the client.
     *
     * @var string
     */
    protected string $uri;

    /**
     * Route matched by URI.
     *
     * @var Route
     */
    protected Route $route;

    /**
     * HTTP method used for this request.
     *
     * @var HttpMethod
     */
    protected HttpMethod $method;

    /**
     * POST data.
     *
     * @var array
     */
    protected array $data;

    /**
     * Query parameters.
     *
     * @var array
     */
    protected array $query;

    /**
     * HTTP request headers.
     *
     * @var array
     */
    protected array $headers = [];

    /**
     * Uploaded files.
     *
     * @var array<string, \Arco\Storage\File>
     */
    protected array $files = [];

    /**
     * Get the request URI.
     *
     * @return string
     */
    public function uri(): string {
        return $this->uri;
    }

    /**
     * Set request URI.
     *
     * @param string $uri
     * @return self
     */
    public function setUri(string $uri): self {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get route matched by this request.
     *
     * @return Route
     */
    public function route(): Route {
        return $this->route;
    }

    /**
     * Set route for this request.
     *
     * @param Route $route
     * @return self
     */
    public function setRoute(Route $route): self {
        $this->route = $route;
        return $this;
    }

    /**
     * Get the request HTTP method.
     *
     * @return HttpMethod
     */
    public function method(): HttpMethod {
        return $this->method;
    }

    /**
     * Set HTTP method.
     *
     * @param HttpMethod $method
     * @return self
     */
    public function setMethod(HttpMethod $method): self {
        $this->method = $method;
        return $this;
    }

    /**
     * Get HTTP request headers as key-value or get only specific value by provinding a `$key`.
     *
     * @param string|null $key
     * @return array|string|null
     */
    public function headers(?string $key = null): array|string|null {
        if (is_null($key)) {
            return $this->headers;
        }

        return $this->headers[strtolower($key)] ?? null;
    }

    /**
     * Set HTTP request headers.
     *
     * @param array $headers
     * @return self
     */
    public function setHeaders(array $headers): self {
        foreach ($headers as $header => $value) {
            $this->headers[strtolower($header)] = $value;
        }

        return $this;
    }

    /**
     * Get file from request.
     *
     * @param string $name
     * @return File|null
     */
    public function file(string $name): ?File {
        return $this->files[$name] ?? null;
    }

    /**
     * Get all files from request.
     *
     * @param string $name
     * @return array<string, \Arco\Storage\File>
     */
    public function files(): array {
        return $this->files ?? [];
    }

    /**
     * Set uploaded files.
     *
     * @param array<string, File> $files
     * @return self
     */
    public function setFiles(array $files): self {
        $this->files = $files;
        return $this;
    }

    /**
     * Get all POST data as key-value or get only specific value by providing a `$key`.
     *
     * @return array|string|null Null if the key doesn't exist, the value of
     * the key if it is present or all the data if no key was provided.
     */
    public function data(?string $key = null): array|string|null {
        if (is_null($key)) {
            return $this->data;
        }

        return $this->data[$key] ?? null;
    }

    /**
     * Set POST data for this request.
     *
     * @param array $data
     * @return self
     */
    public function setPostData(array $data): self {
        $this->data = $data;
        return $this;
    }

    /**
     * Return if isset `$key` inside request data array
     *
     * @param string $key
     * @return boolean
     */
    public function has(string $key): bool {
        return isset($this->data[$key]);
    }

    /**
     * Get all query params as key-value or get only specific value by providing
     * a `$key`.
     *
     * @return array|string|null Null if the key doesn't exist, the value of
     * the key if it is present or all the query params if no key was provided.
     */
    public function query(?string $key = null): array|string|null {
        if (is_null($key)) {
            return $this->query;
        }

        return $this->query[$key] ?? null;
    }

    /**
     * Set query parameters for this request.
     *
     * @param array $query
     * @return self
     */
    public function setQueryParameters(array $query): self {
        $this->query = $query;
        return $this;
    }

    /**
     * Get all route params as key-value or get only specific value by providing
     * a `$key`.
     *
     * @return array|string|null Null if the key doesn't exist, the value of
     * the key if it is present or all the route params if no key was provided.
     */
    public function routeParameters(?string $key = null): array|string|null {
        $parameters = $this->route->parseParameters($this->uri);

        if (is_null($key)) {
            return $parameters;
        }

        return $parameters[$key] ?? null;
    }

    /**
     * Execute the Validation API using the data of this actual request
     *
     * @param array $rules All rules for expected fields. `"field" => ["rules"]`
     * @param array $messages Array of custom messages if the rule is not valid
     * @return array
     */
    public function validate(array $rules, array $messages = []): array {
        $validator = new Validator(array_merge($this->data, $this->query, $this->files));

        return $validator->validate($rules, $messages);
    }
}
