<?php

namespace Arco\Tests\Translation;

use Arco\Translation\FileLoader;
use PHPUnit\Framework\TestCase;

class FileLoaderTest extends TestCase {
    public function test_load_php_file() {
        $loader = new FileLoader(__DIR__.'/languages', 'php');

        $assert1 = $loader->load('en', 'test', '*');
        $assert2 = $loader->load('en', 'test', 'test');

        $expected1 = [
            "test" => "From Global Namespace"
        ];

        $expected2 = [
            "test" => "From Test Namespace"
        ];

        $this->assertEquals($expected1, $assert1);
        $this->assertEquals($expected2, $assert2);
    }

    public function test_load_json_file() {
        $loader = new FileLoader(__DIR__.'/languages', 'json');

        $assert1 = $loader->load('es', 'test', '*');
        $assert2 = $loader->load('es', 'test', 'test');

        $expected1 = [
            "test" => "From Global Namespace"
        ];

        $expected2 = [
            "test" => "From Test Namespace"
        ];

        $this->assertEquals($expected1, $assert1);
        $this->assertEquals($expected2, $assert2);
    }
}
