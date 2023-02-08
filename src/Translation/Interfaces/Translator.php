<?php

namespace Arco\Translation\Interfaces;

interface Translator {
    /**
     * Get the translation for a given key.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return mixed
     */
    public function get(string $key, array $replace = [], ?string $locale = null);

    /**
     * Get the locale code being used.
     *
     * @return string
     */
    public function getLocale(): string;

    /**
     * Set the App locale.
     *
     * @param string $locale
     */
    public function setLocale(string $locale);
}
