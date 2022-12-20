<?php

namespace Arco\Providers;

use Arco\Database\Drivers\DatabaseDriver;
use Arco\Database\Drivers\PDODriver;

class DatabaseDriverServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config("database.connection", "mysql")) {
            "mysql", "pgslq" => singleton(DatabaseDriver::class, PDODriver::class)
        };
    }
}
