<?php

namespace Arco\Storage;

use Arco\Storage\Drivers\FileStorageDriver;

/**
 * File storage utilities.
 */
class Storage {
    /**
     * Put file in the storage directory.
     *
     * @param string $path
     * @param mixed $content
     * @return string URL of the file.
     */
    public static function put(string $path, mixed $content): string {
        return app(FileStorageDriver::class)->put($path, $content);
    }

    public static function url(string $path) {
        return config('app.url').'/'.$path;
    }

    /**
     * Create a symbolic link between the public folder and the storage folder.
     */
    public function link() {
        if (!file_exists('public/storage')) {
            if (PHP_OS === "WINNT") {
                return exec('mklink /J "public\storage" "..\storage"', $output, $return_var);
            } else {
                return symlink('../storage', 'public/storage');
            }
        }
        throw new \Exception("Link already exists.");
    }
}
