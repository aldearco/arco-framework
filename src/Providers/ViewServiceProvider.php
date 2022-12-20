<?php

namespace Arco\Providers;

use Arco\View\View;
use Arco\View\ArrowVulcan;

class ViewServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config("view.engine", "arrow-vulcan")) {
            "arrow-vulcan" => singleton(View::class, fn () => new ArrowVulcan(config("view.path")))
        };
    }
}
