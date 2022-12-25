<?php

use Arco\Auth\Auth;
use Arco\Http\Request;
use Arco\Routing\Route;
use App\Controllers\HomeController;
use App\Controllers\ContactController;

Auth::routes();

Route::get("/", fn () => redirect("/home"));
Route::get("/home", [HomeController::class, "show"]);

Route::get("/contacts", [ContactController::class, "index"]);
Route::get("/contacts/create", [ContactController::class, "create"]);
Route::post("/contacts", [ContactController::class, "store"]);
Route::get("/contacts/edit/{contact}", [ContactController::class, "edit"]);
Route::post("/contacts/edit/{contact}", [ContactController::class, "update"]);
Route::get("/contacts/delete/{contact}", [ContactController::class, "destroy"]);

Route::get("/put", fn () => view("put/put"));
Route::patch("/put", fn (Request $request) => json($request->data()));
Route::delete("/put", fn (Request $request) => json($request->data()));

