<?php

use Arco\Database\DB;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        DB::statement('
            CREATE TABLE notes (
                id INT AUTO_INCREMENT NOT NULL PRIMARY KEY,
                title VARCHAR(256),
                content VARCHAR(256),
                user_id INT,
                FOREIGN KEY (user_id) REFERENCES users(id)
            )
        ');
    }

    public function down() {
        DB::statement('DROP TABLE notes');
    }
};