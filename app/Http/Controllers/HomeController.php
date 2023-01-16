<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Arco\Database\Archer\TableCrafter;

class HomeController extends Controller {
    public function show() {
        return view("home");
    }

    public function test() {
        return view("test");
    }

    public function sql() {
        $table = new TableCrafter('users');
        $table->id();
        $table->string('name');
        $table->string('email', uuid: true);
        $table->string('password');
        $table->rememberToken();
        $table->timestamps();

        return response()->text($table->create());
    }
}