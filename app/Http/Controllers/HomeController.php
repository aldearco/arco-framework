<?php

namespace App\Http\Controllers;

use Arco\Http\Request;
use Arco\Validation\File;
use App\Http\Controllers\Controller;
use Arco\Database\Archer\TableCrafter;
use Arco\Helpers\Arrows\Str;
use Arco\Validation\Rule;

class HomeController extends Controller {
    public function show() {
        return view("home");
    }

    public function storage() {
        return view("test");
    }

    public function testStorage(Request $request) {
        $request->validate([
            "file" => ['nullable', File::image(), File::within('11.5MB', '12MB')]
        ]);

        $test = Str::toBytes('2.5MB');

        var_dump($request->file('file')->size() < $test);
        die;

        // $url = is_null($request->file('file'))
        //             ? 'Se envió vacío'
        //             : $request->file('file')->store('public/testfiles');

        // return back()->withErrors([
        //     "file" => ['nullable' => $url]
        // ]);
    }

    public function rules() {
        return view("rules");
    }

    public function storeRules(Request $request) {
        $request->validate([
            "rule" => ['nullable', 'size:3'],
            "arreglo" => ["nullable", 'size:2']
        ]);

        return view("rules");
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
