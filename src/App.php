<?php

namespace Arco;

use Dotenv\Dotenv;
use Arco\Config\Config;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Server\Server;
use Arco\Database\Archer\Model;
use Arco\Routing\Router;
use Arco\Http\HttpMethod;
use Arco\Session\Session;
use Arco\Http\HttpNotFoundException;
use Arco\Database\Drivers\DatabaseDriver;
use Arco\Session\SessionStorage;
use Arco\Validation\Exceptions\ValidationException;
use Throwable;

class App {
    public static string $root;

    public Router $router;

    public Request $request;

    public Server $server;

    public Session $session;

    public DatabaseDriver $database;

    public static function bootstrap(string $root) {
        self::$root = $root;
        $app = singleton(self::class);
        return $app
            ->loadConfig()
            ->runServiceProviders("boot")
            ->setHttpHandlers()
            ->setUpDatabaseConnection()
            ->runServiceProviders("runtime");

        return $app;
    }

    protected function loadConfig(): self {
        Dotenv::createImmutable(self::$root)->load();
        Config::load(self::$root."/config");

        return $this;
    }

    protected function runServiceProviders(string $type): self {
        foreach (config("providers.$type", []) as $provider) {
            $provider = new $provider();
            $provider->registerServices();
        }
        return $this;
    }

    protected function setHttpHandlers(): self {
        $this->router = singleton(Router::class);
        $this->server = app(Server::class);
        $this->request = singleton(Request::class, fn () => $this->server->getRequest());
        $this->session = singleton(Session::class, fn () => new Session(app(SessionStorage::class)));

        return $this;
    }

    protected function setUpDatabaseConnection(): self {
        $this->database = app(DatabaseDriver::class);
        $this->database->connect(
            config("database.connection"),
            config("database.host"),
            config("database.port"),
            config("database.database"),
            config("database.username"),
            config("database.password"),
        );
        Model::setDatabaseDriver($this->database);

        return $this;
    }

    protected function prepareNextRequest() {
        if ($this->request->method() == HttpMethod::GET) {
            $this->session->set("_previous", $this->request->uri());
        }
    }

    protected function terminate(Response $response) {
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
            if (config('app.env', 'dev') === "dev") {
                $response = json([
                    "error" => $e::class,
                    "message" => $e->getMessage(),
                    "trace" => $e->getTrace()
                ]);
            } else {
                $response = Response::text("Internal Server Error");
            }
            $this->abort($response->setStatus(500));
        }
    }

    public function abort(Response $response) {
        $this->terminate($response);
    }
}
