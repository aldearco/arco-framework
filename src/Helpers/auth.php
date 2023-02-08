<?php

use Arco\Auth\Auth;
use Arco\Auth\Authenticatable;

if (!function_exists('auth')) {
    /**
     * If any model is authenticated return this model
     *
     * @return Authenticatable|null
     */
    function auth(): ?Authenticatable {
        return Auth::user();
    }
}

if (!function_exists('isGuest')) {
    /**
     * Check if is guest user
     *
     * @return boolean
     */
    function isGuest(): bool {
        return Auth::isGuest();
    }
}
