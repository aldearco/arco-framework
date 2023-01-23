<?php 

use Arco\Storage\Storage;

if (!function_exists('stored')) {
    
    function stored(string $path): string {
        return Storage::url($path);
    }
}