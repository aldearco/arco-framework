<?php

namespace Arco\Database;

use Arco\Database\Drivers\DatabaseDriver;

/**
 * Execute actions in the database using the app Database Container
 */
class DB {
    /**
     * Execute a statement using the Database Driver in the app container statically
     *
     * @param string $query
     * @param array $bind
     */
    public static function statement(string $query, array $bind = []) {
        return app(DatabaseDriver::class)->statement($query, $bind);
    }
}
