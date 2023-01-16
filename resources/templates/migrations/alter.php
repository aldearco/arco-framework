<?php

use Arco\Database\Archer\SQLCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        Migrator::alter('$TABLE', function (SQLCrafter $table) {
            //
        });
    }

    public function down() {
        Migrator::alter('$TABLE', function (SQLCrafter $table) {
            //
        });
    }
};