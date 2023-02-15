<?php

namespace Arco\Security;

use Closure;
use Arco\Http\Request;
use Arco\Http\Response;
use Arco\Crypto\Bcrypt;
use Arco\Http\HttpMethod;
use Arco\Http\Middleware;
use Arco\Helpers\Arrows\Cookie;

class VerifyCsrfToken implements Middleware {
    /**
     * Array with the URIs you want to exclude from CSRF Validation
     *
     * @var array
     */
    protected array $exceptions = [];

    private array $protectedMethods = [
        HttpMethod::POST,
        HttpMethod::PUT,
        HttpMethod::PATCH,
        HttpMethod::DELETE,
    ];

    protected function generateCookie() {
        $hasher = new Bcrypt();
        $token = $hasher->hash(session()->token());
        Cookie::set('csrf_token', $token, 0, '/', sameSite: config('session.same_site', 'lax'));
    }

    protected function verifyCookie(): bool {
        $hasher = new Bcrypt();

        return $hasher->verify(
            session()->token(),
            Cookie::get('csrf_token')
        );
    }

    protected function tokensMatch(Request $request) {
        $token = $this->getTokenFromRequest($request);

        if (Cookie::exists('csrf_token')) {
            return is_string($token) &&
                is_string(session()->token()) &&
                session()->token() === $request->data('_token') &&
                $this->verifyCookie();
        }

        return is_string($token) &&
            is_string(session()->token()) &&
            session()->token() === $request->data('_token');
    }

    protected function getTokenFromRequest(Request $request) {
        $token = $request->data('_token') ?: $request->headers('X-CSRF-TOKEN');

        if (is_null($token)) {
            $token = '';
        }

        return $token;
    }

    protected function notSecuredMethod(Request $request): bool {
        return !in_array($request->method(), $this->protectedMethods);
    }

    protected function inExceptions(Request $request) {
        return in_array($request->route()->uri(), $this->exceptions);
    }

    public function handle(Request $request, Closure $next): Response {
        if (
            $this->notSecuredMethod($request) ||
            $this->inExceptions($request)
        ) {
            if (
                !Cookie::exists('csrf_token') ||
                !$this->verifyCookie()
            ) {
                $this->generateCookie();
            }
            return $next($request);
        }

        if ($this->tokensMatch($request)) {
            session()->regenerateToken();
            $this->generateCookie();
            return $next($request);
        }

        return Response::text("CSRF No Válido")->setStatus(403);
    }
}
