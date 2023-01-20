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

        if (file_exists(App::$root . "/app/Policies/$name.php")) {
            $output->writeln("\n<error> ERROR </error> Policy already exists: <fg=#a2c181;options=bold>$name.php</>");
            return Command::FAILURE;
        }

        file_put_contents(App::$root . "/app/Policies/$name.php", $template);
        $output->writeln("\n<question> SUCCESS </question> Policy created: <fg=#a2c181;options=bold>$name.php</>");

        return Command::SUCCESS;
    }
}
