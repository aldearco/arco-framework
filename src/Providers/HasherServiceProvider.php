<?php

namespace Arco\Providers;

use Arco\Crypto\Bcrypt;
use Arco\Crypto\Hasher;

class HasherServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config("hashing.hasher", "bcrypt")) {
            "bcrypt" => singleton(Hasher::class, Bcrypt::class)
        };
    }
}
