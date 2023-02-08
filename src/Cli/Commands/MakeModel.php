<?php

namespace Arco\Cli\Commands;

use Arco\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeModel extends Command {
    protected static $defaultName = "make:model";

    protected static $defaultDescription = "Create a new model file";

    protected function configure() {
        $this
            ->addArgument("name", InputArgument::REQUIRED, "Migration name");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $name = $input->getArgument("name");
        $template = file_get_contents(resourcesDirectory()."/templates/model.php");
        $template = str_replace("ModelName", $name, $template);

        if (file_exists(App::$root . "/app/Models/$name.php")) {
            $output->writeln("\n<error> ERROR </error> Model already exists: <fg=#a2c181;options=bold>$name.php</>");
            return Command::FAILURE;
        }

        file_put_contents(App::$root . "/app/Models/$name.php", $template);
        $output->writeln("\n<question> SUCCESS </question> Model created: <fg=#a2c181;options=bold>$name.php</>");

        return Command::SUCCESS;
    }
}
