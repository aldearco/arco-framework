<?php

namespace Arco\Cli\Commands;

use Arco\App;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakePolicy extends Command {
    protected static $defaultName = "make:policy";

    protected static $defaultDescription = "Create a new policy file";

    protected function configure() {
        $this->addArgument("name", InputArgument::REQUIRED, "Policy name");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        $name = $input->getArgument("name");
        $template = file_get_contents(resourcesDirectory()."/templates/policy.php");
        $template = str_replace("PolicyName", $name, $template);
        file_put_contents(App::$root . "/app/Policies/$name.php", $template);
        $output->writeln("<info>Policy created => $name.php</info>");

        return Command::SUCCESS;
    }
}
