<?php

namespace Arco\Cli\Commands;

use Arco\Database\Migrations\Migrator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeMigration extends Command {
    protected static $defaultName = "make:migration";

    protected static $defaultDescription = "Create a new migration file";

    protected function configure() {
        $this->addArgument("name", InputArgument::REQUIRED, "migration name");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        app(Migrator::class)->make($input->getArgument("name"));
        return Command::SUCCESS;
    }
}
