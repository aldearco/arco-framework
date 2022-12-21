<?php

use App\Models\User;
use Arco\Http\Request;
use Arco\Crypto\Hasher;
use Arco\Http\Response;
use Arco\Routing\Route;

Route::get("/", fn (Request $request) => Response::text(auth()->name));
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

    User::create($data);

    $user = User::firstWhere("email", $data["email"]);

    $user->login();

    return redirect("/");
});