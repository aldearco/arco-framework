<?php

use Arco\Database\Archer\TableCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        Migrator::alter('users', function (TableCrafter $table) {
            $table->string("phone");
        });
    }

    public function down() {
        Migrator::alter('users', function (TableCrafter $table) {
            $table->dropColumn("phone");
        });
    }
};