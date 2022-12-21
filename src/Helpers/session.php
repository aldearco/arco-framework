<?php

use Arco\Session\Session;

/**
 * Access to the client user's session
 *
 * @return Session
 */
function session(): Session {
    return app()->session;
}

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

/**
 * Return the past request data values if exist
 *
 * @param string $field
 */
function old(string $field) {
    return session()->get("_old", [])[$field] ?? null;
}
