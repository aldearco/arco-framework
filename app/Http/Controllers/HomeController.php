<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Arco\Http\Request;
use Arco\Http\Response;

class HomeController extends Controller {
    public function show() {
        return view("home");
    }

    public function test() {
        return view("test");
    }

    public function store_test(Request $request){
        $url = $request->file('file')->store('test/pictures');
        return Response::text($url);
    }
}