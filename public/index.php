<?php

use Arco\Http\Request;
use Arco\Routing\Router;
use Arco\Server\PhpNativeServer;
use Arco\Http\HttpNotFoundException;
use Arco\Http\Response;

require_once "../vendor/autoload.php";

$router = new Router();

$router->get('/test', function (Request $request) {
    $response = new Response();
    $response->setHeader('Content-Type', 'application/json');
    $response->setContent(json_encode(["message" => "GET OK"]));
    return $response;
});

$router->post('/test', function (Request $request) {
    return "POST OK";
});

$server = new PhpNativeServer();
try {
    $request = new Request($server);
    $route = $router->resolve($request);
    $action = $route->action();
    $response = $action($request);
    $server->sendResponse($response);
} catch (HttpNotFoundException $e) {
    $response = new Response();
    $response->setStatus(404);
    $response->setContent("No found");
    $server->sendResponse($response);
}
