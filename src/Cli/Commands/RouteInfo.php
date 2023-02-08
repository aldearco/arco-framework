<?php

namespace Arco\Cli\Commands;

use Arco\App;
use Arco\Routing\Router;
use App\Providers\RouteServiceProvider;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteInfo extends Command {
    protected static $defaultName = "route:info";

    protected static $defaultDescription = "Show a table with information about a route";

    protected function configure() {
        $this->addArgument("name", InputArgument::REQUIRED, "Route name");
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        try {
            singleton(App::class);
            app()->router = singleton(Router::class);
            (new RouteServiceProvider())->registerServices();

            $route = app()->router->getRouteInfo($input->getArgument("name"));

            if (is_null($route)) {
                $output->writeln("\n<error> NOT FOUND </error> There is no route with the name: <fg=#a2c181;options=bold>{$input->getArgument("name")}</>");
                return Command::INVALID;
            }

            $output->writeln("\n<fg=black;bg=#ffffff;options=bold> ROUTE INFO </> => <fg=#a2c181;options=bold>{$input->getArgument("name")}</>");
            $table1 = new Table($output);
            $table1
                ->setHeaders(["METHOD", "URI", "NAME", "ACTION", "PARAMETERS", "MIDDLEWARE"])
                ->setRows($route)
                ->setVertical()
                ->render();

            return Command::SUCCESS;
        } catch (\PDOException $e) {
            $output->writeln("<error>Could not show route list: {$e->getMessage()}</error>");
            $output->writeln($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
