<?php

use Arco\Auth\Auth;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Routing\Route;
use App\Controllers\HomeController;

Auth::routes();

Route::get("/", fn () => redirect("/home"));
Route::get("/home", [HomeController::class, "show"]);

Route::get("/test", fn () => view("test"));
Route::post("/test", function (Request $request) {
    $data = $request->validate([
        "email" => ["required", "email",  "unique:users.fdsfsdg"]
    ]);

    return Response::json($data);
});