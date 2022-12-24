<?php

namespace Arco\Database\Migrations;

use Arco\Database\Drivers\DatabaseDriver;
use Symfony\Component\Console\Output\ConsoleOutput;

class Migrator {
    private ConsoleOutput $output;

    public function __construct(
        private string $migrationsDirectory,
        private string $templatesDirectory,
        private DatabaseDriver $driver,
        private bool $logProgress = true,
    ) {
        $this->migrationsDirectory = $migrationsDirectory;
        $this->templatesDirectory = $templatesDirectory;
        $this->driver = $driver;
        $this->output = new ConsoleOutput();
    }

    /**
     * Show log in the CLI
     *
     * @param string $message
     * @return void
     */
    private function log(string $message) {
        if ($this->logProgress) {
            $this->output->writeln($message);
        }
    }

    /**
     * Create migrations table if not exists
     *
     * @return void
     */
    private function createMigrationsTableIfNotExists() {
        $this->driver->statement(
            "CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(256))"
        );
    }

    /**
     * Execute all migration files in the migrations directory
     *
     * @return void
     */
    public function migrate() {
        $this->createMigrationsTableIfNotExists();
        $migrated = $this->driver->statement("SELECT * FROM migrations");
        $migrations = glob("$this->migrationsDirectory/*.php");

        if (count($migrated) >= count($migrations)) {
            $this->log("<comment>Nothing to migrate</comment>");
            return;
        }

        foreach (array_slice($migrations, count($migrated)) as $file) {
            $migration = require $file;
            $migration->up();
            $name = basename($file);
            $this->driver->statement("INSERT INTO migrations (name) VALUES (?)", [$name]);
            $this->log("<info>Migrated => $name</info>");
        }
    }

    /**
     * Rollback migrations
     *
     * @param integer|null $steps
     * @return void
     */
    public function rollback(?int $steps = null) {
        $this->createMigrationsTableIfNotExists();
        $migrated = $this->driver->statement("SELECT * FROM migrations");

        $pending = count($migrated);

        if ($pending == 0) {
            $this->log("<comment>Nothing to rollback</comment>");
            return;
        }

        if (is_null($steps)) {
            $steps = $pending;
        }

        $migrations = $migrations = array_slice(array_reverse(glob("$this->migrationsDirectory/*.php")), -$pending);

        foreach ($migrations as $file) {
            $migration = require $file;
            $migration->down();
            $name = basename($file);
            $this->driver->statement("DELETE FROM migrations WHERE name = ?", [$name]);
            $this->log("<info>Rollback => $name</info>");
            if (--$steps == 0) {
                break;
            }
        }
    }

    /**
     * Create a migration file in the migrations directory
     *
     * @param string $migrationName
     * @return void
     */
    public function make(string $migrationName) {
        $migrationName = snake_case($migrationName);

        $template = file_get_contents("$this->templatesDirectory/migration.php");

        if (preg_match("/create_.*_table/", $migrationName)) {
            $table = preg_replace_callback("/create_(.*)_table/", fn ($match) => $match[1], $migrationName);
            $template = str_replace('$UP', "CREATE TABLE $table (id INT AUTO_INCREMENT PRIMARY KEY)", $template);
            $template = str_replace('$DOWN', "DROP TABLE $table", $template);
        } elseif (preg_match("/.*(from|to)_(.*)_table/", $migrationName)) {
            $table = preg_replace_callback("/.*(from|to)_(.*)_table/", fn ($match) => $match[2], $migrationName);
            $template = preg_replace('/\$UP|\$DOWN/', "ALTER TABLE $table", $template);
        } else {
            $template = preg_replace_callback("/DB::statement.*/", fn ($match) => "// {$match[0]}", $template);
        }

        $date = date("Y_m_d");
        $id = 0;

        foreach (glob("$this->migrationsDirectory/*.php") as $file) {
            if (str_starts_with(basename($file), $date)) {
                $id++;
            }
        }

        $fileName = sprintf("%s_%06d_%s.php", $date, $id, $migrationName);

        file_put_contents("$this->migrationsDirectory/$fileName", $template);

        $this->log("<info>Migration created => $fileName</info>");

        return $fileName;
    }
}
