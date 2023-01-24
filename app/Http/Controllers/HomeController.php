<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Arco\Database\Archer\TableCrafter;
use Arco\Http\Request;
use Arco\Http\Response;

class HomeController extends Controller {
    public function show() {
        return view("home");
    }

    public function storage() {
        return view("test");
    }

    public function testStorage(Request $request) {
        $url = $request->file('file')->store('public/testfiles');
        return Response::text($url);
    }

    public function index() {
        return json(['message' => ['ok']]);
    }

    public function sql() {
        $table = new TableCrafter('methods');
        $table->bigId();
        $table->string('varchar_test', 100, true);
        $table->integer('user_id');
        $table->bigInteger('big_integer_test');
        $table->decimal('decimal_test');
        $table->text('text_test');
        $table->date('date_test');
        $table->time('time_test');
        $table->timestamp('timestamp_test');
        $table->foreignKey('user_id', 'users', 'id');
        $table->column('custom_column', "VARCHAR(22) NULL UNIQUE");
        $table->rememberToken();
        $table->timestamps();

        return response()->text($table->create());
    }
}
