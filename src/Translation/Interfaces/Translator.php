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
     * Get a translation according to an integer value.
     *
     * @param string $key
     * @param integer $number
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    public function choice(string $key, int $number, array $replace = [], ?string $locale = null): string;

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
