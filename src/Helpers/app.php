<?php

use Arco\App;
use Arco\Config\Config;
use Arco\Container\Container;

/**
 * Resolve app class instances stored in the main container
 *
 * @param [type] $class
 */
function app($class = App::class) {
    return Container::resolve($class);
}

/**
 * Store class instances in the app
 *
 * @param string $class
 * @param string|callable|null|null $build
 */
function singleton(string $class, string|callable|null $build = null) {
    return Container::singleton($class, $build);
}

/**
 * Get enviroment variables
 *
 * @param string $variable
 * @param [type] $default
 */
function env(string $variable, $default = null) {
    return $_ENV[$variable] ?? $default;
}

/**
 * Get configuration from loaded files
 *
 * @param string $configuration Use this structure: `"configKey.key.subKey.etc"`
 * @param [type] $default If not exists assign this value
 *  */
function config(string $configuration, $default = null) {
    return Config::get($configuration, $default);
}

/**
 * Get app's resources directory
 *
 * @return string
 */
function resourcesDirectory(): string {
    return App::$root . "/resources";
}
