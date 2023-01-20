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
            $output->writeln("\n<question> SUCCESS </question> Created symbolic link between <fg=#a2c181>\public</> and <fg=#a2c181>\storage</>.");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln("<error> ERROR </error> Could not create symbolic link between <fg=#a2c181>\public</> and <fg=#a2c181>\storage</>.");
            $output->writeln("<comment>Reason:</comment> {$e->getMessage()}");
            return Command::FAILURE;
        }
    }
}
