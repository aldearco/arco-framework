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

    public function getFilePath(string $key) {
        if (str_contains($key, '/')) {
            return dirname($key);
        }
        return null;
    }

    public function getKeys(string $group) {        
        return explode('.', $group);
    }

    public function getGroup(string $key) {
        if (str_contains($key, '/')) {
            $path = $this->getFilePath($key);
            $key = substr_replace($path.'/', '', $key);
        }
        
        return $key;
    }

    public function load($namespace, $group, $locale) {
        if ($this->isLoaded($namespace, $group, $locale)) {
            return;
        }

        

    }

    protected function isLoaded($namespace, $group, $locale) {
        return isset($this->loaded[$namespace][$group][$locale]);
    }

    public function get(string $key, array $replace = [], ?string $locale = null) {
        $locale = $locale ?: $this->locale;
        
        $this->load(
            $this->getFilePath($key) ?: '*',
            $this->getGroup($key),
            $locale
        );


    }

    public function choice(string $key, int $number, array $replace = [], ?string $locale = null): string {
        return '';
    }
}
