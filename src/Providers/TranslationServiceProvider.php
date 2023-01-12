<?php

namespace Arco\Providers;

use Arco\App;
use Arco\Translation\FileLoader;
use Arco\Translation\TranslatorPHP;
use Arco\Translation\Interfaces\Translator;

class TranslationServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config('app.translator', 'TranslatorPHP')) {
            'TranslatorPHP' => singleton(
                Translator::class,
                fn () => new TranslatorPHP(
                    new FileLoader(App::$root.'/languages', config('app.translationFileType', 'php')),
                    config('app.locale', 'en')
                )
            )
        };
    }
}
