<?php

namespace Arco\Cli\Commands;

use Arco\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Serve extends Command {
    protected static $defaultName = "serve";

    protected static $defaultDescription = "Run PHP development server for this app";

    protected function configure() {
        $this
            ->addOption("host", null, InputOption::VALUE_OPTIONAL, "Host address", "127.0.0.1")
            ->addOption("port", null, InputOption::VALUE_OPTIONAL, "Port", "8080")
            ->addOption("public", null, InputOption::VALUE_OPTIONAL, "Public directory name in the project root", "public");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $host = $input->getOption("host");
        $port = $input->getOption("port");
        $publicDirectory = App::$root . "/" . $input->getOption("public");

        $output->writeln("<info>Starting PHP development server on $host:$port</info>");
        shell_exec("cd $publicDirectory/; php -S $host:$port");

        return Command::SUCCESS;
    }
}
