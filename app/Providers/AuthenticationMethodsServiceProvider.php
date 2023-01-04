<?php

namespace App\Providers;

use Arco\Auth\Authenticators\Methods\Cookie\RememberCookieAuthentication;
use Arco\Providers\ServiceProvider;

class AuthenticationMethodsServiceProvider implements ServiceProvider {
    public function registerServices() {
        RememberCookieAuthentication::handle();
        // Add your implementations here
    }
}
