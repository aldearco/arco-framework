<?php

namespace Arco\Providers;

use Arco\App;
use Arco\Translation\FileLoader;
use Arco\Translation\TranslatorPHP;

class TranslationServiceProvider implements ServiceProvider {
    public function registerServices() {
        match (config('app.translator', 'TranslatorPHP')) {
            'TranslatorPHP' => singleton(
                'translator',
                fn () => new TranslatorPHP(
                    new FileLoader(
                        App::$root.config('app.translationDirectory', '/languages'),
                        config('app.translationFileType', 'php')
                    ),
                    config('app.locale', 'en')
                )
            ),
            'custom' => null // For a custom translation provider, you need to create your own ServiceProvider in the '/app' folder and add this provider in the '/config/providers.php' file.
        };
    }
}
