<?php

namespace App\Http\Controllers;

use Arco\Auth\Access\Gatekeeper;
use Arco\Http\Controller as BaseController;

class Controller extends BaseController {
    use Gatekeeper;
    
}
