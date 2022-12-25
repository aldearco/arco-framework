<?php

namespace Arco\Tests\Database;

use PDOException;
use Arco\Database\Archer\Model;
use Arco\Database\Drivers\PDODriver;
use Arco\Database\Drivers\DatabaseDriver;

trait RefreshDatabase {
    protected function setUp(): void {
        if (is_null($this->driver)) {
            $this->driver = singleton(DatabaseDriver::class, PDODriver::class);
            Model::setDatabaseDriver($this->driver);
            try {
                $this->driver->connect("mysql", "localhost", 3306, "arco_tests", "root", "");
            } catch (PDOException $e) {
                $this->markTestSkipped("Can't connect to test database: {$e->getMessage()}");
            }
        }
    }

    protected function tearDown(): void {
        $this->driver->statement("DROP DATABASE IF EXISTS arco_tests");
        $this->driver->statement("CREATE DATABASE arco_tests");
    }
}
