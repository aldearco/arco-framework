<?php

use Arco\App;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Routing\Route;
use Arco\Http\Middleware;
use Arco\Validation\Rule;
use Arco\Validation\Rules\Confirmed;
use Arco\Validation\Rules\Required;

require_once "../vendor/autoload.php";

$app = App::bootstrap();

$app->router->get('/test/{param}', function (Request $request) {
    return json($request->routeParameters());
});

$app->router->post('/test', function (Request $request) {
    return Response::text("POST OK");
});

$app->router->get('/redirect', function (Request $request) {
    return redirect("/test");
});

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if ( $request->headers('Authorization') != 'test') {
            return json(["message" => "Not authenticated"])->setStatus(401);
        }

        return $next($request);
    }
}

Route::get("/middlewares", fn (Request $request) => json(["message" => "ok"]))
->setMiddlewares([AuthMiddleware::class]);

Route::get("/html", fn (Request $request) => view("home", ["user" => var_dump(PHP_VERSION_ID)]));

Route::post("/validate", fn (Request $request) => json($request->validate([
    "test" => "required",
    "num" => "number",
    "email" => ["required_with:num", "email"]
], [
    "email" => [
        "email" => "Email es obligatorio premoh"
    ]
])));

Route::get("/session", function (Request $request) {
    session()->remove("test");
    return json([
        "id" => session()->id(),
        "test" =>session()->get("test", "por defecto")
    ]);
});

$app->run();
