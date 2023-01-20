<?php

namespace Arco\Cli\Commands;

use Arco\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMiddleware extends Command {
    protected static $defaultName = "make:middleware";

    protected static $defaultDescription = "Create a new middleware file";

    protected function configure() {
        $this->addArgument("name", InputArgument::REQUIRED, "Middleware name");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $name = $input->getArgument("name");
        $template = file_get_contents(resourcesDirectory()."/templates/middleware.php");
        $template = str_replace("MiddlewareName", $name, $template);

        if (file_exists(App::$root . "/app/Http/Middlewares/$name.php")) {
            $output->writeln("\n<error> ERROR </error> Middleware already exists: <fg=#a2c181;options=bold>$name.php</>");
            return Command::FAILURE;
        }

        file_put_contents(App::$root . "/app/Http/Middlewares/$name.php", $template);
        $output->writeln("\n<question> SUCCESS </question> Controller created: <fg=#a2c181;options=bold>$name.php</>");

        return Command::SUCCESS;
    }
}
