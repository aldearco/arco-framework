<?php

use Arco\Auth\Auth;
use Arco\Routing\Route;
use App\Http\Controllers\HomeController;
use App\Http\Middlewares\AuthMiddleware;

Auth::routes();

Route::get("/", fn () => redirect("/home"));
Route::get("/home", [HomeController::class, "show"]);

Route::get("/test", [HomeController::class, 'test']);
Route::post("/test", [HomeController::class, 'store_test']);