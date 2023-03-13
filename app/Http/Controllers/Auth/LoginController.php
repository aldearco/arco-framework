<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Arco\Http\Request;
use Arco\Crypto\Hasher;
use App\Http\Controllers\Controller;
use Arco\Auth\Authenticators\Methods\Cookie\RememberCookieManager;

class LoginController extends Controller {
    use RememberCookieManager;

    public function create() {
        return view("auth/login");
    }

    public function store(Request $request, Hasher $hasher) {
        $data = $request->validate([
            "email" => ["required", "email"],
            "password" => "required",
        ]);

        $user = User::firstWhere("email", $data["email"]);

        if (is_null($user) || !$hasher->verify($data["password"], $user->password)) {
            return back()->withErrors([
                "email" => ["email" => "Credentials do not match"]
            ]);
        }

        if ($request->has('remember')) {
            $this->setRememberCookie($user);
        }

        $user->login();

        return redirect("/");
    }

    public function destroy() {
        if (!isGuest()) {
            // $this->cleanRememberToken(auth()); // You can use this method to clean the user remember_token field from DB
            $this->destroyRememberCookie();
            auth()->logout();
        }
        return redirect("/");
    }
}
