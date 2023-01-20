<?php

use App\Models\User;
use Arco\Database\Archer\TableCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        Migrator::create('notes', function (TableCrafter $table) {
            $table->id();
            $table->timestamps();
            $table->foreignIdFor('users');
        });
    }

    public function down() {
        Migrator::dropIfExists('notes');
    }
};