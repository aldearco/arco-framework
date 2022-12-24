<?php

namespace Arco\Cli;

use Arco\App;
use Arco\Cli\Commands\MakeMigration;
use Arco\Cli\Commands\Migrate;
use Arco\Cli\Commands\MigrateRollback;
use Arco\Cli\Commands\StorageLink;
use Dotenv\Dotenv;
use Arco\Config\Config;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Drivers\DatabaseDriver;
use Arco\Storage\Storage;
use Symfony\Component\Console\Application;

class Cli {
    public static function bootstrap(string $root): self {
        App::$root = $root;
        Dotenv::createImmutable($root)->load();
        Config::load($root . "/config");

        foreach (config("providers.cli") as $provider) {
            (new $provider())->registerServices();
        }

        app(DatabaseDriver::class)->connect(
            config("database.connection"),
            config("database.host"),
            config("database.port"),
            config("database.database"),
            config("database.username"),
            config("database.password"),
        );

        singleton(
            Migrator::class,
            fn () => new Migrator(
                "$root/database/migrations",
                resourcesDirectory() . "/templates",
                app(DatabaseDriver::class)
            )
        );

        singleton(Storage::class);

        return new self();
    }

    public function run() {
        $cli = new Application("Arco");

        $cli->addCommands([
            new MakeMigration(),
            new Migrate(),
            new MigrateRollback(),
            new StorageLink(),
        ]);

        $cli->run();
    }
}
