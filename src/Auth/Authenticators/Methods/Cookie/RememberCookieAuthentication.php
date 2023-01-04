<?php

namespace Arco\Auth\Authenticators\Methods\Cookie;

use Arco\Auth\Authenticatable;
use Arco\Crypto\Cipher;
use Arco\Helpers\Arrows\Cookie;
use Arco\Auth\Authenticators\Methods\AuthenticationMethod;

class RememberCookieAuthentication implements AuthenticationMethod {
    protected static function issetRememberCookie(): bool {
        return Cookie::exists('remember_cookie');
    }

    protected static function getRememberCookie(): string {
        return Cookie::get('remember_cookie');
    }

    protected static function getCookieParams(): array {
        $params = explode('.', self::getRememberCookie());

        return [
            Cipher::decrypt($params[0]),
            Cipher::decrypt($params[1])
        ];
    }

    protected static function isValidCookie() {
        $cookieParts = explode('.', self::getRememberCookie());

        if (count($cookieParts) !== 2) {
            return false;
        }

        $params = self::getCookieParams();
        [$model] = $params;

        return count($params) === 2 && is_subclass_of($model, Authenticatable::class);
    }

    protected static function cookieLogin() {
        [$model, $remembeToken] = self::getCookieParams();

        $authenticatable = $model::firstWhere(
            (new $model())->getRememberTokenName(),
            $remembeToken
        );

        if (is_null($authenticatable)) {
            Cookie::delete('remember_cookie');
        } else {
            $authenticatable->login();
        }
    }

    public static function handle() {
        if (self::issetRememberCookie() && self::isValidCookie() && isGuest()) {
            self::cookieLogin();
        }
    }
}
