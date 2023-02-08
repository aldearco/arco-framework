<?php

use Arco\Database\Archer\TableCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class () implements Migration {
    public function up() {
        Migrator::alter('$TABLE', function (TableCrafter $table) {
            //
        });
    }

    public function down() {
        Migrator::alter('$TABLE', function (TableCrafter $table) {
            //
        });
    }
};
