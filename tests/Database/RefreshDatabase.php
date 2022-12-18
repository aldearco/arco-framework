<?php

namespace Arco\Tests\Database;

use PDOException;
use Arco\Database\Model;
use Arco\Database\Drivers\PDODriver;

trait RefreshDatabase {
    protected function setUp(): void {
        if (is_null($this->driver)) {
            $this->driver = new PDODriver();
            Model::setDatabaseDriver($this->driver);
            try {
                $this->driver->connect("mysql", "localhost", 3306, "curso_framework_tests", "root", "");
            } catch (PDOException $e) {
                $this->markTestSkipped("Can't connect to test database: {$e->getMessage()}");
            }
        }
    }

    protected function tearDown(): void {
        $this->driver->statement("DROP DATABASE IF EXISTS curso_framework_tests");
        $this->driver->statement("CREATE DATABASE curso_framework_tests");
    }
}
