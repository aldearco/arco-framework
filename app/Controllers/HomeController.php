<?php

namespace App\Controllers;

use Arco\Http\Controller;

class HomeController extends Controller {
    public function show() {
        return view("home");
    }
}