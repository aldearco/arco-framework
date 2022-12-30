<?php

namespace App\Http\Middlewares;

use Arco\Security\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware {
    /**
     * Array with the URIs you want to exclude from CSRF Validation
     *
     * @var array
     */
    protected array $exceptions = [
        //
    ];
}