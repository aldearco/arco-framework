<?php 

return [
    /**
     * Service providers that will run before booting application
     */
    "boot" => [
        /**
         * Arco framework service providers
         */
        Arco\Providers\ServerServiceProvider::class,
        Arco\Providers\DatabaseDriverServiceProvider::class,
        Arco\Providers\SessionStorageServiceProvider::class,
        Arco\Providers\ViewServiceProvider::class,
        Arco\Providers\AuthenticatorServiceProvider::class,
        Arco\Providers\HasherServiceProvider::class,
        Arco\Providers\FileStorageDriverServiceProvider::class,
        /**
         * Package service providers
         */
    ],
    /**
     * Service providers that will run after booting application
     */
    "runtime" => [
        App\Providers\RuleServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\AppServiceProvider::class,
    ],
    "cli" => [
        Arco\Providers\DatabaseDriverServiceProvider::class,
    ]
];