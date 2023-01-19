<?php

use Arco\Auth\Auth;
use Arco\Routing\Route;
use App\Http\Controllers\HomeController;
use App\Http\Middlewares\AuthMiddleware;
use Arco\Http\Response;

Auth::routes();

Route::get("/", fn () => redirect("/home"))->name('home');
Route::get("/home", [HomeController::class, "show"]);
Route::get("/sql", [HomeController::class, "sql"]);

Route::get("/test/{param}/folder/{second}", fn (string $param, string $second) => json([]))->name("test.route")->setMiddlewares([AuthMiddleware::class]);

Route::get("/public", function () {

    $uri = route("test.route", ["param" => 'hola', "second" => 'segundo']);

    return Response::text($uri);
});

Route::quiver('homes', HomeController::class);
