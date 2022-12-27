<?php

namespace Arco\Tests\View;

use Dotenv\Dotenv;
use Arco\View\ArrowVulcan;
use PHPUnit\Framework\TestCase;

class ArrowVulcanTest extends TestCase {
    protected ArrowVulcan $engine;

    protected function setUp(): void {
        Dotenv::createImmutable(__DIR__."/../../")->load();
        $this->engine = new ArrowVulcan(__DIR__."/views");
    }

    public function test_renders_template_with_all_features() {
        $parameter1 = "Test 1";
        $parameter2 = 2;

        $expected = '
            <html>
                <head>
                    <title>Test Title</title>
                    <link rel="stylesheet" type="text/css" href="/style.css">
                </head>
                <body>
                    <input type="hidden" name="_method" value="PUT">
                    <h1>'.$parameter1.'</h1>
                    <h1>'.$parameter2.'</h1>
                    <script type="text/javascript" src="file.js"></script>
                </body>
            </html>
        ';

        $content = $this->engine->render("test", compact("parameter1", "parameter2"), "layout");
        // var_dump($content); die;
        $this->assertEquals(preg_replace("/\s*/", "", $expected), preg_replace("/\s*/", "", $content));
    }

}
