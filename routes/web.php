<?php

use Arco\Auth\Auth;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Routing\Route;
use App\Controllers\HomeController;
use App\Models\User;

Auth::routes();

Route::get("/", fn () => redirect("/home"));
Route::get("/home", [HomeController::class, "show"]);

Route::get("/csrf", fn () => view("csrf"));
Route::post("/csrf", fn (Request $request) => Response::text("todo ok"));