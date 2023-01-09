<?php

namespace Arco\Translation;

use Arco\Translation\Interfaces\Loader;
use Arco\Translation\Interfaces\Translator;

class TranslatorPHP implements Translator {
    protected Loader $loader; 

    protected string $locale;

    protected array $loaded = [];

    /**
     * Create new translator instance.
     *
     * @param Loader $loader
     * @param string $locale
     */
    public function __construct(Loader $loader, string $locale) {
        $this->loaded = $loader;

        $this->setLocale($locale);
    }

    /**
     * Set the default locale.
     *
     * @param  string  $locale
     */
    public function setLocale(string $locale) {
        $this->locale = $locale;
    }

    /**
     * Get the locale code being used.
     *
     * @return string
     */
    public function getLocale(): string {
        return $this->locale;
    }

    public function load() {

    }

    public function get(string $key, array $replace = [], ?string $locale = null) {
        
    }

    public function choice(string $key, int $number, array $replace = [], ?string $locale = null): string {
        return '';
    }
}
