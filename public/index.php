<?php

use Arco\App;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Routing\Route;
use Arco\Http\Middleware;

require_once "../vendor/autoload.php";

$app = App::bootstrap();

$app->router->get('/test/{param}', function (Request $request) {
    return Response::json($request->routeParameters());
});

$app->router->post('/test', function (Request $request) {
    return Response::text("POST OK");
});

$app->router->get('/redirect', function (Request $request) {
    return Response::redirect("/test");
});

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if ( $request->headers('Authorization') != 'test') {
            return Response::json(["message" => "Not authenticated"])->setStatus(401);
        }

        return $next($request);
    }
}

Route::get("/middlewares", fn (Request $request) => Response::json(["message" => "ok"]))
->setMiddlewares([AuthMiddleware::class]);

$app->run();
