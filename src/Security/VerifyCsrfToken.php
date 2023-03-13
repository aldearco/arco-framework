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

    /**
     * Generate CSRF cookie.
     *
     * @return void
     */
    protected function generateCookie() {
        $sessionToken = session()->token() ?: session()->regenerateToken();

        $hasher = new Bcrypt();
        $cookieToken = $hasher->hash($sessionToken);
        Cookie::set('csrf_token', $cookieToken, 0, '/', sameSite: config('session.same_site', 'lax'));
    }

    /**
     * Verify cookie CSRF.
     *
     * @return boolean
     */
    protected function verifyCookie(): bool {
        $hasher = new Bcrypt();

        return $hasher->verify(
            session()->token(),
            Cookie::get('csrf_token')
        );
    }

    /**
     * Verify if token match with token stored in session.
     *
     * @param Request $request
     * @return boolean
     */
    protected function tokensMatch(Request $request): bool {
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

    /**
     * Get CSRF token from request.
     *
     * @param Request $request
     * @return string|null
     */
    protected function getTokenFromRequest(Request $request): string|null {
        $token = $request->data('_token') ?: $request->headers('X-CSRF-TOKEN');

        if (is_null($token)) {
            return null;
        }

        return $token;
    }

    /**
     * Check if request method is not secured by CSRF.
     *
     * @param Request $request
     * @return boolean
     */
    protected function notSecuredMethod(Request $request): bool {
        return !in_array($request->method(), $this->protectedMethods);
    }

    /**
     * Check if current request route URI is in CSRF exceptions.
     *
     * @param Request $request
     * @return boolean
     */
    protected function inExceptions(Request $request): bool {
        return in_array($request->route()->uri(), $this->exceptions);
    }

    /**
     * Handle CSRF middleware.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response {
        if (
            $this->notSecuredMethod($request) ||
            $this->inExceptions($request) ||
            $this->tokensMatch($request) ||
            $this->verifyCookie()
        ) {
            if (!Cookie::exists('csrf_token')) {
                $this->generateCookie();
            }
            return $next($request);
        }

        $this->generateCookie();
        
        return Response::text("CSRF No Válido")->setStatus(403);
    }
}
