<?php

namespace Arco\Auth;

use Arco\Routing\Route;
use App\Http\Controllers\Auth\LoginController;
use Arco\Auth\Authenticators\Authenticator;
use App\Http\Controllers\Auth\RegisterController;

class Auth {
    /**
     * Return user object model if is authenticated
     *
     * @return Authenticatable|null
     */
    public static function user(): ?Authenticatable {
        return app(Authenticator::class)->resolve();
    }

    /**
     * Check if user is guest
     *
     * @return boolean
     */
    public static function isGuest(): bool {
        return is_null(self::user());
    }

    /**
     * All authentication routes
     *
     * @return void
     */
    public static function routes() {
        Route::get("/register", [RegisterController::class, "create"])->name('register');
        Route::post("/register", [RegisterController::class, "store"]);
        Route::get("/login", [LoginController::class, "create"])->name('login');
        Route::post("/login", [LoginController::class, "store"]);
        Route::get("/logout", [LoginController::class, "destroy"])->name('logout');
    }
}
