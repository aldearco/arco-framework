<?php

namespace Arco\Tests\Database;

use PHPUnit\Framework\TestCase;
use Arco\Database\Archer\TableCrafter;

class TableCrafterTest extends TestCase {
    public function test_create_basic_table() {
        $table = new TableCrafter('users');
        $table->id();
        $table->string('name');
        $table->string('email', uuid: true);
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();

        $expected = "CREATE TABLE users (id INT AUTO_INCREMENT,name VARCHAR(255),email VARCHAR(255) UNIQUE,password VARCHAR(255),remember_token VARCHAR(100) NULL UNIQUE,created_at TIMESTAMP NULL,updated_at TIMESTAMP NULL,PRIMARY KEY (id))";
        $actual = $table->create();

        $this->assertEquals($expected, $actual);
    }

    public function test_alter_table_to_drop_columns() {
        $table = new TableCrafter('users');
        $table->dropColumn('phone');
        $table->dropColumn('email');

        $expected = "ALTER TABLE users DROP COLUMN phone,DROP COLUMN email";
        $actual = $table->alter();

        $this->assertEquals($expected, $actual);
    }

    public function test_alter_table_to_add_columns() {
        $table = new TableCrafter('users');
        $table->string('phone', 30, true, true);
        $table->string('email', 64);

        $expected = "ALTER TABLE users ADD COLUMN phone VARCHAR(30) NULL UNIQUE,ADD COLUMN email VARCHAR(64)";
        $actual = $table->alter();

        $this->assertEquals($expected, $actual);
    }

    public function test_drop_table_if_exists() {
        $table = new TableCrafter('test');

        $expected = "DROP TABLE IF EXISTS test";
        $actual = $table->dropIfExists();

        $this->assertEquals($expected, $actual);
    }

    public function test_create_table_with_all_available_methods() {
        $table = new TableCrafter('methods');
        $table->bigId();
        $table->string('varchar_test', 100, true);
        $table->integer('user_id');
        $table->bigInteger('big_integer_test');
        $table->decimal('decimal_test');
        $table->text('text_test');
        $table->date('date_test');
        $table->time('time_test');
        $table->timestamp('timestamp_test');
        $table->foreignKey('user_id', 'users', 'id');
        $table->column('custom_column', "VARCHAR(22) NULL UNIQUE");
        $table->rememberToken();
        $table->timestamps();

        $expected = "CREATE TABLE methods (id BIGINT AUTO_INCREMENT,varchar_test VARCHAR(100) NULL,user_id INT,big_integer_test BIGINT,decimal_test DECIMAL(8, 2),text_test TEXT,date_test DATE,time_test TIME,timestamp_test TIMESTAMP,custom_column VARCHAR(22) NULL UNIQUE,remember_token VARCHAR(100) NULL UNIQUE,created_at TIMESTAMP NULL,updated_at TIMESTAMP NULL,FOREIGN KEY (user_id) REFERENCES users(id),PRIMARY KEY (id))";
        $actual = $table->create();

        $this->assertEquals($expected, $actual);
    }
}
