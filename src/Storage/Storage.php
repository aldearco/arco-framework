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

    /**
     * Return an URL for stored files.
     *
     * @param string $path
     * @return string
     */
    public static function url(string $path): string {
        return app()->server->protocol()
            .'://'.config('app.url')
            .'/'.$path;
    }

    /**
     * Create a symbolic link between the public folder and the storage folder.
     */
    public function link() {
        $publicFolder = config('app.public');
        if (!file_exists($publicFolder.'/storage')) {
            if (PHP_OS === "WINNT") {
                return exec('mklink /J "'.$publicFolder.'\storage" "..\storage\public"', $output, $return_var);
            } else {
                return symlink('../storage/public', $publicFolder.'/storage');
            }
        }
        throw new \Exception("Link already exists.");
    }
}
