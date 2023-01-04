<?php

namespace Arco\Auth;

use Arco\Auth\Authenticators\Authenticator;
use Arco\Database\Archer\Model;

class Authenticatable extends Model {
    protected string $rememberTokenName = 'remember_token';

    public function id(): int|string {
        return $this->{$this->primaryKey};
    }

    public function getRememberTokenName() {
        return $this->rememberTokenName;
    }

    public function getRememberToken() {
        return $this->{$this->rememberTokenName};
    }

    public function setRememberToken(?string $token): static {
        $this->{$this->rememberTokenName} = $token;

        return $this;
    }

    public function login() {
        app(Authenticator::class)->login($this);
    }

    public function logout() {
        app(Authenticator::class)->logout($this);
    }

    public function isAuthenticated() {
        app(Authenticator::class)->isAuthenticated($this);
    }
}
