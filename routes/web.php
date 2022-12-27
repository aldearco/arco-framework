<?php

use Arco\Auth\Auth;
use Arco\Routing\Route;
use App\Controllers\HomeController;

Auth::routes();

Route::get("/", fn () => redirect("/home"));
Route::get("/home", [HomeController::class, "show"]);
