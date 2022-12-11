<?php

namespace Arco\Http;

use Closure;

interface Middleware {
    /**
     * Middleware Handle
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response;
}
