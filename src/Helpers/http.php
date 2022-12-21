<?php

use Arco\Http\Request;
use Arco\Http\Response;

/**
 * Return array of data in json format
 *
 * @param array $data
 * @return Response
 */
function json(array $data): Response {
    return Response::json($data);
}

/**
 * Redirect to an `$uri`
 *
 * @param string $uri
 * @return Response
 */
function redirect(string $uri): Response {
    return Response::redirect($uri);
}

/**
 * Redirect to previous session uri
 *
 * @return Response
 */
function back(): Response {
    return redirect(session()->get("_previous", "/"));
}

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

/**
 * Access to the request
 *
 * @return Request
 */
function request(): Request {
    return app()->request;
}
