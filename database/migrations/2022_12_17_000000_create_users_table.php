<?php

use Arco\Database\Archer\TableCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        Migrator::create('users', function (TableCrafter $table) {
            $table->id();
            $table->string('name');
            $table->string('email', uuid: true);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down() {
        Migrator::dropIfExists('users');
    }
};