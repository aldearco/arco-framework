<?php

namespace App\Middlewares;

use Closure;
use Arco\Auth\Auth;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Http\Middleware;

class AuthMiddleware implements Middleware {
    public function handle(Request $request, Closure $next): Response {
        if (Auth::isGuest()) {
            return redirect("/login");
        }

        return $next($request);
    }
}
