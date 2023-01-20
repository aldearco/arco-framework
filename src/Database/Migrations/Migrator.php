<?php

namespace Arco\Database\Migrations;

use Closure;
use Arco\Database\DB;
use Arco\Database\Archer\TableCrafter;
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
            $this->log("\n<fg=white;bg=white> INFO </> Nothing to migrate.");
            return;
        }

        foreach (array_slice($migrations, count($migrated)) as $file) {
            $migration = require $file;
            $migration->up();
            $name = basename($file);
            $this->driver->statement("INSERT INTO migrations (name) VALUES (?)", [$name]);
            $this->log("\n<question> MIGRATED </question> <fg=#a2c181;options=bold>$name</>");
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
            $this->log("\n<question> ROLLBACK </question> <fg=#a2c181;options=bold>$name</>");
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


        if (preg_match("/create_.*_table/", $migrationName)) {
            $template = file_get_contents("$this->templatesDirectory/migrations/create.php");
            $table = preg_replace_callback("/create_(.*)_table/", fn ($match) => $match[1], $migrationName);
            $template = str_replace('$TABLE', $table, $template);
        } elseif (preg_match("/.*(from|to)_(.*)_table/", $migrationName)) {
            $template = file_get_contents("$this->templatesDirectory/migrations/alter.php");
            $table = preg_replace_callback("/.*(from|to)_(.*)_table/", fn ($match) => $match[2], $migrationName);
            $template = str_replace('$TABLE', $table, $template);
        } else {
            $template = file_get_contents("$this->templatesDirectory/migrations/create.php");
            $template = str_replace('$TABLE', 'table', $template);
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

        $this->log("\n<question> SUCCESS </question> Migration created: <fg=#a2c181;options=bold>$fileName</>");

        return $fileName;
    }

    /**
     * Create a Table in DB using Migrations
     *
     * @param string $table
     * @param Closure $crafter
     * @return void
     */
    public static function create(string $table, Closure $crafter) {
        $builder = new TableCrafter($table);
        $crafter($builder);
        DB::statement($builder->create());
    }

    /**
     * Alter a Table in DB using Migrations
     *
     * @param string $table
     * @param Closure $crafter
     * @return void
     */
    public static function alter(string $table, Closure $crafter) {
        $builder = new TableCrafter($table);
        $crafter($builder);
        DB::statement($builder->alter());
    }

    /**
     * Drop a Table if exists in DB using Migrations
     *
     * @param string $table
     * @return void
     */
    public static function dropIfExists(string $table) {
        DB::statement(
            (new TableCrafter($table))
                ->dropIfExists()
        );
    }
}
