<?php


use Arco\Translation\Interfaces\Translator;

if (!function_exists('trans')) {
    /**
     * Get translated texts or return the text that you wrote.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function trans(string $key, array $replace = [], ?string $locale = null): string {
        return app(Translator::class)->get($key, $replace, $locale);
    }
}

if (!function_exists('__')) {
    /**
     * Get translated texts or return the text that you wrote.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function __(string $key, array $replace = [], ?string $locale = null): string {
        try {
            return trans($key, $replace, $locale);
        } catch (\Throwable $e) {
            return $key;
        }
    }
}
