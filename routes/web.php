<?php

use Arco\Auth\Auth;
use Arco\Routing\Route;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get("/", fn () => redirect("/home"))->name('home');
Route::get("/home", [HomeController::class, "show"]);
Route::get("/sql", [HomeController::class, "sql"]);
