<?php

use Arco\Database\DB;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        DB::statement('
            CREATE TABLE notes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(256),
                content VARCHAR(256),
                created_at DATETIME,
                updated_at DATETIME NULL
            )
        ');
    }

    public function down() {
        DB::statement('DROP TABLE notes');
    }
};