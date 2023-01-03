<?php

namespace Arco\Providers;

use Arco\App;
use Arco\Storage\Drivers\DiskFileStorage;
use Arco\Storage\Drivers\FileStorageDriver;

class FileStorageDriverServiceProvider {
    public function registerServices() {
        match (config("storage.driver", "disk")) {
            "disk" => singleton(
                FileStorageDriver::class,
                fn () => new DiskFileStorage(
                    App::$root . "/storage",
                    "storage"
                )
            ),
        };
    }
}
