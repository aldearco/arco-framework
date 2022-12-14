<?php

namespace Arco;

use Throwable;
use Arco\View\View;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Server\Server;
use Arco\Routing\Router;
use Arco\Validation\Rule;
use Arco\View\ArrowVulcan;
use Arco\Server\PhpNativeServer;
use Arco\Http\HttpNotFoundException;
use Arco\Validation\Exceptions\ValidationException;

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
        Rule::loadDefaultRules();

        return $app;
    }

    public function run() {
        try {
            $response = $this->router->resolve($this->request);
            $this->server->sendResponse($response);
        } catch (HttpNotFoundException $e) {
            $this->abort(Response::text("Not found")->setStatus(404));
        } catch (ValidationException $e) {
            $this->abort(json($e->errors())->setStatus(422));
        } catch (Throwable $e) {
            $response = json([
                "error" => $e::class,
                "message" => $e->getMessage(),
                "trace" => $e->getTrace()
            ]);
            $this->abort($response->setStatus(500));
        }
    }

    public function abort(Response $response) {
        $this->server->sendResponse($response);
    }
}
