<?php

namespace Arco\Auth\Authenticators;

use Arco\Auth\Authenticatable;

interface Authenticator {
    public function login(Authenticatable $authenticatable);
    public function logout(Authenticatable $authenticatable);
    public function isAuthenticated(Authenticatable $authenticatable): bool;
    public function resolve(): ?Authenticatable;
}
