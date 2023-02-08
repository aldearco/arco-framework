<?php

namespace Arco\Helpers\Arrows;

class Cookie {
    public static function set(string $name, $value, $expire = 0, $path = '/', $domain = "", $secure = false, $httpOnly = true, $sameSite = 'None') {
        setcookie($name, $value, $expire, "$path", $domain, $secure, $httpOnly);

        setcookie($name, $value, [
            'expires' => $expire,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $sameSite,
        ]);
    }

    public static function get($name, $default = null): string|null {
        if (isset($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }

        return $default;
    }

    public static function exists(string $name): bool {
        return isset($_COOKIE[$name]);
    }

    public static function delete($name, $path = '/', $domain = "") {
        setcookie($name, '', time() - 3600, $path, $domain);
    }
}
