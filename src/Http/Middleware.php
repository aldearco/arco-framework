<?php

namespace Arco\Http;

use Closure;

interface Middleware {
    public function handle(Request $request, Closure $next): Response;
}
