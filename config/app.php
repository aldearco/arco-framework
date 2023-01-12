<?php 

return [
    /**
     * App Basic Configurations
     */
    "name" => env("APP_NAME", "Arco"),
    "env" => env("APP_ENV", "dev"),
    "url" => env("APP_URL", "localhost:8080"),
    "key" => env("APP_KEY", "defaultKey"),

    /**
     * Translation Configurations
     */
    "translator" => "TranslatorPHP",
    "translationFileType" => "php",
    "locale" => "en"
];