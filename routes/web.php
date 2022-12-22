<?php

use App\Controllers\Auth\RegisterController;
use App\Models\User;
use Arco\Http\Request;
use Arco\Crypto\Hasher;
use Arco\Http\Response;
use Arco\Routing\Route;

Route::get("/", function () {
    if (isGuest()) {
        return Response::text("Guest");
    }
    return Response::text(auth()->name);
});

Route::get("/form", fn () => Response::view("form"));

Route::get("/user/{user}", fn (User $user) => json($user->toArray()));
Route::get("/route/{param}", fn (int $param) => json(["param" => $param]));

Route::get("/register", [RegisterController::class, "create"]);

Route::post("/register", [RegisterController::class, "store"]);

Route::get("/login", fn () => view("auth/login"));

Route::post("/login", function (Request $request) {
    $data = $request->validate([
        "email" => ["required", "email"],
        "password" => "required",
    ]);

    $user = User::firstWhere("email", $data["email"]);

    if (is_null($user) || !app(Hasher::class)->verify($data["password"], $user->password)) {
        return back()->withErrors([
            "email" => ["email" => "Credentials do not match"]
        ]);
    }

    $user->login();

    return redirect("/");
});

Route::get("/note", function (Request $request) {
    if (isGuest()) {
        return Response::text("You need to be logged in");
    }
    $notes = User::find(auth()->id)->notes();
    return view("test", [
        "notes" => $notes
    ]);
});

Route::get("/logout", function () {
    auth()->logout();
    return redirect("/");
});

