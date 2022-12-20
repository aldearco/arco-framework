<?php

namespace Arco\Providers;

use Arco\Session\PhpNativeSessionStorage;
use Arco\Session\SessionStorage;

class SessionStorageServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config("session.storage", "native")) {
            "native" => singleton(SessionStorage::class, PhpNativeSessionStorage::class)
        };
    }
}
