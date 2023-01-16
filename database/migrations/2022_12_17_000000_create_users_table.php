<?php

use Arco\Database\Archer\SQLCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        Migrator::create('users', function (SQLCrafter $table) {
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