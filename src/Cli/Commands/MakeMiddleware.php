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
        file_put_contents(App::$root . "/app/Http/Middlewares/$name.php", $template);
        $output->writeln("<info>Middleware created => $name.php</info>");

        return Command::SUCCESS;
    }
}
