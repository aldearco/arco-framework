<?php

namespace Arco\Database;

use Arco\Database\Drivers\DatabaseDriver;

class DB {
    public static function statement(string $query, array $bind = []) {
        return app(DatabaseDriver::class)->statement($query, $bind);
    }
}
