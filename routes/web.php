<?php

use Arco\Auth\Auth;
use Arco\Routing\Route;
use App\Http\Controllers\HomeController;

Auth::routes();

Route::get("/", fn () => redirect(route('home')));
Route::get("/home", [HomeController::class, "show"])->name('home');

Route::get('/test', [HomeController::class, "testing"])->name('testing.show');
Route::post('/test', [HomeController::class, "testStorage"])->name('storage.store');

Route::get('/rules', [HomeController::class, "rules"])->name('rules.create');
Route::post('/rules', [HomeController::class, "storeRules"])->name('rules.store');