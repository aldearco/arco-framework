<?php

use Arco\Database\Archer\SQLCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        Migrator::alter('users', function (SQLCrafter $table) {
            $table->string("phone");
        });
    }

    public function down() {
        Migrator::alter('users', function (SQLCrafter $table) {
            $table->dropColumn("phone");
        });
    }
};