<?php

use App\Models\User;
use Arco\Http\Request;
use Arco\Crypto\Hasher;
use Arco\Http\Response;
use Arco\Routing\Route;

Route::get("/", function (Request $request) {
    if (isGuest()) {
        return Response::text("Guest");
    }
    return Response::text(auth()->id);
});

Route::get("/form", fn (Request $request) => Response::view("form"));

Route::get("/register", fn (Request $request) => Response::view("auth/register"));

Route::post("/register", function (Request $request) {
    $data = $request->validate([
        "email" => ["required", "email"],
        "name" => "required",
        "password" => "required",
        "confirm_password" => "required",
    ]);

    if($data["password"] !== $data["confirm_password"]) {
        return back()->withErrors([
            "confirm_password" => [
                "confirm" => "Passwords do not match"]
            ]
        );
    }

    $data["password"] = app(Hasher::class)->hash($data["password"]);

    User::create($data)->login();

    return redirect("/");
});

Route::get("/login", fn (Request $request) => view("auth/login"));

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

Route::get("/logout", function (Request $request) {
    auth()->logout();
    return redirect("/");
});

