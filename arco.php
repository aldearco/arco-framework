<?php

use Arco\Database\Migrations\Migrator;

require_once "./vendor/autoload.php";

$migrator = new Migrator(
    __DIR__ . "/database/migrations",
    __DIR__ . "/templates",
);

if($argv[1] == "make:migration") {
    $migrator->make($argv[2]);
}