<?php

use Arco\Storage\Storage;

if (!function_exists('stored')) {
    /**
     * Generate the URL for stored content in `storage` folder.
     *
     * @param string $path
     * @return string
     */
    function stored(string $path): string {
        return Storage::url($path);
    }
}
