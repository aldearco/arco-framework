<?php

namespace Arco\Helpers\Arrows;

use Arco\Exceptions\ArrowRejected;

class Str {
    /**
    * Turn any string into snake_case format
    *
    * @param string $str
    * @return string
    */
    public static function snake(string $str): string {
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

    public static function toBytes(string $size): int {
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
        $number = substr($size, 0, -2);
        $unit = strtoupper(substr($size,-2));
    
        $exponent = array_flip($units)[$unit] ?? null;
        if($exponent === null) {
            throw new ArrowRejected("Invalid size format: '{$size}'");
        }
    
        return $number * (1024 ** $exponent);
    }
}
