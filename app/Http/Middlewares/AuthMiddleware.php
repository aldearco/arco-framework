<?php

namespace App\Http\Middlewares;

use Closure;
use Arco\Auth\Auth;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Http\Middleware;
use Arco\Auth\Access\NotAllowedResponse;

class AuthMiddleware implements Middleware {
    use NotAllowedResponse;

    /**
     * Handles an HTTP request and returns a response.
     *
     * @param Request $request The HTTP request being handled.
     * @param Closure $next The next middleware function in the chain.
     * @return Response The HTTP response.
     */
    public function handle(Request $request, Closure $next): Response {
        if (Auth::isGuest()) {
            return redirect("/login");
        }

        return $next($request);
    }
}