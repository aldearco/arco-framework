<?php

use Arco\Auth\Auth;
use App\Models\User;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Routing\Route;

Auth::routes();

Route::get("/", function () {
    if (isGuest()) {
        return Response::text("Guest");
    }
    return Response::text(auth()->name);
});

Route::get("/form", fn () => Response::view("form"));

Route::get("/user/{user}", fn (User $user) => json($user->toArray()));
Route::get("/route/{param}", fn (int $param) => json(["param" => $param]));

Route::get("/note", function (Request $request) {
    if (isGuest()) {
        return Response::text("You need to be logged in");
    }
    $notes = User::find(auth()->id)->notes();
    return view("test", [
        "notes" => $notes
    ]);
});

Route::get("/picture", fn () => Response::view("picture"));

Route::post('/picture', function (Request $request) {
    $url = $request->file('picture')->store();
    return Response::text($url);
});
