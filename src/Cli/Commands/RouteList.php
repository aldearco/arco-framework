<?php

namespace Arco\Cli\Commands;

use Arco\App;
use Arco\Routing\Router;
use App\Providers\RouteServiceProvider;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RouteList extends Command {
    protected static $defaultName = "route:list";

    protected static $defaultDescription = "Show a table with all registered routes";

    protected function execute(InputInterface $input, OutputInterface $output) {
        try {
            singleton(App::class);
            app()->router = singleton(Router::class);
            (new RouteServiceProvider())->registerServices();

            $output->writeln("\n<fg=black;bg=#ffffff;options=bold> ROUTE LIST </>");
            $table = new Table($output);
            $table
                ->setHeaders(["METHOD", "URI", "NAME", "ACTION"])
                ->setRows(app()->router->getRouteList())
                ->render();

            return Command::SUCCESS;
        } catch (\PDOException $e) {
            $output->writeln("<error>Could not show route list: {$e->getMessage()}</error>");
            $output->writeln($e->getTraceAsString());
            return Command::FAILURE;
        }
    }
}
