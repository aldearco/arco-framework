<?php

namespace Arco\Cli\Commands;

use Arco\Storage\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StorageLink extends Command {
    protected static $defaultName = "storage:link";

    protected static $defaultDescription = "Create a sybolic link between storage and public directory";

    protected function execute(InputInterface $input, OutputInterface $output) {
        try {
            (new Storage())->link();
            $output->writeln("<info>Created symbolic link between public/ and storage/.</info>");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error>Could not create symbolic link between 'public/' and 'storage/'</error>");
            $output->writeln("<error>{$e->getMessage()}</error>");
            return Command::FAILURE;
        }
    }
}
