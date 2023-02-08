<?php

use Arco\App;
use Arco\Config\Config;
use Arco\Container\Container;

if (!function_exists('app')) {
    /**
     * Resolve app class instances stored in the main container
     *
     * @param [type] $class
     */
    function app($class = App::class) {
        return Container::resolve($class);
    }
}

if (!function_exists('singleton')) {
    /**
     * Store class instances in the app
     *
     * @param string $class
     * @param string|callable|null|null $build
     */
    function singleton(string $class, string|callable|null $build = null) {
        return Container::singleton($class, $build);
    }
}

if (!function_exists('env')) {
    /**
     * Get enviroment variables
     *
     * @param string $variable
     * @param [type] $default
     */
    function env(string $variable, $default = null) {
        return $_ENV[$variable] ?? $default;
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration from loaded files
     *
     * @param string $configuration Use this structure: `"configKey.key.subKey.etc"`
     * @param [type] $default If not exists assign this value
     *  */
    function config(string $configuration, $default = null) {
        return Config::get($configuration, $default);
    }
}

if (!function_exists('resourcesDirectory')) {
    /**
     * Get app's resources directory
     *
     * @return string
     */
    function resourcesDirectory(): string {
        return App::$root . "/resources";
    }
}

if (!function_exists('asset')) {
    /**
     * Get app's assets directory
     *
     * @return string
     */
    function asset(string $path): string {
        return app()->server->protocol()
        .'://'.config('app.url')
        .config('app.asset_url')
        .'/'.$path;
    }
}
