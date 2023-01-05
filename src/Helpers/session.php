<?php

use Arco\Session\Session;

if (!function_exists('session')) {
    /**
     * Access to the client user's session
     *
     * @return Session
     */
    function session(): Session {
        return app()->session;
    }
}

if (!function_exists('error')) {
    /**
     * Return error stored if exist
     *
     * @param string $field
     */
    function error(string $field) {
        $errors = session()->get("_errors", [])[$field] ?? [];

        $keys = array_keys($errors);

        if (count($keys) > 0) {
            return $errors[$keys[0]];
        }

        return null;
    }
}

if (!function_exists('old')) {
    /**
     * Return the past request data values if exist
     *
     * @param string $field
     */
    function old(string $field) {
        return session()->get("_old", [])[$field] ?? null;
    }
}
