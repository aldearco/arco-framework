<?php

use Arco\Http\Request;
use Arco\Routing\Router;
use Arco\Server\PhpNativeServer;
use Arco\Http\HttpNotFoundException;
use Arco\Http\Response;

require_once "../vendor/autoload.php";

$router = new Router();

$router->get('/test', function (Request $request) {
    return Response::text("GET OK");
});

$router->post('/test', function (Request $request) {
    return Response::text("POST OK");
});

$router->get('/redirect', function (Request $request) {
    return Response::redirect("/test");
});

$server = new PhpNativeServer();
try {
    $request = new Request($server);
    $route = $router->resolve($request);
    $action = $route->action();
    $response = $action($request);
    $server->sendResponse($response);
} catch (HttpNotFoundException $e) {
    $response = Response::text("Not found")->setStatus(404);
    $server->sendResponse($response);
}
