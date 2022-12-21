<?php

namespace Arco\Container;

class Container {
    /**
     * Array that contains all app classes instances
     *
     * @var array
     */
    private static array $instances = [];

    /**
     * Store class instances in the app 
     *
     * @param string $class
     * @param string|callable|null|null $build
     */
    public static function singleton(string $class, string|callable|null $build = null) {
        if (!array_key_exists($class, self::$instances)) {
            match (true) {
                is_null($build) => self::$instances[$class] = new $class(),
                is_string($build) => self::$instances[$class] = new $build(),
                is_callable($build) => self::$instances[$class] = $build(),
            };
        }

        return self::$instances[$class];
    }

    /**
     * Resolve app class instances stored in the main container
     *
     * @param [type] $class
     */
    public static function resolve(string $class) {
        return self::$instances[$class] ?? null;
    }
}
