<?php

namespace Arco;

use Throwable;
use Arco\View\View;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Server\Server;
use Arco\Database\Model;
use Arco\Routing\Router;
use Arco\Http\HttpMethod;
use Arco\Session\Session;
use Arco\Validation\Rule;
use Arco\View\ArrowVulcan;
use Arco\Server\PhpNativeServer;
use Arco\Database\Drivers\PDODriver;
use Arco\Http\HttpNotFoundException;
use Arco\Database\Drivers\DatabaseDriver;
use Arco\Session\PhpNativeSessionStorage;
use Arco\Validation\Exceptions\ValidationException;

class App {
    public Router $router;

    public Request $request;

    public Server $server;

    public View $viewEngine;

    public Session $session;

    public DatabaseDriver $database;

    public static function bootstrap() {
        $app = singleton(self::class);
        $app->router = new Router();
        $app->server = new PhpNativeServer();
        $app->request = $app->server->getRequest();
        $app->viewEngine = new ArrowVulcan(__DIR__."/../views");
        $app->session = new Session(new PhpNativeSessionStorage());
        $app->database = singleton(DatabaseDriver::class, PDODriver::class);
        $app->database->connect("mysql", "localhost", 3306, "curso_framework", "root", "");
        Model::setDatabaseDriver($app->database);
        Rule::loadDefaultRules();

        return $app;
    }

    public function prepareNextRequest() {
        if ($this->request->method() == HttpMethod::GET) {
            $this->session->set("_previous", $this->request->uri());
        }
    }

    public function terminate(Response $response) {
        $this->prepareNextRequest();
        $this->server->sendResponse($response);
        $this->database->close();
        exit();
    }

    public function run() {
        try {
            $this->terminate($this->router->resolve($this->request));
        } catch (HttpNotFoundException $e) {
            $this->abort(Response::text("Not found")->setStatus(404));
        } catch (ValidationException $e) {
            $this->abort(back()->withErrors($e->errors(), 422));
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
        $this->terminate($response);
    }
}
