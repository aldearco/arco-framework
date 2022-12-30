<?php

namespace Arco\Cli;

use Dotenv\Dotenv;
use Arco\App;
use Arco\Cli\Commands\MakeController;
use Arco\Cli\Commands\MakeMiddleware;
use Arco\Cli\Commands\MakeMigration;
use Arco\Cli\Commands\MakeModel;
use Arco\Cli\Commands\MakePolicy;
use Arco\Cli\Commands\Migrate;
use Arco\Cli\Commands\MigrateRollback;
use Arco\Cli\Commands\Serve;
use Arco\Cli\Commands\StorageLink;
use Arco\Config\Config;
use Arco\Database\Migrations\Migrator;
use Arco\Database\Drivers\DatabaseDriver;
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

        return new self();
    }

    public function run() {
        $cli = new Application("Arco");

        $cli->addCommands([
            new MakeMigration(),
            new Migrate(),
            new MigrateRollback(),
            new StorageLink(),
            new MakeController(),
            new MakeModel(),
            new Serve(),
            new MakePolicy(),
            new MakeMiddleware(),
        ]);

        $cli->run();
    }
}
