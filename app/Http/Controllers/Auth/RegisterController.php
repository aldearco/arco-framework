<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Arco\Http\Request;
use Arco\Crypto\Hasher;
use App\Http\Controllers\Controller;

class RegisterController extends Controller {
    public function create() {
        return view("auth/register");
    }

    public function store(Request $request, Hasher $hasher) {
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
    
        $data["password"] = $hasher->hash($data["password"]);
    
        $user = User::create($data);

        $user->login();
    
        return redirect("/");
    }
}
