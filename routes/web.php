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

Route::get("/test", fn () => view("test"));
Route::post("/test", function (Request $request) {
    $data = $request->validate([
        "text" => ["required", "unique"]
    ]);

    Response::json($data);
});