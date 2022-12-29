<?php

namespace Arco\Helpers\Arrows;

class Cookie {
    public static function set(string $name, $value, $expire = 0, $path = '/', $domain = null, $secure = false, $httpOnly = true) {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
    }

    public static function get($name, $default = null): string|null {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }

        return $default;
    }

    public static function delete($name, $path = '/', $domain = null) {
        setcookie($name, '', time() - 3600, $path, $domain);
    }
}
