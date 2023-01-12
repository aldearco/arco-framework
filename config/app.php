<?php 

return [
    /**
     * App Basic Settings
     */
    "name" => env("APP_NAME", "Arco"),
    "env" => env("APP_ENV", "dev"),
    "url" => env("APP_URL", "localhost:8080"),
    "key" => env("APP_KEY", "defaultKey"),

    /**
     * Translation Settings
     */
    "translator" => "TranslatorPHP",
    "translationDirectory" => "/languages",
    "translationFileType" => "php",
    "locale" => "en"
];