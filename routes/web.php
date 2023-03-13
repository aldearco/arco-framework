<?php

use Arco\Auth\Auth;
use Arco\Routing\Route;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get("/", fn () => redirect(route('home')));
Route::get("/home", [HomeController::class, "show"])->name('home');