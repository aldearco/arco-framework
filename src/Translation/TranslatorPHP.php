<?php

namespace Arco\Translation;

use Arco\Translation\Interfaces\Loader;
use Arco\Translation\Interfaces\Translator;

class TranslatorPHP implements Translator {
    protected Loader $loader;

    protected string $locale;

    protected array $loaded = [];

    public string $globalNamespace;

    /**
     * Create new translator instance.
     *
     * @param Loader $loader
     * @param string $locale
     * @param string $globalNamespace
     */
    public function __construct(Loader $loader, string $locale, string $globalNamespace = '*') {
        $this->loader = $loader;
        $this->setLocale($locale);
        $this->globalNamespace = $globalNamespace;
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

    public function getNamespace(string $key) {
        if (str_contains($key, '/')) {
            return dirname($key);
        }
        return $this->globalNamespace;
    }

    public function getGroup(string $key) {
        if (str_contains($key, '/')) {
            $path = $this->getNamespace($key);
            $key = str_replace($path.'/', '', $key);
        }

        return explode('.', $key)[0];
    }

    public function getItem(string $key) {
        if (str_contains($key, '/')) {
            $path = $this->getNamespace($key);
            $key = str_replace($path.'/', '', $key);
        }

        return explode('.', $key)[1];
    }

    public function keyParts(string $key): array {
        return [
            $this->getNamespace($key),
            $this->getGroup($key),
            $this->getItem($key)
        ];
    }

    public function load($namespace, $group, $locale) {
        if ($this->isLoaded($namespace, $group, $locale)) {
            return;
        }

        $lines = $this->loader->load($locale, $group, $namespace);

        $this->loaded[$namespace][$group][$locale] = $lines;
    }

    protected function isLoaded($namespace, $group, $locale) {
        return isset($this->loaded[$namespace][$group][$locale]);
    }

    public function get(string $key, array $replace = [], ?string $locale = null) {
        $locale = $locale ?: $this->locale;

        $this->load(
            $this->getNamespace($key),
            $this->getGroup($key),
            $locale
        );

        [$namespace, $group, $item] = $this->keyParts($key);

        $line = $this->loaded[$namespace][$group][$locale] ?? null;

        if (is_null($line)) {
            return $key;
        }

        $text = $this->loaded[$namespace][$group][$locale][$item];

        return $this->makeReplacements($text, $replace);
    }

    protected function makeReplacements(string $text, array $replace) {
        if (empty($replace)) {
            return $text;
        }

        $replacements = [];

        foreach ($replace as $key => $value) {
            $replacements[':'.$key] = $value;
        }

        return strtr($text, $replacements);
    }
}
