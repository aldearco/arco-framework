<?php 

return [
    /**
     * App Basic Settings
     */
    "name" => env("APP_NAME", "Arco"),
    "env" => env("APP_ENV", "dev"),
    "url" => env("APP_URL", "localhost:8080"),
    "key" => env("APP_KEY", "defaultKey"),
    "public" => "public",

    /**
     * Translation Settings
     */
    "translator" => "TranslatorPHP",
    "translation_directory" => "/languages",
    "translation_file_type" => "php",
    "locale" => "en",

    /**
     * Assets
     */
    "asset_url" => "/assets"
];