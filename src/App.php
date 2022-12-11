<?php

namespace Arco;

use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Server\Server;
use Arco\Routing\Router;
use Arco\Server\PhpNativeServer;
use Arco\Http\HttpNotFoundException;
use Arco\View\ArrowVulcan;
use Arco\View\View;

class App {
    public Router $router;

    public Request $request;

    public Server $server;

    public View $viewEngine;

    public static function bootstrap() {
        $app = singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();
        $app->viewEngine = new ArrowVulcan(__DIR__."/../views");

        return $app;
    }

    public function run() {
        try {
            $response = $this->router->resolve($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $response = Response::text("Not found")->setStatus(404);
            $this->server->sendResponse($response);
        }
    }
}
