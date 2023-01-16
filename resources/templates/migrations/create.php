<?php

use Arco\Database\Archer\SQLCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        Migrator::create('$TABLE', function (SQLCrafter $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down() {
        Migrator::dropIfExists('$TABLE');
    }
};