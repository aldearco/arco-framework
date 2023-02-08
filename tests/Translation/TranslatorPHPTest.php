<?php

namespace Arco\Tests\Translation;

use PHPUnit\Framework\TestCase;
use Arco\Translation\FileLoader;
use Arco\Translation\TranslatorPHP;

class TranslatorPHPTest extends TestCase {
    public function test_get_text_from_translation_php() {
        $translator = new TranslatorPHP(
            (new FileLoader(__DIR__.'/languages', 'php')),
            'en'
        );

        $assert1 = $translator->get('test.test');
        $assert2 = $translator->get('test/test.test');

        $expected1 = "From Global Namespace";
        $expected2 = "From Test Namespace";

        $this->assertEquals($expected1, $assert1);
        $this->assertEquals($expected2, $assert2);
    }

    public function test_get_text_from_translation_json() {
        $translator = new TranslatorPHP(
            new FileLoader(__DIR__.'/languages', 'json'),
            'es'
        );

        $assert1 = $translator->get('test.test');
        $assert2 = $translator->get('test/test.test');

        $expected1 = "From Global Namespace";
        $expected2 = "From Test Namespace";

        $this->assertEquals($expected1, $assert1);
        $this->assertEquals($expected2, $assert2);
    }

    public function test_get_text_from_translation_php_with_replacements() {
        $translator = new TranslatorPHP(
            (new FileLoader(__DIR__.'/languages', 'php')),
            'en'
        );

        $assert = $translator->get('replacement.test', [
            "name" => 'Alberto',
            "number" => 2
        ]);

        $expected = "Hello, my name is Alberto and I have 2 cats";

        $this->assertEquals($expected, $assert);
    }

    public function test_get_text_from_translation_json_with_replacements() {
        $translator = new TranslatorPHP(
            (new FileLoader(__DIR__.'/languages', 'json')),
            'es'
        );

        $assert = $translator->get('replacement.test', [
            "name" => 'Alberto',
            "number" => 2
        ]);

        $expected = "Hello, my name is Alberto and I have 2 cats";

        $this->assertEquals($expected, $assert);
    }
}
