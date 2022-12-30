<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Arco\Http\Response;

class HomeController extends Controller {
    public function show() {
        return view("home");
    }
}