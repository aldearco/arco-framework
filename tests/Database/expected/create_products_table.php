<?php

use Arco\Database\Archer\TableCrafter;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Migrations\Migration;

return new class () implements Migration {
    public function up() {
        Migrator::create('products', function (TableCrafter $table) {
            $table->id();
            $table->timestamps();
        });
    }

    public function down() {
        Migrator::dropIfExists('products');
    }
};
