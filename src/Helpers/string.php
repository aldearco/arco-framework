<?php

use Arco\Helpers\Arrows\Str;

if (!function_exists('snake_case')) {
    /**
     * Turn any string into snake_case format
     *
     * @param string $str
     * @return string
     */
    function snake_case(string $str): string {
        return Str::snake($str);
    }
}

if (!function_exists('class_basename')) {
    /**
     * Get class basename and its returned as string
     *
     * @param string|object $class
     * @return string
     */
    function class_basename(string|object $class): string {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}
