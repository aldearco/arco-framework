<?php

use Arco\Http\Request;
use Arco\Http\Response;

if (!function_exists('json')) {
    /**
     * Return array of data in json format
     *
     * @param array $data
     * @return Response
     */
    function json(array $data): Response {
        return Response::json($data);
    }
}

if (!function_exists('response')) {
    /**
     * Return new response
     *
     * @return Response
     */
    function response() {
        return new Response();
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to an `$uri`
     *
     * @param string $uri
     * @return Response
     */
    function redirect(string $uri): Response {
        return Response::redirect($uri);
    }
}

if (!function_exists('back')) {
    /**
     * Redirect to previous session uri
     *
     * @return Response
     */
    function back(): Response {
        return redirect(request()->headers('referer') ?? session()->get("_previous", "/"));
    }
}

if (!function_exists('view')) {
    /**
     * Return an HTML view
     *
     * @param string $view
     * @param array $params
     * @param string|null $layout
     * @return Response
     */
    function view(string $view, array $params = [], string $layout = null): Response {
        return Response::view($view, $params, $layout);
    }
}

if (!function_exists('request')) {
    /**
     * Access to the request
     *
     * @return Request
     */
    function request(): Request {
        return app()->request;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get CSRF Token if is generated by session
     *
     * @return string|null
     */
    function csrf_token(): string|null {
        return session()->token();
    }
}

if (!function_exists('csrf_input')) {
    /**
     * Return HTML Input with CSRF Token as value
     *
     * @return string
     */
    function csrf_input(): string {
        return '<input type="hidden" name="_token" value="'.csrf_token().'">';
    }
}

if (!function_exists('route')) {
    /**
     * Get the URL for a named route.
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    function route(string $name, array $parameters = []): string {
        $uri = app()->router->getRouteUriByName($name);

        if (!empty($parameters)) {
            foreach ($parameters as $key => $value) {
                $uri = str_replace("{{$key}}", $value, $uri);
            }
        }

        return $uri;
    }
}

if (!function_exists('isRoute')) {
    /**
     * Check if specified route URI is the same URI of the request.
     *
     * @param string $name
     * @param array $parameters
     * @return boolean
     */
    function isRoute(string $name, array $parameters = []): bool {
        $uri = app()->router->getRouteUriByName($name);

        if (!empty($parameters)) {
            foreach ($parameters as $key => $value) {
                $uri = str_replace("{{$key}}", $value, $uri);
            }
        }

        return app()->request->uri() === $uri;
    }
}
