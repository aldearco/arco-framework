<?php

namespace Arco\Config;

class Config {
    /**
     * Array that stores all app configurations
     *
     * @var array
     */
    private static array $config = [];

    /**
     * Load all config from the app config directory files
     *
     * @param string $path
     */
    public static function load(string $path) {
        foreach (glob("$path/*.php") as $config) {
            $key = explode(".", basename($config))[0];
            $values = require_once $config;
            self::$config[$key] = $values;
        }
    }

    /**
     * Get configuration from loaded files
     *
     * @param string $configuration Use this structure: `"configKey.key.subKey.etc"`
     * @param [type] $default If not exists assign this value
     */
    public static function get(string $configuration, $default = null) {
        $keys = explode(".", $configuration);
        $finalKey = array_pop($keys);
        $array = self::$config;

        foreach ($keys as $key) {
            if (!array_key_exists($key, $array)) {
                return $default;
            }
            $array = $array[$key];
        }

        return $array[$finalKey] ?? $default;
    }
}
