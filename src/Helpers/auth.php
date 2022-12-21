<?php

use Arco\Auth\Auth;
use Arco\Auth\Authenticatable;

function auth(): ?Authenticatable {
    return Auth::user();
}

function isGuest(): bool {
    return Auth::isGuest();
}
