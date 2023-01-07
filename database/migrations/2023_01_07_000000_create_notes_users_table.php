<?php

use Arco\Database\DB;
use Arco\Database\Migrations\Migration;

return new class() implements Migration {
    public function up() {
        DB::statement('
            CREATE TABLE notes_users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT,
                note_id INT,
                FOREIGN KEY (user_id) REFERENCES users(id),
                FOREIGN KEY (note_id) REFERENCES notes(id)
            )
        ');
    }

    public function down() {
        DB::statement('DROP TABLE notes_users');
    }
};