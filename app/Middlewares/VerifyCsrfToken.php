<?php

namespace App\Middlewares;

use Arco\Security\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware{
    protected array $exceptions = [
        //
    ];
}