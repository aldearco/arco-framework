<?php

if (!function_exists('snake_case')) {
    /**
     * Turn any string into snake_case format
     *
     * @param string $str
     * @return string
     */
    function snake_case(string $str): string {
        $snake_cased = [];
        $skip = [' ', '-', '_', '/', '\\', '|', ',', '^', '\'', '"', '.', ';', ':', '*', '[', ']', '{', '}'];

        $i = 0;

        while ($i < strlen($str)) {
            $last = count($snake_cased) > 0
                ? $snake_cased[count($snake_cased) - 1]
                : null;
            $char = $str[$i++];
            if (ctype_upper($char)) {
                if ($last != '_') {
                    $snake_cased[] = '_';
                }
                $snake_cased[] = strtolower($char);
            } elseif (ctype_lower($char)) {
                $snake_cased[] = $char;
            } elseif (in_array($char, $skip)) {
                if ($last != '_') {
                    $snake_cased[] = '_';
                }
                while ($i < strlen($str) && in_array($str[$i], $skip)) {
                    $i++;
                }
            }
        }

        if ($snake_cased[0] == '_') {
            $snake_cased[0] = '';
        }

        if ($snake_cased[count($snake_cased) - 1] == '_') {
            $snake_cased[count($snake_cased) - 1] = '';
        }

        return implode($snake_cased);
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
