<?php

namespace Arco\Providers;

use Arco\Auth\Authenticators\Authenticator;
use Arco\Auth\Authenticators\SessionAuthenticator;

class AuthenticatorServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config("auth.method", "session")) {
            "session" => singleton(Authenticator::class, SessionAuthenticator::class)
        };
    }
}
