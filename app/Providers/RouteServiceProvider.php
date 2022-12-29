<?php 

namespace App\Providers;

use Arco\App;
use Arco\Routing\Route;
use Arco\Providers\ServiceProvider;

class RouteServiceProvider implements ServiceProvider {
    public function registerServices() {
        Route::load(App::$root . "/routes");
    }
}