<?php

namespace Arco\Providers;

use Arco\Server\Server;
use Arco\Server\PhpNativeServer;

class ServerServiceProvider implements ServiceProvider {
    public function registerServices() {
        singleton(Server::class, PhpNativeServer::class);
    }
}
