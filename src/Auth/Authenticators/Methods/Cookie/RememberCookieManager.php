<?php

namespace Arco\Auth\Authenticators\Methods\Cookie;

use Arco\Crypto\Cipher;
use Arco\Crypto\Bin2hex;
use Arco\Auth\Authenticatable;
use Arco\Helpers\Arrows\Cookie;

trait RememberCookieManager {
    protected function generateRememberToken(Authenticatable $authenticatable): string {
        if (is_null($authenticatable->getRememberToken())) {
            $rememberToken = Bin2hex::random(16);
            $authenticatable->setRememberToken($rememberToken)->update();
        } else {
            $rememberToken = $authenticatable->getRememberToken();
        }

        return $rememberToken;
    }

    public function setRememberCookie(Authenticatable $authenticatable) {
        $model = get_class($authenticatable);

        $rememberToken = $this->generateRememberToken($authenticatable);

        $cookie = Cipher::encrypt($model) . '.' . Cipher::encrypt($rememberToken);

        Cookie::set('remember_cookie', $cookie, time() + (60 * 60 * 24 * 365), sameSite: config('session.same_site', 'lax'));
    }

    public function cleanRememberToken(Authenticatable $authenticatable) {
        $authenticatable->setRememberToken(null)->update();
    }

    public function destroyRememberCookie() {
        if (Cookie::exists('remember_cookie')) {
            Cookie::delete('remember_cookie');
        }
    }
}
