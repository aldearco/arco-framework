<?php

namespace App\Http\Middlewares;

use Closure;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Http\Middleware;
use Arco\Auth\Access\NotAllowedResponse;

class GuestMiddleware implements Middleware {
    use NotAllowedResponse;

    /**
     * Handles an HTTP request and returns a response.
     *
     * @param Request $request The HTTP request being handled.
     * @param Closure $next The next middleware function in the chain.
     * @return Response The HTTP response.
     */
    public function handle(Request $request, Closure $next): Response {
        if (!isGuest()) {
            return redirect(route('home'));
        }

        return $next($request);
    }
}
