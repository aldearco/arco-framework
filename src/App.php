<?php

namespace Arco;

use Arco\Container\Container;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Server\Server;
use Arco\Routing\Router;
use Arco\Server\PhpNativeServer;
use Arco\Http\HttpNotFoundException;

class App {
    public Router $router;

    public Request $request;

    public Server $server;

    public static function bootstrap() {
        $app = Container::singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();

        return $app;
    }

    public function run() {
        try {
            $route = $this->router->resolve($this->request);
            $this->request->setRoute($route);
            $action = $route->action();
            $response = $action($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $response = Response::text("Not found")->setStatus(404);
            $this->server->sendResponse($response);
        }
    }
}
