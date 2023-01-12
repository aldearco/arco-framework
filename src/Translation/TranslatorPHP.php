<?php

namespace Arco\Translation;

use Arco\Translation\Interfaces\Loader;
use Arco\Translation\Interfaces\Translator;

class TranslatorPHP implements Translator {
    /**
     * Translation file loader
     *
     * @var Loader
     */
    protected Loader $loader;

    /**
     * Current locale
     *
     * @var string
     */
    protected string $locale;

    /**
     * Loaded translation files
     *
     * @var array
     */
    protected array $loaded = [];

    /**
     * Global namespace for translation files
     *
     * @var string
     */
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

    /**
     * Get the namespace for a translation key
     *
     * @param string $key
     * @return string
     */
    public function getNamespace(string $key) {
        if (str_contains($key, '/')) {
            return dirname($key);
        }
        return $this->globalNamespace;
    }

    /**
     * Get the group for a translation key
     *
     * @param string $key
     * @return string
     */
    public function getGroup(string $key) {
        if (str_contains($key, '/')) {
            $path = $this->getNamespace($key);
            $key = str_replace($path.'/', '', $key);
        }

        return explode('.', $key)[0];
    }

    /**
     * Get the item for a translation key
     *
     * @param string $key
     * @return string
     */
    public function getItem(string $key) {
        if (str_contains($key, '/')) {
            $path = $this->getNamespace($key);
            $key = str_replace($path.'/', '', $key);
        }

        return explode('.', $key)[1];
    }

    /**
     * Get the namespace, group, and item for a translation key
     *
     * @param string $key
     * @return array
     */
    public function keyParts(string $key): array {
        return [
            $this->getNamespace($key),
            $this->getGroup($key),
            $this->getItem($key)
        ];
    }

    /**
     * Loads a translation file, given the namespace, group and locale. If the file has already been loaded, it returns nothing.
     *
     * @param string $namespace The namespace of the translation file
     * @param string $group The group of the translation file
     * @param string $locale The locale of the translation file
     */
    public function load($namespace, $group, $locale) {
        if ($this->isLoaded($namespace, $group, $locale)) {
            return;
        }

        $lines = $this->loader->load($locale, $group, $namespace);

        $this->loaded[$namespace][$group][$locale] = $lines;
    }

    /**
     * Check if a translation file has been loaded
     *
     * @param string $namespace The namespace of the translation file
     * @param string $group The group of the translation file
     * @param string $locale The locale of the translation file
     * @return bool
     */
    protected function isLoaded($namespace, $group, $locale) {
        return isset($this->loaded[$namespace][$group][$locale]);
    }

    /**
     * Get the translated string for a given key.
     *
     * @param string $key The key of the translation string
     * @param array $replace The values to replace in the translation string
     * @param string|null $locale The locale of the translation file
     * @return string
     */
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

    /**
     * Replace placeholders (example: `:placeholder`) in a string with the given values
     *
     * @param string $text
     * @param array $replace
     * @return void
     */
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
