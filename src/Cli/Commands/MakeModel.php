<?php

namespace Arco\Cli\Commands;

use Arco\App;
use Arco\Database\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModel extends Command {
    protected static $defaultName = "make:model";

    protected static $defaultDescription = "Create a new model file";

    protected function configure() {
        $this
            ->addArgument("name", InputArgument::REQUIRED, "Migration name")
            ->addOption("migration", "m", InputOption::VALUE_OPTIONAL, "Create migration file for this model", false)
            ->addOption("controller", "c", InputOption::VALUE_OPTIONAL, "Create controller file for this model", false);
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $modelName = $input->getArgument("name");
        $template = file_get_contents(resourcesDirectory()."/templates/model.php");
        $template = str_replace("ModelName", $modelName, $template);
        file_put_contents(App::$root . "/app/Models/$modelName.php", $template);
        $output->writeln("<info>Model created => $modelName.php</info>");

        $migration = $input->getOption("migration");
        if ($migration !== false) {
            app(Migrator::class)->make("create_{$modelName}s_table");
        }

        $controller = $input->getOption("controller");
        if ($controller !== false) {
            $controllerName = "{$modelName}Controller";
            $template = file_get_contents(resourcesDirectory()."/templates/controller.php");
            $template = str_replace("ControllerName", $controllerName, $template);
            file_put_contents(App::$root . "/app/Controllers/$controllerName.php", $template);
            $output->writeln("<info>Controller created => $controllerName.php</info>");
        }

        return Command::SUCCESS;
    }
}
